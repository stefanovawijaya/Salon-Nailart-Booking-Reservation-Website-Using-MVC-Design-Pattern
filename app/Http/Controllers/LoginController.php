<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Salon;

class LoginController extends Controller
{
    public function formClient()
    {
        return view('auth.login-client');
    }

    public function formSalon()
    {
        return view('auth.login-salon');
    }

    public function loginClient(Request $request)
    {
        $credentials = $request->validate([
            'client_email' => ['required', 'email'],
            'client_password' => ['required'],
        ]);

        $client = Client::where('client_email', $credentials['client_email'])->first();
        if (!$client) {
            return back()->withErrors([
                'client_email' => 'Email tidak terdaftar.',
            ])->onlyInput('client_email');
        }

        if (!Hash::check($credentials['client_password'], $client->password)) {
            return back()->withErrors([
                'client_email' => 'Password salah.',
            ])->onlyInput('client_email');
        }

        Auth::guard('client')->login($client, $request->filled('remember'));
        $request->session()->regenerate();
        
        return redirect()->route('home');
    }

    public function loginSalon(Request $request)
    {
        $credentials = $request->validate([
            'salon_email' => ['required', 'email'],
            'salon_password' => ['required'],
        ]);

        $salon = Salon::where('salon_email', $credentials['salon_email'])->first();
        if (!$salon) {
            return back()->withErrors([
                'salon_email' => 'Email tidak terdaftar.',
            ])->onlyInput('salon_email');
        }

        if (!Hash::check($credentials['salon_password'], $salon->salon_password)) {
            return back()->withErrors([
                'salon_email' => 'Password salah.',
            ])->onlyInput('salon_email');
        }

        Auth::guard('salon')->login($salon, $request->filled('remember'));
        $request->session()->regenerate();
        
        return redirect()->route('salon.home');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('client')->check()) {
            Auth::guard('client')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

        if (Auth::guard('salon')->check()) {
            Auth::guard('salon')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login.salon');
        }

        return redirect('login');
    }
}