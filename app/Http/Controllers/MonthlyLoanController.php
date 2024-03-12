<?php

namespace App\Http\Controllers;

use App\Models\Guarantor;
use App\Models\LoanDocument;
use App\Models\Member;
use App\Models\MonthlyCollection;
use App\Models\MonthlyLoan;
use App\Models\MonthlySaving;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MonthlyLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = MonthlyCollection::select(DB::raw('SUM(loan_installment) as total_paid_loan,
        SUM(monthly_interest) as total_interest, SUM(extra_interest) as total_extra_interest'))->first();
        return view('monthlyLoans.index', compact('collections'));
    }

    public function dataLoans(Request $request)
    {
        $columns = array(
            1 =>'account_no',
            2=> 'date',
            7=> 'status',
        );

        $totalData = MonthlyLoan::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {
            $posts = MonthlyLoan::with('member')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  MonthlyLoan::with('member')
                ->where('account_no',$search)
                ->orWhereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = MonthlyLoan::with('member')->where('account_no',$search)
                ->orWhereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                if ($post->status=='active')
                {
                    $status = '<span class="badge bg-success">চলমান</span>';
                }else{
                    $status = '<span class="badge bg-danger">  পরিশোধিত </span>';
                }
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->member->name;
                $nestedData['account_no'] = $post->account_no;
                $nestedData['loan_amount'] = $post->loan_amount;
                $nestedData['interest_rate'] = $post->interest_rate;
                $nestedData['balance'] = $post->balance;
                $nestedData['total_paid_loan'] = $post->total_paid_loan;
                $nestedData['status'] = $status;
                $nestedData['date'] = date('j M Y',strtotime($post->date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="'.route('monthly-loans.show',$post->id).'" class="dropdown-item">View</a>
                                                        <a href="'.route('monthly-loans.edit',$post->id).'" class="dropdown-item">Edit</a>
                                                        <a href="javascript:void(0);" onclick="deleteConfirmation('.$post->id.')" class="dropdown-item">Delete</a>
                                                    </div>
                                                </div>';
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $savings = MonthlySaving::where('status','active')->get();
        $guarantorList = Member::get();
        return  view('monthlyLoans.create',compact('savings','guarantorList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $savings = MonthlySaving::where('account_no',$data['account_no'])->first();
        $existLoan = MonthlyLoan::where('account_no',$data['account_no'])->where('status','active')->first();
        if ($existLoan)
        {
            return response([
                "error" => "Loan already exist"
            ]);
        }
        $data['member_id'] = $savings->member_id;
        $data['account_no'] = $savings->account_no;
        $data['balance'] = $data['loan_amount'];
        $data['trx_id'] = Str::uuid();
        $data['user_id'] = Auth::id();


        if ($request->hasFile('documents')) {
            $imagePath = upload_image($request->file('documents'), 'documents');
            $data['documents'] = $imagePath;
        }
        if ($request->hasFile('g_documents')) {
            $imagePath = upload_image($request->file('g_documents'), 'documents');
            $data['g_documents'] = $imagePath;
        }
        if ($request->hasFile('g_documents1')) {
            $imagePath = upload_image($request->file('g_documents1'), 'documents');
            $data['g_documents1'] = $imagePath;
        }
        if ($request->hasFile('g_documents2')) {
            $imagePath = upload_image($request->file('g_documents2'), 'documents');
            $data['g_documents2'] = $imagePath;
        }

        if ($request->hasFile('signature1')) {
            $imagePath = upload_image($request->file('signature1'), 'signature');
            $data['signature1'] = $imagePath;
        }
        if ($request->hasFile('signature2')) {
            $imagePath = upload_image($request->file('signature2'), 'signature2');
            $data['signature2'] = $imagePath;
        }

        if ($request->hasFile('photo1')) {
            $imagePath = upload_image($request->file('photo1'), 'photo');
            $data['photo1'] = $imagePath;
        }
        if ($request->hasFile('photo2')) {
            $imagePath = upload_image($request->file('photo2'), 'photo');
            $data['photo2'] = $imagePath;
        }

        $loan = MonthlyLoan::create($data);
        $data['loan_id'] = $loan->id;
        $data['loan_type'] = "monthly";
        LoanDocument::create($data);
        $guarantor = Guarantor::create($data);
        $loanTransaction = Transaction::create([
            'transaction_category_id' => 8,
            'date' => $data['date'],
            'trx_id' => $loan->trx_id,
            'loan_id' => $loan->id,
            'amount' => $loan->loan_amount,
            'account_no' => $loan->account_no,
            'member_id' => $loan->member_id,
            'user_id' => $loan->user_id,
            'type' => 'credit',
        ]);

        return response([
            "success" => "Loan Success"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = MonthlyLoan::with('member','loanCollections')->find($id);
        //dd($loan->loanCollections);
        return view('monthlyLoans.show',compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loan = MonthlyLoan::find($id);
        $savings = MonthlySaving::where('status','active')->get();
        $guarantorList = Member::get();
        return view('monthlyLoans.edit',compact('savings','guarantorList','loan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data= $request->all();
        $data['balance'] = $data['loan_amount'];
        $loan = MonthlyLoan::find($id);
        $document = LoanDocument::where('loan_id',$id)
            ->where('loan_type','monthly')->first();
        $guarantor = Guarantor::where('loan_id',$id)
            ->where('loan_type','monthly')->first();
        if (!$guarantor)
        {
            $guarantor = new Guarantor();
            $guarantor->loan_id = $loan->id;
            $guarantor->loan_type = 'monthly';
            $guarantor->account_no = $loan->account_no;
        }
        if ($request->hasFile('documents')) {
            $oldPath = $document->documents;
            $imagePath = upload_image($request->file('documents'), 'documents',$oldPath);
            $data['documents'] = $imagePath;
            $document->documents = $imagePath;
        }else{
            unset($data['documents']);
        }
        if ($request->hasFile('g_documents1')) {
            $oldPath = $guarantor?$guarantor->g_documents1:"";
            $imagePath = upload_image($request->file('g_documents1'), 'documents',$oldPath);
            $data['g_documents1'] = $imagePath;
            $guarantor->g_documents1= $imagePath;

        }else{
            unset($data['g_documents1']);
        }
        if ($request->hasFile('g_documents2')) {
            $oldPath = $guarantor?$guarantor->g_documents2:"";
            $imagePath = upload_image($request->file('g_documents2'), 'documents',$oldPath);
            $data['g_documents2'] = $imagePath;
            $guarantor->g_documents2= $imagePath;

        }else{
            unset($data['g_documents2']);
        }

        if ($request->hasFile('signature1')) {
            $oldPath = $guarantor?$guarantor->signature1:"";
            $imagePath = upload_image($request->file('signature1'), 'signature',$oldPath);
            $data['signature1'] = $imagePath;
            $guarantor->signature1= $imagePath;
        }else{
            unset($data['signature1']);
        }
        if ($request->hasFile('signature2')) {
            $oldPath = $guarantor?$guarantor->signature2:"";
            $imagePath = upload_image($request->file('signature2'), 'signature',$oldPath);
            $data['signature2'] = $imagePath;
            $guarantor->signature2= $imagePath;
        }else{
            unset($data['signature2']);
        }
        if ($request->hasFile('photo1')) {
            $oldPath = $guarantor?$guarantor->photo1:"";
            $imagePath = upload_image($request->file('photo1'), 'photo',$oldPath);
            $data['photo1'] = $imagePath;
            $guarantor->photo1= $imagePath;
        }else{
            unset($data['photo1']);
        }
        if ($request->hasFile('photo2')) {
            $oldPath = $guarantor?$guarantor->photo2:"";
            $imagePath = upload_image($request->file('photo2'), 'photo',$oldPath);
            $data['photo2'] = $imagePath;
            $guarantor->photo2= $imagePath;
        }else{
            unset($data['photo2']);
        }
        $loan->update($data);


        $document->bank_name = $data['bank_name'];
        $document->branch_name = $data['branch_name'];
        $document->account_holder = $data['account_holder'];
        $document->bank_ac_number = $data['bank_ac_number'];
        $document->cheque_number = $data['cheque_number'];

        $document->save();


        $guarantor->name1= $data['name1'];
        $guarantor->address1= $data['address1'];
        $guarantor->g_mobile1= $data['g_mobile1'];
        $guarantor->name2= $data['name2'];
        $guarantor->address2= $data['address2'];
        $guarantor->g_mobile2= $data['g_mobile2'];
        $guarantor->save();

        $loanTransaction = Transaction::where('transaction_category_id',8)->where('trx_id',$loan->trx_id)->first();
        if ($loanTransaction) {
            $loanTransaction->amount = $loan->loan_amount;
            $loanTransaction->date = $loan->date;
            $loanTransaction->save();
        }else{
            $loanTransaction = Transaction::create([
                'transaction_category_id' => 8,
                'date' => $data['date'],
                'trx_id' => $loan->trx_id,
                'loan_id' => $loan->id,
                'amount' => $loan->loan_amount,
                'account_no' => $loan->account_no,
                'member_id' => $loan->member_id,
                'user_id' => $loan->user_id,
                'type' => 'credit',
            ]);
        }


        return redirect()->route('monthly-loans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = MonthlyLoan::find($id);
        $transactionLoan = Transaction::whereIn('transaction_category_id',[9,10,16])->where('loan_id',$loan->id)->delete();
        MonthlyCollection::where('monthly_amount','<',1)->where('loan_id',$loan->id)->delete();
        $collections = MonthlyCollection::where('monthly_amount','>',0)->where('loan_id',$loan->id)->get();
        foreach ($collections as $collection)
        {
            $collection->loan_id = NULL;
            $collection->monthly_interest = NULL;
            $collection->extra_interest = NULL;
            $collection->loan_installment = NULL;
            $collection->interest_installments = NULL;
            $collection->balance = NULL;
            $collection->save();
        }
        Guarantor::where('loan_type','monthly')->where('loan_id',$id)->delete();
        $delete = MonthlyLoan::destroy($id);
        if ($delete == 1) {
            $success = true;
            $message = "Loan deleted successfully";
        } else {
            $success = false;
            $message = "Loan Data not found";
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function getDetails($account)
    {
        $dps = MonthlySaving::where('account_no',$account)->first();

        $data = getMemberDetails($dps->member_id);
        $data['account_no'] = $dps->account_no;
        return json_encode($data);
    }
}
