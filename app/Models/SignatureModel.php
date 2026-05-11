<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignatureModel extends Model
{
    protected $table = 'signature_table';

    protected $fillable = [
        'name',
        'signature',
    ];

    public function invoices()
    {
        return $this->hasMany(InvoiceModel::class, 'signature_id');
    }
}
