<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
/**
 * @OA\Tag(name="Hotels", description="Hotel Management API")
 */
class HotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    /**
     * @OA\Get(
     *     path="/hotels",
     *     summary="Lấy danh sách tất cả khách sạn",
     *     tags={"Hotels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Danh sách khách sạn")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $hotels = $this->hotelService->getAllHotels()->paginate($perPage); // Gọi paginate() đúng cách

            return response()->json([
                'message' => 'Hotels fetched successfully',
                'data' => $hotels->items(),
                'pagination' => [
                    'total' => $hotels->total(),
                    'per_page' => $hotels->perPage(),
                    'current_page' => $hotels->currentPage(),
                    'last_page' => $hotels->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/hotels/{id}",
     *     summary="Lấy thông tin chi tiết của một khách sạn",
     *     tags={"Hotels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của khách sạn",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Thông tin khách sạn"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function show($id): JsonResponse
    {
        return response()->json($this->hotelService->getHotelById($id));
    }

    /**
     * @OA\Post(
     *     path="/hotels",
     *     summary="Tạo mới khách sạn",
     *     tags={"Hotels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "code", "city_id", "email", "telephone", "address_1"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="name_jp", type="string", nullable=true),
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="user_id", type="integer"), 
     *             @OA\Property(property="city_id", type="integer"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="telephone", type="string"),
     *             @OA\Property(property="fax", type="string"),
     *             @OA\Property(property="address_1", type="string"),
     *             @OA\Property(property="address_2", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Khách sạn được tạo thành công")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try{
            $hotel = $this->hotelService->createHotel($request->all());
            return response()->json(['message' => 'Hotel created successfully', 'hotel' => $hotel], 201);
        }
        catch(\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * @OA\Put(
     *     path="/hotels/{id}",
     *     summary="Cập nhật thông tin khách sạn",
     *     tags={"Hotels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của khách sạn",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="name_jp", type="string", nullable=true),
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="user_id", type="integer"), 
     *             @OA\Property(property="city_id", type="integer"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="telephone", type="string"),
     *             @OA\Property(property="fax", type="string"),
     *             @OA\Property(property="address_1", type="string"),
     *             @OA\Property(property="address_2", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công")
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $hotel = $this->hotelService->updateHotel($id, $request->all());
        return response()->json(['message' => 'Hotel updated successfully', 'hotel' => $hotel]);
    }

    public function destroy($id): JsonResponse
    {
        $this->hotelService->deleteHotel($id);
        return response()->json(['message' => 'Hotel deleted successfully'], 204);
    }
}
