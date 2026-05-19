<?php

namespace Tests\Feature;

use App\Models\PtModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_pt_index_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get('/admin-pt');

        $response->assertStatus(200);
        $response->assertViewIs('admin.pt.index');
    }

    public function test_can_create_pt(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-pt/store', [
            'name' => 'PT Test Jaya',
            'pic' => 'John Doe',
            'no_pic' => '081234567890',
            'alamat' => 'Jl. Test No. 1',
            'penagihan' => 'Jane Doe',
            'no_penagihan' => '089876543210',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pt_table', [
            'name' => 'PT Test Jaya',
            'pic' => 'John Doe',
        ]);
    }

    public function test_pt_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/admin-pt/store', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name', 'pic', 'no_pic', 'alamat', 'penagihan', 'no_penagihan',
        ]);
    }

    public function test_can_update_pt(): void
    {
        $pt = PtModel::factory()->create();

        $response = $this->actingAs($this->user)
            ->patch("/admin-pt/update/{$pt->id}", [
                'name' => 'PT Updated Name',
                'pic' => 'Updated PIC',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pt_table', [
            'id' => $pt->id,
            'name' => 'PT Updated Name',
        ]);
    }

    public function test_update_nonexistent_pt_returns_error(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/admin-pt/update/9999', [
                'name' => 'Test',
            ]);

        $response->assertStatus(500);
    }

    public function test_can_delete_pt(): void
    {
        $pt = PtModel::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete("/admin-pt/delete/{$pt->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('pt_table', ['id' => $pt->id]);
    }

    public function test_delete_nonexistent_pt_returns_error(): void
    {
        $response = $this->actingAs($this->user)
            ->delete('/admin-pt/delete/9999');

        $response->assertStatus(500);
    }
}
