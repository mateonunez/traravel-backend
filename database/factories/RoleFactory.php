<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $role = $this->faker->name();

        return [
            'name' => Str::ucfirst($role),
            'code' => Str::lower($role),
            'descrpition' => $role . ' role',
        ];
    }
}
