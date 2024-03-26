<?php

namespace App\Http\Controllers;

use App\Models\AccountClosing;
use App\Models\AddProfit;
use App\Models\DailyCollection;
use App\Models\DailyLoan;
use App\Models\Expense;
use App\Models\FdrCollection;
use App\Models\FdrDeposit;
use App\Models\FdrWithdraw;
use App\Models\Income;
use App\Models\Member;
use App\Models\MonthlyCollection;
use App\Models\MonthlyLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_member = Member::count();

        $dailyCollection = DailyCollection::select(DB::raw('SUM(deposit) as deposit, SUM(withdraw) as withdraw,SUM(loan_return) as paid,
        SUM(interest) as interest'))->first();
        $monthlyCollection = MonthlyCollection::select(DB::raw('SUM(monthly_amount) as deposit,SUM(loan_installment) as paid_loan,
        SUM(monthly_interest) as paid_interest'))->first();
        $dailyProfit = AddProfit::where('type','daily')->sum('amount');
        $monthlyClosing = AccountClosing::select(DB::raw('SUM(total_deposited) as withdraw, SUM(profit) as profit'))->first();

        $fdrDeposit = FdrDeposit::sum('amount');
        $fdrWithdraw = FdrWithdraw::sum('amount');
        $fdrProfit = FdrCollection::sum('profit');

        $dailyLoan = DailyLoan::sum('loan_amount');

        $monthlyLoan = MonthlyLoan::sum('loan_amount');

        $income = Income::sum('amount');
        $expense = Expense::sum('amount');

        //dd($totalMonthlyDeposit);
        return view('dashboard',compact('total_member',
            'dailyCollection','dailyProfit','monthlyCollection','monthlyClosing',
        'fdrDeposit','fdrWithdraw','fdrProfit','dailyLoan','monthlyLoan','expense','income'
        ));
    }
}
