<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetailModel extends Model
{
    use HasFactory;

    protected $table = 'invoice_details';

    protected $fillable = [
        'invoice_id',
        'pengiriman_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(InvoiceModel::class, 'invoice_id');
    }

    public function pengiriman()
    {
        return $this->belongsTo(PengirimanModel::class, 'pengiriman_id');
    }
}
