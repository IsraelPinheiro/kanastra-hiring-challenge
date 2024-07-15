<?php

namespace App\Jobs;

use App\Mail\Debtor\MailBankSlipCreated;
use App\Models\BankSlip;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBankSlipCreationNotification implements ShouldBeUnique, ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private BankSlip $bankSlip)
    {
        $this->queue = 'notifications';

    }

    public function uniqueId(): string
    {
        return $this->bankSlip->id;
    }

    public function handle(): void
    {
        Mail::to($this->bankSlip->debtor_email)->send(new MailBankSlipCreated($this->bankSlip));
        $this->bankSlip->update(['notified_at' => now()]);
    }
}
