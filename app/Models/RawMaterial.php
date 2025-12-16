<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterial extends Model
{
    use HasFactory;

    protected $table = 'raw_materials';
    protected $primaryKey = 'material_id';
    public $timestamps = true;

    protected $fillable = [
        'material_name',
        'material_type',
        'unit',
        'description',
        'stock',
        'supplier_id',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
    ];

    // Relationships
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function purchaseOrderDetails(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'material_id');
    }

    public function receivingDetails(): HasMany
    {
        return $this->hasMany(ReceivingDetail::class, 'material_id');
    }

    // Simple methods
    public function updateStock($quantity, $operation = 'add')
    {
        $currentStock = (float) $this->stock;

        if ($operation === 'add') {
            $newStock = $currentStock + $quantity;
        } elseif ($operation === 'subtract') {
            if ($currentStock < $quantity) {
                return false; // Stock tidak cukup
            }
            $newStock = $currentStock - $quantity;
        } elseif ($operation === 'set') {
            $newStock = $quantity;
        } else {
            return false; // Operation tidak valid
        }

        $this->stock = $newStock;
        return $this->save();
    }

    public function isLowStock($threshold = 50)
    {
        return (float) $this->stock < $threshold;
    }

    // Validation rules
    public static function rules()
    {
        return [
            'material_name' => 'required|string|max:150',
            'material_type' => 'required|in:Logam Ringan,Baja,Plastik/Resin,Lainnya',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'stock' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
        ];
    }
}
