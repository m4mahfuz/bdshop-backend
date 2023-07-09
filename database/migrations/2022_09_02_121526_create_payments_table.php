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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 8, 2);            
            $table->string('currency', 10)->default('BDT'); 
            // $table->enum('method', ['COD', 'Prepaid'])->default('COD');    
            $table->unsignedTinyInteger('method')->default(1);        
            $table->string('status', 10)->default('Pending');

            $table->foreignId('order_id')
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
        Schema::dropIfExists('payments');
    }
};
