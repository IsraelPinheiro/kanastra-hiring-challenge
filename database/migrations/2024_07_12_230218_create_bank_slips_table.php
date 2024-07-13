<?php

use App\Enums\BankSlipStatus as Status;
use App\Models\BankSlipBatch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bank_slips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('debt_id')->unique();
            $table->foreignIdFor(BankSlipBatch::class)->constrained()->cascadeOnDelete();
            $table->string('debtor_name');
            $table->string('debtor_government_id')->index();
            $table->string('debtor_email')->index();
            $table->decimal('amount', 12, 2);
            $table->enum('status', Status::values())->default(Status::AwaitingPayment());
            $table->date('due_date');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_slips');
    }
};
