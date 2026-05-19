<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    // --- formatRupiah ---
    public function test_format_rupiah_with_thousands(): void
    {
        $this->assertEquals('Rp 1.000.000', formatRupiah(1000000));
    }

    public function test_format_rupiah_with_zero(): void
    {
        $this->assertEquals('Rp 0', formatRupiah(0));
    }

    public function test_format_rupiah_with_small_number(): void
    {
        $this->assertEquals('Rp 500', formatRupiah(500));
    }

    public function test_format_rupiah_with_large_number(): void
    {
        $this->assertEquals('Rp 50.000.000', formatRupiah(50000000));
    }

    // --- terbilang ---
    public function test_terbilang_zero(): void
    {
        $this->assertEquals('', terbilang(0));
    }

    public function test_terbilang_single_digit(): void
    {
        $this->assertEquals('Lima', terbilang(5));
    }

    public function test_terbilang_eleven(): void
    {
        $this->assertEquals('Sebelas', terbilang(11));
    }

    public function test_terbilang_twenty(): void
    {
        $this->assertEquals('Dua Puluh', terbilang(20));
    }

    public function test_terbilang_twenty_five(): void
    {
        $this->assertEquals('Dua Puluh Lima', terbilang(25));
    }

    public function test_terbilang_one_hundred(): void
    {
        $this->assertEquals('Seratus', terbilang(100));
    }

    public function test_terbilang_one_hundred_fifty(): void
    {
        $this->assertEquals('Seratus Lima Puluh', terbilang(150));
    }

    public function test_terbilang_two_hundred(): void
    {
        $this->assertEquals('Dua Ratus', terbilang(200));
    }

    public function test_terbilang_one_thousand(): void
    {
        $this->assertEquals('Seribu', terbilang(1000));
    }

    public function test_terbilang_one_million(): void
    {
        $this->assertEquals('Satu Juta', terbilang(1000000));
    }

    public function test_terbilang_complex_number(): void
    {
        $this->assertEquals('Satu Juta Lima Ratus Ribu', terbilang(1500000));
    }

    public function test_terbilang_billion(): void
    {
        $this->assertEquals('Satu Miliar', terbilang(1000000000));
    }

    // --- formatTerbilangRupiah ---
    public function test_format_terbilang_rupiah(): void
    {
        $this->assertEquals('Seribu Rupiah', formatTerbilangRupiah(1000));
    }

    public function test_format_terbilang_rupiah_large(): void
    {
        $result = formatTerbilangRupiah(5000000);
        $this->assertStringEndsWith('Rupiah', $result);
    }

    // --- bulanRomawi ---
    public function test_bulan_romawi_all_months(): void
    {
        $expected = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        foreach ($expected as $month => $roman) {
            $this->assertEquals($roman, bulanRomawi($month), "Month {$month} should be {$roman}");
        }
    }

    public function test_bulan_romawi_invalid_returns_empty(): void
    {
        $this->assertEquals('', bulanRomawi(0));
        $this->assertEquals('', bulanRomawi(13));
    }

    public function test_bulan_romawi_casts_string(): void
    {
        $this->assertEquals('I', bulanRomawi('1'));
        $this->assertEquals('XII', bulanRomawi('12'));
    }
}
