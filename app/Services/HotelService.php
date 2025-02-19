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

        if (!$user) {
            throw new \Exception("User not authenticated.");
        }

        if (!isset($user->role)) {
            throw new \Exception("User role is undefined.");
        }

        // Trả về Query Builder để có thể gọi paginate()
        return ($user->role_id === '1') 
            ? Hotel::orderBy('id', 'desc')  // Không dùng get()
            : Hotel::where('user_id', $user->id)->orderBy('id', 'desc'); // Không dùng get()
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

    public function validateHotel(array $data, $id = null)
    {
        // Quy tắc xác thực
        $rules = [
            'name' => 'required|string|max:255',
            'name_jp' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:hotels,code,' . $id, // Bỏ qua check nếu đang cập nhật chính khách sạn
            'user_id'=> 'required|exists:users,id',
            'city_id' => 'required|exists:cities,id',
            'email' => 'required|email',
            'telephone' => 'required|string',
            'fax' => 'nullable|string',
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
            'tax_code' => 'required|string|max:50', // Thêm quy tắc cho tax_code
            'company_name' => 'required|string|max:255', // Thêm quy tắc cho company_name
        ];

        // Thực hiện xác thực dữ liệu
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator); // Nếu xác thực không thành công, ném lỗi
        }
    }
}

