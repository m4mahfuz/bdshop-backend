<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipperRequest;
use App\Http\Resources\ShipperCollection;
use App\Http\Resources\ShipperResource;
use App\Models\Shipper;
use App\Services\ShipperService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShipperController extends Controller
{
    private $shipper;

    public function __construct(ShipperService $shipper)
    {
        $this->shipper = $shipper;
    }

    public function index()
    {
        $shippers = Shipper::orderBy('id', 'desc')->cursorPaginate(10);


        return (new ShipperCollection($shippers))->additional(
            [
                'meta' => [
                    'totalShippers' => Shipper::count(), //
                ]
            ]
        );
    }

    public function store(StoreShipperRequest $request)
    {

        return response([
            'data'=> new ShipperResource(
                $this->shipper->add($request)
            )
        ], Response::HTTP_CREATED);
    }

    public function update(StoreShipperRequest $request, Shipper $shipper)
    {        
        return response([
            'data' => ShipperResource::make($this->shipper->update($request, $shipper))
        ], Response::HTTP_OK);
    }

    public function destroy(Shipper $shipper)
    {
        $shipper->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

}
