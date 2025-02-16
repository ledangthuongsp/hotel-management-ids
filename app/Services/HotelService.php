<?php
namespace App\Services;

use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HotelService
{
    public function getAllHotels()
    {
        $user = Auth::user();
        return $user->role === 'admin' ? Hotel::all() : Hotel::where('user_id', $user->id)->get();
    }

    public function getHotelById($id)
    {
        $hotel = Hotel::findOrFail($id);
        $this->authorizeUser($hotel);
        return $hotel;
    }

    public function createHotel($data)
    {
        $data['user_id'] = Auth::id();
        $this->validateHotel($data);
        return Hotel::create($data);
    }

    public function updateHotel($id, $data)
    {
        $hotel = Hotel::findOrFail($id);
        $this->authorizeUser($hotel);
        $this->validateHotel($data, $id);
        $hotel->update($data);
        return $hotel;
    }

    public function deleteHotel($id)
    {
        $hotel = Hotel::findOrFail($id);
        $this->authorizeUser($hotel);
        $hotel->delete();
    }

    private function authorizeUser(Hotel $hotel)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $hotel->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function validateHotel(array $data, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'name_jp' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:hotels,code' . ($id ? ",$id" : ""),
            'city_id' => 'required|exists:cities,id',
            'email' => 'required|email',
            'telephone' => 'required|string',
            'fax' => 'required|string',
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
