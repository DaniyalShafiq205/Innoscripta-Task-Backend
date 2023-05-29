<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sources = [
            ['name' => 'CNN', 'slug'=>'cnn','created_at' => now(), 'updated_at' => now()],
            ['name' => 'BBC News', 'slug'=>'BBC News','created_at' => now(), 'updated_at' => now()],
            ['name' => 'The New York Times', 'slug'=>'The New York Times','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Jazeera', 'slug'=>'Al Jazeera','created_at' => now(), 'updated_at' => now()],
            ['name' => 'Google News', 'slug'=>'google-news','created_at' => now(), 'updated_at' => now()],
        ];

        Source::insert($sources);
        // php artisan db:seed --class=SourceSeeder
    }
}
