<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankSlip extends Model
{
    use HasFactory;
    use HasUuids;

    /** Definition */
    protected $casts = [
        //
    ];

    protected $fillable = [
        //
    ];

    /** Relationships */
    public function bankSlipBatch(): BelongsTo
    {
        return $this->belongsTo(BankSlipBatch::class);
    }

    /** Methods */

    /** Accessors & Mutators */
    public function notified(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => ! is_null($this->notified_at)
        );
    }

    /** Scopes */
}
