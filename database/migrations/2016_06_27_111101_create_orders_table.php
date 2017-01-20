<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orderId');
            $table->string('sender');
            $table->string('productid');
            $table->string('status');
            $table->integer('price');
            $table->string('method');
            $table->string('payment')->nullable();
            $table->string('type');
            $table->string('pageId');
            $table->string('userId');
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
        Schema::drop('orders');
    }
}
