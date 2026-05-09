<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverModel;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DriverController extends Controller
{
    public function index()
    {
        try {
            $perPage = $request->per_page ?? 10;

            $data = DriverModel::latest()->paginate($perPage)->withQueryString();

            return view('admin.driver.index', compact('data'));
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat mengambil data driver: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_telp' => 'required|string|max:255',
        ]);

        try {
            DriverModel::create([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
            ]);

            Alert::success('Berhasil', 'Driver "'.$request->name.'" berhasil ditambahkan');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data driver: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'no_telp' => 'sometimes|string|max:255',
        ]);

        try {
            $driver = DriverModel::findOrFail($id);
            $driver->update([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
            ]);

            Alert::success('Berhasil', 'Driver "'.$request->name.'" berhasil diperbarui');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui data driver: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $driver = DriverModel::findOrFail($id);
            $driver->delete();

            Alert::success('Berhasil', 'Driver "'.$driver->name.'" berhasil dihapus');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus data driver: '.$e->getMessage());

            return redirect()->back();
        }
    }
}
