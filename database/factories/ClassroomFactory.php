<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Utils\UniqueCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ClassroomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Classroom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'nickname' => $this->faker->name,
            'code' => UniqueCode::generate(),
            'creator_id' => 1
        ];
    }
}
