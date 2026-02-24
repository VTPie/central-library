<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
        Book::create([
            'title' => 'A Study in Scarlet',
            'cover_url' => 'cover_img/book1.webp',
            'author_id' => 1, // Arthur Conan Doyle
            'category_id' => 1, // Fiction
            'published_year' => 1934,
        ]);

        Book::create([
            'title' => 'Murder on the Orient Express',
            'cover_url' => 'cover_img/book2.webp',
            'author_id' => 2, // Agatha Christie
            'category_id' => 1, // Fiction
            'published_year' => 1934,
        ]);
    }
}
