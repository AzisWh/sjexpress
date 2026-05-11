<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceModel extends Model
{
    protected $table = 'invoice_table';

    protected $fillable = [
        'nomor_invoice',
        'tanggal_invoice',
        'pt_id',
        'nominal_invoice',
        'nominal_cair',
        'status',
        'tanggal_cair',
        'signature_id',
    ];

    public function pt()
    {
        return $this->belongsTo(PtModel::class, 'pt_id');
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetailModel::class, 'invoice_id');
    }

    public function signature()
    {
        return $this->belongsTo(SignatureModel::class, 'signature_id');
    }
    public function pengiriman()
    {
        return $this->belongsToMany(PengirimanModel::class, 'invoice_details', 'invoice_id', 'pengiriman_id');
    }
}
