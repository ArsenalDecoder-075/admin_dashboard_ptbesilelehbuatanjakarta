@extends('layouts.admin')

@section('title', 'Data Raw Material')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <div class="card-tools">
                        <a href="{{ route('raw-materials.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i>
                            Tambah Raw Material
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width:5%">No</th>
                                    <th>Nama Material</th>
                                    <th>Kode</th>
                                    <th>Supplier</th>
                                    <th>Unit</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th style="width:15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rawMaterials as $rawMaterial)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $rawMaterial->material_name }}</td>
                                        <td>{{ $rawMaterial->material_code }}</td>
                                        <td>
                                            {{ $rawMaterial->supplier->supplier_name ?? '-' }}
                                        </td>
                                        <td>{{ $rawMaterial->unit }}</td>
                                        <td>Rp {{ number_format($rawMaterial->price, 0, ',', '.') }}</td>
                                        <td>{{ $rawMaterial->stock }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('raw-materials.edit', $rawMaterial->id) }}"
                                                   class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                    Edit
                                                </a>

                                                <button type="button"
                                                        class="btn btn-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#deleteModal{{ $rawMaterial->id }}">
                                                    <i class="bi bi-trash"></i>
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="deleteModal{{ $rawMaterial->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Yakin ingin menghapus raw material
                                                        <strong>{{ $rawMaterial->material_name }}</strong>?
                                                    </p>
                                                    <p class="text-danger">
                                                        Data yang dihapus tidak dapat dikembalikan.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <form action="{{ route('raw-materials.destroy', $rawMaterial->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data raw material
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="card-footer clearfix">
                    <div class="float-right">
                        Total: {{ $rawMaterials->count() }} Raw Material
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
@endsection
