<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivingDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receiving_details';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rd_id';

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
        'receiving_id',
        'material_id',
        'quantity_received',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity_received' => 'decimal:2',
    ];

    /**
     * Get the receiving that owns the detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiving(): BelongsTo
    {
        return $this->belongsTo(Receiving::class, 'receiving_id', 'receiving_id');
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
     * Get the ordered quantity from purchase order.
     *
     * @return float|null
     */
    public function getOrderedQuantityAttribute(): ?float
    {
        $po = $this->receiving->purchaseOrder;
        $poDetail = $po->purchaseOrderDetails->where('material_id', $this->material_id)->first();

        return $poDetail ? $poDetail->quantity : null;
    }

    /**
     * Calculate the remaining quantity to receive.
     *
     * @return float|null
     */
    public function getRemainingQuantityAttribute(): ?float
    {
        $ordered = $this->ordered_quantity;
        if ($ordered === null) {
            return null;
        }

        return max(0, $ordered - $this->quantity_received);
    }

    /**
     * Check if receiving is complete.
     *
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->remaining_quantity <= 0;
    }

    /**
     * Validation rules for the receiving detail.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'receiving_id' => 'required|exists:receiving,receiving_id',
            'material_id' => 'required|exists:raw_materials,material_id',
            'quantity_received' => 'required|numeric|min:0.01',
        ];
    }
}
