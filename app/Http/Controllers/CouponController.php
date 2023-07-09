<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCouponRequest;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Services\CouponService;
use Symfony\Component\HttpFoundation\Response;

class CouponController extends Controller
{
    protected $coupon;

    public function __construct(CouponService $coupon)
    {
        $this->middleware([
            'auth:admin',
            'type:super-admin,admin'
        ]);//->except('index', 'show');

        $this->coupon = $coupon;
    }

    public function index()
    {
        // return [ 
        //     'data' => CouponResource::collection(
        //             Coupon::with([
        //                 'deduct:id,amount,active,limit,starting,ending,deductable_id'
        //             ])->cursorPaginate()
        //         )
        // ];

        $coupons = Coupon::with([
            'deduct:id,amount,active,limit,starting,ending,deductable_id'
        ])->orderBy('id')->cursorPaginate(10);


        return (new CouponCollection($coupons))->additional(
            [
                'meta' => [
                    'totalCoupons' => Coupon::count(), //
                ]
            ]
        );
    }

    public function store(StoreCouponRequest $request)
    {

        return response([
            'data'=> CouponResource::make(
                $this->coupon->add($request)
            )
        ], Response::HTTP_CREATED);
    }

    public function update(StoreCouponRequest $request, Coupon $coupon)
    {        
        return response([
            'data' => CouponResource::make($this->coupon->update($request, $coupon))
        ], Response::HTTP_OK);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }   

}
