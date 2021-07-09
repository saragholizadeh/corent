<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Sub_Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->text(10),
            'description'=>$this->faker->sentence(5),
            'parent_id' => \App\Models\Category::class,
        ];
    }
}
