<?php

namespace App\Http\Controllers;

use App\Imports\AccountImport;
use App\Imports\AccountMonthlyImport;
use App\Models\DailySavings;
use App\Models\Member;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importDailySavings(Request $request)
    {
        Excel::import(new AccountImport,request()->file('csv_file'));
        return back();

       /* // Process the CSV file
        Excel::import($file, function ($rows) {
            dd($rows);
            foreach ($rows as $row) {
                $existingMember = Member::where([
                    'name' => $row['name'],
                    'father_name' => $row['father_name'],
                    'mother_name' => $row['mother_name']
                ])->first();

                if (!$existingMember) {
                    $newMember = Member::create([
                        'name' => $row['name'],
                        'father_name' => $row['father_name'],
                        'mother_name' => $row['mother_name'],
                        'gender' => $row['gender'],
                        'phone' => $row['phone'],
                        'present_address' => $row['present_address'],
                        'permanent_address' => $row['permanent_address'],
                        'join_date' => $row['join_date']
                    ]);
                } else {
                    $newMember = $existingMember;
                }

                DailySavings::create([
                    'member_id' => $newMember->id,
                    'account_no' => $row['account_no'],
                    'date' => $row['date']
                ]);
            }
        });*/

        //return redirect()->back()->with('success', 'CSV data imported successfully.');
    }

    public function importMonthlySavings()
    {
        Excel::import(new AccountMonthlyImport,request()->file('csv_file'));
        return back();
    }
}
