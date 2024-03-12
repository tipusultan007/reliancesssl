<?php

namespace App\Http\Controllers;

use App\Models\Due;
use App\Models\MonthlyCollection;
use App\Models\MonthlyLoan;
use App\Models\MonthlySaving;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MonthlyCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('monthlyCollections.index');
    }

    public function changeStatus(Request $request)
    {
        $loan = MonthlyLoan::find($request->id);
        $loan->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success','ঋণের স্ট্যাটাস পরিবর্তন করা হয়েছে!');
    }
    public function dataCollections(Request $request)
    {
        $totalData = MonthlyCollection::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = MonthlyCollection::with('member')
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  MonthlyCollection::with('member')
                ->where('account_no','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = MonthlyCollection::where('account_no','LIKE',"%{$search}%")->count();
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
                $nestedData['monthly_amount'] = $post->monthly_amount;
                $nestedData['monthly_interest'] = $post->monthly_interest;
                $nestedData['loan_installment'] = $post->loan_installment;
                $nestedData['balance'] = $post->balance;
                $nestedData['late_fee'] = $post->late_fee;
                $nestedData['due'] = $post->due;
                $nestedData['due_return'] = $post->due_return;
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

    public function dataCollectionsByAccount(Request $request)
    {
        $totalData = MonthlyCollection::where('account_no',$request->account_no)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = MonthlyCollection::with('member')
                ->where('account_no',$request->account_no)
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
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
                $nestedData['monthly_amount'] = $post->monthly_amount;
                $nestedData['monthly_installments'] = $post->monthly_installments;
                $nestedData['monthly_interest'] = $post->monthly_interest;
                $nestedData['loan_installment'] = $post->loan_installment;
                $nestedData['balance'] = $post->balance;
                $nestedData['late_fee'] = $post->late_fee;
                $nestedData['due'] = $post->due;
                $nestedData['due_return'] = $post->due_return;
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
    /*public function store(Request $request)
    {
        $data = $request->all();
        $saving = MonthlySaving::where('account_no',$data['account_no'])->first();
        $data['member_id'] = $saving->member_id;
        $data['monthly_saving_id'] = $saving->id;
        $data['user_id'] = Auth::id();
        $data['trx_id'] = Str::uuid();

        if (empty($request->input('monthly_installments'))
            && empty($request->input('interest_installments'))
            && empty($request->input('loan_installment'))
            && empty($request->input('due_return'))
        )
        {
            return "empty";
        }else{
            $collection = MonthlyCollection::create($data);
            if ($collection->monthly_installments>0)
            {
                $saving->deposit += $collection->monthly_amount;
                $saving->total += $collection->monthly_amount;
                $saving->save();

                Transaction::create([
                    'transaction_category_id' => 3,
                    'date' => $collection->date,
                    'trx_id' => $collection->trx_id,
                    'amount' => $collection->monthly_amount,
                    'account_no' => $collection->account_no,
                    'member_id' => $collection->member_id,
                    'user_id' => $collection->user_id,
                    'type' => 'debit',
                ]);
            }

            if ($collection->loan_installment>0)
            {
                $loan = MonthlyLoan::where('account_no',$collection->account_no)->where('status','active')->first();
                if ($loan) {
                    $loan->balance -= $collection->loan_installment;
                    $loan->paid_loan += $collection->loan_installment;
                    $loan->save();
                    Transaction::create([
                        'transaction_category_id' => 9,
                        'date' => $collection->date,
                        'loan_id' => $collection->loan_id,
                        'trx_id' => $collection->trx_id,
                        'amount' => $collection->loan_installment,
                        'account_no' => $collection->account_no,
                        'member_id' => $collection->member_id,
                        'user_id' => $collection->user_id,
                        'type' => 'debit',
                    ]);

                    $collection->loan_id = $loan->id;
                    $collection->balance = $loan->balance;
                    $collection->save();
                }

            }
            if ($collection->interest_installments>0)
            {
                $loan = MonthlyLoan::where('account_no',$collection->account_no)->where('status','active')->first();
                if ($loan) {
                    Transaction::create([
                        'transaction_category_id' => 10,
                        'date' => $collection->date,
                        'trx_id' => $collection->trx_id,
                        'loan_id' => $collection->loan_id,
                        'amount' => $collection->monthly_interest,
                        'account_no' => $collection->account_no,
                        'member_id' => $collection->member_id,
                        'user_id' => $collection->user_id,
                        'type' => 'debit',
                    ]);
                    $collection->loan_id = $loan->id;
                    $collection->balance = $loan->balance;
                    $collection->save();

                    $loan->paid_interest += $collection->monthly_interest;
                    $loan->save();
                }
            }
            if ($collection->extra_interest>0)
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
            }
            if ($collection->due>0)
            {
                $due = Due::where('account_no',$collection->account_no)->where('remain','>',0)->first();
                if ($due) {
                    $due->due += $collection->due;
                    $due->remain += $collection->due;
                    $due->save();

                    Transaction::create([
                        'transaction_category_id' => 12,
                        'date' => $collection->date,
                        'trx_id' => $collection->trx_id,
                        'amount' => $collection->due_return,
                        'account_no' => $collection->account_no,
                        'member_id' => $collection->member_id,
                        'user_id' => $collection->user_id,
                        'type' => 'credit',
                    ]);

                }else{
                    Due::create([
                        'member_id' => $collection->member_id,
                        'account_no' => $collection->account_no,
                        'due' => $collection->due,
                        'remain' => $collection->due
                    ]);
                    Transaction::create([
                        'transaction_category_id' => 12,
                        'date' => $collection->date,
                        'trx_id' => $collection->trx_id,
                        'amount' => $collection->due,
                        'account_no' => $collection->account_no,
                        'member_id' => $collection->member_id,
                        'user_id' => $collection->user_id,
                        'type' => 'credit',
                    ]);
                }
            }
            if ($collection->due_return>0)
            {
                $due = Due::where('account_no',$collection->account_no)->where('remain','>',0)->first();
                $due->remain -= $collection->due_return;
                $due->save();
                Transaction::create([
                    'transaction_category_id' => 13,
                    'date' => $collection->date,
                    'trx_id' => $collection->trx_id,
                    'amount' => $collection->due_return,
                    'account_no' => $collection->account_no,
                    'member_id' => $collection->member_id,
                    'user_id' => $collection->user_id,
                    'type' => 'debit',
                ]);
            }
            if ($collection->late_fee>0)
            {

                Transaction::create([
                    'transaction_category_id' => 11,
                    'date' => $collection->date,
                    'trx_id' => $collection->trx_id,
                    'amount' => $collection->late_fee,
                    'account_no' => $collection->account_no,
                    'member_id' => $collection->member_id,
                    'user_id' => $collection->user_id,
                    'type' => 'debit',
                ]);
            }
        }

        return json_encode('success');
    }*/
    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();

            $data = $request->all();
            $saving = MonthlySaving::where('account_no', $data['account_no'])->first();

            // Extracted method to handle transaction creation
            $this->createTransaction($saving, $data);

            \DB::commit();

            return json_encode('success');
        } catch (\Exception $e) {
            \DB::rollBack();
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    private function createTransaction($saving, $data)
    {
        $collection = MonthlyCollection::create([
            'member_id' => $saving->member_id,
            'loan_id' => null,
            'account_no' => $data['account_no'],
            'trx_id' => Str::uuid(),
            'monthly_amount' => $data['monthly_amount'],
            'monthly_interest' => $data['monthly_interest'],
            'extra_interest' => $data['extra_interest'],
            'loan_installment' => $data['loan_installment'],
            'monthly_installments' => $data['monthly_installments'],
            'interest_installments' => $data['interest_installments'],
            'balance' => 0, // You might need to update this based on your logic
            'late_fee' => $data['late_fee'],
            'due' => $data['due'],
            'due_return' => $data['due_return'],
            'user_id' => Auth::id(),
            'date' => $data['date'],
            'monthly_saving_id' => $saving->id,
        ]);

        // Handle monthly_installments
        $this->handleMonthlyInstallments($collection, $saving);

        // Handle loan_installment
        $this->handleLoanInstallment($collection);

        // Handle interest_installments
        $this->handleInterestInstallments($collection);

        // Handle extra_interest
        $this->handleExtraInterest($collection);

        // Handle due
        $this->handleDue($collection);

        // Handle due_return
        $this->handleDueReturn($collection);

        // Handle late_fee
        $this->handleLateFee($collection);
    }

    private function handleMonthlyInstallments($collection, $saving)
    {
        if ($collection->monthly_installments > 0) {
            $saving->deposit += $collection->monthly_amount;
            $saving->total += $collection->monthly_amount;
            $saving->save();

            $this->createTransactionEntry(3, $collection, $collection->monthly_amount, 'debit');
        }
    }

    private function handleLoanInstallment($collection)
    {
        if ($collection->loan_installment > 0) {
            $loan = MonthlyLoan::where('account_no', $collection->account_no)->where('status', 'active')->first();

            if ($loan) {
                /*$loan->balance -= $collection->loan_installment;
                $loan->paid_loan += $collection->loan_installment;
                $loan->save();*/

                $this->createTransactionEntry(9, $collection, $collection->loan_installment, 'debit');

                $collection->loan_id = $loan->id;
                $collection->balance = $loan->remain_balance;
                $collection->save();
            }
        }
    }

    private function handleInterestInstallments($collection)
    {
        if ($collection->interest_installments > 0) {
            $loan = MonthlyLoan::where('account_no', $collection->account_no)->where('status', 'active')->first();

            if ($loan) {
                $this->createTransactionEntry(10, $collection, $collection->monthly_interest, 'debit');
                $collection->loan_id = $loan->id;
                $collection->balance = $loan->remain_balance;
                $collection->save();
            }
        }
    }

    private function handleExtraInterest($collection)
    {
        if ($collection->extra_interest > 0) {
            $this->createTransactionEntry(16, $collection, $collection->extra_interest, 'debit');
        }
    }

    private function handleDue($collection)
    {
        if ($collection->due > 0) {
            $due = Due::where('account_no', $collection->account_no)->where('remain', '>', 0)->first();

            if ($due) {
                $due->due += $collection->due;
                $due->remain += $collection->due;
                $due->save();

                $this->createTransactionEntry(12, $collection, $collection->due_return, 'credit');
            } else {
                Due::create([
                    'member_id' => $collection->member_id,
                    'account_no' => $collection->account_no,
                    'due' => $collection->due,
                    'remain' => $collection->due,
                ]);

                $this->createTransactionEntry(12, $collection, $collection->due, 'credit');
            }
        }
    }

    private function handleDueReturn($collection)
    {
        if ($collection->due_return > 0) {
            $due = Due::where('account_no', $collection->account_no)->where('remain', '>', 0)->first();
            $due->remain -= $collection->due_return;
            $due->save();

            $this->createTransactionEntry(13, $collection, $collection->due_return, 'debit');
        }
    }

    private function handleLateFee($collection)
    {
        if ($collection->late_fee > 0) {
            $this->createTransactionEntry(11, $collection, $collection->late_fee, 'debit');
        }
    }

    private function createTransactionEntry($categoryId, $collection, $amount, $type)
    {
        Transaction::create([
            'transaction_category_id' => $categoryId,
            'date' => $collection->date,
            'trx_id' => $collection->trx_id,
            'loan_id' => $collection->loan_id,
            'amount' => $amount,
            'account_no' => $collection->account_no,
            'member_id' => $collection->member_id,
            'user_id' => $collection->user_id,
            'type' => $type,
        ]);
    }

    public function importMonthlySavings(Request $request)
    {
        /*$request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $csvData = file_get_contents($file);
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $headers = $rows[0];
        $dataArray = [];
        foreach ($rows as $row) {
            if (!empty($row) && trim(implode('', $row)) !== '') {
                if ($row != $rows[0])
                {
                    $data = array_combine($headers, $row);
                    if (count($headers) === count($data)) {
                        $dataArray[] = $row;
                    }
                }
            }
        }
          dd($dataArray);
        return json_encode('success');*/
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $csvData = file_get_contents($file);
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $headers = $rows[0];
        /*$dataArray = array_filter(array_map(function ($row) use ($headers) {
            return ($row != $headers) && (!empty($row) && trim(implode('', $row)) !== '')
                ? array_combine($headers, array_map(function ($value, $header) {
                    return $header === 'date' ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : $value;
                }, $row, $headers))
                : null;
        }, array_slice($rows, 1)));*/
        $dataArray = array_filter(array_map(function ($row) use ($headers) {
            return ($row != $headers) && (!empty($row) && trim(implode('', $row)) !== '')
                ? array_combine($headers, $row)
                : null;
        }, array_slice($rows, 1)));

        foreach ($dataArray as $data)
        {
            $this->createTransactionForSavings($data);
        }
        return json_encode('success');

    }

    private function createTransactionForSavings($data)
    {
        $saving = MonthlySaving::where('account_no', $data['account_no'])->first();

        if ($saving) {
            $this->createTransactionFromCsv($saving, $data);
        }

    }
    private function createTransactionFromCsv($saving, $data)
    {
        $collection = MonthlyCollection::create([
            'member_id' => $saving->member_id,
            'loan_id' => null,
            'account_no' => $data['account_no'],
            'trx_id' => Str::uuid(),
            'monthly_amount' => $data['monthly_amount'],
            'monthly_interest' => $data['monthly_interest'],
            'extra_interest' => $data['extra_interest'],
            'loan_installment' => $data['loan_installment'],
            'monthly_installments' => $data['monthly_installments'],
            'interest_installments' => $data['interest_installments'],
            'balance' => 0, // You might need to update this based on your logic
            'late_fee' => $data['late_fee'],
            'user_id' => Auth::id(),
            'date' => Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d'),
            'monthly_saving_id' => $saving->id,
        ]);

        // Handle monthly_installments
        $this->handleMonthlyInstallments($collection, $saving);

        // Handle loan_installment
        $this->handleLoanInstallment($collection);

        // Handle interest_installments
        $this->handleInterestInstallments($collection);

        // Handle extra_interest
        $this->handleExtraInterest($collection);

        // Handle due
        $this->handleDue($collection);

        // Handle due_return
        $this->handleDueReturn($collection);

        // Handle late_fee
        $this->handleLateFee($collection);
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
        $saving = MonthlySaving::where('account_no', $data['account_no'])->first();
        $data['member_id'] = $saving->member_id;
        $data['monthly_saving_id'] = $saving->id;
        $data['user_id'] = Auth::id();

        if ($this->isDataEmpty($request, ['monthly_installments', 'interest_installments', 'loan_installment', 'due_return'])) {
            return "empty";
        }

        $collection = MonthlyCollection::find($id);
        $collection->update($data);

        $this->updateTransaction($collection, 1, 'monthly_amount', $saving);
        $this->updateTransaction($collection, 9, 'loan_installment', $saving);
        $this->updateTransaction($collection, 10, 'monthly_interest', $saving);
        $this->updateTransaction($collection, 16, 'extra_interest', $saving);
        $this->updateTransaction($collection, 12, 'due', $saving);
        $this->updateTransaction($collection, 13, 'due_return', $saving);
        $this->updateTransaction($collection, 11, 'late_fee');

        return json_encode($collection);
    }

    protected function updateTransaction($collection, $categoryId, $amountField, $saving = null)
    {
        $newAmount = $collection->$amountField;
        $transaction = Transaction::where('transaction_category_id', $categoryId)->where('trx_id', $collection->trx_id)->first();

        if (empty($newAmount) || $newAmount == 0) {
            if ($transaction) {
                $transaction->delete();
            }
        } else {
            if ($categoryId == 1 && $saving) {
                $this->updateSavingAndTransaction($saving, $collection, $transaction, $newAmount);
            } elseif ($categoryId == 9) {
                $this->updateLoanAndTransaction($collection, $transaction, $newAmount);
            } else {
                $this->createOrUpdateTransaction($categoryId, $collection, $transaction, $newAmount);
            }
        }
    }

    protected function updateSavingAndTransaction($saving, $collection, $transaction, $amount)
    {
        $this->createOrUpdateTransaction(1, $collection, $transaction, $amount);
    }

    protected function updateLoanAndTransaction($collection, $transaction, $amount)
    {
        $loan = MonthlyLoan::where('account_no', $collection->account_no)->where('status', 'active')->first();
        if ($loan) {
            $collection->loan_id = $loan->id;
            $collection->balance = $loan->remain_balance;
            $collection->save();
        }

        $this->createOrUpdateTransaction(9, $collection, $transaction, $amount);
    }

    protected function createOrUpdateTransaction($categoryId, $collection, $transaction, $amount)
    {
        if ($transaction) {
            $transaction->amount = $amount;
            $transaction->date = $collection->date;
            $transaction->save();
        } else {
            Transaction::create([
                'transaction_category_id' => $categoryId,
                'date' => $collection->date,
                'trx_id' => $collection->trx_id,
                'amount' => $amount,
                'account_no' => $collection->account_no,
                'member_id' => $collection->member_id,
                'user_id' => $collection->user_id,
                'type' => 'debit',
            ]);
        }
    }

    protected function isDataEmpty($request, $fields)
    {
        foreach ($fields as $field) {
            if (empty($request->input($field)) && $request->input($field) != 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = MonthlyCollection::find($id);
        if ($collection->monthly_amount>0)
        {
            $saving = MonthlySaving::where('account_no',$collection->account_no)->first();
            $saving->deposit -= $collection->monthly_amount;
            $saving->total -= $collection->monthly_amount;
            $saving->save();
        }

        if ($collection->loan_installment>0)
        {
            $loan = MonthlyLoan::find($collection->loan_id);
            $loan->balance += $collection->loan_installment;
            $loan->save();
        }
        if ($collection->due>0)
        {
            $due = Due::where('account_no',$collection->account_no)->first();
            if ($due)
            {
                $due->due -= $collection->due;
                $due->remain -= $collection->due;
                $due->save();
            }
        }
        if ($collection->due_return>0)
        {
            $due = Due::where('account_no',$collection->account_no)->first();
            if ($due)
            {
                $due->remain += $collection->due_return;
                $due->save();
            }
        }
        $transaction = Transaction::where('trx_id',$collection->trx_id)->delete();
        $delete = MonthlyCollection::destroy($id);

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

    public function getDetailsMonthly($id)
    {
        $collections = MonthlyCollection::with('member','monthlySaving')->find($id);
        //$monthly = MonthlySaving::where('account_no',$collections->account_no)->first();
        $loan = MonthlyLoan::where('account_no',$collections->account_no)->where('status','active')->latest()->first();
        $data['collection'] = $collections;
        $data['detail'] = getMonthlyInstallment($collections->account_no,$collections->date);
        return json_encode($data);
    }
}
