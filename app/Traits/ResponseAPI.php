<?php

namespace App\Traits;

trait ResponseAPI
{
    /**
     * Core of response
     *
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode
     * @param   boolean         $isSuccess
     */
    public function coreResponse($message, $data = null, $statusCode, $isSuccess = true)
    {
        // Check the params
        if(!$message) return response()->json(['message' => 'Message is required'], 500);
        // Send the response
        if($isSuccess) {
            $response = [
                'message' => $message,
                'error' => false,
                'code' => $statusCode,
            ];
            if (!empty($data)) {
                $response['data'] = $data;
            }
            return response()->json($response, $statusCode);
        } else {
            return response()->json([
                'message' => $message,
                'error' => true,
                'code' => $statusCode,
            ], $statusCode);
        }
    }

    /**
     * Send any success response
     *
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode
     */
    public function success($message, $data, $statusCode = 200)
    {
        return $this->coreResponse($message, $data, $statusCode);
    }

    /**
     * Send any error response
     *
     * @param   string          $message
     * @param   integer         $statusCode
     */
    public function error($message, $statusCode = 500)
    {
        $code = $statusCode > 599 ? 500 : $statusCode;

        if ($code === 0) {
            $code = 500;
        }

        return $this->coreResponse($message, null, $code, false);
    }
}
