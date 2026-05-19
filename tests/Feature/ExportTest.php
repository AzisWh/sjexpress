<?php

namespace Tests\Feature;

use App\Models\ArmadaModel;
use App\Models\DriverModel;
use App\Models\PengirimanModel;
use App\Models\PtModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_export_pengiriman_excel(): void
    {
        $pt = PtModel::factory()->create();
        $armada = ArmadaModel::factory()->create();
        $driver = DriverModel::factory()->create();

        $p1 = PengirimanModel::factory()->create([
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
        ]);
        $p2 = PengirimanModel::factory()->create([
            'pt_id' => $pt->id,
            'armada_id' => $armada->id,
            'driver_id' => $driver->id,
        ]);

        $response = $this->actingAs($this->user)->post('/admin-export/pengiriman-excel', [
            'pengiriman_ids' => [$p1->id, $p2->id],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_export_without_ids_redirects_back(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-export/pengiriman-excel', [
            'pengiriman_ids' => [],
        ]);

        $response->assertRedirect();
    }

    public function test_export_with_no_ids_redirects_back(): void
    {
        $response = $this->actingAs($this->user)->post('/admin-export/pengiriman-excel');

        $response->assertRedirect();
    }
}
