<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Interfaces\IHotelRepository;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;

class HotelRepository implements IHotelRepository
{
    // Lấy tất cả khách sạn
    public function getAllHotels(): Collection
    {
        return Hotel::all();
    }

    // Lấy khách sạn theo city_id
    public function getHotelByCity(int $cityId): Collection
    {
        return Hotel::where('city_id', $cityId)->get();
    }

    // Lấy khách sạn theo tên
    public function getHotelByName(string $name): Collection
    {
        return Hotel::where('name', 'like', "%{$name}%")->get();
    }

    // Lấy khách sạn theo tên và city_id
    public function getHotelByNameAndCity(string $name, int $cityId): Collection
    {
        return Hotel::where('name', 'like', "%{$name}%")
            ->where('city_id', $cityId)
            ->get();
    }

    // Tạo khách sạn mới
    public function create(array $data): Hotel
    {
        return Hotel::create($data);
    }

    // Cập nhật khách sạn
    public function update(int $id, array $data): bool
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return false; // Hotel không tồn tại
        }
        return $hotel->update($data);
    }

    // Xóa khách sạn
    public function delete(int $id): bool
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return false; // Hotel không tồn tại
        }
        return $hotel->delete();
    }
}
