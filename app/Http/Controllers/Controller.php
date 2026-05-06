<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class Controller
{
    use AuthorizesRequests;

    /**
     * Return a standardized success JSON response.
     *
     * @param  mixed  $data
     */
    protected function successResponse($data = null, ?string $message = null, int $status = Response::HTTP_OK): JsonResponse
    {
        $response = ['success' => true];
        if (! is_null($message)) {
            $response['message'] = $message;
        }
        if (! is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    /**
     * Return a standardized error JSON response.
     *
     * @param  mixed|null  $errors
     */
    protected function errorResponse(string $message, int $status = Response::HTTP_BAD_REQUEST, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if (! is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
