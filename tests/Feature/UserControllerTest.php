<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\Role
     */
    protected $roleAdmin;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();
    }

    /** @group user_controller */
    public function test_acting_as_normal_user_cant_access_user_controller()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $this->get('/api/users')
            ->assertStatus(401);
    }

    /** @group user_controller */
    public function test_index()
    {
        User::factory()->count(10)->create();

        $response = $this->get('/api/users');

        $response->assertStatus(200);
        $response->assertSeeText('data');
    }

    /** @group user_controller */
    public function test_show()
    {
        $user = User::factory()->create();

        $response = $this->get('/api/users/' . $user->id);

        $response->assertStatus(200);
        $response->assertSeeText('data');
    }

    /** @group user_controller */
    public function test_store()
    {
        $role = \App\Models\Role::factory()->create();

        $payload = [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => bcrypt('test'),
            'roleId' => $role->id,
        ];

        $response = $this->post('/api/users', $payload);

        $response->assertStatus(201);
        $response->assertSeeText('data');
    }

    /** @group user_controller */
    public function test_update_put()
    {
        $user = User::factory()->create();

        $newUserName = 'Test PUT update';

        $payload = [
            'name' => $newUserName,
            'email' => $user->email,
            'password' => $user->password
        ];

        $response = $this->put('/api/users/' . $user->id, $payload);

        $response->assertStatus(200);
        $response->assertSeeText('data');
        $response->assertSeeText($newUserName);
    }

    /** @group user_controller */
    public function test_update_patch()
    {
        $user = User::factory()->create();

        $newUserName = 'Test PATCH update';

        $payload = [
            'name' => $newUserName,
        ];

        $response = $this->patch('/api/users/' . $user->id, $payload);

        $response->assertStatus(200);
        $response->assertSeeText('data');
        $response->assertSeeText($newUserName);
    }

    /** @group user_controller */
    public function test_delete()
    {
        $user = User::factory()->create();

        $response = $this->delete('/api/users/' . $user->id);

        $response->assertStatus(200);
    }

    /** @group user_controller */
    public function test_create_with_same_email()
    {
        $user = User::factory()->create();

        $response = $this->delete('/api/users/' . $user->id);
        $response->assertStatus(200);

        $payload = [
            'name' => 'Test',
            'email' => $user->email,
            'password' => bcrypt('test'),
            'roleId' => $user->roleId,
        ];

        $response = $this->post('/api/users', $payload);

        $response->assertStatus(201);
        $response->assertSeeText('data');
        $response->assertSeeText($user->email);
    }
}
