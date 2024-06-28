<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdverisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('adverisements')->insert([
            [
                'key' => 'homepage_secion_banner_one',
                'value' => '{"banner_one":{"banner_url":"fsadfasdfsd","status":1,"banner_image":"uploads\/media_644cf1a03b212.png"}}',
            ],
            [
                'key' => 'homepage_secion_banner_two',
                'value' => '{"banner_one":{"banner_url":"test","status":1,"banner_image":"uploads\/media_644cf177be491.png"},"banner_two":{"banner_url":"test","status":1,"banner_image":"uploads\/media_644ce7818d45e.png"}}',
            ],
            [
                'key' => 'homepage_secion_banner_three',
                'value' => '{"banner_one":{"banner_url":"test","status":1,"banner_image":"uploads\/media_644ce82555973.png"},"banner_two":{"banner_url":"test","status":1,"banner_image":"uploads\/media_644ce7c48fc61.png"},"banner_three":{"banner_url":"test","status":1,"banner_image":"uploads\/media_644ce89a6d389.png"}}',
            ],
            [
                'key' => 'homepage_secion_banner_four',
                'value' => '{"banner_one":{"banner_url":"test","status":1,"banner_image":"uploads\/media_644ce9ed3b6ca.png"}}',
            ],
            [
                'key' => 'productpage_banner_section',
                'value' => '{"banner_one":{"banner_url":"#","status":1,"banner_image":"uploads\/media_644cf035b903b.png"}}',
            ],
            [
                'key' => 'cartpage_banner_section',
                'value' => '{"banner_one":{"banner_url":"#","status":1,"banner_image":"uploads\/media_644cf14006136.png"},"banner_two":{"banner_url":"#","status":1,"banner_image":"uploads\/media_644cf14006649.png"}}',
            ],
        ]);
    }
}
