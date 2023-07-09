<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            // $table->string('coupon_code')->nullable();
            // $table->unsignedDecimal('coupon_amount', 8,2)->nullable();
            $table->unsignedTinyInteger('shipping_type');
            $table->unsignedDecimal('shipping_charge', 6,2);
            $table->unsignedDecimal('total', 8,2)->nullable();
            $table->unsignedDecimal('net_total', 8,2)->nullable();
            // $table->unsignedTinyInteger('status')->default(1);
            $table->string('status')->default('Received');
            // $table->dateTime('date');
            // $table->unsignedTinyInteger('status')->default(1);//Received/Paid/Processing/Shipped/Delivered/Cancelled
            $table->unsignedTinyInteger('payment_method');//cod->1/prepaid->2
            // $table->string('payment_gateway');//cod->1/prepaid->2

            $table->foreignId('user_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('shipping_id')
                ->index()
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // $table->foreignId('order_status_id')
                // ->constrained()
                // ->onDelete('cascade');

            // $table->foreignId('coupon_id')
            //     // ->index()
            //     ->nullable()
            //     ->constrained()
            //     ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
