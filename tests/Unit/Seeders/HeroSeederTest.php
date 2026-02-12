<?php

namespace Tests\Unit\Seeders;

use App\Enums\Role;
use App\Enums\SubRole;
use App\Models\Hero;
use Database\Seeders\HeroSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_seeder_creates_all_heroes(): void
    {
        $this->seed(HeroSeeder::class);

        $this->assertEquals(50, Hero::count());
    }

    public function test_hero_seeder_is_idempotent(): void
    {
        $this->seed(HeroSeeder::class);
        $this->seed(HeroSeeder::class);

        $this->assertEquals(50, Hero::count());
    }

    public function test_hero_seeder_assigns_correct_roles(): void
    {
        $this->seed(HeroSeeder::class);

        $ana = Hero::where('slug', 'ana')->first();
        $reinhardt = Hero::where('slug', 'reinhardt')->first();
        $tracer = Hero::where('slug', 'tracer')->first();

        $this->assertEquals(Role::Support, $ana->role);
        $this->assertEquals(Role::Tank, $reinhardt->role);
        $this->assertEquals(Role::Damage, $tracer->role);
    }

    public function test_hero_seeder_assigns_correct_sub_roles(): void
    {
        $this->seed(HeroSeeder::class);

        $ana = Hero::where('slug', 'ana')->first();
        $reinhardt = Hero::where('slug', 'reinhardt')->first();
        $tracer = Hero::where('slug', 'tracer')->first();
        $widowmaker = Hero::where('slug', 'widowmaker')->first();

        $this->assertEquals(SubRole::Tactician, $ana->sub_role);
        $this->assertEquals(SubRole::Stalwart, $reinhardt->sub_role);
        $this->assertEquals(SubRole::Flanker, $tracer->sub_role);
        $this->assertEquals(SubRole::Sharpshooter, $widowmaker->sub_role);
    }
}
