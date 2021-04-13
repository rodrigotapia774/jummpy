<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('proveedor_id');
            $table->foreignId('category_id');
            $table->string('name')->nullable();
            $table->string('resume')->nullable();
            $table->text('content')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('sale')->nullable();
            $table->bigInteger('total')->nullable();
            $table->integer('state');
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
        Schema::dropIfExists('product');
    }
}
