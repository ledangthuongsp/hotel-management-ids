<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
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
    $roles = $this->roleService->listRoles();

    // Kiểm tra nếu không có roles
    if ($roles->isEmpty()) {
        return response()->json(['message' => 'No roles found'], 404);
    }

    return response()->json($roles);
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
     *     @OA\Response(response=403, description="Không có quyền")
     * )
     */
    public function createRole(RoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated());
        return response()->json(['message' => 'Role created successfully', 'role' => $role]);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
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
     *     @OA\Response(response=403, description="Không có quyền"),
     *     @OA\Response(response=404, description="Không tìm thấy quyền")
     * )
     */
    public function deleteRole($id): JsonResponse
    {
        $this->roleService->deleteRole($id);
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
