<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardSuperController extends Controller
{
    public function index()
    {
        $userCount = User::count();

        return view('super.dashboard.index', compact('userCount'));
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
            'User ' . $request->name . ' berhasil dibuat'
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
}
