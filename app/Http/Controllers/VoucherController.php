<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;
use Illuminate\Support\Facades\File;

class VoucherController extends Controller
{
    public function create()
    {
        return view('salon.voucher.add');
    }

    public function edit($voucher_id)
    {
        $voucher = Voucher::findOrFail($voucher_id);

        return view('salon.voucher.edit', compact('voucher'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string|max:50|unique:vouchers,voucher_code',
            'voucher_value' => 'required|numeric|min:0',
            'voucher_image' => 'required|image|max:16384',
        ]);

        $image = $request->file('voucher_image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('img/voucher'), $filename);

        Voucher::create([
            'salon_id' => Auth::guard('salon')->id(),
            'voucher_code' => $request->voucher_code,
            'voucher_value' => $request->voucher_value,
            'voucher_image' => $filename,
        ]);

        return redirect()->route('salon.account')->with('voucher_success', 'Voucher berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,voucher_id',
            'voucher_code' => 'required|string|max:50',
            'voucher_value' => 'required|numeric|min:0',
            'voucher_image' => 'nullable|image|max:16384',
        ]);

        $voucher = Voucher::findOrFail($request->voucher_id);
        $voucher->voucher_code = $request->voucher_code;
        $voucher->voucher_value = $request->voucher_value;

        if ($request->hasFile('voucher_image')) {
            $oldPath = public_path('img/voucher/' . $voucher->voucher_image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $image = $request->file('voucher_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img/voucher'), $filename);
            $voucher->voucher_image = $filename;
        }

        $voucher->save();

        return redirect()->route('salon.account')->with('voucher_success', 'Voucher berhasil diperbarui!');
    }

    public function delete(Request $request)
    {
        $voucher = Voucher::findOrFail($request->voucher_id);

        $imagePath = public_path('img/voucher/' . $voucher->voucher_image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $voucher->delete();

        return redirect()->route('salon.account')->with('voucher_success', 'Voucher berhasil dihapus!');
    }
}
