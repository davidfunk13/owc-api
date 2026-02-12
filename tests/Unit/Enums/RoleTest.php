<?php

namespace Tests\Unit\Enums;

use App\Enums\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function test_role_has_expected_cases(): void
    {
        $this->assertCount(3, Role::cases());
        $this->assertEquals(['tank', 'damage', 'support'], Role::values());
    }

    public function test_role_has_correct_backing_values(): void
    {
        $this->assertEquals('tank', Role::Tank->value);
        $this->assertEquals('damage', Role::Damage->value);
        $this->assertEquals('support', Role::Support->value);
    }

    public function test_role_has_correct_labels(): void
    {
        $this->assertEquals('Tank', Role::Tank->label());
        $this->assertEquals('Damage', Role::Damage->label());
        $this->assertEquals('Support', Role::Support->label());
    }

    public function test_role_from_valid_string(): void
    {
        $this->assertEquals(Role::Tank, Role::from('tank'));
        $this->assertEquals(Role::Damage, Role::from('damage'));
        $this->assertEquals(Role::Support, Role::from('support'));
    }

    public function test_role_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(Role::tryFrom('invalid'));
        $this->assertNull(Role::tryFrom(''));
    }
}
