<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{    
    private $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->middleware([
            'auth',
            'role:super_admin,admin'
        ]);//->except('index', 'show');        

        $this->inventory = $inventory;
    }
    public function index()
    {
        $inventories = Inventory::with([
            'product:id,name,slug,price',
            // 'discount.deduct'
        ])->get();

        return [
            'data' => InventoryResource::collection($inventories),
        ];
    }
    
    // public function store(Request $request)
    // {
        
    // }

    
    public function show(Inventory $inventory)
    {
        return [
            'data' => Inventory::make($inventory->load([
                    'product',
                    // 'discount.deduct'
                ])
            )
        ];
    }
    
    public function update(StoreInventoryRequest $request, Inventory $inventory)
    {
        return response([
            'data' => InventoryResource::make($this->inventory->update($request, $inventory))
        ], Response::HTTP_OK);
    }
    
    // public function destroy($id)
    // {
    //     //
    // }
}
