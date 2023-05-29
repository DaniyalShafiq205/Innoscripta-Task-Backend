<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Entertainment', 'slug'=>'entertainment','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports', 'slug'=>'sports','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technology', 'slug'=>'technology','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Business', 'slug'=>'business','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Health', 'slug'=>'health','created_at' => now(), 'updated_at' => now()],
        ];

        Category::insert($categories);

    }
}
