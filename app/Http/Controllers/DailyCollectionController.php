<?php

namespace App\Http\Controllers;

use App\Models\AddProfit;
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
        /*$collections = DailyCollection::where('loan_installment','>',0)->get();
        foreach ($collections as $collection)
        {
            $this->updateLoanTransactions($collection, 'loan_installment', 6, 7);
        }*/

        return view('dailyCollections.index');
    }
    public function dataCollections(Request $request)
    {
        $columns = array(
            1 =>'account_no',
            7=> 'date',
        );
        $totalData = DailyCollection::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {
            $posts = DailyCollection::with('member')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  DailyCollection::with('member')
                ->where('account_no','LIKE',"%{$search}%")
                ->orWhereHas('member',function ($query) use($search){
                    $query->where('name', 'LIKE',"%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = DailyCollection::with('member')
                ->where('account_no','LIKE',"%{$search}%")
                ->orWhereHas('member',function ($query) use($search){
                    $query->where('name', 'LIKE',"%{$search}%");
                })->count();
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
                $nestedData['profit_amount'] = $post->profit_amount;
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
        $saving = DailySavings::where('account_no', $data['account_no'])->first();

        if ($this->isDataEmpty($data)) {
            return "empty";
        }

        $data['member_id'] = $saving->member_id;
        $data['user_id'] = Auth::id();
        $data['trx_id'] = Str::uuid();

        $this->createDailyCollectionAndTransactions($data);

        return json_encode('success');
    }

    protected function isDataEmpty($data)
    {
        return empty($data['deposit']) && empty($data['withdraw']) && empty($data['loan_installment']) && empty($data['profit_amount']);
    }

    protected function createDailyCollectionAndTransactions($data)
    {
        $collection = DailyCollection::create($data);

        $this->createTransaction($collection, 1, 'deposit');
        $this->createTransaction($collection, 2, 'withdraw');
        $this->createLoanTransactions($collection);


        if ($collection->late_fee > 0) {
            $this->createTransaction($collection, 11, 'late_fee', 'credit');
        }
        if ($collection->profit_amount > 0) {
            AddProfit::create([
                'account_no' => $collection->account_no,
                'type' => 'daily',
                'amount' => $collection->profit_amount,
                'date' => $collection->date,
                'daily_collection_id' => $collection->id,
            ]);
            $this->createTransaction($collection, 23, 'profit_amount', 'debit');
        }

    }

    protected function createTransaction($collection, $categoryId, $amountField, $type = 'debit')
    {
        $amount = $collection->$amountField;

        if ($amount > 0) {
            Transaction::create([
                'transaction_category_id' => $categoryId,
                'date' => $collection->date,
                'trx_id' => $collection->trx_id,
                'amount' => $amount,
                'account_no' => $collection->account_no,
                'member_id' => $collection->member_id,
                'user_id' => $collection->user_id,
                'type' => $type,
            ]);
        }
    }

    protected function createLoanTransactions($collection)
    {
        if ($collection->loan_installment > 0) {
            $loan = DailyLoan::where('account_no', $collection->account_no)->where('status', 'active')->first();

            if ($loan) {
                $calculation = calculatePrincipal($loan->loan_amount, $collection->loan_installment, $loan->total);
                $principal = $calculation['loan'];
                $interest = $calculation['interest'];

                $collection->loan_id = $loan->id;
                $collection->loan_return = $principal;
                $collection->interest = $interest;
                $collection->save();

                $this->createTransaction($collection, 6, 'loan_return', 'credit');
                $this->createTransaction($collection, 7, 'interest', 'credit');

                $collection->loan_balance = $loan->total_balance;
                $collection->save();
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
        $data = $request->all();
        $dailyCollection = DailyCollection::findOrFail($id);

        $this->updateDailyCollectionAndTransactions($dailyCollection, $data);

        return json_encode('success');
    }

    protected function updateDailyCollectionAndTransactions($dailyCollection, $data)
    {
        $dailyCollection->update($data);

        $this->updateTransaction($dailyCollection, 1, 'deposit');
        $this->updateTransaction($dailyCollection, 2, 'withdraw');
        $this->updateTransaction($dailyCollection, 11, 'late_fee', 'credit');
        $this->updateProfitTransaction($dailyCollection, 23, 'profit_amount', 'debit');
        $this->updateLoanTransactions($dailyCollection, 'loan_installment', 6, 7);

        $this->deleteTransactionIfEmpty($dailyCollection, 'deposit', 1);
        $this->deleteTransactionIfEmpty($dailyCollection, 'withdraw', 2);
    }

    protected function updateProfitTransaction($dailyCollection, $categoryId, $amountField, $type = 'debit')
    {
        $amount = $dailyCollection->$amountField;

        $existingTransaction = Transaction::where('trx_id', $dailyCollection->trx_id)
            ->where('transaction_category_id', $categoryId)
            ->first();

        $profit = AddProfit::where('daily_collection_id', $dailyCollection->id)->first();


        if ($amount > 0) {
            if ($profit) {
                $profit->amount = $dailyCollection->profit_amount;
                $profit->save();
            }else{
                AddProfit::create([
                    'account_no' => $dailyCollection->account_no,
                    'type' => 'daily',
                    'amount' => $dailyCollection->profit_amount,
                    'date' => $dailyCollection->date,
                    'daily_collection_id' => $dailyCollection->id,
                ]);
            }
            // Update or create the transaction
            if ($existingTransaction) {
                $existingTransaction->update([
                    'amount' => $amount,
                ]);

            } else {
                Transaction::create([
                    'transaction_category_id' => $categoryId,
                    'date' => $dailyCollection->date,
                    'trx_id' => $dailyCollection->trx_id,
                    'amount' => $amount,
                    'account_no' => $dailyCollection->account_no,
                    'member_id' => $dailyCollection->member_id,
                    'user_id' => $dailyCollection->user_id,
                    'type' => $type,
                ]);

            }
        } elseif ($existingTransaction) {
            // If the amount is 0 or empty, delete the existing transaction
            $existingTransaction->delete();
            $profit->delete();
        }
    }
    protected function updateTransaction($dailyCollection, $categoryId, $amountField, $type = 'debit')
    {
        $amount = $dailyCollection->$amountField;

        $existingTransaction = Transaction::where('trx_id', $dailyCollection->trx_id)
            ->where('transaction_category_id', $categoryId)
            ->first();

        if ($amount > 0) {
            // Update or create the transaction
            if ($existingTransaction) {
                $existingTransaction->update([
                    'amount' => $amount,
                ]);
            } else {
                Transaction::create([
                    'transaction_category_id' => $categoryId,
                    'date' => $dailyCollection->date,
                    'trx_id' => $dailyCollection->trx_id,
                    'amount' => $amount,
                    'account_no' => $dailyCollection->account_no,
                    'member_id' => $dailyCollection->member_id,
                    'user_id' => $dailyCollection->user_id,
                    'type' => $type,
                ]);
            }
        } elseif ($existingTransaction) {
            // If the amount is 0 or empty, delete the existing transaction
            $existingTransaction->delete();
        }
    }

    protected function updateLoanTransactions($dailyCollection, $field, $principalCategoryId, $interestCategoryId)
    {
        $newValue = $dailyCollection->$field;

        if ($newValue > 0) {
            $loan = DailyLoan::where('account_no', $dailyCollection->account_no)
                ->where('status', 'active')
                ->first();

            if ($loan) {
                $calculation = calculatePrincipal($loan->loan_amount, $newValue, $loan->total);
                $principal = $calculation['loan'];
                $interest = $calculation['interest'];
                $dailyCollection->loan_id = $loan->id;
                $dailyCollection->loan_return = $principal;
                $dailyCollection->interest = $interest;
                $dailyCollection->save();

                $this->updateTransaction($dailyCollection, $principalCategoryId, 'loan_return', 'debit');
                $this->updateTransaction($dailyCollection, $interestCategoryId, 'interest', 'debit');

                $dailyCollection->loan_balance = $loan->total_balance;
                $dailyCollection->save();

            }
        } else {
            // If the field is 0 or empty, delete the related transactions
            $this->deleteTransactionByCategory($dailyCollection, $principalCategoryId);
            $this->deleteTransactionByCategory($dailyCollection, $interestCategoryId);

            // Update the related fields in $dailyCollection
            $dailyCollection->loan_id = null;
            $dailyCollection->loan_installment = null;
            $dailyCollection->loan_balance = null;
            $dailyCollection->loan_return = null;
            $dailyCollection->interest = null;
            $dailyCollection->save();
        }
    }

    protected function deleteTransactionByCategory($dailyCollection, $categoryId)
    {
        $existingTransaction = Transaction::where('trx_id', $dailyCollection->trx_id)
            ->where('transaction_category_id', $categoryId)
            ->first();

        if ($existingTransaction) {
            $existingTransaction->delete();
        }
    }
    protected function deleteTransactionIfEmpty($dailyCollection, $amountField, $categoryId)
    {
        $amount = $dailyCollection->$amountField;

        if (empty($amount) || $amount == 0) {
            $existingTransaction = Transaction::where('trx_id', $dailyCollection->trx_id)
                ->where('transaction_category_id', $categoryId)
                ->first();

            if ($existingTransaction) {
                $existingTransaction->delete();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $collection = DailyCollection::find($id);
        $transaction = Transaction::where('trx_id',$collection->trx_id)->delete();
        AddProfit::where('daily_collection_id', $collection->id)->delete();
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
