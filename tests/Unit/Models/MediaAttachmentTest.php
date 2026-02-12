<?php

namespace Tests\Unit\Models;

use App\Enums\MediaType;
use App\Enums\ProcessingStatus;
use App\Models\CareerStatSnapshot;
use App\Models\Game;
use App\Models\MediaAttachment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaAttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_attachment_can_be_created_with_factory(): void
    {
        $attachment = MediaAttachment::factory()->create();

        $this->assertDatabaseHas('media_attachments', ['id' => $attachment->id]);
    }

    public function test_media_attachment_has_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $attachment = MediaAttachment::create([
            'user_id' => $user->id,
            'media_type' => 'scoreboard_screenshot',
            'file_path' => 'screenshots/test-uuid.png',
            'original_filename' => 'scoreboard.png',
            'mime_type' => 'image/png',
            'file_size' => 150000,
        ]);

        $this->assertEquals($user->id, $attachment->user_id);
        $this->assertEquals(MediaType::ScoreboardScreenshot, $attachment->media_type);
        $this->assertEquals('screenshots/test-uuid.png', $attachment->file_path);
        $this->assertEquals('scoreboard.png', $attachment->original_filename);
        $this->assertEquals('image/png', $attachment->mime_type);
        $this->assertEquals(150000, $attachment->file_size);
    }

    public function test_media_attachment_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $attachment = MediaAttachment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $attachment->user);
        $this->assertEquals($user->id, $attachment->user->id);
    }

    public function test_media_attachment_casts_media_type_to_enum(): void
    {
        $attachment = MediaAttachment::factory()->create(['media_type' => 'scoreboard_screenshot']);

        $this->assertInstanceOf(MediaType::class, $attachment->media_type);
        $this->assertEquals(MediaType::ScoreboardScreenshot, $attachment->media_type);
    }

    public function test_media_attachment_casts_processing_status_to_enum(): void
    {
        $attachment = MediaAttachment::factory()->create(['processing_status' => 'completed']);

        $this->assertInstanceOf(ProcessingStatus::class, $attachment->processing_status);
        $this->assertEquals(ProcessingStatus::Completed, $attachment->processing_status);
    }

    public function test_media_attachment_morphs_to_game(): void
    {
        $game = Game::factory()->create();
        $attachment = MediaAttachment::factory()->create([
            'user_id' => $game->user_id,
            'attachable_id' => $game->id,
            'attachable_type' => Game::class,
        ]);

        $this->assertInstanceOf(Game::class, $attachment->attachable);
        $this->assertEquals($game->id, $attachment->attachable->id);
    }

    public function test_media_attachment_morphs_to_career_stat_snapshot(): void
    {
        $snapshot = CareerStatSnapshot::factory()->create();
        $attachment = MediaAttachment::factory()->create([
            'user_id' => $snapshot->user_id,
            'attachable_id' => $snapshot->id,
            'attachable_type' => CareerStatSnapshot::class,
        ]);

        $this->assertInstanceOf(CareerStatSnapshot::class, $attachment->attachable);
        $this->assertEquals($snapshot->id, $attachment->attachable->id);
    }

    public function test_media_attachment_attachable_is_nullable(): void
    {
        $attachment = MediaAttachment::factory()->create([
            'attachable_id' => null,
            'attachable_type' => null,
        ]);

        $this->assertNull($attachment->attachable_id);
        $this->assertNull($attachment->attachable_type);
        $this->assertNull($attachment->attachable);
    }

    public function test_media_attachment_cascades_on_user_delete(): void
    {
        $attachment = MediaAttachment::factory()->create();
        $userId = $attachment->user_id;

        User::find($userId)->delete();

        $this->assertDatabaseMissing('media_attachments', ['id' => $attachment->id]);
    }

    public function test_media_attachment_ocr_data_is_json(): void
    {
        $ocrData = ['raw_text' => 'Player1 - 25 elims', 'confidence' => 0.95];
        $attachment = MediaAttachment::factory()->create(['ocr_data' => $ocrData]);

        $attachment->refresh();
        $this->assertEquals($ocrData, $attachment->ocr_data);
        $this->assertIsArray($attachment->ocr_data);
    }

    public function test_media_attachment_parsed_data_is_json(): void
    {
        $parsedData = ['players' => [['name' => 'Player1', 'eliminations' => 25]]];
        $attachment = MediaAttachment::factory()->create(['parsed_data' => $parsedData]);

        $attachment->refresh();
        $this->assertEquals($parsedData, $attachment->parsed_data);
        $this->assertIsArray($attachment->parsed_data);
    }

    public function test_media_attachment_processing_status_defaults_to_pending(): void
    {
        $user = User::factory()->create();
        $attachment = MediaAttachment::create([
            'user_id' => $user->id,
            'media_type' => 'general_screenshot',
            'file_path' => 'screenshots/test.png',
        ]);

        $this->assertEquals(ProcessingStatus::Pending, $attachment->processing_status);
    }

    public function test_media_attachment_metadata_is_json(): void
    {
        $metadata = ['width' => 1920, 'height' => 1080, 'format' => 'png'];
        $attachment = MediaAttachment::factory()->create(['metadata' => $metadata]);

        $attachment->refresh();
        $this->assertEquals($metadata, $attachment->metadata);
        $this->assertIsArray($attachment->metadata);
    }

    public function test_media_attachment_processed_at_is_datetime(): void
    {
        $attachment = MediaAttachment::factory()->create(['processed_at' => '2026-02-11 12:00:00']);

        $this->assertInstanceOf(Carbon::class, $attachment->processed_at);
    }
}
