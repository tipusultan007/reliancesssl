<?php

namespace App\Http\Controllers;

use App\Models\Fdr;
use App\Models\FdrCollection;
use App\Models\FdrDeposit;
use App\Models\ProfitDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FdrDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fdrDeposits.deposit');
    }
    public function dataDeposits(Request $request)
    {
        $totalData = FdrDeposit::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if(empty($request->input('search.value')))
        {
            $posts = FdrDeposit::with('member','fdr')
                ->offset($start)
                ->limit($limit)
                ->get();
        }else {
            $search = $request->input('search.value');

            $posts =  FdrDeposit::with('member','fdr')
                ->whereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('fdr', function ($query) use ($search) {
                    $query->where('account_no', $search);
                })
                ->offset($start)
                ->limit($limit)
                ->get();

            $totalFiltered = FdrDeposit::whereHas('member', function ($query) use ($search) {
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
                if ($post->status=='active')
                {
                    $status = '<span class="badge bg-success">চলমান</span>';
                }else{
                    $status = '<span class="badge bg-danger">বন্ধ</span>';
                }
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->member->name;
                $nestedData['account_no'] = $post->fdr->account_no;
                $nestedData['fdr_amount'] = $post->amount;
                $nestedData['fdr_balance'] = $post->balance;
                $nestedData['profit_rate'] = $post->profit_rate;
                $nestedData['date'] = date('d-m-Y',strtotime($post->date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="'.route('fdr.show',$post->id).'" class="dropdown-item">View</a>
                                                        <a href="javascript:void(0);" data-account_no="'.$post->fdr->account_no.'" data-id="'.$post->id.'" data-profit_rate="'.$post->profit_rate.'" data-amount="'.$post->amount.'" data-date="'.$post->date.'" class="dropdown-item edit">Edit</a>
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
        $fdr = Fdr::where('account_no',$request->account_no)->first();
        $fdr->fdr_amount += $request->amount;
        $fdr->fdr_balance += $request->amount;
        $fdr->save();

        $fdrDeposit = FdrDeposit::create([
            'fdr_id' => $fdr->id,
            'amount' => $request->amount,
            'remain' => $request->amount,
            'balance' => $fdr->fdr_balance,
            'date' => $request->date,
            'profit_rate' => $request->profit_rate,
            'member_id' => $fdr->member_id,
            'trx_id' => Str::uuid(),
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
        $deposit = FdrDeposit::find($id);
        return view('fdrDeposits.edit',compact('deposit'));
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
        $data['remain'] = $request->amount;
        $deposit = FdrDeposit::find($id);
        $temp = FdrDeposit::find($id);
        $fdr = Fdr::where('account_no',$request->account_no)->first();
        if ($temp->fdr_id == $fdr->id)
        {
            $fdr->fdr_amount -= $temp->amount;
            $fdr->fdr_balance -= $temp->amount;
            $fdr->save();

            $deposit->update($data);

            $fdr->fdr_amount += $request->amount;
            $fdr->fdr_balance += $request->amount;
            $fdr->save();
            $deposit->balance = $fdr->fdr_balance;
            $deposit->save();
        }else{
            $tempFdr = Fdr::find($temp->fdr_id);
            $tempFdr->fdr_amount -= $temp->amount;
            $tempFdr->fdr_balance -= $temp->amount;
            $tempFdr->save();

            $data['fdr_id'] = $fdr->id;
            $data['user_id'] = Auth::id();
            $data['member_id'] = $fdr->member_id;
            $deposit->update($data);

            $fdr->fdr_amount += $request->amount;
            $fdr->fdr_balance += $request->amount;
            $fdr->save();
            $deposit->balance = $fdr->fdr_balance;
            $deposit->save();
        }

        $transaction = Transaction::where('transaction_category_id',21)->where('trx_id',$deposit->trx_id)->first();
        $transaction->amount = $deposit->amount;
        $transaction->date = $deposit->date;
        $transaction->fdr_deposit_id = $deposit->id;
        $transaction->member_id = $deposit->member_id;
        $transaction->account_no = $deposit->fdr->account_no;
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
        $deposit = FdrDeposit::find($id);
        $fdr = Fdr::find($deposit->fdr_id);
        $fdr->fdr_amount -=$deposit->amount;
        $fdr->fdr_balance -=$deposit->remain;
        $fdr->save();


        Transaction::where('transaction_category_id',21)->where('trx_id',$deposit->trx_id)->first();
        $profits = ProfitDetail::where('fdr_deposit_id',$deposit->id)->get();
        foreach ($profits as $profit)
        {
            $collection = FdrCollection::find($profit->fdr_collection_id);
            $transaction = Transaction::where('transaction_category_id',20)->where('trx_id',$profit->trx_id)->first();
            if ($transaction->amount == $profit->amount)
            {
                $transaction->delete();
            }else{
                $transaction->amount -= $profit->amount;
                $transaction->save();
            }
            if ($collection->profit == $profit->amount)
            {
                $collection->delete();
            }else{
                $collection->profit -=$profit->amount;
                $collection->save();
            }

            $fdr->profit -= $profit->amount;
            $fdr->save();
            $profit->delete();
        }

        $delete = FdrDeposit::find($id);
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

    public function fdrDeposits($id)
    {
        $list = FdrDeposit::where('fdr_id',$id)->get();
        return json_encode($list);
    }
}
