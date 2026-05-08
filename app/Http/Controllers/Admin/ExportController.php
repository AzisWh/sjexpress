<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PengirimanExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportPengirimanExcel(Request $request)
    {
        $ids = $request->pengiriman_ids;

        if (! $ids || count($ids) === 0) {
            return back()->with('error', 'Pilih data terlebih dahulu');
        }

        $fileName = 'data_pengiriman_'.date('Ymd_His').'.xlsx';

        return Excel::download(new PengirimanExport($ids), $fileName);
    }
}
