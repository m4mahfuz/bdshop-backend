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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');            
            $table->foreignId('shipping_address_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('shipper_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->unsignedBigInteger('tracking_no')->nullable();
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
        Schema::dropIfExists('shipments');
    }
};
