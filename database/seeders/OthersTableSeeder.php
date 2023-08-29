<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OthersTableSeeder extends Seeder
{
    
    public function run()
    {
        // OrderStatus
        $this->orderStatusData();        
    }

    public function orderStatusData()
    {
        $titles = [
            '1' => 'Received', 
            // '2' => 'Paid',
            // '3' => 'Pending',
            '4' => 'Processing',
            '5' => 'Shipped',
            '6' => 'Cancelled',
            '7' => 'Delivered',
        ];

        foreach ($titles as $key => $value) {
            OrderStatus::create(['title' => $value]);
        }        
    }
}