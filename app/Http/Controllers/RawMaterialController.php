<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index()
    {
        $rawMaterials = RawMaterial::with('supplier')
            ->latest()
            ->get();

        return view('raw-materials.index', compact('rawMaterials'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('raw-materials.create', compact('suppliers'));
    }

    public function store(Request $request)
{
    $request->validate([
        'material_name' => 'required|string|max:255',
        'supplier_id'   => 'required|exists:suppliers,id',
        'unit'          => 'required|string|max:50',
        'stock'         => 'required|numeric|min:0',
        'description'   => 'nullable|string',
    ]);

    RawMaterial::create([
        'material_name' => $request->material_name,
        'supplier_id'   => $request->supplier_id,
        'unit'          => $request->unit,
        'stock'         => $request->stock,
        'description'   => $request->description,
    ]);

    return redirect()
        ->route('raw-materials.index')
        ->with('success', 'Raw Material berhasil ditambahkan');
}

    public function edit(RawMaterial $rawMaterial)
    {
        $suppliers = Supplier::all();
        return view('raw-materials.edit', compact('rawMaterial', 'suppliers'));
    }

  public function update(Request $request, RawMaterial $rawMaterial)
{
    $request->validate([
        'material_name' => 'required|string|max:255',
        'supplier_id'   => 'required|exists:suppliers,id',
        'unit'          => 'required|string|max:50',
        'stock'         => 'required|numeric|min:0',
        'description'   => 'nullable|string',
    ]);

    $rawMaterial->update([
        'material_name' => $request->material_name,
        'supplier_id'   => $request->supplier_id,
        'unit'          => $request->unit,
        'stock'         => $request->stock,
        'description'   => $request->description,
    ]);

    return redirect()
        ->route('raw-materials.index')
        ->with('success', 'Raw Material berhasil diperbarui');
}


    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();

        return redirect()
            ->route('raw-materials.index')
            ->with('success', 'Raw Material berhasil dihapus');
    }
}
