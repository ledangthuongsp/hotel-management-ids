<?php
namespace App\Http\Controllers;

use App\Http\Requests\Reole\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * @OA\Tag(
 *     name="Roles",
 *     description="Quản lý quyền (Admin Only)"
 * )
 */
class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @OA\Get(
     *     path="/roles",
     *     summary="Danh sách các quyền",
     *     tags={"Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Danh sách quyền")
     * )
     */
    public function listRoles(): JsonResponse
    {
        try {
            $roles = $this->roleService->listRoles();

            if ($roles->isEmpty()) {
                return response()->json(['message' => 'No roles found'], 404); // 404 nếu không có dữ liệu
            }

            return response()->json($roles, 200); // 200 OK khi có dữ liệu
        } catch (Exception $e) {
            Log::error('Error fetching roles: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500); // 500 lỗi hệ thống
        }
    }

    /**
     * @OA\Post(
     *     path="/roles",
     *     summary="Tạo quyền mới",
     *     tags={"Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Admin"),
     *             @OA\Property(property="description", type="string", example="Quyền quản trị")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tạo quyền thành công"),
     *     @OA\Response(response=400, description="Yêu cầu sai"),
     *     @OA\Response(response=403, description="Không có quyền")
     * )
     */
    public function createRole(RoleRequest $request): JsonResponse
    {
        try {
            // Kiểm tra quyền trước khi tạo
            $role = $this->roleService->createRole($request->validated());
            return response()->json(['message' => 'Role created successfully', 'role' => $role], 201); // 201 khi tạo thành công
        } catch (Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return response()->json(['message' => 'Bad Request'], 400); // 400 khi có lỗi dữ liệu
        }
    }

    /**
     * @OA\Delete(
     *     path="/roles/{id}",
     *     summary="Xóa quyền",
     *     tags={"Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Xóa thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy quyền"),
     *     @OA\Response(response=403, description="Không có quyền")
     * )
     */
    public function deleteRole($id): JsonResponse
    {
        try {
            $this->roleService->deleteRole($id);
            return response()->json(['message' => 'Role deleted successfully'], 200); // 200 OK khi xóa thành công
        } catch (Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return response()->json(['message' => 'Role not found'], 404); // 404 khi không tìm thấy role
        }
    }
}
