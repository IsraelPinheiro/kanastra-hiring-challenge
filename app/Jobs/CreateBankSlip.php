<?php

namespace App\Jobs;

use App\Models\BankSlip;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateBankSlip implements ShouldBeUnique, ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private string $bankSlipBatchId, private string $debtorName, private string $debtorEmail, private string $debtorGovernmentId, private float $amount, private string $dueDate, private string $debtId) {
        $this->queue = 'bank_slip_processing';
    }

    public function uniqueId(): string
    {
        return $this->debtId;
    }

    public function handle(): void
    {
        if (BankSlip::where('debt_id', $this->debtId)->exists()) {
            Log::info("Bank slip for debt {$this->debtId} already exists. Skipping creation.");

            return;
        }

        BankSlip::create([
            'bank_slip_batch_id' => $this->bankSlipBatchId,
            'debtor_name' => $this->debtorName,
            'debtor_email' => $this->debtorEmail,
            'debtor_government_id' => $this->debtorGovernmentId,
            'amount' => $this->amount,
            'due_date' => $this->dueDate,
            'debt_id' => $this->debtId,
        ]);
    }
}
