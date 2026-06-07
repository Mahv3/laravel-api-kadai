<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $openHour = fake()->numberBetween(8, 11); // 8:00〜11:00 の間
        $closeHour = fake()->numberBetween(20, 22); // 20:00〜22:00 の間

        return [
            'name' => fake()->company() . ' 店',
            'floor' => fake()->randomElement(['1F', '2F', '3F', '4F', '5F', 'B1F']),
            'category' => fake()->randomElement(['レストラン・カフェ', 'ファッション', '雑貨', 'サービス', 'アミューズメント']),
            // open_time / close_time: '09:00:00' のような形式
            'open_time' => sprintf('%02d:00:00', $openHour),
            'close_time' => sprintf('%02d:00:00', $closeHour),
            // tel: ランダムな電話番号（NULLの可能性も20%持たせる）
            'tel' => fake()->optional(0.8)->phoneNumber(),
            // description: ランダムな文章（NULLの可能性も20%持たせる）
            'description' => fake()->optional(0.8)->realText(100), 
            // is_temporarily_closed: 90%はfalse（営業中）、10%の確率でtrue（臨時休業）
            'is_temporarily_closed' => fake()->boolean(10),
        ];
    }
}
