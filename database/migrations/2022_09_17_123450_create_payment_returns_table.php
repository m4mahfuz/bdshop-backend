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
        Schema::create('payment_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('amount', 8,2);//->nullable();
            $table->string('APIConnect')->nullable();
            $table->string('bank_transaction_id')->nullable();
            $table->string('trans_id')->nullable();
            $table->string('refund_ref_id')->nullable();

            $table->string('status');//Return request/To be verified/ Initiated/ Processing/ Refunded
            // $table->string('gw_status');//success/failed/processing
            $table->string('error_reason')->nullable();

            $table->foreignId('payment_id')
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
        Schema::dropIfExists('payment_returns');
    }
};
