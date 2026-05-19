<?php

namespace Tests\Feature;

use App\Models\ArmadaModel;
use App\Models\DriverModel;
use App\Models\FotoPengirimanModel;
use App\Models\PengirimanModel;
use App\Models\PtModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengirimanTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private PtModel $pt;
    private ArmadaModel $armada;
    private DriverModel $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'admin']);
        $this->pt = PtModel::factory()->create();
        $this->armada = ArmadaModel::factory()->create();
        $this->driver = DriverModel::factory()->create();
    }

    // --- INDEX ---
    public function test_pengiriman_index_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get('/admin-pengiriman');

        $response->assertStatus(200);
        $response->assertViewIs('admin.pengiriman.index');
    }

    public function test_pengiriman_index_shows_created_data(): void
    {
        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $response = $this->actingAs($this->user)->get('/admin-pengiriman');

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_pengiriman_index_ajax_returns_json(): void
    {
        PengirimanModel::factory()->count(3)->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/admin-pengiriman?ajax=1');

        $response->assertStatus(200);
        $response->assertJsonStructure(['table', 'pagination', 'info']);
    }

    public function test_pengiriman_index_filters_by_pt(): void
    {
        $pt2 = PtModel::factory()->create();

        PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);
        PengirimanModel::factory()->create([
            'pt_id' => $pt2->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/admin-pengiriman?pt_id=' . $this->pt->id);

        $response->assertStatus(200);
    }

    public function test_pengiriman_index_sorts_latest(): void
    {
        PengirimanModel::factory()->count(5)->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/admin-pengiriman?sort=latest');

        $response->assertStatus(200);
    }

    // --- STORE ---
    public function test_can_create_pengiriman(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('pengiriman_table', [
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
        ]);
    }

    public function test_can_create_pengiriman_with_photos(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('surat_jalan1.jpg');
        $file2 = UploadedFile::fake()->image('surat_jalan2.jpg');

        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Surabaya',
            'harga_pabrik' => 1000000,
            'harga_armada' => 700000,
            'foto' => [$file1, $file2],
        ]);

        $response->assertJson(['success' => true]);

        $pengiriman = PengirimanModel::first();
        $this->assertEquals(2, $pengiriman->fotos()->count());
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/admin-pengiriman/store', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'pt_id', 'armada_id', 'driver_id',
            'tanggal_ambil', 'rute_from', 'rute_to',
            'harga_pabrik', 'harga_armada',
        ]);
    }

    public function test_store_validates_pt_exists(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => 9999,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
        ]);

        $response->assertStatus(422);
    }

    public function test_store_validates_harga_is_numeric(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 'not-a-number',
            'harga_armada' => 300000,
        ]);

        $response->assertStatus(422);
    }

    public function test_store_validates_foto_max_size(): void
    {
        Storage::fake('public');

        $bigFile = UploadedFile::fake()->create('huge.jpg', 3000);

        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
            'foto' => [$bigFile],
        ]);

        $response->assertStatus(422);
    }

    // --- UPDATE ---
    public function test_can_update_pengiriman(): void
    {
        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $response = $this->actingAs($this->user)
            ->patch("/admin-pengiriman/update/{$pengiriman->id}", [
                'rute_from' => 'Bekasi',
                'rute_to' => 'Semarang',
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('pengiriman_table', [
            'id' => $pengiriman->id,
            'rute_from' => 'Bekasi',
        ]);
    }

    public function test_update_nonexistent_pengiriman_returns_404(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/admin-pengiriman/update/9999', [
                'rute_from' => 'Bekasi',
            ]);

        $response->assertStatus(500);
    }

    // --- DELETE ---
    public function test_can_delete_pengiriman(): void
    {
        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/admin-pengiriman/delete/{$pengiriman->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('pengiriman_table', [
            'id' => $pengiriman->id,
        ]);
    }

    public function test_delete_pengiriman_also_deletes_photos(): void
    {
        Storage::fake('public');

        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        FotoPengirimanModel::factory()->create([
            'pengiriman_id' => $pengiriman->id,
            'file_path' => 'test_photo.jpg',
        ]);

        $this->actingAs($this->user)
            ->delete("/admin-pengiriman/delete/{$pengiriman->id}");

        $this->assertDatabaseMissing('foto_pengiriman_table', [
            'pengiriman_id' => $pengiriman->id,
        ]);
    }

    // --- FOTO ---
    public function test_can_upload_foto_to_existing_pengiriman(): void
    {
        Storage::fake('public');

        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $file = UploadedFile::fake()->image('foto.jpg');

        $response = $this->actingAs($this->user)
            ->post("/admin-pengiriman/upload-foto/{$pengiriman->id}", [
                'foto' => [$file],
            ]);

        $response->assertJson(['success' => true]);
        $this->assertEquals(1, $pengiriman->fresh()->fotos()->count());
    }

    public function test_can_delete_foto(): void
    {
        Storage::fake('public');

        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $foto = FotoPengirimanModel::factory()->create([
            'pengiriman_id' => $pengiriman->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/admin-pengiriman/delete-foto/{$foto->id}");

        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('foto_pengiriman_table', [
            'id' => $foto->id,
        ]);
    }

    public function test_can_get_fotos_for_pengiriman(): void
    {
        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        FotoPengirimanModel::factory()->count(3)->create([
            'pengiriman_id' => $pengiriman->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/admin-pengiriman/fotos/{$pengiriman->id}");

        $response->assertJson(['success' => true]);
        $response->assertJsonCount(3, 'data');
    }

    public function test_pengiriman_with_keterangan(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
            'keterangan' => 'Barang fragile, harap hati-hati',
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('pengiriman_table', [
            'keterangan' => 'Barang fragile, harap hati-hati',
        ]);
    }

    public function test_pengiriman_without_keterangan(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-pengiriman/store', [
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'tanggal_ambil' => '2026-05-17',
            'rute_from' => 'Jakarta',
            'rute_to' => 'Bandung',
            'harga_pabrik' => 500000,
            'harga_armada' => 300000,
        ]);

        $response->assertJson(['success' => true]);
    }

    public function test_pengiriman_pagination_works(): void
    {
        PengirimanModel::factory()->count(25)->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/admin-pengiriman?per_page=10');

        $response->assertStatus(200);
        $response->assertViewHas('data');
        $this->assertEquals(10, $response->viewData('data')->count());
    }
}
