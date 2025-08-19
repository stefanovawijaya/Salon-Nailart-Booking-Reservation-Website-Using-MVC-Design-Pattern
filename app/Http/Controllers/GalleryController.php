<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::with('salon')->get();
        return view('home.gallery', compact('gallery'));
    }

    public function create()
    {
        $salon = Auth::guard('salon')->user();

        return view('salon.gallery.add', compact('salon'));
    }

    public function edit($gallery_id)
    {
        $salon = Auth::guard('salon')->user();

        $gallery = Gallery::where('salon_id', $salon->salon_id)
                          ->where('gallery_id', $gallery_id)
                          ->firstOrFail();

        return view('salon.gallery.edit', compact('gallery'));
    }

    public function store(Request $request)
    {
        $salon = Auth::guard('salon')->user();

        $validated = $request->validate([
            'gallery_name' => 'required|string|max:255',
            'gallery_desc' => 'required|string|max:255',
            'gallery_image' => 'required|image|max:16384',
        ]);

        $image = $request->file('gallery_image');
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('img/gallery'), $filename);

        Gallery::create([
            'salon_id' => $salon->salon_id,
            'nailart_name' => $validated['gallery_name'],
            'nailart_desc' => $validated['gallery_desc'],
            'nailart_image' => $filename,
        ]);

        return redirect()->route('salon.account')->with('gallery_success', 'Galeri berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $salon = Auth::guard('salon')->user();

        $request->validate([
            'gallery_id' => 'required|integer',
            'nailart_name' => 'required|string|max:255',
            'nailart_desc' => 'required|string|max:255',
            'nailart_image' => 'nullable|image|mimes:jpeg,jpg,png|max:16384',
        ]);

        $gallery = Gallery::where('salon_id', $salon->salon_id)
                          ->where('gallery_id', $request->gallery_id)
                          ->firstOrFail();

        $gallery->nailart_name = $request->nailart_name;
        $gallery->nailart_desc = $request->nailart_desc;

        if ($request->hasFile('nailart_image')) {
            $oldImagePath = public_path('img/gallery/' . $gallery->nailart_image);
            if ($gallery->nailart_image && File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $image = $request->file('nailart_image');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/gallery'), $filename);

            $gallery->nailart_image = $filename;
        }

        $gallery->save();

        return redirect()->route('salon.account', $gallery->gallery_id)
                         ->with('gallery_success', 'Galeri berhasil diperbarui!');
    }

    public function delete(Request $request)
    {
        $salon = Auth::guard('salon')->user();

        $request->validate([
            'gallery_id' => 'required|integer',
        ]);

        $gallery = Gallery::where('salon_id', $salon->salon_id)
                          ->where('gallery_id', $request->gallery_id)
                          ->firstOrFail();

        $imagePath = public_path('img/gallery/' . $gallery->nailart_image);
        if ($gallery->nailart_image && File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $gallery->delete();

        return redirect()->route('salon.account')->with('gallery_success', 'Galeri berhasil dihapus!');
    }
}
