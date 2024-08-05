<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller as Controller;
 
class BaseController extends Controller
{

    
    public function sendResponse($success, $data, $message, $code){
        $response = [
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    public function sendCreatedResponse($data, $message)
    {
    	return $this->sendResponse(true, $data, $message, 200);
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccessResponse($data, $message)
    {
    	return $this->sendResponse(true, $data, $message, 201);
    }
 
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendErrorUnauthorizeResponse($data, $message, $error)
    {
        return $this->sendResponse(false, $data, $message, 401);
    }
    public function sendErrorBadParamsResponse($data, $message, $error)
    {
        return $this->sendResponse(false, $data, $message, 400);
    }

    public function sendErrorInternalResponse($data, $message, $error)
    {
        return $this->sendResponse(false, $data, $message, 500);
    }

}
