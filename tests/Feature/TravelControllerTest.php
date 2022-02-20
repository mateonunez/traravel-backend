<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Travel;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @group test_controller */
    public function test_acting_as_normal_user_see_only_public_travels()
    {
        $travels = Travel::factory()->count(10)->create();

        $travels[0]->isPublic = true;
        $travels[0]->save();

        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->get('/api/travels');

        $data = json_decode($response->getContent())->data;

        $this->assertCount(1, $data);
    }

    /** @group test_controller */
    public function test_acting_as_admin_see_all_traves()
    {
        $travels = Travel::factory()->count(10)->create();

        $travels[0]->isPublic = true;
        $travels[0]->save();

        $this->actingAsAdmin();
        $response = $this->get('/api/travels');

        $data = json_decode($response->getContent())->data;

        $this->assertCount(10, $data);
    }

    /** @group test_controller */
    public function test_acting_as_editor_see_all_travels()
    {
        $travels = Travel::factory()->count(10)->create();

        $travels[0]->isPublic = true;
        $travels[0]->save();

        $this->actingAsEditor();
        $response = $this->get('/api/travels');

        $data = json_decode($response->getContent())->data;

        $this->assertCount(10, $data);
    }
}
