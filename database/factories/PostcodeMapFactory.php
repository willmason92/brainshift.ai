<?php

namespace Database\Factories;

use App\Models\PostcodeMap;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostcodeMapFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostcodeMap::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'outcode' => $this->faker->postcode,
            'region_id' => Region::factory()->create()->id,
        ];
    }
}
