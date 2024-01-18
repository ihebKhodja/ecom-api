<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('users_id');
            $table->integer('total')->default(0);
            $table->string('adress')->nullable();
            $table->string('province')->nullable();
            $table->string('mobile')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->enum('status',['ordered','delivered','pending'])->default('ordered');


            // Define foreign keys
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
