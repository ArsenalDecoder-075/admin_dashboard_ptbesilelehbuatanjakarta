<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseOrder extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_orders';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'po_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'po_number',
        'supplier_id',
        'po_date',
        'status',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'po_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status constants.
     */
    public const STATUSES = [
        'Draft' => 'Draft',
        'Approved' => 'Approved',
        'Received' => 'Received',
        'Cancelled' => 'Cancelled',
    ];

    /**
     * Get the supplier that owns the purchase order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get all of the purchase order details for the purchase order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrderDetails(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'po_id', 'po_id');
    }

    /**
     * Get the receiving associated with the purchase order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function receiving(): HasOne
    {
        return $this->hasOne(Receiving::class, 'po_id', 'po_id');
    }

    /**
     * Calculate the total amount of the purchase order.
     *
     * @return float
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->purchaseOrderDetails->sum(function ($detail) {
            return $detail->quantity * $detail->price;
        });
    }

    /**
     * Calculate the total quantity of the purchase order.
     *
     * @return float
     */
    public function getTotalQuantityAttribute(): float
    {
        return $this->purchaseOrderDetails->sum('quantity');
    }

    /**
     * Get the status badge color.
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'Draft' => 'warning',
            'Approved' => 'info',
            'Received' => 'success',
            'Cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Check if purchase order can be received.
     *
     * @return bool
     */
    public function canBeReceived(): bool
    {
        return $this->status === 'Approved' && !$this->receiving;
    }

    /**
     * Check if purchase order can be approved.
     *
     * @return bool
     */
    public function canBeApproved(): bool
    {
        return $this->status === 'Draft';
    }

    /**
     * Check if purchase order can be cancelled.
     *
     * @return bool
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['Draft', 'Approved']);
    }

    /**
     * Generate a new PO number.
     *
     * @return string
     */
    public static function generatePONumber(): string
    {
        $date = now()->format('ymd');
        $lastPO = self::where('po_number', 'like', "PO{$date}%")->latest()->first();

        if ($lastPO) {
            $lastNumber = (int) substr($lastPO->po_number, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return "PO{$date}{$nextNumber}";
    }

    /**
     * Scope a query to filter by status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by supplier.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $supplierId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope a query to filter by date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $startDate
     * @param  string  $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('po_date', [$startDate, $endDate]);
    }

    /**
     * Validation rules for the purchase order.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'po_number' => 'required|string|max:50|unique:purchase_orders,po_number',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'po_date' => 'required|date',
            'status' => 'required|in:' . implode(',', array_keys(self::STATUSES)),
            'remarks' => 'nullable|string',
        ];
    }

    /**
     * Validation rules for updating purchase order.
     *
     * @param  int  $id
     * @return array
     */
    public static function updateRules($id): array
    {
        return [
            'po_number' => 'required|string|max:50|unique:purchase_orders,po_number,' . $id . ',po_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'po_date' => 'required|date',
            'status' => 'required|in:' . implode(',', array_keys(self::STATUSES)),
            'remarks' => 'nullable|string',
        ];
    }
}
