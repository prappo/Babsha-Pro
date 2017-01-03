<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fbId');
            $table->string('name');
            $table->string('image');
            $table->string('street');
            $table->string('city');
            $table->string('postal_code');
            $table->string('state');
            $table->string('country');
            $table->string('mobile');
            $table->string('lang')->nullable();
            $table->string('bot')->nullable();
            $table->string('coordinates')->nullable();
            $table->string('address')->nullable();
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
        Schema::drop('customers');
    }
}
