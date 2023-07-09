<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProductReportService 
{

    // public function yearlyProductSalesByCategory()
    // {
    //     $year = Carbon::now()->year;
        
    //     $categoriesWihOrderProductCount = Category::select(['name'])->whereNotNull('parent_id')->active()->withCount([
    //         'orderProducts' => function (Builder $query) use ($year) {
    //             $query->whereYear('order_product.created_at', $year);
    //         }
    //     ])->get();

    //     return $this->arrange($categoriesWihOrderProductCount);
    // }


    public function categoryWiseProductsSalesForMonths(int $numberOfMonths)
    {        
        $date = Carbon::now()->subMonth($numberOfMonths);
        // dd($date);
        
        $categoriesWihOrderProductCount = Category::select(['name'])->whereNotNull('parent_id')->active()->withCount([
            'orderProducts' => function (Builder $query) use ($date) {
                $query->whereYear('order_product.created_at', '>=', $date);
            }
        ])->get();

        return $this->arrange($categoriesWihOrderProductCount);
    }

    public function arrange($categoriesWihCount)
    {
        $counts = [];
        $categories = [];

        foreach ($categoriesWihCount as $category) {
            if ($category->order_products_count !== 0) {             
                $counts[] = $category->order_products_count;
                $categories[] = $category->name;
            }
        }
        
        return [
            'counts' => $counts,
            'categories' => $categories,
        ];
    }
	
}