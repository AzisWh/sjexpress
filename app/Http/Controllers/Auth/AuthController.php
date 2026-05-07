<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $remember = $request->boolean('remember');

            if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ], $remember)) {

                $request->session()->regenerate();

                $user = Auth::user();

                if ($user->role === 'superadmin') {
                    Alert::success('Login Berhasil', 'Selamat datang Super Admin!');

                    return redirect()->route('super-dashboard');
                }

                if ($user->role === 'admin') {
                    Alert::success('Login Berhasil', 'Selamat datang Admin!');

                    return redirect()->route('admin-dashboard');
                }

                Auth::logout();
                Alert::error('Akses Ditolak', 'Role tidak dikenali.');

                return redirect()->route('login-view');
            }

            Alert::error('Login Gagal', 'Email atau password salah');

            return back();

        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::success('Logout', 'Berhasil logout');

        return redirect()->route('login-view');
    }
}
