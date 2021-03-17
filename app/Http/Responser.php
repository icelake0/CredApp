<?php

namespace App\Http;

use App\Http\Collections\ApiPaginatedCollection;
use Illuminate\Http\JsonResponse;

class Responser
{
    /**
     * Return a new JSON response with JsonSerializable data
     * 
     * @param int $status
     * @param mixed $data
     * @param string|null $message
     * @return Illuminate\Http\JsonResponse
     */
    public static function send(
        int $status,
        $data = [],
        string $message = null
    ): JsonResponse {
        $response = [
            'status' => $status,
            'data' => $data,
            "message" => ucwords($message)
        ];
        return response()->json($response, $status);
    }

    /**
     * Return a new JSON response with JsonSerializable data
     * 
     * @param int $status
     * @param string|null $error
     * @return Illuminate\Http\JsonResponse
     */
    public static function sendError(
        int $status,
        string $error = null
    ): JsonResponse {
        $response = [
            'status' => $status,
            "error" => ucwords($error)
        ];
        return response()->json($response, $status);
    }

    /**
     * Return a new JSON response with JsonSerializable data
     * 
     * @param int $status
     * @param mixed|null $errors
     * @param string|null $message
     * @return Illuminate\Http\JsonResponse
     */
    public static function sendErrors(
        int $status,
        $errors = [],
        string $messgae = null
    ): JsonResponse {
        $response = [
            'status' => $status,
            'errors' => $errors,
            "message" => $messgae
        ];
        return response()->json($response, $status);
    }
}
