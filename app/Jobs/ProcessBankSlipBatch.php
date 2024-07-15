<?php

namespace App\Jobs;

use App\Enums\BankSlipBatchStatus;
use App\Http\Traits\CsvParser;
use App\Models\BankSlipBatch;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessBankSlipBatch implements ShouldBeUnique, ShouldQueue
{
    use CsvParser, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private BankSlipBatch $bankSlipBatch, private int $chunkSize = 10000)
    {
        $this->queue = 'bank_slip_batch_processing';
    }

    public function uniqueId(): string
    {
        return $this->bankSlipBatch->id;
    }

    public function handle(): void
    {
        $bankSlipBatchID = $this->bankSlipBatch->id;

        $this->bankSlipBatch->update(['processing_attempts' => $this->bankSlipBatch->processing_attempts + 1]);

        $csvData = $this->CsvToArray(
            Storage::disk(config('filesystems.default'))->get($this->bankSlipBatch->file_path)
        );

        if ($this->bankSlipBatch->status == BankSlipBatchStatus::Failed()) {
            //Filter out the rows that have already been processed to avoid unnecessary processing
            $processedDebtIds = $this->bankSlipBatch->bankSlips->pluck('debt_id')->toArray();
            $csvData = collect($csvData)->filter(fn (array $row) => ! in_array($row['debtId'], $processedDebtIds))->toArray();
        }

        //Split the CSV data into chunks of rows to avoid memory issues
        $chunks = array_chunk($csvData, $this->chunkSize);

        collect($chunks)->each(function (array $dataChunk, int $index) use ($bankSlipBatchID) {
            Bus::batch(collect($dataChunk)->map(
                fn (array $row) => new CreateBankSlip(
                    $this->bankSlipBatch->id,
                    $row['name'],
                    $row['email'],
                    $row['governmentId'],
                    $row['debtAmount'],
                    $row['debtDueDate'],
                    $row['debtId']
                )
            ))->finally(function (Batch $batch) use ($bankSlipBatchID) {
                $bankSlipBatch = BankSlipBatch::find($bankSlipBatchID);

                if ($bankSlipBatch->status != BankSlipBatchStatus::Failed()) {
                    $bankSlipBatch->update([
                        'status' => $batch->hasFailures() ? BankSlipBatchStatus::Failed() : BankSlipBatchStatus::Processed(),
                    ]);
                }

                if ($batch->hasFailures()) {
                    Log::error("Batch {$bankSlipBatch->id} - One or more bank slips failed to be created.");
                }
            })->name('Processing Batch '.$this->bankSlipBatch->id.' - Chunk '.$index + 1)
                ->allowFailures()
                ->dispatch();
        });

    }
}
