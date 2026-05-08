<?php

namespace App\Exports;

use App\Models\PengirimanModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengirimanExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    protected $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $data = PengirimanModel::with(['pt', 'driver', 'armada'])
            ->whereIn('id', $this->ids)
            ->get();

        return $data->map(function ($item, $index) {
            return [
                'NO' => $index + 1,
                'TGL AMBIL PAKET' => $item->tanggal_ambil,
                'NO POL' => $item->armada->plat_nomor ?? '-',
                'JENIS ARMADA' => $item->armada->nama_armada ?? '-',
                'DRIVER' => $item->driver->name ?? '-',
                'RUTE' => $item->rute_from.' - '.$item->rute_to,
                'HARGA PABRIK' => $item->harga_pabrik,
                'HARGA ARMADA' => $item->harga_armada,
                'INVOICE' => $item->invoiceDetails()->exists() ? 'SUDAH' : 'BELUM',
                'PT' => $item->pt->name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'TGL AMBIL PAKET',
            'NO POL',
            'JENIS ARMADA',
            'DRIVER',
            'RUTE',
            'HARGA PABRIK',
            'HARGA ARMADA',
            'INVOICE',
            'PT',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [

            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],

                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '198754',
                    ],
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
