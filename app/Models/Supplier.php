<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'supplier_id';

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
        'supplier_name',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all of the raw materials for the supplier.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get all of the purchase orders for the supplier.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Scope a query to search suppliers by name or contact person.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('supplier_name', 'like', '%' . $search . '%')
                     ->orWhere('contact_person', 'like', '%' . $search . '%')
                     ->orWhere('email', 'like', '%' . $search . '%');
    }

    /**
     * Get the supplier's full address.
     *
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        return $this->address ?? '';
    }

    /**
     * Validation rules for the supplier.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'supplier_name' => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
        ];
    }
}
