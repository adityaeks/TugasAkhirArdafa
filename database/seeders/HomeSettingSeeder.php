<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('home_settings')->insert([
            [
                'key' => 'popular_category_section',
                'value' => '[{"category":"10","sub_category":"13","child_category":null},{"category":"11","sub_category":null,"child_category":null},{"category":"12","sub_category":null,"child_category":null},{"category":"10","sub_category":null,"child_category":null}]'
            ],
            [
                'key' => 'product_slider_section_one',
                'value' => '{"category":"11","sub_category":null,"child_category":null}'
            ],
            [
                'key' => 'product_slider_section_two',
                'value' => '{"category":"12","sub_category":null,"child_category":null}'
            ],
            [
                'key' => 'product_slider_section_three',
                'value' => '[{"category":"10","sub_category":"14","child_category":null},{"category":"10","sub_category":"13","child_category":null}]'
            ]
        ]);
    }
}
