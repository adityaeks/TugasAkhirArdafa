<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
        DB::table('categories')->insert([
            [
                'name' => 'Tumpeng & Nasi Liwet',
                'slug' => 'tumpeng-nasi-liwet',
                'updated_at' => now()
            ],
            [
                'name' => 'Daily Home Catering',
                'slug' => 'daily-home-catering',
                'updated_at' => now()
            ],
            [
                'name' => 'Prasmanan Buffet',
                'slug' => 'prasmanan-buffet',
                'updated_at' => now()
            ],
            [
                'name' => 'Meal Box',
                'slug' => 'meal-box',
                'updated_at' => now()
            ],
            [
                'name' => 'Snack Box',
                'slug' => 'snack-box',
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
