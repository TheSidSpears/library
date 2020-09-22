<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        $birthYear = $this->faker->year();
        $deathYear = (int) $birthYear+random_int(50, 90);
        $deathYear = $deathYear < date("Y") ? $deathYear : null;

        return [
            'name' => $this->faker->firstName,
            'second_name' => $this->faker->lastName,
            'birth_year' => $birthYear,
            'death_year' => $deathYear,
        ];
    }
}
