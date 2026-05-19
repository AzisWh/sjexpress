<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverModel extends Model
{
    use HasFactory;

    protected $table = 'driver_table';

    protected $fillable = [
        'name',
        'no_telp',
    ];
}
