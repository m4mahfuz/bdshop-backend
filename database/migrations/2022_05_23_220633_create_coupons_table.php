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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->text('categories')->nullable();
            $table->text('users')->nullable();
            $table->tinyInteger('usage')->default(2); //1-> Single 2:// Multiple
            $table->tinyInteger('amount_type')->default(1); // 1->percentage 2->fixed 
            $table->unsignedMediumInteger('minimum_spending')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
