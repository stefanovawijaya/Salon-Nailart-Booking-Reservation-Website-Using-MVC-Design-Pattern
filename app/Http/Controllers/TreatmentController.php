<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TreatmentController extends Controller
{
    public function create()
    {
        return view('salon.treatment.add');
    }

    public function edit($treatment_id)
    {
        $treatment = Treatment::findOrFail($treatment_id);

        return view('salon.treatment.edit', [
            'treatment' => $treatment
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'treatment_name' => 'required|string|max:255',
            'treatment_price' => 'required|numeric|min:0',
            'treatment_image' => 'required|image|max:16384',
        ]);

        $salon_id = Auth::guard('salon')->id();

        $image = $request->file('treatment_image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('img/treatment'), $filename);

        Treatment::create([
            'salon_id' => $salon_id,
            'treatment_name' => $request->treatment_name,
            'treatment_price' => $request->treatment_price,
            'treatment_image' => $filename,
        ]);

        return redirect()->route('salon.account')->with('treatment_success', 'Treatment berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'treatment_name' => 'required|string|max:255',
            'treatment_price' => 'required|numeric|min:0',
            'treatment_image' => 'nullable|image|max:16384',
        ]);

        $treatment = Treatment::findOrFail($request->treatment_id);
        $treatment->treatment_name = $request->treatment_name;
        $treatment->treatment_price = $request->treatment_price;

        if ($request->hasFile('treatment_image')) {
            $oldImagePath = public_path('img/treatment/' . $treatment->treatment_image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $image = $request->file('treatment_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img/treatment'), $filename);
            $treatment->treatment_image = $filename;
        }

        $treatment->save();

        return redirect()->route('salon.account')->with('treatment_success', 'Treatment berhasil diperbarui!');
    }

    public function delete(Request $request)
    {
        $treatment = Treatment::findOrFail($request->treatment_id);

        $imagePath = public_path('img/treatment/' . $treatment->treatment_image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $treatment->delete();

        return redirect()->route('salon.account')->with('treatment_success', 'Treatment berhasil dihapus!');
    }
}
