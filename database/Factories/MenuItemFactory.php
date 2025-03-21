<?php

namespace Kwidoo\Mere\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * extends \Illuminate\Database\Eloquent\Factories\Factory<\Kwidoo\Mere\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'path' => '/' . $this->faker->slug(),
            'component' => 'GenericResource',
            'props' => [
                'fields' => [['key' => 'name']],
                'rules' => ['name' => 'required'],
                'actions' => ['create' => true],
            ],
        ];
    }
}
