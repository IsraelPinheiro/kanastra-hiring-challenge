<?php

namespace Tests\Feature\Auth;

use Illuminate\Http\JsonResponse;

test('status page is loaded', function () {
    $this->get('/api/status')
        ->assertStatus(JsonResponse::HTTP_OK)->assertJson([
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
        ]);
});
