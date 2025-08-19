<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Voucher;

class ClientController extends Controller
{
    public function homePage()
    {
        $data = [
            'gallery' => Gallery::inRandomOrder()->take(5)->get(),
            'voucher' => Voucher::orderByDesc('created_at')->take(5)->get(),
        ];

        return view('home.index', $data);
    }

    public function profile()
    {
        $client = Auth::guard('client')->user();

        if (!$client) {
            return redirect()->route('login');
        }

        return view('client.profile', compact('client'));
    }

    public function edit()
    {
        $client = Auth::guard('client')->user();

        if (!$client) {
            return redirect()->route('login');
        }

        return view('client.edit', compact('client'));
    }

    public function update(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phonenumber' => 'required|digits_between:8,15',
            'client_image_icon' => 'nullable|image|max:16384',
        ], [
            'client_name.required' => 'Nama wajib diisi.',
            'client_phonenumber.required' => 'Nomor telepon wajib diisi.',
            'client_phonenumber.digits_between' => 'Nomor telepon harus terdiri dari 8 hingga 15 digit.',
            'client_image_icon.image' => 'File yang diunggah harus berupa gambar.',
            'client_image_icon.max' => 'Ukuran gambar maksimal adalah 16MB.',
        ]);

        $client->client_name = $request->client_name;
        $client->client_phonenumber = $request->client_phonenumber;

        if ($request->hasFile('client_image_icon')) {
            $image = $request->file('client_image_icon');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img/profile'), $filename);
            $client->client_image_icon = $filename;
        }

        $client->save();

        return redirect()->route('client.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
