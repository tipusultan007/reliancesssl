<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('incomes.categories');
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
        $category = IncomeCategory::create($request->all());
        $transactionCategory = TransactionCategory::create([
            'name' => $category->name,
            'type' => 'office_income',
            'income_category_id' => $category->id,
        ]);
    }

    public function dataIncomeCategories(Request $request)
    {
        $totalData = IncomeCategory::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        if(empty($request->input('search.value')))
        {
            $posts = IncomeCategory::with('incomes')->offset($start)
                ->limit($limit)
                ->orderBy('name','asc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  IncomeCategory::with('incomes')->where('name','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('name','asc')
                ->get();

            $totalFiltered = IncomeCategory::where('name','LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['income'] = $post->incomes->sum('amount');
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
        $trx_cat = TransactionCategory::where('income_category_id',$id)->first();
        $trx = Transaction::where('transaction_category_id',$trx_cat->id)->delete();
        $trx_cat->delete();
        $incomes = Income::where('category_id',$id)->delete();
        $delete = IncomeCategory::destroy($id);
        if ($delete == 1) {
            $success = true;
            $message = "Category deleted successfully";
        } else {
            $success = false;
            $message = "Category not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
