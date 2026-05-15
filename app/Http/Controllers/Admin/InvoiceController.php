<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetailModel;
use App\Models\InvoiceModel;
use App\Models\PengirimanModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function generateInvoicePdf(Request $request)
    {
        $request->validate([
            'pengiriman_ids' => 'required|array|min:1',
            'pengiriman_ids.*' => 'required|exists:pengiriman_table,id',
        ]);

        DB::beginTransaction();

        try {

            $pengirimanIds = $request->pengiriman_ids;

            $pengirimanList = PengirimanModel::with([
                'pt',
                'armada',
                'driver',
                'fotos',
            ])
                ->whereIn('id', $pengirimanIds)
                ->get();

            // VALIDASI FOTO
            $tanpaFoto = $pengirimanList->filter(fn ($p) => $p->fotos->count() === 0);

            if ($tanpaFoto->isNotEmpty()) {

                $listNo = $tanpaFoto->pluck('id')->implode(', ');

                return response()->json([
                    'success' => false,
                    'message' => "Pengiriman ID {$listNo} belum upload surat jalan.",
                ], 422);
            }

            // VALIDASI PT
            $ptIds = $pengirimanList->pluck('pt_id')->unique();

            if ($ptIds->count() > 1) {

                return response()->json([
                    'success' => false,
                    'message' => 'Semua pengiriman harus dari PT yang sama untuk 1 invoice.',
                ], 422);
            }

            InvoiceDetailModel::whereIn('pengiriman_id', $pengirimanIds)
                ->delete();

            $emptyInvoices = InvoiceModel::doesntHave('details')->get();

            foreach ($emptyInvoices as $emptyInvoice) {
                $emptyInvoice->delete();
            }

            $ptId = $ptIds->first();

            $nominalInvoice = $pengirimanList->sum('harga_pabrik');

            $now = Carbon::now();

            $bulanRomawi = bulanRomawi($now->month);

            $tahun = $now->year;

            $lastInvoice = InvoiceModel::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $now->month)
                ->orderBy('id', 'desc')
                ->first();

            $urut = 1;

            if ($lastInvoice) {

                $parts = explode('/', $lastInvoice->nomor_invoice);

                if (isset($parts[1]) && is_numeric($parts[1])) {

                    $urut = (int) $parts[1] + 1;

                } else {

                    $lastCount = InvoiceModel::whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $now->month)
                        ->count();

                    $urut = $lastCount + 1;
                }
            }

            $nomorInvoice = 'INV/'.
                str_pad($urut, 3, '0', STR_PAD_LEFT).
                '/'.
                $bulanRomawi.
                '/'.
                $tahun;

            $invoice = InvoiceModel::create([
                'nomor_invoice' => $nomorInvoice,
                'tanggal_invoice' => $now->toDateString(),
                'pt_id' => $ptId,
                'nominal_invoice' => $nominalInvoice,
                'generated_by' => auth()->id(),
                'verification_token' => Str::uuid(),
            ]);

            foreach ($pengirimanIds as $pid) {

                InvoiceDetailModel::create([
                    'invoice_id' => $invoice->id,
                    'pengiriman_id' => $pid,
                ]);
            }

            DB::commit();

            $invoice->load([
                'pt',
                'generator',
                'details.pengiriman.armada',
                'details.pengiriman.driver',
                'details.pengiriman.fotos',
            ]);

            return $this->generatePdf($invoice, $pengirimanList);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal generate invoice: '.$e->getMessage(),
            ], 500);
        }
    }

    public function verifyInvoice($token)
    {
        $invoice = InvoiceModel::with([
            'pt',
            'generator',
        ])->where('verification_token', $token)->firstOrFail();

        return view('admin.invoice.verify', compact('invoice'));
    }

    public function publicPdf($token)
    {
        $invoice = InvoiceModel::with([
            'pt',
            'details.pengiriman.armada',
            'details.pengiriman.driver',
            'details.pengiriman.fotos',
            'generator',
        ])->where('verification_token', $token)->firstOrFail();

        $pengirimanList = $invoice->details->pluck('pengiriman');

        return $this->generatePdf($invoice, $pengirimanList);
    }

    private function generatePdf(InvoiceModel $invoice, $pengirimanList)
    {
        $nominalInvoice = $pengirimanList->sum('harga_pabrik');

        $data = [
            'invoice' => $invoice,
            'pengirimanList' => $pengirimanList,
            'pt' => $invoice->pt,
            'total' => $nominalInvoice,
            'terbilang' => formatTerbilangRupiah($nominalInvoice),
        ];

        $pdf = Pdf::loadView('admin.invoice.pdf', $data)
            ->setPaper('a4', 'portrait');

        $filename = str_replace('/', '-', $invoice->nomor_invoice).'.pdf';

        return $pdf->stream($filename);
    }
}
