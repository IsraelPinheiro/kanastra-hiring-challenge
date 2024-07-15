<?php

namespace Tests\Feature\Auth;

use App\Enums\BankSlipStatus;
use App\Models\BankSlip;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

test('bank slips are listed', function () {
    $this->get('/api/bank-slips')
        ->assertStatus(JsonResponse::HTTP_OK)->assertJson([]);
});

test('speficic bank slip is accessed', function () {
    $slip = BankSlip::factory()->create();

    $this->get("/api/bank-slips/{$slip->id}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson($slip->toArray());
});

test('invalid bank slip is not accessed', function () {
    $this->get('/api/bank-slips/'.Str::uuid()->toString())
        ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
});

/** Payment */
test('bank slip can be paid', function () {
    $slip = BankSlip::factory()->create();

    $this->post("/api/bank-slips/{$slip->id}/pay")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson($slip->toArray());
});

test('canceled bank slip cant be paid', function () {
    $slip = BankSlip::factory()->canceled()->create();

    $this->post("/api/bank-slips/{$slip->id}/pay")
        ->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
});

test('bank slip cant be paid again', function () {
    $slip = BankSlip::factory()->paid()->create();

    $this->post("/api/bank-slips/{$slip->id}/pay")
        ->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
});

/** Cancelation */
test('bank slip can be canceled', function () {
    $slip = BankSlip::factory()->create();

    $this->delete("/api/bank-slips/{$slip->id}")
        ->assertStatus(JsonResponse::HTTP_OK)
        ->assertJson([
            'id' => $slip->id,
            'status' => BankSlipStatus::Canceled(),
        ]);
});

test('bank slip cant be canceled again', function () {
    $slip = BankSlip::factory()->canceled()->create();

    $this->delete("/api/bank-slips/{$slip->id}")
        ->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
});

test('paid bank slip cant be canceled', function () {
    $slip = BankSlip::factory()->paid()->create();

    $this->delete("/api/bank-slips/{$slip->id}")
        ->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
});
