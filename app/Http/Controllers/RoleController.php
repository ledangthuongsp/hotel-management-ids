<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * @OA\Tag(
 *     name="Role",
 *     description="Role Management APIs"
 * )
 */
class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/roles",
     *     tags={"Role"},
     *     summary="Get all roles",
     *     @OA\Response(
     *         response=200,
     *         description="List of roles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAllRoles()
    {
        return $this->roleService->getAllRoles();
    }

    /**
     * @OA\Post(
     *     path="/roles",
     *     tags={"Role"},
     *     summary="Create a new role",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", enum={"Admin", "Member"}),
     *             @OA\Property(property="description", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="role", type="object")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Cannot add more roles"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function createRole(CreateRoleRequest $request)
    {
        return $this->roleService->createRole($request);
    }

    /**
     * @OA\Put(
     *     path="/roles/{id}",
     *     tags={"Role"},
     *     summary="Update role by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Role ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="role", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Role not found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function updateRole(UpdateRoleRequest $request, $id)
    {
        return $this->roleService->updateRole($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/roles/{id}",
     *     tags={"Role"},
     *     summary="Delete role by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Role ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Cannot delete role, it is assigned to users"),
     *     @OA\Response(response=404, description="Role not found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function deleteRole($id)
    {
        return $this->roleService->deleteRole($id);
    }
}
