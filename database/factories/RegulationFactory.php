<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Regulation;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegulationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Regulation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::pluck('id');


        return [
            'user_id'=>\App\Models\User::inRandomOrder()->first()->id,
            'country'=>$this->faker->country,
            'photo'=>$this->faker->text(10),
            'description'=>$this->faker->sentences(50),
            'short_description'=>$this->faker->sentence(50),
            'population'=>$this->faker->numerify('########'),
            'area'=>$this->faker->numerify('########'),
            'internet_penetration'=>$this->faker->numerify('#########'),
            'national_currency'=>$this->faker->word,
            'goverment'=>$this->faker->words,
            'president'=>$this->faker->name,
            'capital'=>$this->faker->city,
            'language'=>$this->faker->words,
            'economic_growth'=>$this->faker->numerify('#'),
            'dgtl_curr_lgs'=>$this->faker->sentence(1),
            'dgtl_curr_tax'=>$this->faker->words,
            'dgtl_curr_pymt'=>$this->faker->words,
            'dgtl_curr_ntiol'=>$this->faker->words,
            'ICO'=>$this->faker->word,
            'crpto_antimon_rules'=>$this->faker->word,
        ];
    }
}
