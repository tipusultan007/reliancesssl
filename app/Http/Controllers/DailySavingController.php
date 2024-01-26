<?php

namespace App\Http\Controllers;

use App\Models\AccountClosing;
use App\Models\DailyCollection;
use App\Models\DailyLoan;
use App\Models\DailySavings;
use App\Models\Guarantor;
use App\Models\Member;
use App\Models\MonthlyLoan;
use App\Models\MonthlySaving;
use App\Models\Nominee;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DailySavingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dailySavings.index');
    }

    public function dataAllSavings(Request $request)
    {
        $totalData = DailySavings::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if(empty($request->input('search.value')))
        {
            $posts = DailySavings::with('member')
                ->offset($start)
                ->limit($limit)
                ->orderBy('account_no','asc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  DailySavings::with('member')
                ->where('account_no',$search)
                ->orWhereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy('account_no','asc')
                ->get();

            $totalFiltered = DailySavings::with('member')->where('account_no',$search)
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
                    $status = '<span class="badge bg-danger">বন্ধ</span>';
                }
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->member->name;
                $nestedData['account_no'] = $post->account_no;
                $nestedData['deposit'] = $post->deposit;
                $nestedData['withdraw'] = $post->withdraw;
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
                                                        <a href="'.route('daily-savings.show',$post->id).'" class="dropdown-item">View</a>
                                                        <a href="'.route('daily-savings.edit',$post->id).'" class="dropdown-item">Edit</a>
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

    public function getSavingsTransaction(Request $request)
    {
        $totalData = DailyCollection::where('account_no',$request->account_no)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        if(empty($request->input('search.value')))
        {
            $posts = DailyCollection::with('member')
                ->where('account_no',$request->account_no)
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  DailyCollection::where('account_no',$request->account_no)
                ->with('member')
                ->where('account_no','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = DailyCollection::where('account_no',$request->account_no)
                ->where('account_no','LIKE',"%{$search}%")
                ->orWhere('members.name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                //  $show =  route('daily-collections.show',$post->id);
                // $edit =  route('daily-collections.edit',$post->id);

                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->member->name;
                $nestedData['account_no'] = $post->account_no;
                $nestedData['deposit'] = $post->deposit;
                $nestedData['withdraw'] = $post->withdraw;
                $nestedData['loan_installment'] = $post->loan_installment;
                $nestedData['loan_balance'] = $post->loan_balance;
                $nestedData['late_fee'] = $post->late_fee;
                $nestedData['loan_id'] = $post->loan_id;
                $nestedData['date'] = date('d/m/Y',strtotime($post->date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" data-id="'.$post->id.'" class="dropdown-item view">বিস্তারিত</a>
                                                        <a href="javascript:void(0);" data-id="'.$post->id.'" class="dropdown-item edit">সম্পাদন করুন</a>
                                                        <a href="javascript:void(0);" data-id="'.$post->id.'" onclick="deleteConfirmation('.$post->id.')" class="dropdown-item delete"> ডিলেট করুন</a>
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
        return view('dailySavings.create');
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
        $data['account_no'] = 'DS'.$str_pad;
        $validator = Validator::make($data, [
            'account_no' => 'required|unique:daily_savings,account_no',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $dailySavings = DailySavings::create($data);
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
        $saving = DailySavings::with('member')->find($id);
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


        return view('dailySavings.show',compact('saving','guarantorData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $savings = DailySavings::find($id);
        $nominee = Nominee::where('account_no',$savings->account_no)->first();
        if ($nominee === null) {
            $nominee = new Nominee(['account_no' => $savings->account_no]);
        }
        $nominee->save();

        return view('dailySavings.edit',compact('savings','nominee'));
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
        $data['account_no'] = 'DS'.$str_pad;

        $dailySavings = DailySavings::find($id);
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

        return redirect()->route('daily-savings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $savings = DailySavings::find($id);
        $transactions = Transaction::where('account_no',$savings->account_no)->delete();
        $loans = DailyLoan::where('account_no',$savings->account_no)->delete();
        $accountClosing = AccountClosing::where('type','daily')->where('account_no',$savings->account_no)->delete();
        $nominee = Nominee::where('account_no',$savings->account_no)->delete();
        $guarantor = Guarantor::where('account_no',$savings->account_no)->delete();
        $collection = DailyCollection::where('account_no',$savings->account_no)->delete();
        $delete = DailySavings::destroy($id);

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
        $account_no = 'DS'.$str_pad;
        $savings = DailySavings::where('account_no',$account_no)->count();
        return $savings;
    }

    public function savingsDetails($id)
    {
        $savings = DailySavings::find($id);
        $member = Member::find($savings->member_id);
        $dailySavings = DailySavings::where('member_id',$member->id)->where('status','active')->sum('total');
        $dailyLoans = DailyLoan::where('member_id',$member->id)->where('status','active')->sum('balance');
        //$dailyLoans = DailyLoan::where('member_id',$id)->where('status','active')->get();
        $data = [];
        $data['member'] = $member;
        $data['daily_savings'] = $dailySavings;
        $data['daily_loans'] = $dailyLoans;
        $data['account_no'] = $savings->account_no;
        $data['guarantor_loan'] = guarantorLoan($member->id);

        return json_encode($data);
    }

    public function guarantorDetails($id)
    {
        $data = getMemberDetails($id);
        return json_encode($data);
    }
    public function getSavings(Request $request)
    {
        $account = $request->account_no;
        $date2 = $request->date;
        $savings = DailySavings::with('member')->where('account_no',$account)->first();
        $loan = DailyLoan::where('account_no',$account)->where('status','active')->first();
        $data = [];
        $data['savings'] = $savings;
        $data['loan'] = $loan?$loan:"";
        if ($loan) {
            $totalInstallments = countDaysExcludingFridays($loan->date, $date2);
            $collections = DailyCollection::where('loan_id', $loan->id)->get();
            $dueInstallment = $totalInstallments - $collections->count();
            $data['dueInstallment'] = $dueInstallment;
        }

        return json_encode($data);

    }


}
