<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payloadAdmin = [
            'name' => 'Admin',
            'code' => 'admin',
            'description' => 'Administrator',
        ];

        $payloadEditor = [
            'name' => 'Editor',
            'code' => 'editor',
            'description' => 'Editor',
        ];

        Role::create($payloadAdmin);
        Role::create($payloadEditor);
    }
}
