<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable; // Thêm dòng này
use App\Exceptions\AppException; // Thêm dòng này
use Illuminate\Http\JsonResponse; // Thêm dòng này
use Illuminate\Http\Response; // Thêm dòng này

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse
    {
        // Kiểm tra nếu lỗi là AppException
        if ($e instanceof AppException) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getStatusCode());
        }

        // Gọi phương thức render của lớp cha để xử lý các ngoại lệ khác
        return parent::render($request, $e);
    }
}