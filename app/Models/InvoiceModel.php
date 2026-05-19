<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceModel extends Model
{
    use HasFactory;

    protected $table = 'invoice_table';

    protected $fillable = [
        'nomor_invoice',
        'tanggal_invoice',
        'pt_id',
        'nominal_invoice',
        'nominal_cair',
        'status',
        'tanggal_cair',
        'generated_by',
        'verification_token',
    ];

    public function pt()
    {
        return $this->belongsTo(PtModel::class, 'pt_id');
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetailModel::class, 'invoice_id');
    }

    public function pengiriman()
    {
        return $this->belongsToMany(PengirimanModel::class, 'invoice_details', 'invoice_id', 'pengiriman_id');
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
