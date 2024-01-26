<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\MonthlySaving;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccountMonthlyImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $date = Carbon::parse($row['date']);
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
                    'join_date' => $date->format('Y-m-d')
                ]);
            } else {
                $newMember = $existingMember;
            }

            $str_pad = str_pad($row['account_no'], 4, "0", STR_PAD_LEFT);
            $account_no = 'DPS'.$str_pad;
            $savings = MonthlySaving::where('account_no',$account_no)->first();
            if (!$savings) {
                MonthlySaving::create([
                    'member_id' => $newMember->id,
                    'account_no' => $account_no,
                    'monthly_amount' => $row['monthly_amount'],
                    'duration' => $row['duration'],
                    'date' => $date->format('Y-m-d')
                ]);
            }
        }
    }
}
