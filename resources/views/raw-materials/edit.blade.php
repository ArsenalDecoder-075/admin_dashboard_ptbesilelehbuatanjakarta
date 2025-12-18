@extends('layouts.admin')

@section('title', 'Edit Raw Material')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">Form Edit Raw Material</h3>
                </div>

                <form action="{{ route('raw-materials.update', $rawMaterial->material_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        {{-- Nama Material --}}
                        <div class="form-group">
                            <label>Nama Material</label>
                            <input type="text"
                                   name="material_name"
                                   class="form-control @error('material_name') is-invalid @enderror"
                                   value="{{ old('material_name', $rawMaterial->material_name) }}"
                                   placeholder="Masukkan nama material">
                            @error('material_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tipe Material --}}
                        <div class="form-group">
                            <label>Tipe Material</label>
                            <input type="text"
                                   name="material_type"
                                   class="form-control @error('material_type') is-invalid @enderror"
                                   value="{{ old('material_type', $rawMaterial->material_type) }}"
                                   placeholder="Contoh: Besi, Plastik, Karet">
                            @error('material_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Supplier --}}
                        <div class="form-group">
                            <label>Supplier</label>
                            <select name="supplier_id"
                                    class="form-control @error('supplier_id') is-invalid @enderror">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id', $rawMaterial->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->supplier_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Unit --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <input type="text"
                                           name="unit"
                                           class="form-control @error('unit') is-invalid @enderror"
                                           value="{{ old('unit', $rawMaterial->unit) }}"
                                           placeholder="Kg / Pcs / Liter">
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Stok --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stok</label>
                                    <input type="number"
                                           name="stock"
                                           class="form-control @error('stock') is-invalid @enderror"
                                           value="{{ old('stock', $rawMaterial->stock) }}"
                                           min="0">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label>Deskripsi (Opsional)</label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Tambahkan deskripsi">{{ old('description', $rawMaterial->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
