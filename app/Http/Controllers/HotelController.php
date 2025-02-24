<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Hotel;
use Illuminate\Validation\ValidationException;
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
            $page = (int) $request->input('page', 1); // Lấy trang hiện tại từ request
            $query = $this->hotelService->getAllHotels();

            // Lấy danh sách khách sạn có phân trang
             $hotels = $query->paginate($perPage, ['*'], 'page', $page); // Truyền page vào paginate
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
            ]);
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
        try
        {
            return response()->json($this->hotelService->getHotelById($id));
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
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
     *             @OA\Property(property="address_2", type="string", nullable=true), 
     *             @OA\Property(property="tax_code", type="string"),
     *             @OA\Property(property="company_name", type="string")
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
            return response()->json([
                'error' => 'Error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
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
     *             @OA\Property(property="address_2", type="string", nullable=true),
     *             @OA\Property(property="tax_code",type="string"),
     *             @OA\Property(property="company_name",type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công")
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $hotel = Hotel::findOrFail($id);

            // Validate data from frontend using the HotelService
            $this->hotelService->validateHotel($request->all(), $id);  // Call validateHotel from the service

            // Update the hotel
            $hotel->update($request->all());

            return response()->json(['message' => 'Hotel updated successfully', 'hotel' => $hotel], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $this->hotelService->deleteHotel($id);
            return response()->json(['message' => 'Hotel deleted successfully'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
        // --- UI Routes ---1
    }
    // Hiển thị danh sách khách sạn (UI)
    public function ui_index()
    {
        $hotels = Hotel::all();
        return view('hotels.index', compact('hotels'));
    }

    // Hiển thị chi tiết khách sạn (UI)
    public function ui_show($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('hotels.show', compact('hotel'));
    }

    // Hiển thị form tạo khách sạn mới (UI)
    public function ui_create(Request $request)
    {
        return view('modals.create_hotel');
    }

    public function ui_store(Request $request)
    {
        try {
            // Xác thực dữ liệu bằng validateHotel từ HotelService
            $validatedData = $this->hotelService->validateHotel($request->all());

            // Gọi service để tạo khách sạn
            $hotel = $this->hotelService->createHotel($validatedData);

            // ✅ Nếu thành công, redirect về danh sách hotels với thông báo
            return redirect()->route('hotels.index')->with('success', 'Hotel created successfully!');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->route('hotels.index')->with('error', 'Error creating hotel: ' . $e->getMessage());
        }
    }
    // Hiển thị form chỉnh sửa khách sạn (UI)
    public function ui_edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('hotels.edit', compact('hotel'));
    }
    public function search(Request $request)
    {
        try {
            // Lấy dữ liệu tìm kiếm từ request
            $filters = $request->only(['name', 'code', 'city_id']);

            // Lấy toàn bộ kết quả phù hợp với tìm kiếm
            $query = Hotel::query();

            if (!empty($filters['name'])) {
                $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
            }

            if (!empty($filters['code'])) {
                $query->where('code', 'LIKE', '%' . $filters['code'] . '%');
            }

            if (!empty($filters['city_id'])) {
                $query->where('city_id', $filters['city_id']);
            }

            // Lấy tất cả các kết quả phù hợp
            $results = $query->get();

            // **Phân trang lại dựa trên kết quả tìm kiếm**
            $page = request('page', 1);
            $perPage = 5; // Số item trên mỗi trang
            $offset = ($page - 1) * $perPage;

            // Cắt danh sách kết quả theo trang
            $paginatedResults = $results->slice($offset, $perPage)->values();

            return response()->json([
                'message' => 'Search completed successfully',
                'data' => $paginatedResults,
                'pagination' => [
                    'total' => $results->count(),
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil($results->count() / $perPage),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }
}
