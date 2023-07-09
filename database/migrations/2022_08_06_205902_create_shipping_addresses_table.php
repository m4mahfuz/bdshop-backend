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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();                            
            $table->string('name');
            $table->text('address_line');
            $table->string('phone');
            $table->string('city');
            $table->unsignedSmallInteger('postal_code')->nullable();

            $table->foreignId('order_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->index()
                ->nullable()
                ->constrained()
                ->nullOnDelete();            

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
        Schema::dropIfExists('shipping_addresses');
    }
};
