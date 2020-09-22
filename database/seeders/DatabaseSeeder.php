<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        Book::factory(10)->create();
        Author::factory(10)->create();
        $this->setAuthorship();
    }

    protected function setAuthorship(): void
    {
        $books = Book::query()->select(['id'])->get();
        $authors = Author::query()->select(['id'])->get();

        $books->each(function (Book $book) use ($authors) {
            $authorsCount = random_int(1, 2);
            $book->authors()->attach($authors->random($authorsCount));
        });
    }
}
