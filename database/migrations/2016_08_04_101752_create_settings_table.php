<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('userId');
            $table->string('token')->nullable();
            $table->string('appId')->nullable();
            $table->string('appSec')->nullable();
            $table->string('email')->nullable();
            $table->string('currency')->nullable();
            $table->string('paymentMethod')->nullable();
            $table->string('tax')->nullable();
            $table->string('shipping')->nullable();
            $table->string('afterOrderMsg')->nullable();
            $table->string('logo')->nullable();
            $table->string('title')->nullable();
            $table->string('subTitle')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('map')->nullable();
            $table->string('paypalClientId')->nullable();
            $table->string('paypalClientSecret')->nullable();
            $table->string('wooConsumerKey')->nullable();
            $table->string('wooConsumerSecret')->nullable();
            $table->string('wpUrl')->nullable();
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
        Schema::drop('settings');
    }
}
