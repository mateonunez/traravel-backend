<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * @var \App\Models\Role
     */
    protected $roleAdmin;

    /**
     * @var \App\Models\User
     */
    protected $userAdmin;

    /**
     * Initial setup for tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        if (!$this->app) {
            $this->refreshApplication();
        }

        $this->refreshDatabase();
    }

    /**
     * Return admin headers for API calls.
     *
     * @return void
     */
    protected function actingAsAdmin(): void
    {
        $payload = [
            'email' => 'admin@traravel.com',
            'password' => Hash::make('admin'),
        ];

        if (!$this->roleAdmin) {
            $this->roleAdmin = Role::factory()->create(['code' => Role::ADMIN]);
        }

        if (!$this->userAdmin) {
            $this->userAdmin = User::factory()->create([
                'email' => $payload['email'],
                'password' => bcrypt($payload['password']),
                'roleId' => $this->roleAdmin->id,
            ]);
        }

        Passport::actingAs($this->userAdmin);
    }
}
