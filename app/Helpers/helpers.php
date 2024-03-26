<?php

use App\Helpers\ImageUploadHelper;
use App\Models\Fdr;
use App\Models\FdrCollection;
use App\Models\FdrDeposit;
use App\Models\ProfitDetail;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('removeFile')) {
    function removeFile($fileName): void
    {
        if (File::exists(public_path('upload/' . $fileName))) {
            File::delete(public_path('upload/' . $fileName));
        }
    }
}

if (!function_exists('convertYmdToDmy')) {
    function convertYmdToDmy($date): string
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }
}

if (!function_exists('trxId')) {
    function trxId($date): string
    {
        $record = Transaction::latest()->first();
        $dateTime = new Carbon($date);
        $time = date('His');
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $uid = substr(str_shuffle($permitted_chars), 0, 6);
        if ($record) {
            $expNum = explode('-', $record->trx_id);
            if ($dateTime->format('dmy') == $expNum[1]) {
                $nextTxNumber = 'TRX-' . $expNum[1] . '-' . $uid . '-' . $time;
            } else {
                //increase 1 with last invoice number
                $nextTxNumber = 'TRX-' . $dateTime->format('dmy') . '-' . $uid . '-' . $time;
            }
        } else {
            $nextTxNumber = 'TRX-' . $dateTime->format('dmy') . '-' . $uid . '-' . $time;
        }

        return $nextTxNumber;
    }
}
/*
 * Calculate Simple Interest
 * $p=principal, $r=rate
 */
function simpleInterest($p, $r)
{
    return ($p * 1 * $r) / 100;
}

function getMemberDetails($member_id)
{
    $member = \App\Models\Member::find($member_id);
    $monthly = \App\Models\MonthlySaving::where('member_id', $member_id)->where('status', 'active')->get();
    $daily = \App\Models\DailySavings::where('member_id', $member_id)->where('status', 'active')->get();
    $monthlyLoans = \App\Models\MonthlyLoan::where('member_id', $member_id)->where('status', 'active')->get();
    $dailyLoans = \App\Models\DailyLoan::where('member_id', $member_id)->where('status', 'active')->get();

    $data['member'] = $member;
    $data['total_monthly_savings'] = $monthly->sum('total');
    $data['total_daily_savings'] = $daily->sum('total');
    //$data['dps_accounts'] = $monthly;
    $data['total_monthly_loans'] = $monthlyLoans->sum('balance');
    $data['total_daily_loans'] = $dailyLoans->sum('balance');
    $data['guarantor_loan'] = guarantorLoan($member_id);
    return $data;
}

function getDueInstallment($loanId)
{
    $loan = \App\Models\DailyLoan::find($loanId);
    $date1 = $loan->date;
}
function countMonthsFromNextMonth($date1, $date2)
{
//    $nextMonth = Carbon::parse($date1)->addMonth();
//    $now = Carbon::parse($date2);
//
//    if ($nextMonth->lt($now)) {
//        $start = $nextMonth->firstOfMonth();
//        $end = $now->lastOfMonth();
//        $months = $start->diffInMonths($end);
//        return $months + 1;
//    }
//
//    return 0;

//    $nextMonth = Carbon::parse($date1)->addMonth()->firstOfMonth();
//    $now = Carbon::parse($date2);
//
//    if ($nextMonth->lt($now)) {
//        $start = $nextMonth;
//        $end = $now;
//        $months = $start->diffInMonths($end);
//        return $months + 1; // Add 1 to include the next month
//    }
//
//    return 0; // If the next month is in the future, return 0
    $start = (new DateTime($date1))->modify('first day of this month')->modify('+1 month'); // Add 1 month
    $end = (new DateTime($date2))->modify('last day of this month');
    $interval = DateInterval::createFromDateString('1 month');
    $period = new DatePeriod($start, $interval, $end);

    $months = array();

    foreach ($period as $dt) {
        $months[] = $dt->format("F Y");
    }
    return count($months);
}

function getMonthListFromDate($date1, $date2)
{
   /* $start = (new DateTime($date1))->modify('first day of this month');
    $end = (new DateTime($date2))->modify('last day of this month');
    $interval = DateInterval::createFromDateString('1 month');
    $period = new DatePeriod($start, $interval, $end);

    $months = array();

    foreach ($period as $dt) {
        $months[] = $dt->format("F Y");
    }
    return count($months);*/
   /* $start = DateTime::createFromFormat('Y-m-d', $date1);
    $end = DateTime::createFromFormat('Y-m-d', $date2);

    if (!$start || !$end || $start > $end) {
        throw new InvalidArgumentException("Invalid date range");
    }

    $diff = $end->diff($start);

    return $diff->y * 12 + $diff->m;*/
    $start = new DateTime($date1);
    $end = new DateTime($date2);
    $interval = DateInterval::createFromDateString('1 month');
    $period = new DatePeriod($start, $interval, $end);

    $months = array();

    foreach ($period as $dt) {
        $months[] = $dt->format("F Y");
    }
    return count($months);
}

function getMonthlyInstallment($account, $date)
{
    $savings = \App\Models\MonthlySaving::where('account_no', $account)->first();
    $loan = \App\Models\MonthlyLoan::where('account_no', $account)->where('balance', '>', 0)->first();
    $collection = \App\Models\MonthlyCollection::where('account_no', $account);
    $interest = 0;
    $dueInterest = 0;
    $data = [];
    $paidSavings = $collection->sum('monthly_installments');

    $dueSavings = getMonthListFromDate($savings->date, $date) - $paidSavings;
    if ($loan) {
        $principal = $loan->remain_balance;
        $rate = $loan->interest_rate;
        $interest = ($principal * $rate * 1) / 100;
        $paidInterest = $collection->where('loan_id', $loan->id)->sum('interest_installments');
        $dueInterest = countMonthsFromNextMonth($loan->date, $date) - $paidInterest;
    }


    $data['due_savings'] = $dueSavings;
    $data['interest_rate'] = $interest;
    $data['monthly_amount'] = $savings->monthly_amount;
    $data['due_interest'] = $dueInterest;
    return $data;
}

function countDaysExcludingFridays($date1, $date2)
{
    $start = Carbon::parse($date1)->addDay();
    $end = Carbon::parse($date2);

    $count = 0;

    while ($start->lte($end)) {
        if ($start->dayOfWeek !== Carbon::FRIDAY) {
            $count++;
        }
        $start->addDay();
    }

    return $count;
}

function guarantorLoan($memberId)
{
    $guarantors = \App\Models\Guarantor::where('g_member_id', $memberId)->get();
    $total = 0;
    foreach ($guarantors as $guarantor) {
        if ($guarantor->loan_type == "daily") {
            $daily = \App\Models\DailyLoan::find($guarantor->loan_id);
            $total += $daily->balance;
        } else {
            $monthly = \App\Models\MonthlyLoan::find($guarantor->loan_id);
            $total += $monthly->balance;
        }
    }
    return $total;
}

function generateProfit($fdrId)
{
    $deposits = FdrDeposit::where('fdr_id', $fdrId)->get();
    $profit = [];
    $total = 0;
    foreach ($deposits as $deposit) {
        $profitCount = ProfitDetail::where('fdr_deposit_id', $deposit->id)->sum('installments');
        $profitRate = $deposit->remain * $deposit->profit_rate / 100;
        $months = countMonths($deposit->date, date('Y-m-d'));
        $due = $months - $profitCount;
        $data['fdr_deposit_id'] = $deposit->id;
        $data['rate'] = $profitRate;
        $data['due'] = $due;
        $data['profit'] = $profitRate * $due;
        $total += $profitRate * $due;
        $profit[] = $data;
    }
    return $total;
}

function countMonths($date1, $date2)
{
    $start_date = Carbon::parse($date1);
    $end_date = Carbon::parse($date2);

    $count = $start_date->diffInDays($end_date) / 30;

    return (int)($count);
}

if (!function_exists('upload_image')) {
    function upload_image($file, $folder, $oldFilePath = null)
    {
        return ImageUploadHelper::upload($file, $folder, $oldFilePath);
    }
}

function calculatePrincipal($amount,$installment,$total_amount)
{
    $principal = $amount*$installment/$total_amount;
    $data['loan'] = $principal;
    $data['interest'] = $installment - $principal;

    return $data;
}
function calculateRemainingDepositAndProfit($initialDeposit, $profit, $withdrawals, $remainingBalance) {
    // Calculate the remaining deposit
    $remainingDeposit = max(0, $initialDeposit - $withdrawals);

    // Calculate the remaining profit
    $remainingProfit = max(0, $remainingBalance - $remainingDeposit);

    return [
        'remainingDeposit' => $remainingDeposit,
        'remainingProfit' => $remainingProfit
    ];
}
