<?php

namespace Tests\Feature;

use App\Models\ArmadaModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArmadaTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_armada_index_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get('/admin-armada');

        $response->assertStatus(200);
        $response->assertViewIs('admin.armada.index');
    }

    public function test_can_create_armada_without_photo(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-armada/store', [
            'nama_armada' => 'Tronton',
            'plat_nomor' => 'B 1234 ABC',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('armada_table', [
            'nama_armada' => 'Tronton',
            'plat_nomor' => 'B 1234 ABC',
        ]);
    }

    public function test_can_create_armada_with_photo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('armada.jpg');

        $response = $this->actingAs($this->user)->post('/admin-armada/store', [
            'nama_armada' => 'Fuso',
            'plat_nomor' => 'B 5678 XYZ',
            'foto_armada' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('armada_table', [
            'nama_armada' => 'Fuso',
        ]);
    }

    public function test_armada_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/admin-armada/store', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nama_armada', 'plat_nomor']);
    }

    public function test_can_update_armada(): void
    {
        $armada = ArmadaModel::factory()->create();

        $response = $this->actingAs($this->user)
            ->patch("/admin-armada/update/{$armada->id}", [
                'nama_armada' => 'Updated Armada',
                'plat_nomor' => 'D 9999 AAA',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('armada_table', [
            'id' => $armada->id,
            'nama_armada' => 'Updated Armada',
        ]);
    }

    public function test_can_update_armada_with_new_photo(): void
    {
        Storage::fake('public');

        $armada = ArmadaModel::factory()->create(['foto_armada' => 'old_photo.jpg']);

        Storage::disk('public')->put('FotoArmada/old_photo.jpg', 'old content');

        $newFile = UploadedFile::fake()->image('new_armada.jpg');

        $response = $this->actingAs($this->user)
            ->patch("/admin-armada/update/{$armada->id}", [
                'foto_armada' => $newFile,
            ]);

        $response->assertRedirect();
        Storage::disk('public')->assertMissing('FotoArmada/old_photo.jpg');
    }

    public function test_can_delete_armada(): void
    {
        Storage::fake('public');

        $armada = ArmadaModel::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete("/admin-armada/delete/{$armada->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('armada_table', ['id' => $armada->id]);
    }

    public function test_delete_armada_removes_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('FotoArmada/test_photo.jpg', 'content');

        $armada = ArmadaModel::factory()->create(['foto_armada' => 'test_photo.jpg']);

        $this->actingAs($this->user)
            ->delete("/admin-armada/delete/{$armada->id}");

        Storage::disk('public')->assertMissing('FotoArmada/test_photo.jpg');
    }

    public function test_armada_pagination_works(): void
    {
        ArmadaModel::factory()->count(25)->create();

        $response = $this->actingAs($this->user)
            ->get('/admin-armada?per_page=10');

        $response->assertStatus(200);
    }
}
