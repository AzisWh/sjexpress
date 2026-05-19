<?php

namespace Tests\Feature;

use App\Models\ArmadaModel;
use App\Models\DriverModel;
use App\Models\FotoPengirimanModel;
use App\Models\InvoiceDetailModel;
use App\Models\InvoiceModel;
use App\Models\PengirimanModel;
use App\Models\PtModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
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

    private function createPengirimanWithFoto(): PengirimanModel
    {
        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        FotoPengirimanModel::factory()->create([
            'pengiriman_id' => $pengiriman->id,
        ]);

        return $pengiriman;
    }

    public function test_can_generate_invoice_pdf(): void
    {
        $p1 = $this->createPengirimanWithFoto();
        $p2 = $this->createPengirimanWithFoto();

        $response = $this->actingAs($this->user)->post('/admin-invoice/generate-pdf', [
            'pengiriman_ids' => [$p1->id, $p2->id],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        $this->assertDatabaseHas('invoice_table', [
            'pt_id' => $this->pt->id,
            'generated_by' => $this->user->id,
        ]);

        $this->assertEquals(2, InvoiceDetailModel::count());
    }

    public function test_generate_invoice_rejects_pengiriman_without_foto(): void
    {
        $pengiriman = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);

        $response = $this->actingAs($this->user)->post('/admin-invoice/generate-pdf', [
            'pengiriman_ids' => [$pengiriman->id],
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_generate_invoice_rejects_mixed_pt(): void
    {
        $pt2 = PtModel::factory()->create();

        $p1 = $this->createPengirimanWithFoto();

        $pengiriman2 = PengirimanModel::factory()->create([
            'pt_id' => $pt2->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
        ]);
        FotoPengirimanModel::factory()->create(['pengiriman_id' => $pengiriman2->id]);

        $response = $this->actingAs($this->user)->post('/admin-invoice/generate-pdf', [
            'pengiriman_ids' => [$p1->id, $pengiriman2->id],
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_generate_invoice_validates_pengiriman_ids_required(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/admin-invoice/generate-pdf', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pengiriman_ids']);
    }

    public function test_generate_invoice_validates_pengiriman_ids_exist(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/admin-invoice/generate-pdf', [
                'pengiriman_ids' => [9999],
            ]);

        $response->assertStatus(422);
    }

    public function test_invoice_number_format_is_correct(): void
    {
        $pengiriman = $this->createPengirimanWithFoto();

        $this->actingAs($this->user)->post('/admin-invoice/generate-pdf', [
            'pengiriman_ids' => [$pengiriman->id],
        ]);

        $invoice = InvoiceModel::first();

        $this->assertMatchesRegularExpression(
            '/^\d{3}\/INV\/7084\/(I|II|III|IV|V|VI|VII|VIII|IX|X|XI|XII)\/\d{4}$/',
            $invoice->nomor_invoice
        );
    }

    public function test_invoice_verification_page_works(): void
    {
        $invoice = InvoiceModel::factory()->create([
            'pt_id' => $this->pt->id,
            'verification_token' => 'test-token-123',
        ]);

        $response = $this->get('/invoice/verify/test-token-123');

        $response->assertStatus(200);
        $response->assertViewIs('admin.invoice.verify');
    }

    public function test_invoice_verification_404_for_invalid_token(): void
    {
        $response = $this->get('/invoice/verify/nonexistent-token');

        $response->assertStatus(404);
    }

    public function test_invoice_public_pdf_download_works(): void
    {
        $pengiriman = $this->createPengirimanWithFoto();

        $this->actingAs($this->user)->post('/admin-invoice/generate-pdf', [
            'pengiriman_ids' => [$pengiriman->id],
        ]);

        $invoice = InvoiceModel::first();

        $response = $this->get("/invoice/file/{$invoice->verification_token}");

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_invoice_public_pdf_404_for_invalid_token(): void
    {
        $response = $this->get('/invoice/file/nonexistent-token');

        $response->assertStatus(404);
    }

    public function test_nominal_invoice_is_sum_of_harga_pabrik(): void
    {
        $p1 = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'harga_pabrik' => 1000000,
        ]);
        FotoPengirimanModel::factory()->create(['pengiriman_id' => $p1->id]);

        $p2 = PengirimanModel::factory()->create([
            'pt_id' => $this->pt->id,
            'armada_id' => $this->armada->id,
            'driver_id' => $this->driver->id,
            'harga_pabrik' => 2000000,
        ]);
        FotoPengirimanModel::factory()->create(['pengiriman_id' => $p2->id]);

        $this->actingAs($this->user)->post('/admin-invoice/generate-pdf', [
            'pengiriman_ids' => [$p1->id, $p2->id],
        ]);

        $invoice = InvoiceModel::first();
        $this->assertEquals(3000000, $invoice->nominal_invoice);
    }

    public function test_invoice_has_verification_token(): void
    {
        $pengiriman = $this->createPengirimanWithFoto();

        $this->actingAs($this->user)->post('/admin-invoice/generate-pdf', [
            'pengiriman_ids' => [$pengiriman->id],
        ]);

        $invoice = InvoiceModel::first();
        $this->assertNotNull($invoice->verification_token);
    }

    public function test_regenerating_invoice_updates_details(): void
    {
        $p1 = $this->createPengirimanWithFoto();
        $p2 = $this->createPengirimanWithFoto();

        $this->actingAs($this->user)->post('/admin-invoice/generate-pdf', [
            'pengiriman_ids' => [$p1->id, $p2->id],
        ]);

        $this->assertEquals(1, InvoiceModel::count());
        $this->assertEquals(2, InvoiceDetailModel::where('pengiriman_id', $p1->id)
            ->orWhere('pengiriman_id', $p2->id)->count());
    }

    public function test_invoice_verification_is_public_no_auth_needed(): void
    {
        $invoice = InvoiceModel::factory()->create([
            'pt_id' => $this->pt->id,
            'verification_token' => 'public-token',
        ]);

        $response = $this->get('/invoice/verify/public-token');

        $response->assertStatus(200);
    }
}
