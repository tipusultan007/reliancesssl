<?php

namespace App\Http\Controllers;

use App\Models\AccountClosing;
use App\Models\DailyLoan;
use App\Models\Due;
use App\Models\Guarantor;
use App\Models\MonthlyCollection;
use App\Models\MonthlyLoan;
use App\Models\MonthlySaving;
use App\Models\Nominee;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MonthlySavingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('monthlySavings.index');
    }
    public function dataSavings(Request $request)
    {
        $totalData = MonthlySaving::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if(empty($request->input('search.value')))
        {
            $posts = MonthlySaving::with('member')
                ->offset($start)
                ->limit($limit)
                ->orderBy('account_no','asc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  MonthlySaving::with('member')
                ->where('account_no',$search)
                ->orWhereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy('account_no','asc')
                ->get();

            $totalFiltered = MonthlySaving::with('member')->where('account_no',$search)
                ->orWhereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })->count();
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
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->member->name;
                $nestedData['account_no'] = $post->account_no;
                $nestedData['deposit'] = $post->deposit;
                $nestedData['withdraw'] = $post->withdraw;
                $nestedData['monthly_amount'] = $post->monthly_amount;
                $nestedData['duration'] = $post->duration;
                $nestedData['profit'] = $post->profit;
                $nestedData['total'] = $post->total;
                $nestedData['status'] = $status;
                $nestedData['date'] = date('j M Y',strtotime($post->date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="'.route('monthly-savings.show',$post->id).'" class="dropdown-item">View</a>
                                                        <a href="'.route('monthly-savings.edit',$post->id).'" class="dropdown-item">Edit</a>
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
        return view('monthlySavings.create');
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
        $str_pad = str_pad($data['account_no'], 4, "0", STR_PAD_LEFT);
        $account_no = 'DPS'.$str_pad;
        $data['account_no'] = $account_no;
        $validator = Validator::make($data, [
            'account_no' => 'required|unique:monthly_savings,account_no',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $savings = MonthlySaving::create($data);
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
        $saving = MonthlySaving::find($id);
        $guarantorData = [];
        $guarantors = Guarantor::where('g_member_id',$saving->member_id)->get();
        foreach ($guarantors as $guarantor)
        {
            if ($guarantor->loan_type=='daily')
            {
                $dailyLoan = DailyLoan::with('member')->find($guarantor->loan_id);
                if ($dailyLoan->balance>0)
                {
                    $data['account_no'] = $dailyLoan->account_no;
                    $data['name'] = $dailyLoan->member->name;
                    $data['balance'] = $dailyLoan->balance;
                    $guarantorData[] = $data;
                }
            }elseif($guarantor->loan_type=='monthly'){
                $monthly = MonthlyLoan::with('member')->find($guarantor->loan_id);
                if ($monthly->balance>0)
                {
                    $data['account_no'] = $monthly->account_no;
                    $data['name'] = $monthly->member->name;
                    $data['balance'] = $monthly->balance;
                    $guarantorData[] = $data;
                }
            }
        }
        return view('monthlySavings.show',compact('saving','guarantorData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $saving = MonthlySaving::find($id);
        $nominee = Nominee::where('account_no',$saving->account_no)->first();
        if ($nominee === null) {
            $nominee = new Nominee(['account_no' => $saving->account_no]);
        }
        $nominee->save();
        return view('monthlySavings.edit',compact('saving','nominee'));
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
        $data = $request->all();
        $str_pad = str_pad($request->account_no, 4, "0", STR_PAD_LEFT);
        $data['account_no'] = 'DPS'.$str_pad;

        $dailySavings = MonthlySaving::find($id);
        $tempAccount = $dailySavings->account_no;
        $dailySavings->update($data);

        $nominee = Nominee::where('account_no',$tempAccount)->first();
//        $nominee->nominee_name = $data['nominee_name'];
//        $nominee->nominee_address = $data['nominee_address'];
//        $nominee->nominee_mobile = $data['nominee_mobile'];
//        $nominee->birth_date = $data['birth_date'];
//        $nominee->nominee_relation = $data['nominee_relation'];
//        $nominee->nominee_percentage = $data['nominee_percentage'];
//        $nominee->nominee_name1 = $data['nominee_name1'];
//        $nominee->birth_date1 = $data['birth_date1'];
//        $nominee->nominee_percentage1 = $data['nominee_percentage1'];
//        $nominee->nominee_mobile1 = $data['nominee_mobile1'];
//        $nominee->nominee_relation1 = $data['nominee_relation1'];
//        $nominee->nominee_address1 = $data['nominee_address1'];
        if ($request->hasFile('nominee_nid')) {
            $oldFilePath = $nominee->nominee_nid;
            $imagePath = upload_image($request->file('nominee_nid'), 'nominee_nid', $oldFilePath);
            $data['nominee_nid'] = $imagePath;
        }else{
            unset($data['nominee_nid']);
        }
        if ($request->hasFile('nominee_nid1')) {

            $oldFilePath = $nominee->nominee_nid1;
            $imagePath = upload_image($request->file('nominee_nid1'), 'nominee_nid', $oldFilePath);
            $data['nominee_nid1'] = $imagePath;

        }else{
            unset($data['nominee_nid1']);
        }
        if ($request->hasFile('nominee_photo')) {
            $oldFilePath = $nominee->nominee_photo;
            $imagePath = upload_image($request->file('nominee_photo'), 'nominee_photo', $oldFilePath);
            $data['nominee_photo'] = $imagePath;
        }else{
            unset($data['nominee_photo']);
        }
        if ($request->hasFile('nominee_photo1')) {
            $oldFilePath = $nominee->nominee_photo1;
            $imagePath = upload_image($request->file('nominee_photo1'), 'nominee_photo', $oldFilePath);
            $data['nominee_photo1'] = $imagePath;
        }else{
            unset($data['nominee_photo1']);
        }
        $nominee->update($data);

        return redirect()->route('monthly-savings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $savings = MonthlySaving::find($id);
        MonthlyLoan::where('account_no',$savings->account_no)->delete();
        MonthlyCollection::where('account_no',$savings->account_no)->delete();
        Transaction::where('account_no',$savings->account_no)->delete();
        $accountClosing = AccountClosing::where('type','daily')->where('account_no',$savings->account_no)->delete();
        $nominee = Nominee::where('account_no',$savings->account_no)->delete();
        $guarantor = Guarantor::where('account_no',$savings->account_no)->delete();
        $delete = MonthlySaving::destroy($id);

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

    public function existMonthlyAccount($account)
    {
        $str_pad = str_pad($account, 4, "0", STR_PAD_LEFT);
        $account_no = 'DPS'.$str_pad;
        $savings = MonthlySaving::where('account_no',$account_no)->count();

        return $savings;
    }

    public function getMonthlySavings(Request $request)
    {
        $savings = MonthlySaving::with('member')->where('account_no',$request->account_no)->first();
        $loan = MonthlyLoan::where('account_no',$request->account_no)->where('status','active')->first();
        $due = Due::where('account_no',$request->account_no)->first();
        $data = [];
        $data['savings'] = $savings;
        $data['loan'] = $loan?$loan:"";
        $data['due'] = $due?$due:"";
        $data['detail'] = getMonthlyInstallment($request->account_no,$request->date);
        /*if ($loan) {
            $totalInstallments = getMonthListFromDate($loan->date, date('Y-m-d'));
            $collections = MonthlyCollection::where('loan_id', $loan->id)->get();
            $dueInstallment = $totalInstallments - $collections->count();
            $data['dueInstallment'] = $dueInstallment;
        }*/

        return json_encode($data);
    }
}
