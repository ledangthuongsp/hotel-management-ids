<?php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Hotel;

interface IHotelRepository
{
    public function getAllHotels(): Collection;
    public function getHotelByCity(int $cityId): Collection;
    public function getHotelByName(string $name): Collection;
    public function getHotelByNameAndCity(string $name, int $cityId): Collection;
    public function create(array $data): Hotel;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
