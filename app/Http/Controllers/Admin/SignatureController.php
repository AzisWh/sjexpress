<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SignatureModel;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SignatureController extends Controller
{
    public function index(Request $request)
    {
        try {

            $perPage = $request->per_page ?? 10;

            $data = SignatureModel::latest()
                ->paginate($perPage)
                ->withQueryString();

            return view('admin.signature.index', compact('data'));

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'signature' => 'required',
        ]);

        try {

            SignatureModel::create([
                'name' => $request->name,
                'signature' => $request->signature,
            ]);

            Alert::success('Berhasil', 'Signature berhasil ditambahkan');

            return back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'signature' => 'required',
        ]);

        try {

            $signature = SignatureModel::findOrFail($id);

            $signature->update([
                'name' => $request->name,
                'signature' => $request->signature,
            ]);

            Alert::success('Berhasil', 'Signature berhasil diupdate');

            return back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    public function destroy($id)
    {
        try {

            $signature = SignatureModel::findOrFail($id);

            $signature->delete();

            Alert::success('Berhasil', 'Signature berhasil dihapus');

            return back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    // javasctript fetch all signature
    public function getAll()
    {
        try {

            $data = SignatureModel::latest()->get();

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
