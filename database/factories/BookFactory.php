<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement([
                'Hobbit', 'Learn PHP in 5 minutes', 'The Great Gatsby', 'One Hundred Years of Solitude',
                'War and Peace', 'Lolita', 'Hamlet', 'Crime and Punishment', 'Nineteen Eighty Four', 'Heart of Darkness'
            ]),
            'description' => $this->faker->sentence(),
            'publish_year' => $this->faker->year(),
        ];
    }
}
