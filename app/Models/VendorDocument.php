<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorDocument extends Model
{
    protected $fillable = ['vendor_id', 'path', 'original_name', 'type'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}