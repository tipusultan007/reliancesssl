<?php

namespace App\Http\Controllers;

use App\Models\Fdr;
use App\Models\FdrClosing;
use App\Models\FdrCollection;
use App\Models\FdrDeposit;
use App\Models\FdrWithdraw;
use App\Models\FdrWithdrawDetail;
use App\Models\ProfitDetail;
use App\Models\Transaction;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FdrCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profitCollections.index');
    }
    public function dataProfitCollections(Request $request)
    {
        $totalData = FdrCollection::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = FdrCollection::with('member')
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  FdrCollection::with('member')
                ->where('account_no','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = FdrCollection::where('account_no','LIKE',"%{$search}%")
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
                $nestedData['fdr_withdraw'] = $post->fdr_withdraw;
                $nestedData['fdr_balance'] = $post->fdr_balance;
                $nestedData['profit'] = $post->profit;
                $nestedData['date'] = date('d/m/Y',strtotime($post->date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" data-id="'.$post->id.'" class="dropdown-item view">বিস্তারিত</a>
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
    public function dataProfitByAccount(Request $request)
    {
        $totalData = FdrCollection::where('account_no',$request->account_no)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = FdrCollection::with('member')
                ->where('account_no',$request->account_no)->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  FdrCollection::with('member')
                ->where('account_no',$request->account_no)
                ->where('account_no','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = FdrCollection::where('account_no',$request->account_no)->where('account_no','LIKE',"%{$search}%")
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
                $nestedData['fdr_withdraw'] = $post->fdr_withdraw;
                $nestedData['fdr_balance'] = $post->fdr_balance;
                $nestedData['profit'] = $post->profit;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (empty($request->input('fdr_withdraw')) && empty($request->input('profit')))
        {
            return "empty";
        }else{
            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $fdr = Fdr::where('account_no', $data['account_no'])->first();
            $data['member_id'] = $fdr->member_id;
            $data['user_id'] = Auth::id();
            $generatedProfits = $this->generateProfit($fdr->id);
            $collection = FdrCollection::create($data);

            foreach ($generatedProfits as $item)
            {
                ProfitDetail::create([
                    'fdr_collection_id' => $collection->id,
                    'fdr_deposit_id' => $item['fdr_deposit_id'],
                    'amount' => $item['profit'],
                    'installments' => $item['due'],
                    'trx_id' => $collection->trx_id,
                ]);
            }

            if ($collection->profit>0) {
                $fdr->profit +=$collection->profit;
                $fdr->save();
                $collection->fdr_balance = $fdr->fdr_balance;
                $collection->Save();

                Transaction::create([
                    'transaction_category_id' => 20,
                    'date' => $collection->date,
                    'trx_id' => $collection->trx_id,
                    'amount' => $collection->profit,
                    'account_no' => $collection->account_no,
                    'member_id' => $collection->member_id,
                    'user_id' => $collection->user_id
                ]);
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $collection = FdrCollection::find($id);
        $fdr = Fdr::where('account_no',$collection->account_no)->first();

        /*if ($collection->fdr_withdraw)
        {
            $fdr->fdr_balance += $collection->fdr_withdraw;
            $fdr->save();
            $trx = Transaction::where('transaction_category_id',19)->where('trx_id',$collection->trx_id)->delete();
        }*/

        if ($collection->profit)
        {
            $fdr->profit -= $collection->profit;
            $fdr->save();
            ProfitDetail::where('fdr_collection_id',$collection->id)->delete();
            $trx = Transaction::where('transaction_category_id',20)->where('trx_id',$collection->trx_id)->delete();
        }

        $delete = FdrCollection::destroy($id);
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

    public function getFdr($accountNo)
    {
        $fdr = Fdr::with('member')->where('account_no',$accountNo)->first();
        $data['fdr'] = $fdr;
        $data['member'] = $fdr->member;
        $data['profit'] = $this->generateProfit($fdr->id);
        return json_encode($data);
    }

    public function generateProfit($fdrId)
    {
        $deposits = FdrDeposit::where('fdr_id',$fdrId)->get();
        $profit = [];
        $total = 0;
        foreach ($deposits as $deposit)
        {
            $profitCount = ProfitDetail::where('fdr_deposit_id',$deposit->id)->sum('installments');
            $profitRate = $deposit->remain*$deposit->profit_rate/100;
            $months = $this->countMonths($deposit->date,date('Y-m-d'));
            $due = $months - $profitCount;
            $data['fdr_deposit_id'] = $deposit->id;
            $data['rate'] = $profitRate;
            $data['due'] = $due ;
            $data['profit'] = $profitRate * $due ;
            $total += $profitRate * $due;
            $profit[] = $data;
        }
        return $profit;
    }

    public function countMonths($date1,$date2)
    {
        $start_date = Carbon::parse($date1);
        $end_date = Carbon::parse($date2);

        $count = $start_date->diffInDays($end_date)/30;

        return (int)($count);
    }

    public function profitWithdraw($fdr, $date, $trxId)
    {
        $data['trx_id'] = $trxId;
        //$fdr = Fdr::find($fdrId);
        $data['member_id'] = $fdr->member_id;
        $data['account_no'] = $fdr->account_no;
        $data['profit'] = generateProfit($fdr->id);
        $data['date'] = $date;
        $data['user_id'] = Auth::id();
        $generatedProfits = $this->generateProfit($fdr->id);
        $collection = FdrCollection::create($data);

        foreach ($generatedProfits as $item)
        {
            ProfitDetail::create([
                'fdr_collection_id' => $collection->id,
                'fdr_deposit_id' => $item['fdr_deposit_id'],
                'amount' => $item['profit'],
                'installments' => $item['due'],
                'trx_id' => $collection->trx_id,
            ]);
        }

        if ($collection->profit>0) {
            $fdr->profit +=$collection->profit;
            $fdr->save();
            $collection->fdr_balance = $fdr->fdr_balance;
            $collection->Save();

            Transaction::create([
                'transaction_category_id' => 20,
                'date' => $collection->date,
                'trx_id' => $collection->trx_id,
                'amount' => $collection->profit,
                'account_no' => $collection->account_no,
                'member_id' => $collection->member_id,
                'user_id' => $collection->user_id
            ]);
        }
    }

    public function fdrWithdraw($fdr, $date, $trxId)
    {
       $withdraw = FdrWithdraw::create([
            'fdr_id' => $fdr->id,
            'amount' => $fdr->fdr_balance,
            'remain' => 0,
            'member_id' => $fdr->member_id,
            'trx_id' => $trxId,
            'date' => $date,
            'user_id' => Auth::id()
        ]);
        foreach ($fdr->deposits->where('remain','>',0) as $deposit)
        {

            FdrWithdrawDetail::create([
                'fdr_withdraw_id' => $withdraw->id,
                'fdr_deposit_id' => $deposit->id,
                'amount' => $deposit->remain,
                'trx_id' => $withdraw->trx_id
            ]);

            $deposit->remain = 0;
            $deposit->save();

        }

        $transaction = Transaction::create([
            'transaction_category_id' => 22,
            'date' => $withdraw->date,
            'trx_id' => $withdraw->trx_id,
            'amount' => $withdraw->amount,
            'account_no' => $fdr->account_no,
            'member_id' => $fdr->member_id,
            'user_id' => $withdraw->user_id
        ]);
    }


    public function fdrClosing(Request $request)
    {
        $fdr = Fdr::with('deposits')->find($request->fdr_id);
        $trxId = Str::uuid();
        $date = $request->date;
        $data = $request->all();
        $data['trx_id'] = $trxId;
        $data['user_id'] = Auth::id();
        $data['member_id'] = $fdr->member_id;
        //dd($data);
        try {
            $profit = $this->profitWithdraw($fdr, $date, $trxId);
        }catch (Exception $e)
        {

        }
        try {
            $withdraw = $this->fdrWithdraw($fdr,$date,$trxId);
        }catch (Exception $e)
        {

        }
        try {
            $closing = FdrClosing::create($data);
        }catch (Exception $e)
        {

        }
        $fdr->fdr_balance = 0;
        $fdr->status = 'closed';
        $fdr->save();
        $closingFee = Transaction::create([
            'transaction_category_id' => 25,
            'date' => $date,
            'trx_id' => $trxId,
            'amount' => $request->closing_fee,
            'account_no' => $fdr->account_no,
            'member_id' => $fdr->member_id,
            'user_id' => Auth::id()
        ]);
        return back();
    }

    public function activeFdr($fdrId)
    {
        $fdr = Fdr::with('deposits')->find($fdrId);
        $closedFdr = FdrClosing::where('fdr_id',$fdrId)->first();
        $profitCollection = FdrCollection::where('trx_id',$closedFdr->trx_id)->first();
        if ($profitCollection) {
            $profitItems = ProfitDetail::where('fdr_collection_id', $profitCollection->id)->delete();
            $fdr->profit -= $profitCollection->profit;
            $fdr->save();
        }
        $withdraw = FdrWithdraw::where('trx_id',$closedFdr->trx_id)->first();
        $withdrawItems = FdrWithdrawDetail::where('fdr_withdraw_id',$withdraw->id)->get();
        if ($withdraw){
            foreach ($withdrawItems as $item)
            {
                $deposit = FdrDeposit::find($item->fdr_deposit_id);
                $deposit->remain = $item->amount;
                $deposit->save();

                $item->delete();
            }

            $fdr->fdr_balance = $withdraw->amount;
            $fdr->save();

            $withdraw->delete();
        }

        $fdr->status = 'active';
        $fdr->save();
        $profitCollection->delete();
        $closedFdr->delete();
        $transactions = Transaction::where('trx_id',$closedFdr->trx_id)->delete();
        return back();
    }
    public function getProfitDetails($id)
    {
        $withdraw = FdrCollection::with('fdr','member')->find($id);

        return json_encode($withdraw);
    }

}
