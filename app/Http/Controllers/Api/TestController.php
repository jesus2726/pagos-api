<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @tags Test
 */
class TestController extends Controller
{
    /**
     * Test endpoint
     *
     * Simple test endpoint to verify Scramble is working.
     */
    public function test()
    {
        return response()->json([
            'message' => 'Scramble is working!',
            'timestamp' => now()->toISOString(),
        ]);
    }
}
