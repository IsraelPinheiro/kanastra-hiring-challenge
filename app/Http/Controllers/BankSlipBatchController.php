<?php

namespace App\Http\Controllers;

use App\Enums\BankSlipBatchStatus as Status;
use App\Http\Traits\CsvParser;
use App\Models\BankSlipBatch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class BankSlipBatchController extends Controller
{
    use CsvParser;

    public function store(Request $request)
    {
        request()->validate([
            'batch_file' => ['required', 'file', 'mimetypes:text/csv'],
        ]);

        $csvData = $this->CsvToArray(request()->file('batch_file')->get());

        request()->merge([
            'csv_data' => $csvData,
        ])->validate([
            'csv_data.*.name' => ['required', 'string'],
            'csv_data.*.governmentId' => ['required', 'numeric'],
            'csv_data.*.email' => ['required', 'email'],
            'csv_data.*.debtAmount' => ['required', 'numeric'],
            'csv_data.*.debtDueDate' => ['required', 'date'],
            'csv_data.*.debtId' => ['required', 'uuid'],
        ]);

        try {
            $uplodadedFile = Storage::disk(config('filesystems.default'))->put('/bank_slip_batches', $request->file('batch_file'));
        } catch (Throwable $th) {
            abort(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, __('bank_slip_batch.file_upload_error'));
        }

        return response()->json(
            BankSlipBatch::create([
                'file_path' => $uplodadedFile,
                'bank_slips_count' => count($csvData),
            ])->fresh(),
            JsonResponse::HTTP_CREATED
        );
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date', 'before_or_equal:today'],
            'status' => ['nullable', Rule::in(Status::values())],
        ]);

        return response()->json(
            BankSlipBatch::when(request('status'), fn (Builder $query): Builder => $query->where('status', request('status')))
                ->whereDate('created_at', '>=', request('start_date'))
                ->whereDate('created_at', '<=', request('end_date'))
                ->paginate(),
            JsonResponse::HTTP_OK
        );
    }

    public function show(BankSlipBatch $bankSlipBatch)
    {
        return response()->json(
            $bankSlipBatch,
            JsonResponse::HTTP_OK
        );
    }

    public function getFile(BankSlipBatch $bankSlipBatch): BinaryFileResponse
    {
        return response()->download($bankSlipBatch->file());
    }

    public function cancel(BankSlipBatch $bankSlipBatch): JsonResponse
    {
        abort_if($bankSlipBatch->status == Status::Processing(), JsonResponse::HTTP_BAD_REQUEST, __('bank_slip_batch.cannot_cancel_processing_batch'));
        abort_if($bankSlipBatch->status == Status::Canceled(), JsonResponse::HTTP_BAD_REQUEST, __('bank_slip_batch.already_canceled'));

        $bankSlipBatch->cancel();

        return response()->json($bankSlipBatch, JsonResponse::HTTP_OK);
    }
}
