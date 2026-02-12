<?php

namespace Tests\Unit\Models;

use App\Models\CustomStatDefinition;
use App\Models\GameCustomStat;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomStatDefinitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_custom_stat_definition_can_be_created_with_factory(): void
    {
        $def = CustomStatDefinition::factory()->create();

        $this->assertDatabaseHas('custom_stat_definitions', ['id' => $def->id]);
    }

    public function test_custom_stat_definition_has_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $def = CustomStatDefinition::create([
            'user_id' => $user->id,
            'name' => 'Eliminations',
            'slug' => 'eliminations',
            'data_type' => 'integer',
            'unit' => 'kills',
        ]);

        $this->assertEquals('Eliminations', $def->name);
        $this->assertEquals('eliminations', $def->slug);
        $this->assertEquals('integer', $def->data_type);
        $this->assertEquals('kills', $def->unit);
    }

    public function test_custom_stat_definition_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $def = CustomStatDefinition::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $def->user);
        $this->assertEquals($user->id, $def->user->id);
    }

    public function test_custom_stat_definition_has_many_game_custom_stats(): void
    {
        $def = CustomStatDefinition::factory()->create();
        GameCustomStat::factory()->create(['custom_stat_definition_id' => $def->id]);

        $this->assertCount(1, $def->gameCustomStats);
    }

    public function test_custom_stat_definition_slug_unique_per_user(): void
    {
        $user = User::factory()->create();
        CustomStatDefinition::factory()->create(['user_id' => $user->id, 'slug' => 'eliminations']);

        $this->expectException(QueryException::class);
        CustomStatDefinition::factory()->create(['user_id' => $user->id, 'slug' => 'eliminations']);
    }

    public function test_custom_stat_definition_cascades_on_user_delete(): void
    {
        $def = CustomStatDefinition::factory()->create();
        $userId = $def->user_id;

        User::find($userId)->delete();

        $this->assertDatabaseMissing('custom_stat_definitions', ['id' => $def->id]);
    }

    public function test_custom_stat_definition_defaults(): void
    {
        $user = User::factory()->create();
        $def = CustomStatDefinition::create([
            'user_id' => $user->id,
            'name' => 'Test Stat',
            'slug' => 'test-stat',
        ]);

        $this->assertEquals('integer', $def->data_type);
        $this->assertEquals(0, $def->sort_order);
        $this->assertTrue($def->is_active);
    }
}
