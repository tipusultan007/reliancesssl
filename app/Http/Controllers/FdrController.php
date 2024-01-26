<?php

namespace App\Http\Controllers;

use App\Models\Fdr;
use App\Models\FdrCollection;
use App\Models\FdrDeposit;
use App\Models\FdrWithdraw;
use App\Models\Nominee;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FdrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fdrs = Fdr::all();
        /*foreach ($fdrs as $fdr)
        {
            $fdrDeposit = FdrDeposit::create([
                'fdr_id' => $fdr->id,
                'amount' => $fdr->fdr_amount,
                'remain' => $fdr->fdr_amount,
                'balance' => $fdr->fdr_balance,
                'date' => $fdr->date,
                'profit_rate' => 2,
                'member_id' => $fdr->member_id,
                'trx_id' => $fdr->trx_id,
                'user_id' => $fdr->user_id
            ]);

            $transaction = Transaction::create([
                'transaction_category_id' => 21,
                'date' => $fdrDeposit->date,
                'trx_id' => $fdrDeposit->trx_id,
                'fdr_deposit_id' => $fdrDeposit->id,
                'amount' => $fdrDeposit->amount,
                'account_no' => $fdrDeposit->fdr->account_no,
                'member_id' => $fdrDeposit->member_id,
                'user_id' => $fdrDeposit->user_id,
                'type' => 'debit',
            ]);
        }*/
        return view('fdrs.index');
    }

    public function dataAllFdr(Request $request)
    {
        $totalData = Fdr::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if(empty($request->input('search.value')))
        {
            $posts = Fdr::with('member')
                ->offset($start)
                ->limit($limit)
                ->orderBy('account_no','asc')
                ->get();
        }else {
            $search = $request->input('search.value');

            $posts =  Fdr::with('member')
                ->where('account_no','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('account_no','asc')
                ->get();

            $totalFiltered = Fdr::where('account_no','LIKE',"%{$search}%")->count();
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
                    $status = '<span class="badge bg-danger">বন্ধ</span>';
                }

                $type ="";
                if ($post->type == "monthly")
                {
                    $type = '<span class="badge bg-success">মাসিক মুনাফা</span>';
                }else{
                    $type = '<span class="badge text-dark bg-warning">কালান্তিক মুনাফা</span>';
                }
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->member->name??'';
                $nestedData['account_no'] = $post->account_no;
                $nestedData['fdr_amount'] = $post->fdr_amount;
                $nestedData['fdr_balance'] = $post->fdr_balance;
                $nestedData['profit'] = $post->profit;
                $nestedData['status'] = $status;
                $nestedData['type'] = $type;

                $nestedData['date'] = date('j M Y',strtotime($post->date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="'.route('fdr.show',$post->id).'" class="dropdown-item">বিস্তারিত</a>
                                                        <a href="'.route('fdr.edit',$post->id).'" class="dropdown-item">এডিট</a>
                                                        <a href="javascript:void(0);" onclick="deleteConfirmation('.$post->id.')" class="dropdown-item">ডিলেট</a>
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
        return view('fdrs.create');
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
        $str_pad = str_pad($request->account_no, 4, "0", STR_PAD_LEFT);
        $data['account_no'] = 'FDR'.$str_pad;
        $data['user_id'] = Auth::id();
        $data['fdr_balance'] = $data['fdr_amount'];
        $data['trx_id'] = Str::uuid();
        $validator = Validator::make($data, [
            'account_no' => 'required|unique:fdrs,account_no',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $fdr = Fdr::create($data);
        $fdrDeposit = FdrDeposit::create([
            'fdr_id' => $fdr->id,
            'amount' => $fdr->fdr_amount,
            'remain' => $fdr->fdr_amount,
            'balance' => $fdr->fdr_balance,
            'date' => $fdr->date,
            'profit_rate' => $request->profit_rate,
            'member_id' => $fdr->member_id,
            'trx_id' => $fdr->trx_id,
            'user_id' => $fdr->user_id
        ]);
        if ($request->hasFile('nominee_nid')) {
            $imagePath = upload_image($request->file('nominee_nid'), 'nominee_nid');
            $data['nominee_nid'] = $imagePath;
        }
        if ($request->hasFile('nominee_nid1')) {
            $imagePath = upload_image($request->file('nominee_nid1'), 'nominee_nid');
            $data['nominee_nid1'] = $imagePath;
        }
        if ($request->hasFile('nominee_photo')) {
            $imagePath = upload_image($request->file('nominee_photo'), 'nominee_photo');
            $data['nominee_photo'] = $imagePath;
        }
        if ($request->hasFile('nominee_photo1')) {
            $imagePath = upload_image($request->file('nominee_photo1'), 'nominee_photo');
            $data['nominee_photo1'] = $imagePath;
        }
        $nominee = Nominee::create($data);
        $transaction = Transaction::create([
            'transaction_category_id' => 21,
            'date' => $fdr->date,
            'trx_id' => $fdr->trx_id,
            'fdr_deposit_id' => $fdrDeposit->id,
            'amount' => $fdrDeposit->amount,
            'account_no' => $fdrDeposit->fdr->account_no,
            'member_id' => $fdrDeposit->member_id,
            'user_id' => $fdrDeposit->user_id,
            'type' => 'debit',
        ]);

        return response()->json(['success' => 'Account created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fdr = Fdr::with('member')->find($id);
        return view('fdrs.show',compact('fdr'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fdr = Fdr::find($id);

        return view('fdrs.edit',compact('fdr'));
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
        $oldFdr = Fdr::find($id);
        $data = $request->all();
        $str_pad = str_pad($request->account_no, 4, "0", STR_PAD_LEFT);
        $data['account_no'] = 'FDR'.$str_pad;
        $fdr = Fdr::find($id);

        $nominee = Nominee::where('account_no',$oldFdr->account_no)->first();
        if ($request->hasFile('nominee_nid')) {
            $imagePath = upload_image($request->file('nominee_nid'), 'nominee_nid');
            $data['nominee_nid'] = $imagePath;
        }
        if ($request->hasFile('nominee_nid1')) {
            $imagePath = upload_image($request->file('nominee_nid1'), 'nominee_nid');
            $data['nominee_nid1'] = $imagePath;
        }
        if ($request->hasFile('nominee_photo')) {
            $imagePath = upload_image($request->file('nominee_photo'), 'nominee_photo');
            $data['nominee_photo'] = $imagePath;
        }
        if ($request->hasFile('nominee_photo1')) {
            $imagePath = upload_image($request->file('nominee_photo1'), 'nominee_photo');
            $data['nominee_photo1'] = $imagePath;
        }
        $nominee->update($data);

        $fdr->update($data);

        return redirect()->route('fdr.edit',$id)->with('success','FDR আপডেট সফল হয়েছে!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fdr = Fdr::find($id);
        $deposits = FdrDeposit::where('fdr_id',$id)->get();
        $withdraws = FdrWithdraw::where('fdr_id',$id)->get();
        foreach ($deposits as $row)
        {
            $transaction = Transaction::where('transaction_category_id',21)->where('trx_id',$row->trx_id)->delete();
            $row->delete();
        }
        foreach ($withdraws as $row)
        {
            $transaction = Transaction::where('transaction_category_id',22)->where('trx_id',$row->trx_id)->delete();
            $row->delete();
        }
        FdrCollection::where('account_no',$fdr->account_no)->delete();
        Transaction::where('account_no',$fdr->account_no)->delete();
        $delete = Fdr::destroy($id);
        if ($delete == 1) {
            $success = true;
            $message = "Data deleted successfully";
        } else {
            $success = false;
            $message = "Data not found";
        }

        //  return response
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function existAccount($account)
    {
        $str_pad = str_pad($account, 4, "0", STR_PAD_LEFT);
        $account_no = 'FDR'.$str_pad;
        $savings = Fdr::where('account_no',$account_no)->count();

        return $savings;
    }
}
