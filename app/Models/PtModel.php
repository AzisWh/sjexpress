<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PtModel extends Model
{
    protected $table = 'pt_table';

    protected $fillable = [
        'name',
        'pic',
        'no_pic',
        'alamat',
        'penagihan',
        'no_penagihan',
    ];
}
