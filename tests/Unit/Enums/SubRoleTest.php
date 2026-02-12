<?php

namespace Tests\Unit\Enums;

use App\Enums\Role;
use App\Enums\SubRole;
use PHPUnit\Framework\TestCase;

class SubRoleTest extends TestCase
{
    public function test_sub_role_has_expected_cases(): void
    {
        $this->assertCount(10, SubRole::cases());
        $this->assertEquals([
            'bruiser', 'initiator', 'stalwart',
            'flanker', 'sharpshooter', 'specialist', 'recon',
            'medic', 'survivor', 'tactician',
        ], SubRole::values());
    }

    public function test_sub_role_has_correct_backing_values(): void
    {
        $this->assertEquals('bruiser', SubRole::Bruiser->value);
        $this->assertEquals('initiator', SubRole::Initiator->value);
        $this->assertEquals('stalwart', SubRole::Stalwart->value);
        $this->assertEquals('flanker', SubRole::Flanker->value);
        $this->assertEquals('sharpshooter', SubRole::Sharpshooter->value);
        $this->assertEquals('specialist', SubRole::Specialist->value);
        $this->assertEquals('recon', SubRole::Recon->value);
        $this->assertEquals('medic', SubRole::Medic->value);
        $this->assertEquals('survivor', SubRole::Survivor->value);
        $this->assertEquals('tactician', SubRole::Tactician->value);
    }

    public function test_sub_role_has_correct_labels(): void
    {
        $this->assertEquals('Bruiser', SubRole::Bruiser->label());
        $this->assertEquals('Initiator', SubRole::Initiator->label());
        $this->assertEquals('Stalwart', SubRole::Stalwart->label());
        $this->assertEquals('Flanker', SubRole::Flanker->label());
        $this->assertEquals('Sharpshooter', SubRole::Sharpshooter->label());
        $this->assertEquals('Specialist', SubRole::Specialist->label());
        $this->assertEquals('Recon', SubRole::Recon->label());
        $this->assertEquals('Medic', SubRole::Medic->label());
        $this->assertEquals('Survivor', SubRole::Survivor->label());
        $this->assertEquals('Tactician', SubRole::Tactician->label());
    }

    public function test_sub_role_for_role_tank(): void
    {
        $tankSubRoles = SubRole::forRole(Role::Tank);
        $this->assertCount(3, $tankSubRoles);
        $this->assertEquals([SubRole::Bruiser, SubRole::Initiator, SubRole::Stalwart], $tankSubRoles);
    }

    public function test_sub_role_for_role_damage(): void
    {
        $damageSubRoles = SubRole::forRole(Role::Damage);
        $this->assertCount(4, $damageSubRoles);
        $this->assertEquals([SubRole::Flanker, SubRole::Sharpshooter, SubRole::Specialist, SubRole::Recon], $damageSubRoles);
    }

    public function test_sub_role_for_role_support(): void
    {
        $supportSubRoles = SubRole::forRole(Role::Support);
        $this->assertCount(3, $supportSubRoles);
        $this->assertEquals([SubRole::Medic, SubRole::Survivor, SubRole::Tactician], $supportSubRoles);
    }

    public function test_sub_role_role_method(): void
    {
        $this->assertEquals(Role::Tank, SubRole::Bruiser->role());
        $this->assertEquals(Role::Tank, SubRole::Initiator->role());
        $this->assertEquals(Role::Tank, SubRole::Stalwart->role());
        $this->assertEquals(Role::Damage, SubRole::Flanker->role());
        $this->assertEquals(Role::Damage, SubRole::Sharpshooter->role());
        $this->assertEquals(Role::Damage, SubRole::Specialist->role());
        $this->assertEquals(Role::Damage, SubRole::Recon->role());
        $this->assertEquals(Role::Support, SubRole::Medic->role());
        $this->assertEquals(Role::Support, SubRole::Survivor->role());
        $this->assertEquals(Role::Support, SubRole::Tactician->role());
    }

    public function test_sub_role_from_valid_string(): void
    {
        $this->assertEquals(SubRole::Bruiser, SubRole::from('bruiser'));
        $this->assertEquals(SubRole::Tactician, SubRole::from('tactician'));
    }

    public function test_sub_role_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(SubRole::tryFrom('invalid'));
    }
}
