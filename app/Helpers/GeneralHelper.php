<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class GeneralHelper
{
    /**
     * Genera un UUID
     */
    public static function generateUuid(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Respuesta estándar de API
     */
    public static function apiResponse($data = null, $message = '', $code = 200)
    {
        $response = [
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $code);
    }

    /**
     * Respuesta de error estándar
     */
    public static function apiError($message = 'Error', $code = 400, $errors = null)
    {
        $response = [
            'message' => $message,
            'errors'  => $errors,
        ];

        return response()->json($response, $code);
    }
}
