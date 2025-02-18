<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * Các phương thức không cần phải thay đổi
     * ...
     */

    // /**
    //  * Render exception into an HTTP response.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \Exception  $e
    //  * @return \Illuminate\Http\Response
    //  */
    // public function render($request, Exception $e)
    // {
    //     // Nếu yêu cầu là API (wantsJson), trả về JSON.
    //     if ($request->wantsJson()) {
    //         return $this->handleApiException($e);
    //     }

    //     // Nếu không phải API, tiếp tục xử lý mặc định
    //     return parent::render($request, $e);
    // }

    /**
     * Handle API exceptions.
     *
     * @param \Exception $e
     * @return JsonResponse
     */
    protected function handleApiException(Exception $e): JsonResponse
    {
        // Xử lý lỗi không xác thực (401)
        if ($e instanceof AuthenticationException) {
            return $this->unauthenticatedResponse($e);
        }

        // Xử lý lỗi không tìm thấy (404)
        if ($e instanceof NotFoundHttpException) {
            return $this->notFoundResponse($e);
        }

        // Xử lý lỗi phương thức không được phép (405)
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->methodNotAllowedResponse($e);
        }

        // Xử lý lỗi xác thực (422)
        if ($e instanceof ValidationException) {
            return $this->validationErrorResponse($e);
        }

        // Lỗi chung (500)
        return $this->defaultErrorResponse($e);
    }

    /**
     * Xử lý lỗi không xác thực (401)
     */
    private function unauthenticatedResponse(AuthenticationException $e)
    {
        return response()->json([
            'error' => 'Unauthenticated',
            'message' => $e->getMessage(),
        ], 401);  // 401 Unauthorized
    }

    /**
     * Xử lý lỗi không tìm thấy (404)
     */
    private function notFoundResponse(NotFoundHttpException $e)
    {
        return response()->json([
            'error' => 'Not Found',
            'message' => 'The resource you are looking for could not be found.',
        ], 404);  // 404 Not Found
    }

    /**
     * Xử lý lỗi phương thức không được phép (405)
     */
    private function methodNotAllowedResponse(MethodNotAllowedHttpException $e)
    {
        return response()->json([
            'error' => 'Method Not Allowed',
            'message' => $e->getMessage(),
        ], 405);  // 405 Method Not Allowed
    }

    /**
     * Xử lý lỗi xác thực (422)
     */
    private function validationErrorResponse(ValidationException $e)
    {
        return response()->json([
            'error' => 'Validation Error',
            'message' => $e->validator->errors(),
        ], 422);  // 422 Unprocessable Entity
    }

    /**
     * Xử lý lỗi chung (500)
     */
    private function defaultErrorResponse(Exception $e)
    {
        return response()->json([
            'error' => 'Internal Server Error',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);  // 500 Internal Server Error
    }
}
