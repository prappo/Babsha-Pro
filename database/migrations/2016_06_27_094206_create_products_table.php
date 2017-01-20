<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fbId');
            $table->string('title');
            $table->string('short_description');
            $table->string('long_description');
            $table->string('price');
            $table->string('image');
            $table->string('status');
            $table->string('category');
            $table->string('featured');
            $table->string('userId');
            $table->string('pageId');
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
        Schema::drop('products');
    }
}
