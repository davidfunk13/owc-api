<?php

namespace Tests\Unit\Enums;

use App\Enums\ProcessingStatus;
use PHPUnit\Framework\TestCase;

class ProcessingStatusTest extends TestCase
{
    public function test_processing_status_has_expected_cases(): void
    {
        $this->assertCount(4, ProcessingStatus::cases());
        $this->assertEquals([
            'pending',
            'processing',
            'completed',
            'failed',
        ], ProcessingStatus::values());
    }

    public function test_processing_status_has_correct_labels(): void
    {
        $this->assertEquals('Pending', ProcessingStatus::Pending->label());
        $this->assertEquals('Processing', ProcessingStatus::Processing->label());
        $this->assertEquals('Completed', ProcessingStatus::Completed->label());
        $this->assertEquals('Failed', ProcessingStatus::Failed->label());
    }

    public function test_processing_status_from_valid_string(): void
    {
        $this->assertEquals(ProcessingStatus::Pending, ProcessingStatus::from('pending'));
        $this->assertEquals(ProcessingStatus::Completed, ProcessingStatus::from('completed'));
    }

    public function test_processing_status_try_from_invalid_string_returns_null(): void
    {
        $this->assertNull(ProcessingStatus::tryFrom('invalid'));
    }
}
