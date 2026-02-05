<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_factory(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
        $this->assertNotNull($user->sub);
        $this->assertNotNull($user->battlenet_id);
        $this->assertNotNull($user->battletag);
    }

    public function test_user_has_fillable_attributes(): void
    {
        $user = User::create([
            'sub' => 'test-sub-123',
            'battlenet_id' => '999888777',
            'battletag' => 'TestUser#1234',
        ]);

        $this->assertEquals('test-sub-123', $user->sub);
        $this->assertEquals('999888777', $user->battlenet_id);
        $this->assertEquals('TestUser#1234', $user->battletag);
    }

    public function test_user_hides_remember_token(): void
    {
        $user = User::factory()->create([
            'remember_token' => 'secret_token',
        ]);

        $array = $user->toArray();

        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_user_can_create_api_tokens(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-token');

        $this->assertNotNull($token->plainTextToken);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-token',
        ]);
    }

    public function test_update_or_create_creates_new_user(): void
    {
        $user = User::updateOrCreate(
            ['battlenet_id' => '123456789'],
            [
                'sub' => 'new-sub',
                'battletag' => 'NewPlayer#0001',
            ]
        );

        $this->assertDatabaseCount('users', 1);
        $this->assertEquals('123456789', $user->battlenet_id);
        $this->assertEquals('new-sub', $user->sub);
        $this->assertEquals('NewPlayer#0001', $user->battletag);
    }

    public function test_update_or_create_updates_existing_user(): void
    {
        User::factory()->create([
            'battlenet_id' => '123456789',
            'sub' => 'old-sub',
            'battletag' => 'OldPlayer#0001',
        ]);

        $user = User::updateOrCreate(
            ['battlenet_id' => '123456789'],
            [
                'sub' => 'updated-sub',
                'battletag' => 'UpdatedPlayer#0002',
            ]
        );

        $this->assertDatabaseCount('users', 1);
        $this->assertEquals('updated-sub', $user->sub);
        $this->assertEquals('UpdatedPlayer#0002', $user->battletag);
    }
}
