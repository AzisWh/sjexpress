<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PtModel;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PtController extends Controller
{
    public function index()
    {
        $data = PtModel::all();

        return view('admin.pt.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'no_pic' => 'required|string|max:255',
            'alamat' => 'required|string',
            'penagihan' => 'required|string',
            'no_penagihan' => 'required|string|max:255',
        ]);

        try {
            PtModel::create([
                'name' => $request->name,
                'pic' => $request->pic,
                'no_pic' => $request->no_pic,
                'alamat' => $request->alamat,
                'penagihan' => $request->penagihan,
                'no_penagihan' => $request->no_penagihan,
            ]);

            Alert::success('Berhasil', 'PT "'.$request->name.'" berhasil ditambahkan');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data barang: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'pic' => 'sometimes|string|max:255',
            'no_pic' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string',
            'penagihan' => 'sometimes|string',
            'no_penagihan' => 'sometimes|string|max:255',
        ]);

        try {
            $pt = PtModel::findOrFail($id);
            $pt->update($request->only([
                'name',
                'pic',
                'no_pic',
                'alamat',
                'penagihan',
                'no_penagihan',
            ]));

            Alert::success('Berhasil', 'PT "'.$request->name.'" berhasil diupdate');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat mengupdate data barang: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $pt = PtModel::findOrFail($id);
            $pt->delete();

            Alert::success('Berhasil', 'PT "'.$pt->name.'" berhasil dihapus');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus data barang: '.$e->getMessage());

            return redirect()->back();
        }
    }
}
