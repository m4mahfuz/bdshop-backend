<?php

namespace App\Services;

use App\Models\Inventory;
use App\Http\Requests\StoreInventoryRequest;
use Symfony\Component\HttpFoundation\Response;

class InventoryService 
{	
	public function update(StoreInventoryRequest $request, Inventory $inventory)
	{				
		$inventory = tap($inventory)->updateOrFail([
			'quantity' => $request->getQuantity(),
			'sku' => $request->getSku(),
		]);			

		return $this->loadRelationOf($inventory);
	}

	public function loadRelationOf(Inventory $inventory)
	{
		return $inventory->load([
			// 'product.categories:id,name,slug', 
			'product', 
			// 'discount.deduct'
		]);
	}

}