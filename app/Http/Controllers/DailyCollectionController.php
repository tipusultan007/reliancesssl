<?php

namespace App\Http\Controllers;

use App\Models\DailyCollection;
use App\Models\DailyLoan;
use App\Models\DailySavings;
use App\Models\Member;
use App\Models\Transaction;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DailyCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('dailyCollections.index');
    }
    public function dataCollections(Request $request)
    {
        $totalData = DailyCollection::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DailyCollection::with('member')
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  DailyCollection::join('members','members.id','=','daily_collections.member_id')
                ->with('member')
                ->where('daily_collections.account_no','LIKE',"%{$search}%")
                ->orWhere('members.name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = DailyCollection::join('members','members.id','=','daily_collections.member_id')
                ->where('daily_collections.account_no','LIKE',"%{$search}%")
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
        $saving = DailySavings::where('account_no',$data['account_no'])->first();
        $data['member_id'] = $saving->member_id;
        $data['user_id'] = Auth::id();
        $data['trx_id'] = Str::uuid();

        if (empty($request->input('deposit')) && empty($request->input('withdraw')) && empty($request->input('loan_installment')) )
        {
            return "empty";
        }else{
            $collection = DailyCollection::create($data);
            if ($collection->deposit>0)
            {
                $saving->deposit += $collection->deposit;
                $saving->total += $collection->deposit;
                $saving->save();

                Transaction::create([
                    'transaction_category_id' => 1,
                    'date' => $collection->date,
                    'trx_id' => $collection->trx_id,
                    'amount' => $collection->deposit,
                    'account_no' => $collection->account_no,
                    'member_id' => $collection->member_id,
                    'user_id' => $collection->user_id,
                    'type' => 'debit',
                ]);
            }
            if ($collection->withdraw>0)
            {
                $saving->withdraw += $collection->withdraw;
                $saving->total -= $collection->withdraw;
                $saving->save();


                Transaction::create([
                    'transaction_category_id' => 2,
                    'date' => $collection->date,
                    'trx_id' => $collection->trx_id,
                    'amount' => $collection->withdraw,
                    'account_no' => $collection->account_no,
                    'member_id' => $collection->member_id,
                    'user_id' => $collection->user_id,
                    'type' => 'debit',
                ]);
            }
            if ($collection->loan_installment>0)
            {
                $loan = DailyLoan::where('account_no',$collection->account_no)->where('status','active')->first();
                if ($loan) {
                    $interest = ($collection->loan_installment * $loan->interest_rate)/100;
                    $principal = $collection->loan_installment - $interest;
                    $loan->balance -= $collection->loan_installment;
                    $loan->paid_loan += $principal;
                    $loan->paid_interest += $interest;
                    $loan->save();

                    Transaction::create([
                        'transaction_category_id' => 6,
                        'date' => $collection->date,
                        'trx_id' => $collection->trx_id,
                        'loan_id' => $collection->loan_id,
                        'amount' => $principal,
                        'account_no' => $collection->account_no,
                        'member_id' => $collection->member_id,
                        'user_id' => $collection->user_id,
                        'type' => 'debit',
                    ]);
                    Transaction::create([
                        'transaction_category_id' => 7,
                        'date' => $collection->date,
                        'trx_id' => $collection->trx_id,
                        'loan_id' => $collection->loan_id,
                        'amount' => $interest,
                        'account_no' => $collection->account_no,
                        'member_id' => $collection->member_id,
                        'user_id' => $collection->user_id,
                        'type' => 'debit',
                    ]);

                    /*if ($collection->extra_interest>0)
                    {
                        Transaction::create([
                            'transaction_category_id' => 16,
                            'date' => $collection->date,
                            'trx_id' => $collection->trx_id,
                            'loan_id' => $collection->loan_id,
                            'amount' => $collection->extra_interest,
                            'account_no' => $collection->account_no,
                            'member_id' => $collection->member_id,
                            'user_id' => $collection->user_id,
                            'type' => 'debit',
                        ]);
                    }*/

                    $collection->loan_id = $loan->id;
                    $collection->loan_balance = $loan->balance;
                    $collection->loan_return = $principal;
                    $collection->interest = $interest;
                    $collection->save();
                }

            }
        }


        return json_encode('success');
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
        $collection = DailyCollection::find($id);
        $temp = DailyCollection::find($id);
        $saving = DailySavings::where('account_no',$temp->account_no)->first();
        $data = $request->all();
        $collection->update($data);

        if ($collection->deposit>0)
        {
            $saving->deposit -= $temp->deposit;
            $saving->total -= $temp->deposit;
            $saving->save();
            $saving->deposit += $collection->deposit;
            $saving->total += $collection->deposit;
            $saving->save();

            $transaction = Transaction::where('transaction_category_id',1)->where('trx_id',$collection->trx_id)->first();
            if($transaction)
            {
                $transaction->amount = $collection->deposit;
                $transaction->date = $collection->date;
                $transaction->save();
            }else{
                $transaction = Transaction::create([
                'transaction_category_id' => 1,
                'date' => $collection->date,
                'trx_id' => $collection->trx_id,
                'amount' => $collection->deposit,
                'account_no' => $collection->account_no,
                'member_id' => $collection->member_id,
                'user_id' => $collection->user_id,
                'type' => 'debit',
            ]);
            }
           /* Transaction::create([
                'transaction_category_id' => 1,
                'date' => $collection->date,
                'trx_id' => $collection->trx_id,
                'amount' => $collection->deposit,
                'account_no' => $collection->account_no,
                'member_id' => $collection->member_id,
                'user_id' => $collection->user_id,
                'type' => 'debit',
            ]);*/
        }
        if ($collection->withdraw>0)
        {
            $saving->withdraw -= $temp->withdraw;
            $saving->total += $temp->withdraw;
            $saving->save();

            $saving->withdraw += $collection->withdraw;
            $saving->total -= $collection->withdraw;
            $saving->save();

            $transaction = Transaction::where('transaction_category_id',2)->where('trx_id',$collection->trx_id)->first();
            if($transaction)
            {
                $transaction->amount = $collection->withdraw;
                $transaction->date = $collection->date;
                $transaction->save();
            }else{
               $transaction = Transaction::create([
                    'transaction_category_id' => 2,
                    'date' => $collection->date,
                    'trx_id' => $collection->trx_id,
                    'amount' => $collection->withdraw,
                    'account_no' => $collection->account_no,
                    'member_id' => $collection->member_id,
                    'user_id' => $collection->user_id,
                    'type' => 'debit',
                ]);
            }
        }
        if ($collection->loan_installment>0)
        {
            $loan = DailyLoan::where('account_no',$collection->account_no)->where('status','active')->first();
            if ($loan) {
                $loan->balance += $temp->loan_installment;
                $loan->paid_loan -= $temp->loan_return;
                $loan->paid_interest -= $temp->interest;
                $loan->save();

                $interest = ($collection->loan_installment * $loan->interest_rate)/100;
                $principal = $collection->loan_installment - $interest;
                $loan->balance -= $collection->loan_installment;
                $loan->paid_loan += $principal;
                $loan->paid_interest += $interest;
                $loan->save();

                $transactionLoan = Transaction::where('transaction_category_id',6)
                    ->where('trx_id',$collection->trx_id)->first();
                $transactionLoan->amount = $principal;
                $transactionLoan->date = $collection->date;
                $transactionLoan->save();

                $transactionInterest = Transaction::where('transaction_category_id',7)
                    ->where('trx_id',$collection->trx_id)->first();
                $transactionInterest->amount = $interest;
                $transactionInterest->date = $collection->date;
                $transactionInterest->save();

                $collection->loan_id = $loan->id;
                $collection->loan_balance = $loan->balance;
                $collection->loan_return = $principal;
                $collection->interest = $interest;
                $collection->save();
            }

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
        $collection = DailyCollection::find($id);
        if ($collection->deposit>0)
        {
            $saving = DailySavings::where('account_no',$collection->account_no)->first();
            $saving->deposit -= $collection->deposit;
            $saving->total -= $collection->deposit;
            $saving->save();
        }
        if ($collection->withdraw>0)
        {
            $saving = DailySavings::where('account_no',$collection->account_no)->first();
            $saving->withdraw -= $collection->withdraw;
            $saving->total += $collection->withdraw;
            $saving->save();
        }
        if ($collection->loan_installment>0)
        {
            $loan = DailyLoan::find($collection->loan_id);
            $loan->balance += $collection->loan_installment;
            $loan->paid_loan -= $collection->loan_return;
            $loan->paid_interest -= $collection->interest;
            $loan->save();
        }
        $transaction = Transaction::where('trx_id',$collection->trx_id)->delete();
        $delete = DailyCollection::destroy($id);

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
        $collections = DailyCollection::with('member')->find($id);
        return json_encode($collections);
    }
    public function deleteLoanCollection($id)
    {
        $record = DailyCollection::find($id);
        $loan = DailyLoan::find($record->loan_id);
        $loan->balance += $record->loan_installment;
        $loan->paid_loan -= $record->loan_return;
        $loan->paid_interest -= $record->interest;
        $loan->save();

        if ($record) {
            $record->loan_id = null;
            $record->loan_installment = 0;
            $record->interest = 0;
            $record->loan_return = 0;
            $record->loan_balance = 0;
            $record->late_fee = 0;
            $record->save();

            Transaction::where('transaction_category_id',6)->where('trx_id',$record->trx_id)->delete();
            Transaction::where('transaction_category_id',7)->where('trx_id',$record->trx_id)->delete();
        }
        if ($record->daily_saving_id == null)
        {
            $record->delete();
        }

        return redirect()->back()->with('success','Successfully deleted!');
    }

    public function deleteSavingCollection($id)
    {
        $record = DailyCollection::find($id);

        if ($record) {
            $record->deposit = 0;
            $record->withdraw = 0;
            $record->daily_saving_id = null;
            $record->total = 0;
            $record->save();

            Transaction::where('transaction_category_id',1)->where('trx_id',$record->trx_id)->delete();
            Transaction::where('transaction_category_id',2)->where('trx_id',$record->trx_id)->delete();
        }
        if ($record->loan_id == null)
        {
            $record->delete();
        }

        return redirect()->back()->with('success','Successfully deleted!');
    }
}
