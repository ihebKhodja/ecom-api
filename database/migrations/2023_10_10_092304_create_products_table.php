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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->decimal('price',6, 2);
            $table->string('image')->nullable();
            $table->unsignedInteger('categories_id');
            $table->unsignedInteger('users_id');
            
            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('categories_id')->references('id')->on('categories');
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
