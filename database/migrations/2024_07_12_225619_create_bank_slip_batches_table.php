<?php

use App\Enums\BankSlipBatchStatus as Status;
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
        Schema::create('bank_slip_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', Status::values())->default(Status::Pending());
            $table->string('file_path')->unique();
            $table->integer('bank_slips_count')->default(0);
            $table->tinyInteger('processing_attempts')->default(0);
            $table->timestamp('processing_started_at')->nullable();
            $table->timestamp('processing_finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_slip_batches');
    }
};
