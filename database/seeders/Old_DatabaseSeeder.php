<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Deduct;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Meta;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shipper;
use App\Models\Shipping;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      
        $this->productsWithRelatedData();

        // // OrderStatus
        $this->orderStatusData();

        $this->shipperData();
        $this->shippingData();
        Coupon::factory()->has(Deduct::factory())->create();
        Admin::factory()->create(['email' => 'admin@example.com']);
    }

    public function productsWithRelatedData() {
        $user = User::factory()->create([
            'name' => 'Khalid Bin Walid',
            'email' => 'mm@abc.com'
        ]);
        
        $address = $user->addresses()->create([
            'title' => 'Home',
            'name' => $user->name,
            'address_line' => '15/1, Dhanmondi',
            'phone' => $user->phone,            
            'postal_code' => 1000,
            'city' => 'Dhaka'
        ]);

        $user->addresses()->create([
            'title' => 'Office',
            'name' => $user->name,
            'address_line' => '5/2, Sector-3, Uttara',
            'phone' => $user->phone,            
            'postal_code' => 1122,
            'city' => 'Dhaka'
        ]);

        $defaultShippingAddress = $user->defaultShippingAddress()->create([
            'address_id' => $address->id
        ]);

        // $role = Role::factory()->create(['name' => 'super_admin']);

        // $user->roles()->attach($role);
       // Create discounts 
        for ($i = 1; $i <= 10; $i++) {
            Discount::factory()->has(Deduct::factory())->create();
        }

        $discountProduct = Discount::factory()->has(Deduct::factory())->create();
        $discountCategory = Discount::factory()->has(Deduct::factory())->create();

        // Category::factory()->count(21)->create();
        Category::factory()->has(Meta::factory())->count(21)->create(['discount_id' => $discountCategory->id]);
       
       $this->createSubCategory(); 

        Tag::factory()->count(30)->create();

        $products = Product::factory()
                        ->count(250)                       
                        ->create(['discount_id' => $discountProduct->id]);    


        $categories =  Category::all();
        $tags =  Tag::all();

        Product::all()->each(function ($product) use ($categories, $tags) {
            $product->update([
                'category_id' => $categories->random()->id//->pluck('id'),//->toArray()
            ]);

           
            $product->tags()->syncWithoutDetaching(
                $tags->random(2)->pluck('id')->toArray()
            );
        });
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
            // OrderStatus::create(['title' => $key]);
            OrderStatus::create(['title' => $value]);
        }        
    }

    public function shipperData()
    {
        Shipper::create([
            'name' => 'ABCx-BD',
            'address' => '123/4, Dhanmondi',
            'url' => 'https://..',
            'phone' => '+8801777778789'
        ]);
    }

    public function shippingData()
    {
        $shipping = Shipping::create([
            'city' => 'Dhaka',
            // 'charge' => 80,
            // 'shipping_type_id' => 1
            // 'delivery_time_min' => 3
            // 'delivery_time_max' => 5
        ]);

        $shippingType1 = $shipping->shippingTypes()->create([
            'type' => 1,
            'delivery_time_min' => 3, //day
            'delivery_time_max' => 5,
        ]);

        $shippingType2 = $shipping->shippingTypes()->create([
            'type' => 2,
            'delivery_time_min' => 2, // hour
            'delivery_time_max' => 4,
        ]);

        $charge1 = $shippingType1->shippingCharge()->create([
            'charge' => 80.00,
        ]);

        $charge2 = $shippingType2->shippingCharge()->create([
            'charge' => 120.00,
        ]);
        
    }

    public function createSubCategory()
    {
        $parentId = 1;
        for ($i=8; $i <21 ; $i= $i+2) { 
            Category::find($i)->fill([
                'parent_id' => $parentId
            ])->save();

            Category::find($i+1)->fill([
                'parent_id' => $parentId
            ])->save();
            $parentId++;
        }
    }
}
