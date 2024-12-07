<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
/**
     * The name of the model that is being factory-generated.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'category' => $this->faker->randomElement(['Electronics', 'Fashion', 'Home', 'Books']),
            'description' => $this->faker->paragraph(),
            'date_time' => $this->faker->dateTimeThisYear(),
            'type' => $this->faker->randomElement(['physical', 'digital']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 1, 999),
            'images' => null,
        ];
    }
}
