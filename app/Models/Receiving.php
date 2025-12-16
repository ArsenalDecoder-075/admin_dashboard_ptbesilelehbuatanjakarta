<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receiving extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receiving';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'receiving_id';

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
        'po_id',
        'receiving_number',
        'receiving_date',
        'received_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'receiving_date' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Get the purchase order that owns the receiving.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'po_id');
    }

    /**
     * Get all of the receiving details for the receiving.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivingDetails(): HasMany
    {
        return $this->hasMany(ReceivingDetail::class, 'receiving_id', 'receiving_id');
    }

    /**
     * Calculate the total quantity received.
     *
     * @return float
     */
    public function getTotalReceivedAttribute(): float
    {
        return $this->receivingDetails->sum('quantity_received');
    }

    /**
     * Generate a new receiving number.
     *
     * @return string
     */
    public static function generateReceivingNumber(): string
    {
        $date = now()->format('ymd');
        $lastReceiving = self::where('receiving_number', 'like', "RCV{$date}%")->latest()->first();

        if ($lastReceiving) {
            $lastNumber = (int) substr($lastReceiving->receiving_number, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return "RCV{$date}{$nextNumber}";
    }

    /**
     * Process the receiving and update stock.
     *
     * @return bool
     */
    public function processReceiving(): bool
    {
        try {
            foreach ($this->receivingDetails as $detail) {
                $material = $detail->rawMaterial;
                $material->updateStock($detail->quantity_received, 'add');
            }

            // Update PO status
            $this->purchaseOrder->status = 'Received';
            $this->purchaseOrder->save();

            return true;
        } catch (\Exception $e) {
            return false;
        }
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
        return $query->whereBetween('receiving_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by purchase order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $poId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPurchaseOrder($query, $poId)
    {
        return $query->where('po_id', $poId);
    }

    /**
     * Validation rules for the receiving.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'po_id' => 'required|exists:purchase_orders,po_id',
            'receiving_number' => 'required|string|max:50|unique:receiving,receiving_number',
            'receiving_date' => 'required|date',
            'received_by' => 'nullable|string|max:100',
        ];
    }

    /**
     * Validation rules for updating receiving.
     *
     * @param  int  $id
     * @return array
     */
    public static function updateRules($id): array
    {
        return [
            'po_id' => 'required|exists:purchase_orders,po_id',
            'receiving_number' => 'required|string|max:50|unique:receiving,receiving_number,' . $id . ',receiving_id',
            'receiving_date' => 'required|date',
            'received_by' => 'nullable|string|max:100',
        ];
    }
}
