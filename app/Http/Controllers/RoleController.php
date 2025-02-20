<?php
namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest\RoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;

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

    // ---- API Methods ----

    /**
     * @OA\Get(
     *     path="/roles",
     *     summary="Danh sách các quyền",
     *     tags={"Roles"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(response=200, description="Danh sách quyền"),
     *     @OA\Response(response=404, description="Không có quyền")
     * )
     */
    public function listRoles(): JsonResponse
    {
        try {
            $roles = $this->roleService->listRoles();

            if ($roles->isEmpty()) {
                return response()->json(['message' => 'No roles found'], 404);
            }

            return response()->json($roles, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
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
    public function createRole(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255'
            ]);

            // 🔥 Nếu role đã bị soft delete, khôi phục thay vì tạo mới
            $deletedRole = Role::withTrashed()->where('name', $validated['name'])->first();
            if ($deletedRole) {
                $deletedRole->restore(); // Khôi phục role cũ
                $deletedRole->update($validated); // Cập nhật thông tin mới
                return response()->json(['message' => 'Role restored successfully', 'role' => $deletedRole], 200);
            }

            // 🔥 Giới hạn số lượng role (không tính role đã bị xóa)
            if (Role::count() >= 2) {
                return response()->json(['message' => 'You can only create 2 roles.'], 400);
            }

            // Nếu không có role trùng tên, tạo mới
            $role = Role::create($validated);

            return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
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
     *         description="ID của quyền",
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
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }
    public function findRoleById($id):JsonResponse
    {
        try 
        {
            $role = Role::findOrFail($id);
            return response()->json([
                'message' => 'User retrieved successfully',
                'role' => $role
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'message'=>'Error fetching',
                'error'=>$e->getMessage()
            ]);
        }
    }
    // ---- UI Methods ----

    // Hiển thị danh sách quyền (UI)
    public function ui_index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    // Hiển thị chi tiết quyền (UI)
    public function ui_show($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.show', compact('role'));
    }

    // Hiển thị form tạo quyền mới (UI)
    public function ui_create()
    {
        return view('roles.create');
    }

    // Hiển thị form chỉnh sửa quyền (UI)
    public function ui_edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    // Xóa quyền (UI)
    public function destroy($id)
    {
        $role = Role::find($id);

        // Kiểm tra xem role này có người dùng nào không
        $userCount = User::where('role_id', $role->id)->count();
        
        if ($userCount > 0) {
            return redirect()->back()->with('error', 'Không thể xóa role này vì có người dùng đang sở hữu nó');
        }

        // Nếu không có user nào, tiến hành xóa
        $role->delete();
        return redirect()->back()->with('success', 'Role đã được xóa thành công');
    }
}
