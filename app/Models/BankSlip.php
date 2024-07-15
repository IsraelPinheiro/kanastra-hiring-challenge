<?php

namespace App\Models;

use App\Enums\BankSlipStatus as Status;
use App\Mail\Debtor\MailBankSlipCanceled;
use App\Mail\Debtor\MailBankSlipCreated;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperBankSlip
 */
class BankSlip extends Model
{
    use HasFactory;
    use HasUuids;

    protected static function boot(): void
    {
        parent::boot();

        static::created(function (self $bankSlip) {
            Mail::to($bankSlip->debtor_email)->later(30, new MailBankSlipCreated($bankSlip));
        });
    }

    /** Definition */
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
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

        Mail::to($this->debtor_email)->send(new MailBankSlipCanceled($this));

        return $this;
    }

    public function pay(): static
    {
        $this->update(['status' => Status::Paid(), 'paid_at' => now()]);

        return $this;
    }

    public function getPdfFilePath(): string
    {
        $filePath = 'bank_slip_pdf/'.$this->debt_id.'.pdf';

        if (Storage::disk(config('filesystems.default'))->exists($filePath)) {
            return $filePath;
        }

        Storage::disk(config('filesystems.default'))->put($filePath, PDF::loadView('exports.bankSlip', ['bankSlip' => $this])->output());

        return $filePath;

    }

    /** Accessors & Mutators */
    public function notified(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => ! is_null($this->notified_at)
        );
    }

    public function pdfFilePath(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->getPdfFilePath()
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
