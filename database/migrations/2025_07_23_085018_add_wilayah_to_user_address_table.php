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
        Schema::table('user_address', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable()->after('email');
            $table->unsignedBigInteger('regency_id')->nullable()->after('province_id');
            $table->unsignedBigInteger('district_id')->nullable()->after('regency_id');
            $table->unsignedBigInteger('village_id')->nullable()->after('district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_address', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
        });
    }
};
