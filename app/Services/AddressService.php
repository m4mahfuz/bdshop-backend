<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAddressRequest;


class AddressService 
{
	public function add(StoreAddressRequest $request)
	{
		$address = DB::transaction(function() use($request) {

			$address = Auth::user()->addresses()->create([
				'active' => $request->getActive(),
				'name' => $request->getName(),
				'title' =>  $request->getTitle(),
				'address_line' => $request->getAddressLine(),
				'phone' => $request->getPhone(), 
				'city' => $request->getCity(), 
				'postal_code' => $request->getPostalCode() 
			]);			

			if ($request->getDefaultShippingAddress()) {

				Auth::user()->defaultShippingAddress()->updateOrCreate(
					[
						'user_id' => Auth::user()->id
					], 
					[
			            'address_id' => $address->id
		        	]
		    	);
			}
			
			return $address;
		});

		return $address;		 
	}

	public function update(StoreAddressRequest $request, Address $address)
	{		

		
		$address = $address->fill([
			'active' => $request->getActive(),
			'title' =>  $request->getTitle(),
			'name' => $request->getName(),
			'address_line' => $request->getAddressLine(),
			'phone' => $request->getPhone(), 
			'city' => $request->getCity(), 
			'postal_code' => $request->getPostalCode() 
		]);
			
		$address->save();
		$address->fresh();

		return $address;		
	}	
}