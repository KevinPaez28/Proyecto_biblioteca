<?php

namespace App\Helpers;

class ResponseFormatter
{
    /**
     * success con opción de meta
     * @param string $message
     * @param int $status
     * @param mixed $data
     * @param array|null $meta
     */
    public static function success($message = "Operación exitosa", $status = 200, $data = null, $meta = null) {

        $response = [
            "success" => true,
            "code" => $status,
            "message" => $message,
            "data" => $data
        ];

        if ($meta !== null) {
            $response["meta"] = $meta;
        }

        return response()->json($response, $status);
    }

    public static function error($message, $status, $errors = []) {

        return response()->json([
            "success"=> false,
            "code" => $status,
            "message"=> $message,
            "errors" => $errors
        ], $status);

    }
}
