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
        Schema::create('shipping_types', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->unsignedTinyInteger('type'); // 1: standard  2- Express
            // $table->unsignedDecimal('charge', 6,2);
            $table->unsignedTinyInteger('delivery_time_min'); // unit: hour for express else day
            $table->unsignedTinyInteger('delivery_time_max');

            $table->foreignId('shipping_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');  

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
        Schema::dropIfExists('shipping_types');
    }
};
