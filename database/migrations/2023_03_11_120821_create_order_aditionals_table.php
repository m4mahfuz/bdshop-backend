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
        Schema::create('order_aditionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');
            $table->string('discount_type');
            $table->unsignedDecimal('discounted_price', 8, 2)->nullable();
            $table->unsignedSmallInteger('additional_quantity')->nullable();
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
        Schema::dropIfExists('order_aditionals');
    }
};
