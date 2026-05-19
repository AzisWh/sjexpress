<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        try {
            $perPage = $request->per_page ?? 10;

            $data = User::latest()->paginate($perPage)->withQueryString();

            return view('super.user.index', compact('data'));
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat mengambil data user: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sama',
        ]);

        try {

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Alert::success(
                'Berhasil',
                'User '.$request->name.' berhasil dibuat'
            );

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error(
                'Gagal',
                $e->getMessage()
            );

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sama',
        ]);

        try {

            $user = User::findOrFail($id);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Update password hanya kalau diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            Alert::success(
                'Berhasil',
                'User '.$request->name.' berhasil diupdate'
            );

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error(
                'Gagal',
                $e->getMessage()
            );

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {

            $user = User::findOrFail($id);

            $nama = $user->name;

            $user->delete();

            Alert::success(
                'Berhasil',
                'User '.$nama.' berhasil dihapus'
            );

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error(
                'Gagal',
                $e->getMessage()
            );

            return redirect()->back();
        }
    }
}
