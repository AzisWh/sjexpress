<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArmadaModel;
use App\Models\DriverModel;
use App\Models\PtModel;

class AdminController extends Controller
{
    public function index()
    {
        $ptCount = PtModel::count();
        $armadaCount = ArmadaModel::count();
        $driverCount = DriverModel::count();

        return view('admin.dashboard.index', [
            'ptCount' => $ptCount,
            'armadaCount' => $armadaCount,
            'driverCount' => $driverCount,
        ]);
    }
}
