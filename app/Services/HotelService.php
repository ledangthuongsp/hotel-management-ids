<?php
namespace App\Services;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\IHotelRepository;

class HotelService
{
    protected $hotelRepository;

    public function __construct(IHotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    // Lấy tất cả khách sạn
    public function getAllHotels(): Collection
    {
        return $this->hotelRepository->getAllHotels();
    }

    // Lấy khách sạn theo city_id
    public function getHotelByCity(int $cityId): Collection
    {
        return $this->hotelRepository->getHotelByCity($cityId);
    }

    // Lấy khách sạn theo tên
    public function getHotelByName(string $name): Collection
    {
        return $this->hotelRepository->getHotelByName($name);
    }

    // Lấy khách sạn theo tên và city_id
    public function getHotelByNameAndCity(string $name, int $cityId): Collection
    {
        return $this->hotelRepository->getHotelByNameAndCity($name, $cityId);
    }

    // Tạo khách sạn mới
    public function createHotel(array $data): Hotel
    {
        return $this->hotelRepository->create($data);
    }

    // Cập nhật khách sạn
    public function updateHotel(int $id, array $data): bool
    {
        return $this->hotelRepository->update($id, $data);
    }

    // Xóa khách sạn
    public function deleteHotel(int $id): bool
    {
        return $this->hotelRepository->delete($id);
    }
}
