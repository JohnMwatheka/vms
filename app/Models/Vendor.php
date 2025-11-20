<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use App\Enums\VendorStage;

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
        'current_stage' => VendorStage::class,
        'approved_at' => 'datetime',
    ];

    public function creator(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents(): Relations\HasMany
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function histories(): Relations\HasMany
    {
        return $this->hasMany(VendorHistory::class)->orderByDesc('created_at');
    }
}