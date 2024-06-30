<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shipping_rules')->insert([
            [
                'name' => 'Pengiriman Gratis',
                'type' => 'min_cost',
                'min_cost' => '0',
                'cost' => '0',
                'status' => '1'
            ]
        ]);

    }
}
