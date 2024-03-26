<?php

namespace App\Http\Controllers;

use App\Models\AddProfit;
use App\Models\DailyCollection;
use App\Models\DailyLoan;
use App\Models\DailySavings;
use App\Models\ExpenseCategory;
use App\Models\FdrCollection;
use App\Models\FdrDeposit;
use App\Models\FdrWithdraw;
use App\Models\IncomeCategory;
use App\Models\Member;
use App\Models\MonthlyCollection;
use App\Models\MonthlyLoan;
use App\Models\MonthlySaving;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard()
    {
        $total_member = Member::count();
        $dailyCollection = DailyCollection::select(DB::raw('SUM(deposit) as deposit, SUM(withdraw) as withdraw'))->first();
        $dailyProfit = AddProfit::where('type','daily')->sum('amount');
        return view('dashboard',compact('total_member',
        'dailyCollection','dailyProfit'
        ));

    }
    public function savingCollections()
    {
        return view('reports.dailySavings');
    }
    public function monthlyCollections()
    {
        return view('reports.monthlySavings');
    }
    public function monthlyLoanCollections()
    {
        return view('reports.monthlyLoan');
    }
    public function dailyLoanCollections()
    {
        return view('reports.dailyLoan');
    }
    public function fdrCollections()
    {
        return view('reports.profitCollection');
    }

    public function fdrDeposits()
    {
        return view('reports.fdrDeposits');
    }
    public function fdrWithdraws()
    {
        return view('reports.fdrWithdraws');
    }
    public function dataFdrDeposits(Request $request)
    {
        if (!empty($request->date1) && !empty($request->date2)) {
            $date1 = $request->date1;
            $date2 = $request->date2;
        }else{
            $date1 = '2000-01-01';
            $date2 = date('Y-m-d');
        }

        $totalData = FdrDeposit::whereBetween('date',[$date1,$date2])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if(empty($request->input('search.value')))
        {
            $posts = FdrDeposit::with('member','fdr')
                ->whereBetween('date',[$date1,$date2])
                ->offset($start)
                ->limit($limit)
                ->get();
        }else {
            $search = $request->input('search.value');

            $posts =  FdrDeposit::with('member','fdr')
                ->whereBetween('date',[$date1,$date2])
                ->whereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('fdr', function ($query) use ($search) {
                    $query->where('account_no', $search);
                })
                ->offset($start)
                ->limit($limit)
                ->get();

            $totalFiltered = FdrDeposit::whereBetween('date',[$date1,$date2])->whereHas('member', function ($query) use ($search) {
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
    public function dataFdrWithdraws(Request $request)
    {
        if (!empty($request->date1) && !empty($request->date2)) {
            $date1 = $request->date1;
            $date2 = $request->date2;
        }else{
            $date1 = '2000-01-01';
            $date2 = date('Y-m-d');
        }

        $totalData = FdrWithdraw::whereBetween('date',[$date1,$date2])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if(empty($request->input('search.value')))
        {
            $posts = FdrWithdraw::with('member','fdr')
                ->whereBetween('date',[$date1,$date2])
                ->offset($start)
                ->limit($limit)
                ->get();
        }else {
            $search = $request->input('search.value');

            $posts =  FdrWithdraw::with('member','fdr')
                ->whereBetween('date',[$date1,$date2])
                ->whereHas('member', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('fdr', function ($query) use ($search) {
                    $query->where('account_no', $search);
                })
                ->offset($start)
                ->limit($limit)
                ->get();

            $totalFiltered = FdrWithdraw::whereBetween('date',[$date1,$date2])->whereHas('member', function ($query) use ($search) {
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
    public function dailyReport(Request $request)
    {
        if (!empty($request->date1) && !empty($request->date2)) {
            $date1 = $request->date1;
            $date2 = $request->date2;
        }else{
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d');
        }

        //$transactions = Transaction::with('category')->where('date',date('Y-m-d'))->get()->groupBy('transaction_category_id');
        $categories = TransactionCategory::with('transactions')->get();
        /*$incomeCategories = IncomeCategory::with('incomes')->get();
        $expenseCategories = ExpenseCategory::with('expenses')->get();*/
        $userTrx = User::with('transactions')->get();

        $trx = [];
        foreach ($userTrx as $user)
        {
            $trxItem['name'] = $user->name;
            $trxItem['savings'] = $user->transactions->whereIn('transaction_category_id',[1,3])->whereBetween('date',[$date1,$date2])->sum('amount');
            $trxItem['loan_collection'] = $user->transactions->whereIn('transaction_category_id',[6,9])->whereBetween('date',[$date1,$date2])->sum('amount');
            $trxItem['interest'] = $user->transactions->whereIn('transaction_category_id',[7,10])->whereBetween('date',[$date1,$date2])->sum('amount');
            $trxItem['other'] = $user->transactions->where('transaction_category_id',[11,14,15])->whereBetween('date',[$date1,$date2])->sum('amount');
            $trxItem['total'] = $trxItem['savings'] + $trxItem['loan_collection'] + $trxItem['interest'] + $trxItem['other'];
            $trx[] = $trxItem;
        }
        $income = [];
        $expense = [];
        $totalIncome = 0;
        $totalExpense = 0;
        $data = [];
        foreach ($categories as $category)
        {
            if($category->type == "income" || $category->type == "office_income") {
                $income[$category->name] = $category->transactions->whereBetween('date',[$date1,$date2])->sum('amount');
                $totalIncome += $category->transactions->whereBetween('date',[$date1,$date2])->sum('amount');
            }elseif ($category->type == "expense" || $category->type == "office_expense"){
                $expense[$category->name] = $category->transactions->whereBetween('date',[$date1,$date2])->sum('amount');
                $totalExpense += $category->transactions->whereBetween('date',[$date1,$date2])->sum('amount');
            }
        }
        /*foreach ($incomeCategories as $category)
        {

                $income[$category->name] = $category->incomes->whereBetween('date',[$date1,$date2])->sum('amount');
                $totalIncome += $category->incomes->whereBetween('date',[$date1,$date2])->sum('amount');
        }

        foreach ($expenseCategories as $category)
        {

            $expense[$category->name] = $category->expenses->whereBetween('date',[$date1,$date2])->sum('amount');
            $totalExpense += $category->expenses->whereBetween('date',[$date1,$date2])->sum('amount');
        }*/

        $data['income'] = $income;
        $data['expense'] = $expense;
        //dd($income);


        return view('reports.dailyReport',compact('data','income','totalIncome','totalExpense','trx'));
    }
    public function savingCollectionsData(Request $request)
    {
        if (!empty($request->date1) && !empty($request->date2)) {
            $date1 = $request->date1;
            $date2 = $request->date2;
        }else{
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d');
        }
        $totalData = DailyCollection::whereBetween('date',[$date1,$date2])->where('deposit','>',0)->orWhere('withdraw','>',0)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DailyCollection::with('member')
                ->whereBetween('date',[$date1,$date2])
                ->where('deposit','>',0)
                ->orWhere('withdraw','>',0)
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  DailyCollection::join('members','members.id','=','daily_collections.member_id')
                ->with('member')
                ->where('deposit','>',0)->orWhere('withdraw','>',0)
                ->whereBetween('date',[$date1,$date2])
                ->where('daily_collections.account_no','LIKE',"%{$search}%")
                ->orWhere('members.name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = DailyCollection::join('members','members.id','=','daily_collections.member_id')
                ->whereBetween('date',[$date1,$date2])
                ->where('deposit','>',0)->orWhere('withdraw','>',0)
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
    public function dailyLoanCollectionsData(Request $request)
    {
        if (!empty($request->date1) && !empty($request->date2)) {
            $date1 = $request->date1;
            $date2 = $request->date2;
        }else{
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d');
        }
        $totalData = DailyCollection::whereBetween('date',[$date1,$date2])->where('loan_installment','>',0)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DailyCollection::with('member')
                ->whereBetween('date',[$date1,$date2])
                ->where('loan_installment','>',0)
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  DailyCollection::join('members','members.id','=','daily_collections.member_id')
                ->with('member')
                ->whereBetween('date',[$date1,$date2])
                ->where('loan_installment','>',0)
                ->where('daily_collections.account_no','LIKE',"%{$search}%")
                ->orWhere('members.name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = DailyCollection::join('members','members.id','=','daily_collections.member_id')
                ->where('loan_installment','>',0)
                ->whereBetween('date',[$date1,$date2])
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
                $nestedData['loan_installment'] = $post->loan_return;
                $nestedData['interest'] = $post->interest;
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
    public function monthlyCollectionsData(Request $request)
    {
        if (!empty($request->date1) && !empty($request->date2)) {
            $date1 = $request->date1;
            $date2 = $request->date2;
        }else{
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d');
        }

        $totalData = MonthlyCollection::where('monthly_amount','>',0)->whereBetween('date',[$date1, $date2])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = MonthlyCollection::with('member')
                ->whereBetween('date',[$date1, $date2])
                ->where('monthly_amount','>',0)
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  MonthlyCollection::with('member')
                ->where('monthly_amount','>',0)
                ->whereBetween('date',[$date1, $date2])
                ->where('account_no','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = MonthlyCollection::where('monthly_amount','>',0)->whereBetween('date',[$date1, $date2])->where('account_no','LIKE',"%{$search}%")->count();
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
    public function monthlyLoanCollectionsData(Request $request)
    {
        if (!empty($request->date1) && !empty($request->date2)) {
            $date1 = $request->date1;
            $date2 = $request->date2;
        }else{
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d');
        }
        $totalData = MonthlyCollection::where('monthly_interest','>',0)->orWhere('loan_installment','>',0)->whereBetween('date',[$date1, $date2])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = MonthlyCollection::with('member')
                ->where('monthly_interest','>',0)->orWhere('loan_installment','>',0)
                ->whereBetween('date',[$date1, $date2])
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  MonthlyCollection::with('member')
                ->where('monthly_interest','>',0)->orWhere('loan_installment','>',0)
                ->whereBetween('date',[$date1, $date2])
                ->where('account_no','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = MonthlyCollection::where('monthly_interest','>',0)->orWhere('loan_installment','>',0)->whereBetween('date',[$date1, $date2])->where('account_no','LIKE',"%{$search}%")->count();
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
    public function profitCollectionsData(Request $request)
    {
        if (!empty($request->date1) && !empty($request->date2)) {
            $date1 = $request->date1;
            $date2 = $request->date2;
        }else{
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d');
        }

        $totalData = FdrCollection::whereBetween('date',[$date1,$date2])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            if (!empty($request->date1) && !empty($request->date2)) {
                $posts = FdrCollection::with('member')
                    ->whereBetween('date',[$date1,$date2])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy('date','desc')
                    ->get();
            }else{
                $posts = FdrCollection::with('member')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy('date','desc')
                    ->get();
            }

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

    public function transactionSummary(Request $request)
    {
        // Separate the categories by type
        $categoryGroups = [
            'office_income' => [],
            'office_expense' => [],
            'income' => [],
            'expense' => [],

        ];

        return view('reports.transactionSummary',compact('categoryGroups'));
    }
    public function dataTransactionSummary(Request $request)
    {
        $chunkSize = 100000; // Adjust as needed
        $categoriesWithSum = [];

        $date1 = $request->input('date1'); // Assuming you pass 'date1' and 'date2' in your AJAX request
        $date2 = $request->input('date2');

        $query = Transaction::query();

        if ($date1 && $date2) {
            $query->whereBetween('date', [$date1, $date2]);
        }

        $query->chunk($chunkSize, function ($transactions) use (&$categoriesWithSum) {
            $categoryTotals = $transactions->groupBy('transaction_category_id')
                ->map(function ($group) {
                    return $group->sum('amount');
                });

            foreach ($categoryTotals as $categoryId => $totalAmount) {
                if (!isset($categoriesWithSum[$categoryId])) {
                    $categoriesWithSum[$categoryId] = 0;
                }
                $categoriesWithSum[$categoryId] += $totalAmount;
            }
        });

        $categories = TransactionCategory::orderBy('name')->get();
        $totalSum = collect($categoriesWithSum)->sum();

        // Separate the categories by type
        $categoryGroups = [
            'income' => [],
            'office_income' => [],
            'expense' => [],
            'office_expense' => [],
        ];

        foreach ($categories as $category) {
            $categoryType = $category->type;

            if (array_key_exists($categoryType, $categoryGroups)) {
                $categoryGroups[$categoryType][] = $category;
            }
        }

        return response()->json([
            'categoryGroups' => $categoryGroups,
            'categoriesWithSum' => $categoriesWithSum,
            'totalSum' => $totalSum,
        ]);
    }
}
