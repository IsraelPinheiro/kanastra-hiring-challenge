<?php

namespace App\Models;

use App\Enums\BankSlipStatus as Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperBankSlip
 */
class BankSlip extends Model
{
    use HasFactory;
    use HasUuids;

    /** Definition */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /** Relationships */
    public function bankSlipBatch(): BelongsTo
    {
        return $this->belongsTo(BankSlipBatch::class);
    }

    /** Methods */
    public function cancel(): static
    {
        $this->update(['status' => Status::Canceled()]);

        //TODO: Notify debtor about the cancelation

        return $this;
    }

    /** Accessors & Mutators */
    public function notified(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => ! is_null($this->notified_at)
        );
    }

    /** Scopes */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when(
            $search,
            fn (Builder $query) => $query
                ->where('debt_id', 'like', "%$search%")
                ->orWhere('debtor_name', 'like', "%$search%")
                ->orWhere('debtor_email', 'like', "%$search%")
                ->orWhere('debtor_government_id', 'like', "%$search%")
        );
    }

    public function scopeNotified(Builder $query, bool $notified = true): Builder
    {
        return $query->when(
            $notified,
            fn (Builder $query) => $query->whereNotNull('notified_at'),
            fn (Builder $query) => $query->whereNull('notified_at')
        );
    }
}
