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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->morphs('dealable');             
            $table->tinyInteger('amount_type')->default(1); // 1->percentage 2->fixed 
            $table->unsignedMediumInteger('amount');
            // $table->boolean('active')->default(true);
            $table->dateTime('starting', $precision = 0);
            $table->dateTime('ending', $precision = 0);
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
        Schema::dropIfExists('deals');
    }
};
