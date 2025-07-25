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
            $table->id();
            $table->string('invoice_id');
            $table->unsignedBigInteger('user_id');
            $table->double('sub_total');
            $table->double('amount');
            $table->integer('product_qty');
            $table->integer('product_weight');
            $table->string('payment_method');
            $table->integer('payment_status');
            $table->text('order_address');
            $table->text('shipping_method');
            $table->text('coupon');
            $table->string('order_status');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
