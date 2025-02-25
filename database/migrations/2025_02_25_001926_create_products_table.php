<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->integer('price');
            $table->text('description');
            $table->string('brand')->nullable();
            $table->string('url')->nullable();
            $table->text('specifications')->nullable();
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
