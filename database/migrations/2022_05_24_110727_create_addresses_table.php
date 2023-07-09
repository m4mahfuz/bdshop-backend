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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 160);
            $table->string('name');
            $table->text('address_line');
            $table->string('phone');
            $table->unsignedSmallInteger('postal_code')->nullable();            
            $table->string('city');
            $table->boolean('active')->default(true);

            $table->foreignId('user_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');            

            // $table->string('country', 50);            

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
        Schema::dropIfExists('addresses');
    }
};
