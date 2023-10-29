<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Send a success response.
     *
     * @param mixed $result Data to be sent in the response.
     * @param string $message A success message to include in the response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message)
    {
        // Create a response array with success, message, and result
        $response = [
            'success' => true,
            'message' => $message,
            'result' => $result
        ];

        // Return a JSON response with a 200 status code
        return response()->json($response, 200);
    }

    /**
     * Send an error response.
     *
     * @param string $error A main error message to include in the response.
     * @param array $errorMessages Additional error messages (optional).
     * @param int $code HTTP status code for the response (default is 404).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        // Create a response array with success set to false and the main error message
        $response = [
            "success" => false,
            "message" => $error,
        ];

        // Check if there are additional error messages
        if (!empty($errorMessages)) {
            // Include additional error messages in the 'data' field of the response
            $response['data'] = $errorMessages;
        }

        // Return a JSON response with the specified HTTP status code
        return response()->json($response, $code);
    }
}
