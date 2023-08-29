<?php

namespace App\Services;

use App\Models\Shipper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreShipperRequest;


class ShipperService 
{
	public function add(StoreShipperRequest $request)
	{

		$shipper = Shipper::create([
			'active' => $request->getActive(),
			'name' => $request->getName(),
			'address' => $request->getAddress(),
			'url' =>  $request->getUrl(),
			'phone' => $request->getPhone(), 			
		]);			

		return $shipper;

	}

	public function update(StoreShipperRequest $request, Shipper $shipper)
	{				
		$shipper = $shipper->fill([
			'active' => $request->getActive(),
			'name' => $request->getName(),
			'address' => $request->getAddress(),
			'url' =>  $request->getUrl(),
			'phone' => $request->getPhone(), 			
		]);
			
		$shipper->save();
		$shipper->fresh();

		return $shipper;		
	}	
}