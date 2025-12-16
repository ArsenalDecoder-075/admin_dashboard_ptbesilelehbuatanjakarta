@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalStock, 0, ',', '.') }}</h3>
                    <p>Total Stok Barang</p>
                </div>
                <div class="icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <a href="{{ route('raw-materials.index') }}" class="small-box-footer">
                    More info <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalMaterials }}</h3>
                    <p>Jenis Bahan Baku</p>
                </div>
                <div class="icon">
                    <i class="bi bi-boxes"></i>
                </div>
                <a href="{{ route('raw-materials.index') }}" class="small-box-footer">
                    More info <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalSuppliers }}</h3>
                    <p>Total Supplier</p>
                </div>
                <div class="icon">
                    <i class="bi bi-people"></i>
                </div>
                <a href="{{ route('suppliers.index') }}" class="small-box-footer">
                    More info <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalPurchaseOrders }}</h3>
                    <p>Total Purchase Order</p>
                </div>
                <div class="icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <a href="{{ route('purchase-orders.index') }}" class="small-box-footer">
                    More info <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main row -->
    <div class="row">
        <!-- Recent Purchase Orders -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Purchase Order Terbaru</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPurchaseOrders as $po)
                                <tr>
                                    <td>{{ $po->po_number }}</td>
                                    <td>{{ $po->supplier->supplier_name }}</td>
                                    <td>{{ $po->po_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{
                                            $po->status == 'Approved' ? 'success' :
                                            ($po->status == 'Draft' ? 'warning' :
                                            ($po->status == 'Received' ? 'info' : 'danger'))
                                        }}">
                                            {{ $po->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase-orders.show', $po->po_id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data Purchase Order</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Materials -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bahan Baku Stok Rendah</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockMaterials as $material)
                                <tr>
                                    <td>{{ $material->material_name }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ $material->stock }} {{ $material->unit }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">Semua stok bahan baku aman</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
