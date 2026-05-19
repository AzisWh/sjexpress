<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoPengirimanModel extends Model
{
    use HasFactory;

    protected $table = 'foto_pengiriman_table';

    protected $fillable = [
        'pengiriman_id',
        'file_path'
    ];

    // Relasi
    public function pengiriman()
    {
        return $this->belongsTo(PengirimanModel::class, 'pengiriman_id');
    }
}
