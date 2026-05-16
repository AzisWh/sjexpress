<?php

namespace App\Exports;

use App\Models\PengirimanModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengirimanExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithStyles
{
    protected $ids;

    protected $totalHargaPabrik = 0;

    protected $totalHargaArmada = 0;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $data = PengirimanModel::with(['pt', 'driver', 'armada'])
            ->whereIn('id', $this->ids)
            ->get();

        $this->totalHargaPabrik = $data->sum('harga_pabrik');
        $this->totalHargaArmada = $data->sum('harga_armada');

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

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;

                // total row
                $lastRow = $sheet->getHighestRow() + 1;

                // merge cell
                $sheet->mergeCells("A{$lastRow}:F{$lastRow}");

                $sheet->setCellValue("A{$lastRow}", 'TOTAL');

                // total harga
                $sheet->setCellValue("G{$lastRow}", $this->totalHargaPabrik);
                $sheet->setCellValue("H{$lastRow}", $this->totalHargaArmada);

                $sheet->getStyle("G{$lastRow}:H{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->getStyle("A{$lastRow}:J{$lastRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '212529',
                        ],
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],

                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                ]);
            },
        ];
    }
}
