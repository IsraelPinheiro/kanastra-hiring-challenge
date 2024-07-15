<?php

namespace Tests\Feature\Auth;

use App\Enums\BankSlipBatchStatus;
use App\Models\BankSlipBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

/** Creation */
test('batch can be created', function () {
    $this->json('POST', '/api/batches', [
        'batch_file' => new UploadedFile(__DIR__ . '/input_file_10_OK.csv', 'input_file_10_OK.csv', 'text/csv', null, true)
    ])
    ->assertStatus(JsonResponse::HTTP_CREATED);
});

test('batch cant be created with invalid file', function () {
    $this->json('POST', '/api/batches', [
        'batch_file' => new UploadedFile(__DIR__ . '/input_file_10_INVALID.csv', 'input_file_10_INVALID.csv', 'text/csv', null, true)
    ])
    ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
});

test('batch cant be created with invalid file format', function () {
    $this->json('POST', '/api/batches', [
        'batch_file' => new UploadedFile(__DIR__ . '/input_file_10_OK.pdf', 'input_file_10_OK.pdf', 'application/pdf', null, true)
    ])
    ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
});

/** Listing */
test('batches are listed', function () {
    BankSlipBatch::factory(2)->create();
    $this->json('GET', '/api/batches', [
        'start_date' => today(),
        'end_date' => today(),
        'status' => null
    ])->assertStatus(JsonResponse::HTTP_OK)->assertJsonCount(2, 'data');
});

test('batches are listed correctly with date filter', function () {
    BankSlipBatch::factory()->create();
    BankSlipBatch::factory()->create([
        'created_at' => today()->subDay()
    ]);
    $this->json('GET', '/api/batches', [
        'start_date' => today(),
        'end_date' => today(),
        'status' => null
    ])->assertStatus(JsonResponse::HTTP_OK)->assertJsonCount(1, 'data');
});

test('batches are listed correctly with status filter', function () {
    BankSlipBatch::factory()->create();
    BankSlipBatch::factory()->canceled()->create();
    $this->json('GET', '/api/batches', [
        'start_date' => today(),
        'end_date' => today(),
        'status' => BankSlipBatchStatus::Canceled()
    ])->assertStatus(JsonResponse::HTTP_OK)->assertJsonCount(1, 'data');
});

test('invalid list request is not accessible', function () {
    $this->json('GET', '/api/batches', [
        'start_date' => today(),
        'end_date' => today()->subDay(),
    ])->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
});

test('speficic batch is accessed', function () {
    $slip = BankSlipBatch::factory()->create();

    $this->get("/api/batches/{$slip->id}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson($slip->toArray());
});

test('invalid batch is not accessed', function () {
    $this->get('/api/batches/'.Str::uuid()->toString())
        ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
});

/* Cancelation */
test('batch can be canceled', function () {
    $batch = BankSlipBatch::factory()->create();

    $this->delete("/api/batches/{$batch->id}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson([
            'id' => $batch->id,
            'status' => BankSlipBatchStatus::Canceled(),
        ]);
});

test('batch cant be canceled again', function () {
    $batch = BankSlipBatch::factory()->canceled()->create();

    $this->delete("/api/batches/{$batch->id}")
        ->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
});

test('batch cant be canceled while processing', function () {
    $batch = BankSlipBatch::factory()->processing()->create();

    $this->delete("/api/batches/{$batch->id}")
        ->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
});