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
        Schema::create('deducts', function (Blueprint $table) {
            $table->id();
            $table->morphs('deductable');             
            // $table->unsignedSmallInteger('rate')->default(0);
            $table->unsignedMediumInteger('amount')->nullable();
            $table->boolean('active')->default(true);
            // $table->unsignedMediumInteger('minimum_spending')->nullable();
            $table->unsignedMediumInteger('limit')->nullable();
            $table->date('starting')->nullable();
            $table->date('ending')->nullable();
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
        Schema::dropIfExists('deducts');
    }
};
