<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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
    public function file()
    {
        return Storage::disk(config('filesystems.default'))->get($this->file_path);
    }

    /** Accessors & Mutators */

    /** Scopes */
}
