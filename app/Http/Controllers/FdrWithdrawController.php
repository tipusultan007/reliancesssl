<?php

namespace App\Http\Controllers;

use App\Models\Fdr;
use App\Models\FdrCollection;
use App\Models\FdrDeposit;
use App\Models\FdrWithdraw;
use App\Models\FdrWithdrawDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FdrWithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fdrWithdraws.withdraw');
    }
    public function dataWithdraws(Request $request)
    {
        $totalData = FdrWithdraw::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if(empty($request->input('search.value')))
        {
            $posts = FdrWithdraw::with('member','fdr')
                ->offset($start)
                ->limit($limit)
                ->get();
        }else {
            $search = $request->input('search.value');

            $posts =  FdrWithdraw::with('member','fdr')
                ->whereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('fdr', function ($query) use ($search) {
                    $query->where('account_no', $search);
                })
                ->offset($start)
                ->limit($limit)
                ->get();

            $totalFiltered = FdrWithdraw::whereHas('member', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
                ->orWhereHas('fdr', function ($query) use ($search) {
                    $query->where('account_no', $search);
                })->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->member->name;
                $nestedData['account_no'] = $post->fdr->account_no;
                $nestedData['fdr_amount'] = $post->amount;
                $nestedData['fdr_balance'] = $post->remain;
                $nestedData['date'] = date('d-m-Y',strtotime($post->date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" data-id="'.$post->id.'" class="dropdown-item view">View</a>
                                                        <a href="javascript:void(0);" data-account_no="'.$post->fdr->account_no.'" data-id="'.$post->id.'"  data-amount="'.$post->amount.'" data-date="'.$post->date.'" class="dropdown-item edit">Edit</a>
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
        $data = $request->all();
        $tempAmount = $request->amount;
        $fdr = Fdr::where('account_no',$request->account_no)->first();
        $data['fdr_id'] = $fdr->id;
        $data['member_id'] = $fdr->member_id;
        $data['trx_id'] = Str::uuid();
        $data['user_id'] = Auth::id();
        $fdrWithdraw = FdrWithdraw::create($data);
        $deposits = FdrDeposit::where('fdr_id',$fdr->id)->where('remain','>',0)->get();

        foreach ($deposits as $deposit)
        {
                if ($tempAmount == 0) {
                    break;
                } elseif ($deposit->remain <= $tempAmount) {
                    $tempRemain = $deposit->remain;
                    $tempAmount -= $deposit->remain;
                    $deposit->remain -= $tempRemain;
                    $deposit->save();
                    $fdr->fdr_balance -= $tempRemain;
                    $fdr->save();
                    FdrWithdrawDetail::create([
                        'fdr_withdraw_id' => $fdrWithdraw->id,
                        'fdr_deposit_id' => $deposit->id,
                        'amount' => $tempRemain,
                        'trx_id' => $fdrWithdraw->trx_id
                    ]);
                } elseif ($deposit->remain >= $tempAmount) {
                    $deposit->remain -= $tempAmount;
                    $deposit->save();
                    $fdr->fdr_balance -= $tempAmount;
                    $fdr->save();
                    FdrWithdrawDetail::create([
                        'fdr_withdraw_id' => $fdrWithdraw->id,
                        'fdr_deposit_id' => $deposit->id,
                        'amount' => $tempAmount,
                        'trx_id' => $fdrWithdraw->trx_id
                    ]);
                    $tempAmount = 0;
                    break;
                }
        }
        $fdrWithdraw->remain = $fdr->fdr_balance;
        $fdrWithdraw->save();

        $transaction = Transaction::create([
            'transaction_category_id' => 22,
            'date' => $fdrWithdraw->date,
            'trx_id' => $fdrWithdraw->trx_id,
            'amount' => $fdrWithdraw->amount,
            'account_no' => $fdrWithdraw->fdr->account_no,
            'member_id' => $fdrWithdraw->member_id,
            'user_id' => $fdrWithdraw->user_id
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
        $data = $request->all();
        $fdrWithdraw = FdrWithdraw::find($id);
        $fdrWithdraw->update($data);
        $fdrWithdrawDetails = FdrWithdrawDetail::where('fdr_withdraw_id',$id)->get();
        foreach ($fdrWithdrawDetails as $detail)
        {
            $deposit = FdrDeposit::find($detail->fdr_deposit_id);
            $deposit->remain += $detail->amount;
            $deposit->save();
            $fdr = Fdr::find($deposit->fdr_id);
            $fdr->fdr_balance += $detail->amount;
            $fdr->save();
            $detail->delete();
        }
        $tempAmount = $request->amount;
        $fdr = Fdr::find($fdrWithdraw->fdr_id);
        $data['fdr_id'] = $fdr->id;
        $data['member_id'] = $fdr->member_id;
        //$data['trx_id'] = Str::uuid();
        $data['user_id'] = Auth::id();
        //$fdrWithdraw = FdrWithdraw::create($data);
        $deposits = FdrDeposit::where('fdr_id',$fdr->id)->where('remain','>',0)->get();

        foreach ($deposits as $deposit)
        {
            if ($tempAmount == 0) {
                break;
            } elseif ($deposit->remain <= $tempAmount) {
                $tempRemain = $deposit->remain;
                $tempAmount -= $deposit->remain;
                $deposit->remain -= $tempRemain;
                $deposit->save();
                $fdr->fdr_balance -= $tempRemain;
                $fdr->save();
                FdrWithdrawDetail::create([
                    'fdr_withdraw_id' => $fdrWithdraw->id,
                    'fdr_deposit_id' => $deposit->id,
                    'amount' => $tempRemain,
                    'trx_id' => $fdrWithdraw->trx_id
                ]);
            } elseif ($deposit->remain >= $tempAmount) {
                $deposit->remain -= $tempAmount;
                $deposit->save();
                $fdr->fdr_balance -= $tempAmount;
                $fdr->save();
                FdrWithdrawDetail::create([
                    'fdr_withdraw_id' => $fdrWithdraw->id,
                    'fdr_deposit_id' => $deposit->id,
                    'amount' => $tempAmount,
                    'trx_id' => $fdrWithdraw->trx_id
                ]);
                $tempAmount = 0;
                break;
            }
        }
        $fdrWithdraw->remain = $fdr->fdr_balance;
        $fdrWithdraw->save();

        $transaction = Transaction::where('transaction_category_id',22)->where('trx_id',$fdrWithdraw->trx_id)->first();
        $transaction->amount = $fdrWithdraw->amount;
        $transaction->date = $fdrWithdraw->date;
        $transaction->save();

        return response()->json([
            'message' => "success"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy($id)
    {
        $withdraw = FdrWithdraw::with('fdr')->find($id);
        $fdr = $withdraw->fdr;
        $details = FdrWithdrawDetail::where('fdr_withdraw_id',$id)->get();
        foreach ($details as $item)
        {
            $deposit = FdrDeposit::find($item->fdr_deposit_id);
            $deposit->remain += $item->amount;
            $deposit->save();
            $fdr->fdr_balance += $item->amount;
            $fdr->save();
            $item->delete();
        }
        $transaction = Transaction::where('transaction_category_id',22)->where('trx_id',$withdraw->trx_id)->delete();
        $withdraw->delete();

        return response()->json([
            'status' => "success"
        ]);
    }

    public function getDetails($id)
    {
        $withdraw = FdrWithdraw::with('fdr','member')->find($id);

        return json_encode($withdraw);
    }


}
