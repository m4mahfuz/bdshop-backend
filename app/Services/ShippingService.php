<?php

namespace App\Services;

use App\Models\Shipping;
use App\Http\Requests\StoreShippingRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ShippingService 
{

	public function add(StoreShippingRequest $request)
	{
		$shipping = DB::transaction(function() use($request) {

			$shipping = Shipping::firstOrCreate(
				['city' => $request->getShippingCity()],
				[			    
					'active' => $request->getShippingActive(),
				]
			);

			$shippingType = $shipping->shippingTypes()->create([
				'active' => $request->getShippingTypesActive(),
	            'type' => $request->getShippingType(),
	            'delivery_time_min' => $request->getShippingDeliveryTimeMinimum(),
	            'delivery_time_max' => $request->getShippingDeliveryTimeMaximum() ,
	        ]);

	         $charge = $shippingType->shippingCharge()->create([
	           	'active' => $request->getShippingChargeActive(), 
	            'charge' => $request->getShippingCharge(),
	        ]);
			
			return $shipping; 
		});

		return $shipping;
	}

	public function update(StoreShippingRequest $request, Shipping $shipping)
	{		

			// $shipping = DB::transaction(function() use($request, $shipping) {

			$shipping->update([			    
				'active' => $request->getShippingActive(),
			]);

			// $shippingType = $shipping->shippingTypes()->update([
			// 	'active' => $request->getShippingTypesActive(),
	        //     'type' => $request->getShippingType(),
	        //     'delivery_time_min' => $request->getShippingDeliveryTimeMinimum(),
	        //     'delivery_time_max' => $request->getShippingDeliveryTimeMaximum() ,
	        // ]);

	        //  $charge = $shippingType->shippingCharge()->update([
	        //    	'active' => $request->getShippingChargeActive(), 
	        //     'charge' => $request->getShippingCharge(),
	        // ]);
			
		// 	return $shipping; 
		// });
		
		return $shipping;
	}	
	
}