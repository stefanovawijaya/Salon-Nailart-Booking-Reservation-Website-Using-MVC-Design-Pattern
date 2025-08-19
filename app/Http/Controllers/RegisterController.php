<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Salon;

class RegisterController extends Controller
{
    public function formClient()
    {
        return view('auth.register-client');
    }

    public function formSalon()
    {
        return view('auth.register-salon');
    }

    public function registerClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_email' => 'required|email|unique:clients,client_email',
            'client_password' => 'required|min:6',
            'confirm_client_password' => 'required|same:client_password'
        ], [
            'client_email.unique' => 'Email sudah terdaftar.',
            'client_password.min' => 'Kata sandi minimal harus 6 karakter.',
            'confirm_client_password.same' => 'Kata sandi dan konfirmasi kata sandi tidak cocok.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $client = Client::create([
            'client_email' => $request->client_email,
            'client_password' => Hash::make($request->client_password),
        ]);

        if ($client) {
            return redirect()->route('login');
        }

        return back()->withInput();
    }

    public function registerSalon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_email' => 'required|email|unique:salons,salon_email',
            'salon_password' => 'required|min:6|confirmed',
        ], [
            'salon_email.unique' => 'Email sudah terdaftar.',
            'salon_password.min' => 'Kata sandi minimal harus 6 karakter.',
            'salon_password.confirmed' => 'Kata sandi dan konfirmasi kata sandi tidak cocok.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $salon = Salon::create([
            'salon_email' => $request->salon_email,
            'salon_password' => Hash::make($request->salon_password),
        ]);

        if ($salon) {
            return redirect()->route('login.salon');
        }

        return back()->withInput();
    }
}