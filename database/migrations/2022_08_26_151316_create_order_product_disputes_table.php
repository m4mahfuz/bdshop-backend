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
        Schema::create('order_product_disputes', function (Blueprint $table) {
            $table->id();
            $table->string('status'); // cancelled / return initiated/processing/returned
            $table->string('reason')->nullable();
            $table->foreignId('order_product_id')
                ->index()
                ->constrained('order_product')
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
        Schema::dropIfExists('order_product_disputes');
    }
};
