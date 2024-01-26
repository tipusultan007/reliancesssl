<?php

namespace App\Http\Controllers;

use App\Models\AccountClosing;
use App\Models\DailyCollection;
use App\Models\DailyLoan;
use App\Models\DailySavings;
use App\Models\DailySavingsClosing;
use App\Models\Fdr;
use App\Models\FdrClosing;
use App\Models\FdrCollection;
use App\Models\FdrDeposit;
use App\Models\FdrWithdraw;
use App\Models\FdrWithdrawDetail;
use App\Models\Guarantor;
use App\Models\Introducer;
use App\Models\Member;
use App\Models\MonthlyCollection;
use App\Models\MonthlyLoan;
use App\Models\MonthlySaving;
use App\Models\MonthlySavingsClosing;
use App\Models\Nominee;
use App\Models\ProfitDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('members.index');
    }

    public function membersData(Request $request)
    {
        $columns = array(
            1 =>'name',
            5 =>'join_date',
            8 => 'status',
        );

        $totalData = Member::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {
            $posts = Member::with('daily','monthly','dailyLoan','monthlyLoan','fdr')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  Member::with('daily','monthly','dailyLoan','monthlyLoan','fdr')
                ->where('nid_no','LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->orWhere('phone', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Member::where('nid_no','LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->orWhere('phone', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $status = '';
                if ($post->status=='active')
                {
                    $status = '<span class="badge bg-success pt-1">সক্রিয়</span>';
                }else{
                    $status = '<span class="badge bg-danger pt-1">বন্ধ</span>';
                }
                $depositBalance = $post->daily->sum('total') + $post->monthly->sum('total') + $post->fdr->sum('fdr_balance');
                $loanBalance = $post->dailyLoan->sum('balance') + $post->monthlyLoan->sum('balance');
                $nestedData['id'] = $post->id;
                $nestedData['deposited'] = $depositBalance;
                $nestedData['remainLoan'] = $loanBalance;
                $nestedData['name'] = $post->name;
                $nestedData['photo'] = '<img src="'.asset('storage').'/'.$post->photo.'" class="img-fluid" alt="'.$post->name.'">';
                $nestedData['father_name'] = $post->father_name??'';
                $nestedData['phone'] = $post->phone??'';
                $nestedData['nid_no'] = $post->nid_no??'';
                $nestedData['status'] = $status??'';
                $nestedData['present_address'] = $post->present_address??'';
                $nestedData['join_date'] = date('d/m/Y',strtotime($post->join_date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="'.route('members.show',$post->id).'" class="dropdown-item">View</a>
                                                        <a href="'.route('members.edit',$post->id).'" class="dropdown-item">Edit</a>
                                                        <a href="javascript:void(0);" data-id="'.$post->id.'" onclick="deleteConfirmation('.$post->id.')" class="dropdown-item">Delete</a>
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
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$request->validate([
            'nid' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birth_id' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'introducer_signature' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);*/
        $data = $request->all();
        if ($request->hasFile('nid')) {
            $imagePath = upload_image($request->file('nid'), 'nid');
            $data['nid'] = $imagePath;
        }
        if ($request->hasFile('birth_id')) {
            $imagePath = upload_image($request->file('birth_id'), 'birth_cert');
            $data['birth_id'] = $imagePath;
        }
        if ($request->hasFile('photo')) {
            $imagePath = upload_image($request->file('photo'), 'photo');
            $data['photo'] = $imagePath;
        }
        if ($request->hasFile('signature')) {
            $imagePath = upload_image($request->file('signature'), 'signature');
            $data['signature'] = $imagePath;
        }
        if ($request->hasFile('introducer_signature')) {
            $imagePath = upload_image($request->file('introducer_signature'), 'signature');
            $data['introducer_signature'] = $imagePath;
        }
        $member = Member::create($data);
        $data['member_id'] = $member->id;
        $introducer = Introducer::create($data);
        return "success";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
        $dataLoan = [];
        $member = Member::with('daily','dailyLoan','monthly','monthlyLoan')->find($id);
        $savings = DailySavings::where('member_id',$id)->get();
        foreach ($member->daily as $daily)
        {
            $nested['account_no'] = $daily->account_no;
            $nested['id'] = $daily->id;
            $nested['date'] = date('d/m/Y',strtotime($daily->date));
            $nested['balance'] = $daily->total;
            $nested['status'] = $daily->status;
            $nested['type'] = 'daily';
            $data[] = $nested;
        }
        foreach ($member->monthly as $monthly)
        {
            $nested['account_no'] = $monthly->account_no;
            $nested['id'] = $monthly->id;
            $nested['date'] = date('d/m/Y',strtotime($monthly->date));
            $nested['balance'] = $monthly->total;
            $nested['status'] = $monthly->status;
            $nested['type'] = 'monthly';
            $data[] = $nested;
        }
        foreach ($member->dailyLoan as $daily)
        {
            $nested['account_no'] = $daily->account_no;
            $nested['id'] = $daily->id;
            $nested['date'] = date('d/m/Y',strtotime($daily->date));
            $nested['balance'] = $daily->balance;
            $nested['status'] = $daily->status;
            $nested['type'] = 'daily';
            $dataLoan[] = $nested;
        }
        foreach ($member->monthlyLoan as $monthly)
        {
            $nested['account_no'] = $monthly->account_no;
            $nested['id'] = $monthly->id;
            $nested['date'] = date('d/m/Y',strtotime($monthly->date));
            $nested['balance'] = $monthly->balance;
            $nested['status'] = $monthly->status;
            $nested['type'] = 'monthly';
            $dataLoan[] = $nested;
        }
        return view('members.show',compact('member','savings','data','member','dataLoan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $member = Member::find($id);
        $introducerData = [
            'member_id' => $id,
        ];
        $introducer = Introducer::firstOrCreate($introducerData);
        return view('members.edit',compact('member','introducer'));
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
        $member = Member::find($id);
        $introducer = Introducer::where('member_id',$member->id)->first();

        if ($request->hasFile('nid')) {
            $imagePath = upload_image($request->file('nid'), 'nid');
            $data['nid'] = $imagePath;
        }else{
            unset($data['nid']);
        }
        if ($request->hasFile('birth_id')) {
            $imagePath = upload_image($request->file('birth_id'), 'birth_cert');
            $data['birth_id'] = $imagePath;
        }else{
            unset($data['birth_id']);
        }
        if ($request->hasFile('photo')) {
            $imagePath = upload_image($request->file('photo'), 'photo');
            $data['photo'] = $imagePath;
        }else{
            unset($data['photo']);
        }
        if ($request->hasFile('signature')) {
            $imagePath = upload_image($request->file('signature'), 'signature');
            $data['signature'] = $imagePath;
        }else{
            unset($data['signature']);
        }
        if ($request->hasFile('introducer_signature')) {
            $imagePath = upload_image($request->file('introducer_signature'), 'signature');
            $data['introducer_signature'] = $imagePath;
        }else{
            unset($data['introducer_signature']);
        }

        $member->update($data);
        $data['member_id'] = $member->id;

        if ($introducer) {
            if (array_key_exists('exist_member_id', $data)) {
                $introducer->exist_member_id = $data['exist_member_id'];
            }

            $introducer->introducer_name = $data['introducer_name'];
            $introducer->introducer_father = $data['introducer_father'];
            $introducer->introducer_address = $data['introducer_address'];
            $introducer->introducer_mobile = $data['introducer_mobile'];

            $introducer->save();
        }else{
            Introducer::create($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        Transaction::where('member_id',$id)->delete();
        $dailySavings = DailySavings::where('member_id',$id)->get();
        foreach ($dailySavings as $row)
        {
            Nominee::where('account_no',$row->account_no)->delete();
            $row->delete();
        }
        $monthlySavings = MonthlySaving::where('member_id',$id)->get();
        foreach ($monthlySavings as $row)
        {
            Nominee::where('account_no',$row->account_no)->delete();
            $row->delete();
        }
        $dailyLoans = DailyLoan::where('member_id',$id)->get();
        foreach ($dailyLoans as $row)
        {
            Guarantor::where('account_no',$row->account_no)->delete();
            $row->delete();
        }
        $monthlyLoans = MonthlyLoan::where('member_id',$id)->get();
        foreach ($monthlyLoans as $row)
        {
            Guarantor::where('account_no',$row->account_no)->delete();
            $row->delete();
        }
        $fdrs = Fdr::where('member_id',$id)->get();
        foreach ($fdrs as $row)
        {
            FdrDeposit::where('fdr_id',$row->id)->delete();
            $fdrWithdraws = FdrWithdraw::where('fdr_id',$row->id)->get();
            foreach ($fdrWithdraws as $fdrWithdraw)
            {
                FdrWithdrawDetail::where('fdr_withdraw_id',$fdrWithdraw->id)->delete();
                $fdrWithdraw->delete();
            }
            Nominee::where('account_no',$row->account_no)->delete();
        }

        $fdrCollections = FdrCollection::where('member_id',$id)->get();
        foreach ($fdrCollections as $fdrCollection)
        {
            ProfitDetail::where('fdr_collection_id',$fdrCollection->id)->delete();
            $fdrCollection->delete();
        }
        FdrClosing::where('member_id',$id)->delete();
        Fdr::where('member_id',$id)->delete();
        AccountClosing::where('member_id',$id)->delete();
        DailyCollection::where('member_id',$id)->delete();
        MonthlyCollection::where('member_id',$id)->delete();
        $delete = Member::destroy($id);

        // check data deleted or not
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

    public function getDetails($id)
    {
        $member = Member::find($id);
        $dailySavings = DailySavings::where('member_id',$id)->where('status','active')->sum('total');
        $dailyLoans = DailyLoan::where('member_id',$id)->where('status','active')->sum('balance');
        //$dailyLoans = DailyLoan::where('member_id',$id)->where('status','active')->get();
        $data = [];
        $data['member'] = $member;
        $data['daily_savings'] = $dailySavings;
        $data['daily_loans'] = $dailyLoans;

        return json_encode($data);
    }

    public function dataMemberAccounts($id)
    {
        $data = [];
        $member = Member::find($id);
        foreach ($member->daily()->get() as $daily)
        {
            $nested['account_no'] = $daily->account_no;
            $nested['date'] = date('d/m/Y',strtotime($daily->date));
            $nested['balance'] = $daily->total;
            $nested['status'] = $daily->status;
            $nested['type'] = 'daily';
            $data[] = $nested;
        }
        foreach ($member->monthly()->get() as $monthly)
        {
            $nested['account_no'] = $monthly->account_no;
            $nested['date'] = date('d/m/Y',strtotime($monthly->date));
            $nested['balance'] = $monthly->total;
            $nested['status'] = $monthly->status;
            $nested['type'] = 'monthly';
            $data[] = $nested;
        }

        dd($data);
    }

}
