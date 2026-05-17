<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArmadaModel;
use App\Models\DriverModel;
use App\Models\FotoPengirimanModel;
use App\Models\PengirimanModel;
use App\Models\PtModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->per_page ?? 10;

            $query = PengirimanModel::with(['pt', 'armada', 'driver', 'fotos']);

            if ($request->filled('pt_id')) {
                $query->where('pt_id', $request->pt_id);
            }

            $sort = $request->sort ?? 'latest';

            switch ($sort) {
                case 'oldest':
                    $query->oldest('created_at');
                    break;
                case 'tanggal-terbaru':
                    $query->orderBy('tanggal_ambil', 'desc');
                    break;
                case 'tanggal-terlama':
                    $query->orderBy('tanggal_ambil', 'asc');
                    break;
                default:
                    $query->latest('created_at');
                    break;
            }

            $data = $query->paginate($perPage)->withQueryString();

            $pt = PtModel::all();
            $armada = ArmadaModel::all();
            $driver = DriverModel::all();

            return view('admin.pengiriman.index', compact(
                'data',
                'pt',
                'armada',
                'driver'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'pt_id' => 'required|exists:pt_table,id',
            'armada_id' => 'required|exists:armada_table,id',
            'driver_id' => 'required|exists:driver_table,id',
            'tanggal_ambil' => 'required|date',
            'rute_from' => 'required',
            'rute_to' => 'required',
            'harga_pabrik' => 'required|numeric',
            'harga_armada' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'foto.*' => 'nullable|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $pengiriman = PengirimanModel::create($request->except('foto'));

            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $fileName = 'sj_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                    $file->storeAs('SuratJalan', $fileName, 'public');

                    FotoPengirimanModel::create([
                        'pengiriman_id' => $pengiriman->id,
                        'file_path' => $fileName,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengiriman berhasil ditambahkan',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadFoto(Request $request, $id)
    {
        try {
            $request->validate([
                'foto.*' => 'required|image|max:2048',
            ]);

            $pengiriman = PengirimanModel::findOrFail($id);

            foreach ($request->file('foto') as $file) {
                $fileName = 'sj_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->storeAs('SuratJalan', $fileName, 'public');

                FotoPengirimanModel::create([
                    'pengiriman_id' => $id,
                    'file_path' => $fileName,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteFoto($id)
    {
        try {
            $foto = FotoPengirimanModel::findOrFail($id);

            if (Storage::disk('public')->exists('SuratJalan/'.$foto->file_path)) {
                Storage::disk('public')->delete('SuratJalan/'.$foto->file_path);
            }

            $foto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getFotos($id)
    {
        try {
            $pengiriman = PengirimanModel::with('fotos')->findOrFail($id);

            $fotos = $pengiriman->fotos->map(function ($foto) {
                return [
                    'id' => $foto->id,
                    'file_path' => $foto->file_path,
                    'url' => asset('storage/SuratJalan/'.$foto->file_path),
                ];
            });

            return response()->json(['success' => true, 'data' => $fotos, 'pengiriman_id' => $pengiriman->id]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pengiriman = PengirimanModel::findOrFail($id);
            $pengiriman->update($request->only('pt_id', 'armada_id', 'driver_id', 'tanggal_ambil', 'rute_from', 'rute_to', 'harga_pabrik', 'harga_armada', 'keterangan'));

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pengiriman = PengirimanModel::findOrFail($id);

            foreach ($pengiriman->fotos as $foto) {
                if (Storage::disk('public')->exists('SuratJalan/'.$foto->file_path)) {
                    Storage::disk('public')->delete('SuratJalan/'.$foto->file_path);
                }
            }

            $pengiriman->delete();

            Alert::success('Berhasil', 'Data pengiriman dihapus');

            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());

            return redirect()->back();
        }
    }
}
