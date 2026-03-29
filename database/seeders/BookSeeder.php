<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Grimm Fairy Tales',
            'cover_url' => 'cover_img/book1.webp',
            'author_id' => 2, // Jacob - Wilhelm Grimm
            'category_id' => 1, // Fiction
            'published_year' => 1934,
        ]);

        Book::create([
            'title' => 'Don Quixote',
            'cover_url' => 'cover_img/book2.webp',
            'author_id' => 1, // Miguel De Cervantes
            'category_id' => 1, // Fiction
            'published_year' => 1934,
        ]);
    }
}
