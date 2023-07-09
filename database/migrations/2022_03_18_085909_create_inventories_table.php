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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->index();
            // $table->decimal('price', $precision = 8, $scale = 2);
            $table->integer('quantity')->default(0);            

            // $table->foreignId('product_id')
            //     ->index()
            //     ->constrained()
            //     ->onDelete('cascade');

            // $table->foreignId('discount_id')
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
        Schema::dropIfExists('inventories');
    }
};
