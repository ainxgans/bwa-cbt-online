<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Programming',
                'slug' => 'programming',
                'created_at' => now(),
                'updated_at' => now(),],
            ['name' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'created_at' => now(),
                'updated_at' => now(),],
            [
                'name' => 'Product Design',
                'slug' => 'product-design',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
