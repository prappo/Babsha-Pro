<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//
//        DB::table('settings')->insert([
//            'key' => 'token',
//            'value' => 'your facebook token'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'appId',
//            'value' => 'Your facebook App ID'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'appSec',
//            'value' => 'Your facebook App secret'
//        ]);
//
//
//        DB::table('settings')->insert([
//            'key' => 'email',
//            'value' => 'email@domain.com'
//        ]);
//
//        DB::table('settings')->insert([
//            'key' => 'currency',
//            'value' => 'USD'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'paymentMethod',
//            'value' => 'PayPal'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'tax',
//            'value' => '0'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'shipping',
//            'value' => '0'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'afterOrderMsg',
//            'value' => 'Thank you for your order . We will contact your soon'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'logo',
//            'value' => 'zubehor.jpg'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'title',
//            'value' => 'Shop Title'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'subTitle',
//            'value' => 'Sub title'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'address',
//            'value' => 'Shop address here'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'phone',
//            'value' => '+8801780000000'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'map',
//            'value' => 'Rampura,Dhaka,Bangladesh'
//        ]);
//
//        DB::table('settings')->insert([
//            'key' => 'reg',
//            'value' => 'on'
//        ]);
//
//        DB::table('settings')->insert([
//            'key' => 'lang',
//            'value' => 'no'
//        ]);
//
//        DB::table('settings')->insert([
//            'key' => 'fixedLang',
//            'value' => 'en'
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'fixedLangOp',
//            'value' => 'no'
//        ]);
//
//        DB::table('settings')->insert([
//            'key' => 'paypalClientId',
//            'value' => ''
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'paypalClientSecret',
//            'value' => ''
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'wooConsumerKey',
//            'value' => ''
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'wooConsumerSecret',
//            'value' => ''
//        ]);
//        DB::table('settings')->insert([
//            'key' => 'wpUrl',
//            'value' => ''
//        ]);

        DB::table('site_settings')->insert([
            'key' => 'token',
            'value' => 'your facebook token'
        ]);
        DB::table('site_settings')->insert([
            'key' => 'appId',
            'value' => 'Your facebook App ID'
        ]);
        DB::table('site_settings')->insert([
            'key' => 'appSec',
            'value' => 'Your facebook App secret'
        ]);





            User::create([
            'name' => 'Super Admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('123456'),
            'type' => 'admin',
        ]);


    }
}
