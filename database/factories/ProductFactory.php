<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 設定 Faker 語系為繁體中文
        $faker = FakerFactory::create('zh_TW');

        // 隨機選取一個已存在的 Type
        $type = Type::inRandomOrder()->firstOrCreate([
            'name' => $faker->randomElement(['蛋糕', '餅乾', '果汁'])
        ]);
        return [
            'type_id' => $type->id,
            'product_name' => $faker->name,
            'product_description' => $faker->realText(20),
            'price' => $faker->numberBetween(30, 1000),
        ];
    }
}
