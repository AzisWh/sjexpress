<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('terbilang')) {
    function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($angka < 12) {
            return $baca[$angka];
        }

        if ($angka < 20) {
            return $baca[$angka - 10] . ' Belas';
        }

        if ($angka < 100) {
            return $baca[intval($angka / 10)] . ' Puluh' . ($angka % 10 ? ' ' . $baca[$angka % 10] : '');
        }

        if ($angka < 200) {
            return 'Seratus' . ($angka - 100 ? ' ' . terbilang($angka - 100) : '');
        }

        if ($angka < 1000) {
            return $baca[intval($angka / 100)] . ' Ratus' . ($angka % 100 ? ' ' . terbilang($angka % 100) : '');
        }

        if ($angka < 2000) {
            return 'Seribu' . ($angka - 1000 ? ' ' . terbilang($angka - 1000) : '');
        }

        if ($angka < 1000000) {
            return terbilang(intval($angka / 1000)) . ' Ribu' . ($angka % 1000 ? ' ' . terbilang($angka % 1000) : '');
        }

        if ($angka < 1000000000) {
            return terbilang(intval($angka / 1000000)) . ' Juta' . ($angka % 1000000 ? ' ' . terbilang($angka % 1000000) : '');
        }

        if ($angka < 1000000000000) {
            return terbilang(intval($angka / 1000000000)) . ' Miliar' . ($angka % 1000000000 ? ' ' . terbilang($angka % 1000000000) : '');
        }

        return terbilang(intval($angka / 1000000000000)) . ' Triliun' . ($angka % 1000000000000 ? ' ' . terbilang($angka % 1000000000000) : '');
    }
}

if (!function_exists('formatTerbilangRupiah')) {
    function formatTerbilangRupiah($angka)
    {
        return terbilang($angka) . ' Rupiah';
    }
}

if (!function_exists('bulanRomawi')) {
    function bulanRomawi($bulan)
    {
        $romawi = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        return $romawi[(int) $bulan] ?? '';
    }
}
