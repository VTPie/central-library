<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::create(['name' => 'Athur Conan Doyle', 'birth_date' => '1859-05-22']);
        Author::create(['name' => 'Agatha Christie', 'birth_date' => '1890-09-15']);
    }
}
