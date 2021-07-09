<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = Category::where('parent_id' , '!=',123)->get();
        $users = User::pluck('id');
        return [
            'user_id'=>$this->faker->randomElement($users),
            'category_id'=>$this->faker->randomElement($categories),
            'title'=>$this->faker->sentence(10),
            'short_description'=>$this->faker->sentence(15),
            'long_description'=>$this->faker->sentence(40),
            'picture'=>$this->faker->sentence(40),
            'study_time'=>$this->faker->numerify('##'),
            'likes'=>$this->faker->numerify('####'),
            'tags'=>$this->faker->words,

        ];
    }
}
