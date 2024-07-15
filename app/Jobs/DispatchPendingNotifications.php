<?php

namespace App\Jobs;

use App\Models\BankSlip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class DispatchPendingNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection $bankSlips;

    public function __construct()
    {
        $this->queue = 'notifications';
        $this->bankSlips = BankSlip::query()->notified(false)->get();
    }

    public function handle(): void
    {
        Bus::batch(collect($this->bankSlips)->map(
            fn (BankSlip $bankSlip) => new SendBankSlipCreationNotification($bankSlip)
        ))->name('Send Bank Slip Creation Notifications')
            ->dispatch();
    }
}
