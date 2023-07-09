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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description');
            $table->boolean('active')->default(true);
            $table->decimal('price', $precision = 8, $scale = 2);
            $table->string('unit');
            $table->unsignedSmallInteger('unit_quantity');

            $table->foreignId('inventory_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('featured_image_id')
                ->index()
                ->nullable();
                

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('discount_id')
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
        Schema::dropIfExists('products');
    }
};
