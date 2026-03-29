<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::create(['name' => 'Miguel De Cervantes', 'birth_date' => '1859-05-22']);
        Author::create(['name' => 'Jacob - Wilhelm Grimm', 'birth_date' => '1890-09-15']);
    }
}
