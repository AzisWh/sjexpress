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

class InvoiceController extends Controller
{
    // public function generateInvoicePdf(Request $request)
    // {
    //     $request->validate([
    //         'pengiriman_ids' => 'required|array|min:1',
    //         'signature_id' => 'required|exists:signature_table,id',
    //         'pengiriman_ids.*' => 'required|exists:pengiriman_table,id',
    //     ]);

    //     try {
    //         $pengirimanIds = $request->pengiriman_ids;

    //         $pengirimanList = PengirimanModel::with(['pt', 'armada', 'driver', 'fotos'])
    //             ->whereIn('id', $pengirimanIds)
    //             ->get();

    //         $tanpaFoto = $pengirimanList->filter(fn ($p) => $p->fotos->count() === 0);
    //         if ($tanpaFoto->isNotEmpty()) {
    //             $listNo = $tanpaFoto->pluck('id')->implode(', ');

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => "Pengiriman ID {$listNo} belum upload surat jalan.",
    //             ], 422);
    //         }

    //         $ptIds = $pengirimanList->pluck('pt_id')->unique();
    //         if ($ptIds->count() > 1) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Semua pengiriman harus dari PT yang sama untuk 1 invoice.',
    //             ], 422);
    //         }

    //         $existingDetails = InvoiceDetailModel::whereIn('pengiriman_id', $pengirimanIds)->get();
    //         $existingInvoiceIds = $existingDetails->pluck('invoice_id')->unique();

    //         // if ($existingInvoiceIds->count() === 1) {
    //         //     // Semua pengiriman sudah ada di 1 invoice yang sama -> re-download PDF
    //         //     $invoice = InvoiceModel::with(['pt', 'details.pengiriman.armada', 'details.pengiriman.driver', 'details.pengiriman.fotos'])
    //         //         ->findOrFail($existingInvoiceIds->first());

    //         //     return $this->generatePdf($invoice, $pengirimanList);
    //         // }

    //         if ($existingInvoiceIds->count() === 1) {

    //             $invoice = InvoiceModel::with([
    //                 'pt',
    //                 'details.pengiriman.armada',
    //                 'details.pengiriman.driver',
    //                 'details.pengiriman.fotos',
    //             ])->findOrFail($existingInvoiceIds->first());

    //             $invoice->update([
    //                 'signature_id' => $request->signature_id,
    //             ]);

    //             $invoice->load('signature');

    //             return $this->generatePdf($invoice, $pengirimanList);
    //         }

    //         if ($existingInvoiceIds->count() > 1) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Pengiriman yang dipilih sudah tercatat di invoice yang berbeda-beda. Pilih pengiriman dari invoice yang sama saja.',
    //             ], 422);
    //         }

    //         // Sebagian sudah ada di invoice, sebagian belum -> tidak boleh campur
    //         if ($existingDetails->count() > 0 && $existingDetails->count() < count($pengirimanIds)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Sebagian pengiriman sudah ada di invoice lain. Pilih semua pengiriman dari invoice yang sama, atau pilih yang belum memiliki invoice.',
    //             ], 422);
    //         }

    //         // Semua belum punya invoice -> buat baru
    //         DB::beginTransaction();

    //         $ptId = $ptIds->first();
    //         $pt = $pengirimanList->first()->pt;
    //         $nominalInvoice = $pengirimanList->sum('harga_pabrik');
    //         $now = Carbon::now();

    //         // Generate nomor invoice: INV/001/XII/2025
    //         $bulanRomawi = bulanRomawi($now->month);
    //         $tahun = $now->year;

    //         $lastInvoice = InvoiceModel::whereYear('created_at', $tahun)
    //             ->whereMonth('created_at', $now->month)
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         $urut = 1;
    //         if ($lastInvoice) {
    //             $parts = explode('/', $lastInvoice->nomor_invoice);
    //             if (isset($parts[1]) && is_numeric($parts[1])) {
    //                 $urut = (int) $parts[1] + 1;
    //             } else {
    //                 $lastCount = InvoiceModel::whereYear('created_at', $tahun)
    //                     ->whereMonth('created_at', $now->month)
    //                     ->count();
    //                 $urut = $lastCount + 1;
    //             }
    //         }

    //         $nomorInvoice = 'INV/'.str_pad($urut, 3, '0', STR_PAD_LEFT).'/'.$bulanRomawi.'/'.$tahun;

    //         $invoice = InvoiceModel::create([
    //             'nomor_invoice' => $nomorInvoice,
    //             'tanggal_invoice' => $now->toDateString(),
    //             'pt_id' => $ptId,
    //             'nominal_invoice' => $nominalInvoice,
    //             'signature_id' => $request->signature_id,
    //         ]);

    //         foreach ($pengirimanIds as $pid) {
    //             InvoiceDetailModel::create([
    //                 'invoice_id' => $invoice->id,
    //                 'pengiriman_id' => $pid,
    //             ]);
    //         }

    //         DB::commit();

    //         $invoice->load(['pt', 'details.pengiriman.armada', 'details.pengiriman.driver', 'details.pengiriman.fotos']);

    //         return $this->generatePdf($invoice, $pengirimanList);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal generate invoice: '.$e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function generateInvoicePdf(Request $request)
    {
        $request->validate([
            'pengiriman_ids' => 'required|array|min:1',
            'signature_id' => 'required|exists:signature_table,id',
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

            /*
            |--------------------------------------------------------------------------
            | HAPUS RELASI LAMA
            |--------------------------------------------------------------------------
            | Jadi pengiriman bisa regroup invoice kapan aja
            |
            */

            InvoiceDetailModel::whereIn('pengiriman_id', $pengirimanIds)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | HAPUS INVOICE KOSONG
            |--------------------------------------------------------------------------
            */

            $emptyInvoices = InvoiceModel::doesntHave('details')->get();

            foreach ($emptyInvoices as $emptyInvoice) {
                $emptyInvoice->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE INVOICE BARU
            |--------------------------------------------------------------------------
            */

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
                'signature_id' => $request->signature_id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | INSERT DETAIL
            |--------------------------------------------------------------------------
            */

            foreach ($pengirimanIds as $pid) {

                InvoiceDetailModel::create([
                    'invoice_id' => $invoice->id,
                    'pengiriman_id' => $pid,
                ]);
            }

            DB::commit();

            $invoice->load([
                'pt',
                'signature',
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

    private function generatePdf(InvoiceModel $invoice, $pengirimanList)
    {
        $nominalInvoice = $pengirimanList->sum('harga_pabrik');
        $invoice->load('signature');

        $signature = $invoice->signature;

        $data = [
            'invoice' => $invoice,
            'pengirimanList' => $pengirimanList,
            'pt' => $invoice->pt,
            'total' => $nominalInvoice,
            'terbilang' => formatTerbilangRupiah($nominalInvoice),
            'signature' => $signature,
        ];

        $pdf = Pdf::loadView('admin.invoice.pdf', $data)
            ->setPaper('a4', 'portrait');

        $filename = str_replace('/', '-', $invoice->nomor_invoice).'.pdf';

        return $pdf->stream($filename);
    }
}
