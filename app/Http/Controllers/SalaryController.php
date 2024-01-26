<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('salaries.index');
    }
    public function dataSalaries(Request $request)
    {
        $totalData = Salary::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        if(empty($request->input('search.value')))
        {
            $posts = Salary::with('user')->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  Salary::with('user')->where('description','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('date','desc')
                ->get();

            $totalFiltered = Salary::where('description','LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['id'] = $post->id;
                $nestedData['salary'] = $post->salary;
                $nestedData['date'] = date('d/m/Y',strtotime($post->date));
                $nestedData['notes'] = $post->notes??'';
                $nestedData['month_year'] = $post->month_year;
                $nestedData['name'] = $post->user->name;
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
        $data['trx_id'] = Str::uuid();
        $data['expense_category_id'] = 1;
        $salary = Salary::create($data);
        $expense = Expense::create([
            'category_id' => 1,
            'amount' => $salary->salary,
            'date'=>$salary->date,
            'description' => $salary->user->name,
            'user_id' => Auth::id(),
            'trx_id' => $salary->trx_id
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
        $salary = Salary::find($id);
        Expense::where('category_id',1)->where('trx_id',$salary->trx_id)->delete();
        $delete = Salary::destroy($id);
        if ($delete == 1) {
            $success = true;
            $message = "Loan deleted successfully";
        } else {
            $success = false;
            $message = "Loan Data not found";
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
