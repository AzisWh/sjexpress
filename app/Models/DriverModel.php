<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverModel extends Model
{
    protected $table = 'driver_table';

    protected $fillable = [
        'name',
        'no_telp',
    ];
}
