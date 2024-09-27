<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type>
 */
class TypeFactory extends Factory
{
    protected $model = Type::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        // 設定指定類別名稱
        $typeNames = [
            '蛋糕',
            '餅乾',
            '果汁'
        ];
        return [
            'name' => $faker->unique()->randomElement($typeNames),
            'sort' => $faker->numberBetween(1, 100),  // 排序
        ];
    }
}
