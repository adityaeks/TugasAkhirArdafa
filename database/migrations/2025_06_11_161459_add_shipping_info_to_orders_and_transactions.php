<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingInfoToOrdersAndTransactions extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier')->nullable();
            $table->string('service')->nullable();
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->string('courier')->nullable();
            $table->string('service')->nullable();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('courier')->nullable();
            $table->string('service')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier', 'service']);
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn(['courier', 'service']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['courier', 'service']);
        });
    }
}
