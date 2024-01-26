<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = IncomeCategory::pluck('name','id');
        //dd($categories);
        return view('incomes.index',compact('categories'));
    }
    public function dataIncomes(Request $request)
    {
        $totalData = Income::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        if(empty($request->input('search.value')))
        {
            $posts = Income::offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  Income::where('description','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = Income::where('description','LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['id'] = $post->id;
                $nestedData['category'] = $post->category->name;
                $nestedData['amount'] = $post->amount;
                $nestedData['date'] = date('d/m/Y',strtotime($post->date));
                $nestedData['description'] = $post->description??'';
                $nestedData['user'] = $post->user->name;
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" data-id="'.$post->id.'" data-category-id="'.$post->category_id.'" data-amount="'.$post->amount.'" data-date="'.$post->date.'" class="dropdown-item edit">সম্পাদন করুন</a>
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
        $data['trx_id'] = Str::uuid();
        $data['user_id'] = Auth::id();
        $income = Income::create($data);
        $trx_cat_id = TransactionCategory::where('income_category_id',$income->category_id)->first();

        $transaction = Transaction::create([
            'transaction_category_id' => $trx_cat_id->id,
            'trx_id' => $income->trx_id,
            'date' => $income->date,
            'amount' => $income->amount,
            'user_id' => $income->user_id,
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
        $income = Income::find($id);
        $income->update($data);
        $trx = Transaction::where('trx_id',$income->trx_id)->first();
        $trx->amount = $income->amount;
        $trx->date = $income->date;
        $trx->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = Income::find($id);
        Transaction::where('trx_id',$income->trx_id)->delete();
        $delete = Income::destroy($id);
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
}
