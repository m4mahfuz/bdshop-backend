<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserReportService 
{

    // public function yearly()
    // {
    //     $users = User::select(
    //         DB::raw("(COUNT(*)) as count"),
    //         DB::raw("MONTHNAME(created_at) as month_name")
    //     )
    //     ->whereYear('created_at', date('Y'))
    //     ->groupBy('month_name')
    //     ->get();
    
    //     $counts = [];
    //     $months = [];

    //     $currentMonth = Carbon::now()->month;

    //     for ($i=1; $i <= $currentMonth; $i++) { 

    //         $found = false;
    //         $month = date("F", strtotime(date("Y")."-".$i."-01"));

    //         foreach ($users as $user) {
    //             if ($user->month_name === $month) {
    //                 $counts[] = $user->count;
    //                 $months[] = $user->month_name;
    //                 $found = true;
    //             }
    //         }
    
    //         if (!$found) {
    //             $counts[] = 0;
    //             $months[] = $month;
    //         }
    //     }

    //     return [
    //         'counts' => $counts,
    //         'months' => $months,
    //     ];
    // }

    public function usersRegisteredFor($numberOfMonths)
    {
        // $numberOfMonths = 12;
        $currentMonth = Carbon::now()->month;
        
        $numberOfMonths = $numberOfMonths > $currentMonth ? $currentMonth : $numberOfMonths;

        $date = Carbon::now()->subMonth($numberOfMonths-1);

        $month= $date->month;

        $users = User::select(
            DB::raw("(COUNT(*)) as count"),
            DB::raw("MONTHNAME(created_at) as month_name")
        )
        // ->whereYear('created_at', date('Y'))
        ->whereYear('created_at', '>=', $date)
        ->groupBy('month_name')
        ->get();

        $counts = [];
        $months = [];

        for ($i=$month; $i <= $currentMonth; $i++) { 

            $found = false;

            $month = date("F", strtotime(date("Y")."-".$i."-01"));

            foreach ($users as $user) {
                if ($user->month_name === $month) {
                    $counts[] = $user->count;
                    $months[] = $user->month_name;
                    $found = true;
                }
            }
    
            if (!$found) {
                $counts[] = 0;
                $months[] = $month;
            }
        }

        return [
            'counts' => $counts,
            'months' => $months,
        ];
    }
	
}