<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArmadaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ArmadaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->per_page ?? 10;

            $data = ArmadaModel::latest()->paginate($perPage)->withQueryString();

            return view('admin.armada.index', compact('data'));
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat mengambil data armada: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_armada' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:255',
            'foto_armada' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {

            $fileName = null;
            if ($request->hasFile('foto_armada')) {
                $file = $request->file('foto_armada');
                $fileName = 'armada'.$file->getClientOriginalExtension();
                $file->storeAs('FotoArmada', $fileName, 'public');
            }

            ArmadaModel::create([
                'nama_armada' => $request->nama_armada,
                'plat_nomor' => $request->plat_nomor,
                'foto_armada' => $fileName,
            ]);

            Alert::success('Berhasil', 'PT "'.$request->nama_armada.'" berhasil ditambahkan');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data armada: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_armada' => 'nullable|string|max:255',
            'plat_nomor' => 'nullable|string|max:255',
            'foto_armada' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $armada = ArmadaModel::findOrFail($id);

            if ($request->filled('nama_armada')) {
                $armada->nama_armada = $request->nama_armada;
            }

            if ($request->filled('plat_nomor')) {
                $armada->plat_nomor = $request->plat_nomor;
            }

            if ($request->hasFile('foto_armada')) {

                // delete old foto_armada if exists
                if ($armada->foto_armada && Storage::disk('public')->exists('FotoArmada/'.$armada->foto_armada)) {
                    Storage::disk('public')->delete('FotoArmada/'.$armada->foto_armada);
                }

                $file = $request->file('foto_armada');
                $fileName = 'armada_'.time().'.'.$file->getClientOriginalExtension();
                $file->storeAs('FotoArmada', $fileName, 'public');

                $armada->foto_armada = $fileName;
            }

            $armada->save();

            Alert::success('Berhasil', 'Data armada berhasil diupdate');

            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat update: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $armada = ArmadaModel::findOrFail($id);
            if ($armada->foto_armada && Storage::disk('public')->exists('FotoArmada/'.$armada->foto_armada)) {
                Storage::disk('public')->delete('FotoArmada/'.$armada->foto_armada);
            }
            $armada->delete();

            Alert::success('Berhasil', 'Armada berhasil dihapus');

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus data armada: '.$e->getMessage());

            return redirect()->back();
        }
    }
}
