<?php

namespace App\Jobs;

use App\Enums\BankSlipBatchStatus;
use App\Http\Traits\CsvParser;
use App\Models\BankSlipBatch;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessBankSlipBatch implements ShouldBeUnique, ShouldQueue
{
    use Batchable, CsvParser, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private BankSlipBatch $bankSlipBatch)
    {
        $this->queue = 'bank_slip_batch_processing';
    }

    public function uniqueId(): string
    {
        return $this->bankSlipBatch->id;
    }

    public function handle(): void
    {
        Bus::batch(collect($this->CsvToArray(
            Storage::disk(config('filesystems.default'))->get($this->bankSlipBatch->file_path)
        ))->map(
            fn (array $row) => new CreateBankSlip(
                $row['name'],
                $row['email'],
                $row['governmentId'],
                $row['debtAmount'],
                $row['debtDueDate'],
                $row['debtId']
            )
        ))
        ->finally(function (Batch $batch) {
            $this->bankSlipBatch->update([
                'processing_attempts' => $this->bankSlipBatch->processing_attempts + 1,
                'status' => $batch->hasFailures() ? BankSlipBatchStatus::Failed() : BankSlipBatchStatus::Processed(),
            ]);

            if($batch->hasFailures()) {
                Log::error("Batch {$this->bankSlipBatch->id} - One or more bank slips failed to be created.");
            }
        })->name('Batch {$this->bankSlipBatch->id} processing')
            ->allowFailures()
            ->dispatch();
    }
}
