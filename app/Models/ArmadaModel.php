<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArmadaModel extends Model
{
    protected $table = 'armada_table';

    protected $fillable = [
        'nama_armada',
        'plat_nomor',
        'foto_armada',
    ];
}
