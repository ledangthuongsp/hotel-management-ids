<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\UserRequest\CreateUserRequest;
use App\Http\Requests\UserRequest\SearchUserRequest;
use Illuminate\Routing\Controller;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use App\Models\User;
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
        $this->middleware('auth:api'); // 🔥 Bảo vệ API bằng Authentication
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
        return $this->userService->createUser($request);
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


    // Hiển thị danh sách khách sạn (UI)
    public function ui_index()
    {
        $hotels = User::all();
        return view('hotels.index', compact('hotels'));
    }

    // Hiển thị chi tiết khách sạn (UI)
    public function ui_show($id)
    {
        $hotel = User::findOrFail($id);
        return view('hotels.show', compact('hotel'));
    }

    // Hiển thị form tạo khách sạn mới (UI)
    public function ui_create()
    {
        return view('hotels.create');
    }

    // Hiển thị form chỉnh sửa khách sạn (UI)
    public function ui_edit($id)
    {
        $hotel = User::findOrFail($id);
        return view('hotels.edit', compact('hotel'));
    }
    public function search(SearchUserRequest $request)
    {
        // Lấy dữ liệu tìm kiếm từ request
        $filters = $request->only(['name', 'code', 'city_id']);

        // Áp dụng scope tìm kiếm
        $hotels = User::search($filters)->get();

        // Trả về kết quả dưới dạng JSON
        return response()->json($hotels);
    }
}
