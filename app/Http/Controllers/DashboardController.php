<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\RawMaterial;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data untuk dashboard
        $totalSuppliers = Supplier::count();
        $totalMaterials = RawMaterial::count();
        $totalStock = RawMaterial::sum('stock');
        $totalPurchaseOrders = PurchaseOrder::count();

        // Recent purchase orders
        $recentPurchaseOrders = PurchaseOrder::with('supplier')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Low stock materials
        $lowStockMaterials = RawMaterial::where('stock', '<', 50)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalSuppliers',
            'totalMaterials',
            'totalStock',
            'totalPurchaseOrders',
            'recentPurchaseOrders',
            'lowStockMaterials'
        ));
    }
}
