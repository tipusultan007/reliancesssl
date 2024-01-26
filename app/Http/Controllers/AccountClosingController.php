<?php

namespace App\Http\Controllers;

use App\Models\AccountClosing;
use App\Models\DailyLoan;
use App\Models\DailySavings;
use App\Models\MonthlyLoan;
use App\Models\MonthlySaving;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountClosingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //dd($request->all());
        $accountClosing = AccountClosing::create($request->all());
        if ($accountClosing->type=="daily")
        {
            $savings = DailySavings::where('account_no',$accountClosing->account_no)->first();
            $savings->withdraw += $accountClosing->total_deposited;
            $savings->profit = $accountClosing->profit;
            $savings->bonus = $accountClosing->bonus;
            $savings->status = 'closed';
            $savings->save();

            if ($accountClosing->loan_balance>0)
            {
                $loan = DailyLoan::find($accountClosing->loan_id);
                $loan->balance -= $accountClosing->loan_balance;
                $loan->status = "paid";
                $loan->save();
            }
            $withdraw = Transaction::create([
                'transaction_category_id' => 2,
                'date' => $accountClosing->date,
                'trx_id' => $accountClosing->trx_id,
                'amount' => $accountClosing->total_deposited,
                'account_no' => $accountClosing->account_no,
                'member_id' => $accountClosing->member_id,
                'user_id' => $accountClosing->user_id
            ]);
            $profit = Transaction::create([
                'transaction_category_id' => 23,
                'date' => $accountClosing->date,
                'trx_id' => $accountClosing->trx_id,
                'amount' => $accountClosing->profit,
                'account_no' => $accountClosing->account_no,
                'member_id' => $accountClosing->member_id,
                'user_id' => $accountClosing->user_id
            ]);
            $closingFee = Transaction::create([
                'transaction_category_id' => 25,
                'date' => $accountClosing->date,
                'trx_id' => $accountClosing->trx_id,
                'amount' => $request->service_charge,
                'account_no' => $accountClosing->account_no,
                'member_id' => $accountClosing->member_id,
                'user_id' => Auth::id()
            ]);
        }else{
            $savings = MonthlySaving::where('account_no',$accountClosing->account_no)->first();
            $savings->withdraw += $accountClosing->depositor_owing;
            $savings->profit = $accountClosing->profit;
            $savings->bonus = $accountClosing->bonus;
            $savings->status = 'closed';
            $savings->save();

            if ($accountClosing->loan_balance>0)
            {
                $loan = MonthlyLoan::find($accountClosing->loan_id);
                $loan->balance -= $accountClosing->loan_balance;
                $loan->paid_loan += $accountClosing->loan_balance;
                $loan->paid_interest += $accountClosing->due_interest;
                $loan->status = "paid";
                $loan->save();
            }
            $withdraw = Transaction::create([
                'transaction_category_id' => 4,
                'date' => $accountClosing->date,
                'trx_id' => $accountClosing->trx_id,
                'amount' => $accountClosing->total_deposited,
                'account_no' => $accountClosing->account_no,
                'member_id' => $accountClosing->member_id,
                'user_id' => $accountClosing->user_id
            ]);

            $profit = Transaction::create([
                'transaction_category_id' => 24,
                'date' => $accountClosing->date,
                'trx_id' => $accountClosing->trx_id,
                'amount' => $accountClosing->profit,
                'account_no' => $accountClosing->account_no,
                'member_id' => $accountClosing->member_id,
                'user_id' => $accountClosing->user_id
            ]);
            $closingFee = Transaction::create([
                'transaction_category_id' => 25,
                'date' => $accountClosing->date,
                'trx_id' => $accountClosing->trx_id,
                'amount' => $request->service_charge,
                'account_no' => $accountClosing->account_no,
                'member_id' => $accountClosing->member_id,
                'user_id' => Auth::id()
            ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function makeAccountActive($account)
    {
        $closing = AccountClosing::where('account_no',$account)->first();
        if ($closing)
        {
            if ($closing->type == "daily")
            {
                $savings = DailySavings::where('account_no',$account)->first();
                $savings->withdraw -= $closing->depositor_owing;
                $savings->profit -= $closing->profit;
                $savings->bonus = $closing->bonus;
                $savings->status = 'active';
                $savings->save();

                if ($closing->loan_balance>0)
                {
                    $loan = DailyLoan::find($closing->loan_id);
                    $loan->balance += $closing->loan_balance;
                    $loan->status = "active";
                    $loan->save();
                }

                $transaction = Transaction::where('trx_id',$closing->trx_id)->delete();

            }else{
                $savings = MonthlySaving::where('account_no',$account)->first();
                $savings->withdraw -= $closing->depositor_owing;
                $savings->profit -= $closing->profit;
                $savings->bonus = $closing->bonus;
                $savings->status = 'active';
                $savings->save();

                if ($closing->loan_balance>0)
                {
                    $loan = MonthlyLoan::find($closing->loan_id);
                    $loan->balance += $closing->loan_balance;
                    $loan->paid_loan -= $closing->loan_balance;
                    $loan->paid_interest -= $closing->due_interest;
                    $loan->status = "active";
                    $loan->save();
                }

                $transaction = Transaction::where('trx_id',$closing->trx_id)->delete();

            }

            $closing->delete();
        }

        return "success";

    }
}
