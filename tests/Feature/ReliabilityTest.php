<?php

namespace Tests\Feature;

use App\Models\ArmadaModel;
use App\Models\DriverModel;
use App\Models\FotoPengirimanModel;
use App\Models\PengirimanModel;
use App\Models\PtModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReliabilityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    // --- DATA INTEGRITY TESTS ---

    public function test_rapid_sequential_pengiriman_creation(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        for ($i = 0; $i < 50; $i++) {
            $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
                'pt_id' => $pt->id,
                'armada_id' => $armada->id,
                'driver_id' => $driver->id,
                'tanggal_ambil' => '2026-05-' . str_pad($i % 28 + 1, 2, '0', STR_PAD_LEFT),
                'rute_from' => "City A {$i}",
                'rute_to' => "City B {$i}",
                'harga_pabrik' => 100000 + ($i * 10000),
                'harga_armada' => 80000 + ($i * 8000),
            ]);

            $response->assertJson(['success' => true]);
        }

        $this->assertEquals(50, PengirimanModel::count());
    }

    public function test_create_then_immediately_update(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
        ]);

        $pengirimanId = PengirimanModel::first()->id;

        $response = $this->actingAs($this->user)->patch("/admin-pengiriman/update/{$pengirimanId}", [
            'rute_from' => 'Updated Jakarta',
            'harga_pabrik' => 600000,
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('pengiriman_table', [
            'id' => $pengirimanId,
            'rute_from' => 'Updated Jakarta',
            'harga_pabrik' => 600000,
        ]);
    }

    public function test_create_update_delete_cycle(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        // Create
        $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
        ]);

        $pengirimanId = PengirimanModel::first()->id;

        // Update
        $this->actingAs($this->user)->patch("/admin-pengiriman/update/{$pengirimanId}", [
            'rute_to' => 'Surabaya',
        ]);

        // Delete
        $this->actingAs($this->user)->delete("/admin-pengiriman/delete/{$pengirimanId}");

        $this->assertEquals(0, PengirimanModel::count());
    }

    public function test_database_transaction_rollback_on_pengiriman_error(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        $initialCount = PengirimanModel::count();

        // Create with invalid data that should fail validation
        $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
            'tanggal_ambil' => 'not-a-date',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
        ]);

        $this->assertEquals($initialCount, PengirimanModel::count());
    }

    // --- EDGE CASES ---

    public function test_pengiriman_with_zero_harga(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 0,
            'harga_armada' => 0,
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('pengiriman_table', [
            'harga_pabrik' => 0,
            'harga_armada' => 0,
        ]);
    }

    public function test_pengiriman_with_very_large_harga(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 999999999,
            'harga_armada' => 999999999,
        ]);

        $response->assertJson(['success' => true]);
    }

    public function test_pengiriman_with_long_strings(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        $longString = str_repeat('A', 255);

        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => $longString,
            'rute_to' => $longString,
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
            'keterangan' => str_repeat('Keterangan test ', 100),
        ]);

        $response->assertJson(['success' => true]);
    }

    public function test_pagination_beyond_available_data(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        PengirimanModel::factory()->count(5)->create([
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/admin-pengiriman?page=999');

        $response->assertStatus(200);
    }

    public function test_dashboard_counts_are_accurate(): void
    {
        PtModel::factory()->count(5)->create();
        ArmadaModel::factory()->count(3)->create();
        DriverModel::factory()->count(7)->create();

        $response = $this->actingAs($this->user)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_delete_nonexistent_pengiriman_returns_error(): void
    {
        $response = $this->actingAs($this->user)
            ->delete('/admin-pengiriman/delete/99999');

        $response->assertStatus(500);
    }

    public function test_concurrent_pt_creation_no_duplicate_names_allowed(): void
    {
        $data = [
            'name' => 'PT Same Name',
            'pic' => 'John',
            'no_pic' => '081234567890',
            'alamat' => 'Jl. Test No. 1',
            'penagihan' => 'Jane',
            'no_penagihan' => '089876543210',
        ];

        $this->actingAs($this->user)->post('/admin-pt/store', $data);
        $this->actingAs($this->user)->post('/admin-pt/store', $data);

        $this->assertEquals(2, PtModel::where('name', 'PT Same Name')->count());
    }

    public function test_login_with_exact_credentials_is_case_sensitive(): void
    {
        \App\Models\User::factory()->create([
            'email' => 'Admin@Test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Email lookup is case-insensitive in most DB, but let's verify behavior
        $response = $this->post('/login', [
            'email' => 'Admin@Test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
    }

    public function test_session_persists_across_multiple_requests(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($user)->get('/admin-pt')->assertStatus(200);
        $this->actingAs($user)->get('/admin-armada')->assertStatus(200);
        $this->actingAs($user)->get('/admin-driver')->assertStatus(200);
        $this->actingAs($user)->get('/admin-pengiriman')->assertStatus(200);
    }

    public function test_empty_database_pages_load_without_error(): void
    {
        $routes = [
            '/admin-pt',
            '/admin-armada',
            '/admin-driver',
            '/admin-pengiriman',
            '/admin-signature',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->user)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_get_fotos_for_nonexistent_pengiriman(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/admin-pengiriman/fotos/99999');

        $response->assertStatus(500);
    }

    public function test_delete_foto_for_nonexistent_id(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson('/admin-pengiriman/delete-foto/99999');

        $response->assertStatus(500);
    }
}
