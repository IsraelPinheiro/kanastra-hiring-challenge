<?php

namespace App\Models;

use App\Enums\BankSlipBatchStatus as Status;
use App\Enums\BankSlipStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperBankSlipBatch
 */
class BankSlipBatch extends Model
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
    public function bankSlips(): HasMany
    {
        return $this->hasMany(BankSlip::class);
    }

    /** Methods */
    public function cancel(): static
    {
        $this->update(['status' => Status::Canceled()]);
        $this->bankSlips()->where('status', '!=', BankSlipStatus::Paid())->get()->each(fn (BankSlip $bankSlip) => $bankSlip->cancel());

        return $this;
    }
    public function file()
    {
        return Storage::disk(config('filesystems.default'))->path($this->file_path);
    }

    /** Accessors & Mutators */

    /** Scopes */
}
