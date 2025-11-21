<?php

namespace App\Models;

use App\Enums\VendorStage;           // THIS LINE WAS MISSING!
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_name',
        'email',
        'phone',
        'address',
        'category',
        'current_stage',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        // DO NOT cast current_stage to enum — Laravel doesn't support it natively
    ];

    /**
     * Get current_stage as enum when reading
     */
    public function getCurrentStageAttribute($value): VendorStage|string
    {
        if ($value === null) {
            return 'new'; // fallback
        }

        return VendorStage::tryFrom($value) ?? $value;
    }

    /**
     * Save enum or string as string in DB
     */
    public function setCurrentStageAttribute(string|VendorStage $value): void
    {
        $this->attributes['current_stage'] = $value instanceof VendorStage
            ? $value->value
            : $value;
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(VendorHistory::class)->orderByDesc('created_at');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('current_stage', VendorStage::Approved->value);
    }

    public function scopeAtStage($query, string|VendorStage $stage)
    {
        $value = $stage instanceof VendorStage ? $stage->value : $stage;
        return $query->where('current_stage', $value);
    }
}