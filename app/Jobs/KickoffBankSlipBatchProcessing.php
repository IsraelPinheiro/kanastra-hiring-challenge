<?php

namespace App\Jobs;

use App\Enums\BankSlipBatchStatus as Status;
use App\Models\BankSlipBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class KickoffBankSlipBatchProcessing implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection $batches;

    public function __construct()
    {
        $this->queue = 'bank_slip_batch_processing';
        $this->batches = BankSlipBatch::whereIn('status', [Status::Pending(), Status::Failed()])->orderBy('created_at', 'asc')->get();
    }

    public function uniqueId(): string
    {
        return Str::uuid()->toString();
    }

    public function handle(): void
    {
        if ($this->batches->isEmpty()) {
            return;
        }

        collect($this->batches)->each(fn (BankSlipBatch $batch) => ProcessBankSlipBatch::dispatch($batch));

        $this->batches->toQuery()->update([
            'status' => Status::Processing(),
        ]);
    }
}
