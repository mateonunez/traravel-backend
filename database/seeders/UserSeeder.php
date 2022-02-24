<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'admin@traravel.in',
            'password' => Hash::make('admin'),
            'emailVerifiedAt' => Carbon::now()
        ];

        $userAdmin = User::create($payloadAdmin);
        $roleAdmin = Role::getAdminRole();

        $userAdmin->roles()->attach($roleAdmin);

        $payloadEditor = [
            'name' => 'Editor',
            'email' => 'editor@traravel.in',
            'password' => Hash::make('editor'),
            'emailVerifiedAt' => Carbon::now()
        ];

        $userEditor = User::create($payloadEditor);
        $roleEditor = Role::getEditorRole();

        $userEditor->roles()->attach($roleEditor);
    }
}
