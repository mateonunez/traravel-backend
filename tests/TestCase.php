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

    /** @var \App\Models\Role */
    protected $roleAdmin;

    /** @var \App\Models\Role */
    protected $roleEditor;

    /** @var \App\Models\User */
    protected $userAdmin;

    /** @var \App\Models\User */
    protected $userEditor;

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
            ]);

            $this->userAdmin->roles()->attach($this->roleAdmin);
        }

        Passport::actingAs($this->userAdmin);
    }

    /**
     * Return admin headers for API calls.
     *
     * @return void
     */
    protected function actingAsEditor(): void
    {
        $payload = [
            'email' => 'editor@traravel.com',
            'password' => Hash::make('editor'),
        ];

        if (!$this->roleEditor) {
            $this->roleEditor = Role::factory()->create(['code' => Role::EDITOR]);
        }

        if (!$this->userEditor) {
            $this->userEditor = User::factory()->create([
                'email' => $payload['email'],
                'password' => bcrypt($payload['password']),
            ]);

            $this->userEditor->roles()->attach($this->roleEditor);
        }

        Passport::actingAs($this->userEditor);
    }
}
