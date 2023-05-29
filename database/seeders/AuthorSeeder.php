<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authors = [
            ['name' => 'John Smith', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Emily Johnson', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Michael Brown', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sophia Davis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Daniel Wilson', 'created_at' => now(), 'updated_at' => now()],
        ];

        Author::insert($authors);

    }
}
