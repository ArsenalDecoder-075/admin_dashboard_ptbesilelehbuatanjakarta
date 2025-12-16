<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_details';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pod_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'po_id',
        'material_id',
        'quantity',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    /**
     * Get the purchase order that owns the detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'po_id');
    }

    /**
     * Get the raw material that owns the detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'material_id', 'material_id');
    }

    /**
     * Calculate the subtotal for this detail.
     *
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Validation rules for the purchase order detail.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'po_id' => 'required|exists:purchase_orders,po_id',
            'material_id' => 'required|exists:raw_materials,material_id',
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
        ];
    }
}
