<?php
namespace App\Http\Controllers;

use App\Services\HotelService;
use App\Http\Requests\CreateHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use Illuminate\Http\Request;


class HotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
        $this->middleware('auth');
    }

    // Hiển thị danh sách tất cả khách sạn
    public function index()
    {
        $hotels = $this->hotelService->getAllHotels();
        return view('hotels.index', compact('hotels'));
    }

    // Hiển thị form tạo khách sạn mới
    public function create()
    {
        return view('hotels.create');
    }

    // Lưu khách sạn mới
    public function store(CreateHotelRequest $request)
    {
        $validated = $request->validated();
        $this->hotelService->createHotel($validated);
        return redirect()->route('hotels.index');
    }

    // Hiển thị form chỉnh sửa khách sạn
    public function edit($id)
    {
        $hotel = $this->hotelService->getHotelByCity($id);
        return view('hotels.edit', compact('hotel'));
    }

    // Cập nhật khách sạn
    public function update(UpdateHotelRequest $request, $id)
    {
        $validated = $request->validated();
        $this->hotelService->updateHotel($id, $validated);
        return redirect()->route('hotels.index');
    }

    // Xóa khách sạn
    public function destroy($id)
    {
        $this->hotelService->deleteHotel($id);
        return redirect()->route('hotels.index');
    }
}
