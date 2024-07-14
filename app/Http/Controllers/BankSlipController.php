<?php

namespace App\Http\Controllers;

use App\Enums\BankSlipStatus as Status;
use App\Models\BankSlip;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BankSlipController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'due_date_start' => ['nullable', 'date', Rule::when(request('due_date_end'), 'before_or_equal:due_date_end')],
            'due_date_end' => ['nullable', 'date', Rule::when(request('due_date_start'), 'after_or_equal:due_date_start'), 'before_or_equal:today'],
            'created_at_start' => ['nullable', 'date', Rule::when(request('created_at_end'), 'before_or_equal:created_at_end')],
            'created_at_end' => ['nullable', 'date', Rule::when(request('created_at_start'), 'after_or_equal:created_at_start'), 'before_or_equal:today'],
            'search' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(Status::values())],
            'batch_id' => ['nullable', Rule::exists('bank_slip_batches', 'id')],
        ]);

        return response()->json(
            BankSlip::search(request('search'))
                ->when(request('batch_id'), fn (Builder $query): Builder => $query->where('bank_slip_batch_id', request('batch_id')))
                ->when(request('status'), fn (Builder $query): Builder => $query->where('status', request('status')))
                ->when(request('due_date_start'), fn (Builder $query): Builder => $query->whereDate('due_date', '>=', request('due_date_start')))
                ->when(request('due_date_end'), fn (Builder $query): Builder => $query->whereDate('due_date', '<=', request('due_date_end')))
                ->when(request('created_at_start'), fn (Builder $query): Builder => $query->whereDate('created_at', '>=', request('created_at_start')))
                ->when(request('created_at_end'), fn (Builder $query): Builder => $query->whereDate('created_at', '<=', request('created_at_end')))
                ->paginate(),
            JsonResponse::HTTP_OK
        );
    }

    public function show(BankSlip $bankSlip): JsonResponse
    {
        return response()->json($bankSlip->load('bankSlipBatch'), JsonResponse::HTTP_OK);
    }

    public function cancel(BankSlip $bankSlip): JsonResponse
    {
        abort_unless($bankSlip->status == Status::AwaitingPayment(), JsonResponse::HTTP_BAD_REQUEST, __('bank_slips.cannot_cancel_paid_slip'));

        $bankSlip->cancel();

        return response()->json($bankSlip, JsonResponse::HTTP_OK);
    }

    public function pay(BankSlip $bankSlip): JsonResponse
    {
        abort_if($bankSlip->status == Status::Paid(), JsonResponse::HTTP_BAD_REQUEST, __('bank_slips.already_paid'));
        abort_if($bankSlip->status == Status::Canceled(), JsonResponse::HTTP_BAD_REQUEST, __('bank_slips.cannot_pay_canceled_slip'));

        $bankSlip->pay();

        return response()->json($bankSlip, JsonResponse::HTTP_OK);
    }
}
