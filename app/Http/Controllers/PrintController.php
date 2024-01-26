<?php

namespace App\Http\Controllers;

use App\Models\FdrWithdraw;
use App\Models\MonthlyCollection;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function monthly($id)
    {
        $data = MonthlyCollection::with('member','user')->find($id);
        return view('print.monthlyCollection',compact('data'));
    }

    public function fdrWithdraw($id)
    {
        $data = FdrWithdraw::with('fdr','member','user')->find($id);
        return view('print.fdrWithdraw',compact('data'));
    }
}
