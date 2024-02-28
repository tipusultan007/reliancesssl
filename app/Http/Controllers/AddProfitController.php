<?php

namespace App\Http\Controllers;

use App\Models\AddProfit;
use App\Models\DailySavings;
use App\Models\MonthlySaving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddProfitController extends Controller
{

    public function dailyProfits()
    {
        $savings = DailySavings::with('member')->get();
        $profits = AddProfit::where('type','daily')->orderBy('date','desc')->paginate(10);
        return view('add-profits.daily',compact('savings','profits'));
    }

    public function monthlyProfits()
    {
        $savings = MonthlySaving::with('member')->get();
        $profits = AddProfit::where('type','monthly')->orderBy('date','desc')->paginate(10);
        return view('add-profits.monthly',compact('savings','profits'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'account_no' => 'required|string|max:255',
            'type' => 'required|in:daily,monthly',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'year_month' => 'nullable|string|max:7',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // To repopulate the form with old input
        }

        $data = $request->all();

        AddProfit::create($data);

        return redirect()->back()->with('success','লভ্যাংশ প্রদান করা হয়েছে!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AddProfit $addProfit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddProfit $addProfit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $rules = [
            'account_no' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'year_month' => 'nullable|string|max:7',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        $addProfit = AddProfit::find($data['id']);
        $addProfit->update($data);

        return redirect()->back()->with('success','লভ্যাংশ প্রদান আপডেট করা হয়েছে!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddProfit $addProfit)
    {
        $addProfit->delete();

        return redirect()->back()->with('success','লভ্যাংশ ডিলেট করা হয়েছে!');
    }
}
