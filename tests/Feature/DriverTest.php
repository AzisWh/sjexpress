<?php

namespace Tests\Feature;

use App\Models\DriverModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_driver_index_loads(): void
    {
        DriverModel::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/admin-driver');

        $response->assertStatus(200);
        $response->assertViewIs('admin.driver.index');
    }

    public function test_can_create_driver(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-driver/store', [
            'name' => 'Driver Test',
            'no_telp' => '081234567890',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('driver_table', [
            'name' => 'Driver Test',
            'no_telp' => '081234567890',
        ]);
    }

    public function test_driver_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/admin-driver/store', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'no_telp']);
    }

    public function test_can_update_driver(): void
    {
        $driver = DriverModel::factory()->create();

        $response = $this->actingAs($this->user)
            ->patch("/admin-driver/update/{$driver->id}", [
                'name' => 'Updated Driver',
                'no_telp' => '089999999999',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('driver_table', [
            'id' => $driver->id,
            'name' => 'Updated Driver',
        ]);
    }

    public function test_update_nonexistent_driver_returns_error(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/admin-driver/update/9999', [
                'name' => 'Test',
                'no_telp' => '081234567890',
            ]);

        $response->assertStatus(500);
    }

    public function test_can_delete_driver(): void
    {
        $driver = DriverModel::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete("/admin-driver/delete/{$driver->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('driver_table', ['id' => $driver->id]);
    }

    public function test_delete_nonexistent_driver_returns_error(): void
    {
        $response = $this->actingAs($this->user)
            ->delete('/admin-driver/delete/9999');

        $response->assertStatus(500);
    }
}
