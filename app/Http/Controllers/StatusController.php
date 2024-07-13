<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $status = collect([
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'api_version' => config('app.version').' '.config('app.release_type'),
            'server_time' => now()->toDateTimeString(),
        ]);

        if (app()?->isLocal()) {
            $status->put('debug_mode', config('app.debug'));
            $status->put('server_name', php_uname('n'));
            $status->put('database_connection', 'online');
            $status->put('telescope_enabled', config('telescope.enabled'));
            $status->put('pulse_enabled', config('pulse.enabled'));

            try {
                DB::connection()->getPdo();
            } catch (\Throwable $th) {
                $status->put('database_connection', 'offline');
            }
        }

        return response()->json($status, JsonResponse::HTTP_OK);
    }
}
