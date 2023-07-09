<?php

namespace App\Rules;

use App\Models\DailyDeal;
use App\Models\WeeklyDeal;
use Illuminate\Contracts\Validation\Rule;

class UniqueDealCheck implements Rule
{
    protected $params;

    public function __construct(array $params)
    {
       $this->params = $params; 
    }


    public function passes($attribute, $value)
    {
        $productId = $this->params['product_id'];
        $starting= $this->params['starting'];
        $ending= $this->params['ending'];

        
        if ($this->params['deal_type'] === 'daily') {
       
            $deals = DailyDeal::where('product_id',$productId)->with('deal')->get();
        } 
                
        if ($this->params['deal_type'] === 'weekly') {
       
            $deals = WeeklyDeal::where('product_id',$productId)->with('deal')->get();
        }

        foreach ($deals as $deal) {
           
            if ($deal->deal->starting->equalTo($starting) and $deal->deal->ending->equalTo($ending)) {
                return false;
            }
        }
        
        return true;
    }

    
    public function message()
    {
        return "*This deal already exists or invalid.";
    }
}