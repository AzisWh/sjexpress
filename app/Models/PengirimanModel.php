<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanModel extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_table';

    protected $fillable = [
        'pt_id',
        'armada_id',
        'driver_id',
        'tanggal_ambil',
        'rute_from',
        'rute_to',
        'harga_pabrik',
        'harga_armada',
        'keterangan',
    ];

    public function pt()
    {
        return $this->belongsTo(PtModel::class, 'pt_id');
    }

    public function armada()
    {
        return $this->belongsTo(ArmadaModel::class, 'armada_id');
    }

    public function driver()
    {
        return $this->belongsTo(DriverModel::class, 'driver_id');
    }

    public function fotos()
    {
        return $this->hasMany(FotoPengirimanModel::class, 'pengiriman_id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetailModel::class, 'pengiriman_id');
    }

    public function getHasInvoiceAttribute()
    {
        return $this->invoiceDetails()->exists();
    }
}
