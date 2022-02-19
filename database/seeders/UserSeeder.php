<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
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
            'email' => 'admin@traravel.com',
            'password' => Hash::make('admin'),
            'emailVerifiedAt' => Carbon::now()
        ];

        $userAdmin = User::create($payloadAdmin);
        $roleAdmin = Role::getAdminRole();

        $userAdmin->roles()->attach($roleAdmin);
    }
}
