<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        $setRandomAuthor = function ($book) use ($authors, &$data) {
            $data[] = [
                'book_id'   => $book->id,
                'author_id' => $authors->random()->id
            ];
        };

        $books->each($setRandomAuthor);
        $books->random(3)->each($setRandomAuthor);

        DB::table('author_book')->insert($data);
    }
}
