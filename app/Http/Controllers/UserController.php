<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\UserRequest\CreateUserRequest;
use App\Http\Requests\UserRequest\SearchUserRequest;
use Illuminate\Routing\Controller;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
/**
 * @OA\Tag(
 *     name="User",
 *     description="User Management APIs"
 * )
 */
class UserController extends Controller 
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Lấy danh sách tất cả khách sạn",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Danh sách khách sạn")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);  // Lấy số lượng bản ghi mỗi trang
            $query = $this->userService->getAllUser();

            // Nếu có các điều kiện tìm kiếm, áp dụng chúng ở đây
            if ($name = $request->input('name')) {
                $query->where('name', 'like', "%$name%");
            }

            if ($email = $request->input('email')) {
                $query->where('email', 'like', "%$email%");
            }

            if ($roleId = $request->input('role_id')) {
                $query->where('role_id', $roleId);
            }

            // Lấy danh sách người dùng phân trang
            $users = $query->paginate($perPage);

            return response()->json([
                'message' => 'Users fetched successfully',
                'data' => $users->items(),
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"User"},
     *     summary="Get user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getUser($id)
    {
        return $this->userService->getUser($id);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"User"},
     *     summary="Create a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "user_name", "email", "password", "role_id"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="user_name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="role_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Validation error"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function createUser(CreateUserRequest $request)
    {
        try {
            return $this->userService->createUser($request);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    
    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"User"},
     *     summary="Update user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="user_name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="role_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function updateUser(UpdateUserRequest $request, $id)
    {
        return $this->userService->updateUser($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"User"},
     *     summary="Delete user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function deleteUser($id)
    {
        return $this->userService->deleteUser($id);
    }
    
    public function search(Request $request)
    {
        $filters = $request->only(['name', 'user_name', 'email', 'role_id']); // Lấy tất cả các bộ lọc từ request

        $users = User::search($filters)->get(); // Sử dụng scopeSearch để tìm kiếm theo các bộ lọc

        return response()->json($users);
    }



    // Hiển thị danh sách khách sạn (UI)
    public function ui_index()
    {
        $users = User::all();
        // Kiểm tra nếu có dữ liệu
        return view('users.index', compact('users'));
    }

    // Hiển thị chi tiết khách sạn (UI)
    public function ui_show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // Hiển thị form tạo khách sạn mới (UI)
    public function ui_create()
    {
        return view('users.create');
    }

    // Hiển thị form chỉnh sửa khách sạn (UI)
    public function ui_edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }
}
