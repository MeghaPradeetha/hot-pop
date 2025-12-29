<?php


namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
class APIBaseController extends Controller
{
    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondSuccess($data = [], $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'code'    => $code, // Legacy support if needed
        ], $code);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $code
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError($message = 'Error', $code = 400, $data = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->respondError($message, 401);
    }
}