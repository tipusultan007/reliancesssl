<?php

namespace App\Http\Controllers;

use App\Imports\AccountImport;
use App\Models\DailySavings;
use App\Models\Introducer;
use App\Models\Member;
use App\Models\MonthlySaving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{
    public function newAccount()
    {
        return view('accounts.new_account');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            if ($request->hasFile('nid')) {
                $imagePath = upload_image($request->file('nid'), 'nid');
                $data['nid'] = $imagePath;
            }
            if ($request->hasFile('birth_id')) {
                $imagePath = upload_image($request->file('birth_id'), 'birth_cert');
                $data['birth_id'] = $imagePath;
            }
            if ($request->hasFile('photo')) {
                $imagePath = upload_image($request->file('photo'), 'photo');
                $data['photo'] = $imagePath;
            }
            if ($request->hasFile('signature')) {
                $imagePath = upload_image($request->file('signature'), 'signature');
                $data['signature'] = $imagePath;
            }
            if ($request->hasFile('introducer_signature')) {
                $imagePath = upload_image($request->file('introducer_signature'), 'signature');
                $data['introducer_signature'] = $imagePath;
            }
            $member = Member::where('name',$request->name)
                ->where('father_name',$request->father_name)
                ->where('mother_name',$request->mother_name)
                ->first();
            if (!$member) {
                $member = Member::create($data);
            }
            $data['member_id'] = $member->id;
            if ($request->filled('exist_member_id')) {
                $introducer = Introducer::create($data);
            }

            $savings = [];
            switch ($request->type){
                case 'daily':
                    $str_pad = str_pad($request->daily_account_no, 4, "0", STR_PAD_LEFT);
                    $data['account_no'] = 'DS'.$str_pad;
                    $validator = Validator::make($data, [
                        'account_no' => 'required|unique:daily_savings,account_no',
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->with('error', 'হিসাব নম্বরটি আগে ব্যবহার করা হয়েছে!');
                    }
                    $savings = DailySavings::create($data);
                    break;
                case 'monthly':
                    $str_pad = str_pad($request->monthly_account_no, 4, "0", STR_PAD_LEFT);
                    $account_no = 'DPS'.$str_pad;
                    $data['account_no'] = $account_no;
                    $validator = Validator::make($data, [
                        'account_no' => 'required|unique:monthly_savings,account_no',
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->with('error', 'হিসাব নম্বরটি আগে ব্যবহার করা হয়েছে!');
                    }
                    $savings = MonthlySaving::create($data);
                    break;
                default:
            }
            return redirect()->back()->with('success',"সঞ্চয় হিসাব তৈরি সম্পন্ন হয়েছে!");
        }catch (\Exception $e)
        {
            return redirect()->back()->with('error',"সঞ্চয় হিসাব তৈরি করা হয়নি!আবার চেষ্টা করুন!");
        }
    }


}
