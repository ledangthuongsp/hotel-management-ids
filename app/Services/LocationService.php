<?php
namespace App\Services;

use App\Models\City;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Facades\Http;
use Exception;

class LocationService
{
    public function fetchAndStoreLocations()
    {
        try {
            // Lấy danh sách tỉnh/thành phố
            $response = Http::withoutVerifying()->get('https://provinces.open-api.vn/api/');
            if ($response->failed()) {
                throw new Exception("Lỗi khi lấy dữ liệu từ API.");
            }
            $cities = $response->json();

            foreach ($cities as $city) {
                $newCity = City::updateOrCreate(
                    ['name' => $city['name']],
                    ['description' => $city['name']]
                );

                // Lấy danh sách quận/huyện của tỉnh
                $districtResponse = Http::withoutVerifying()->get("https://provinces.open-api.vn/api/p/{$city['code']}?depth=2");
                if ($districtResponse->successful()) {
                    foreach ($districtResponse->json()['districts'] as $district) {
                        $newDistrict = District::updateOrCreate(
                            ['name' => $district['name'], 'city_id' => $newCity->id]
                        );

                        // Lấy danh sách phường/xã của quận
                        $wardResponse = Http::withoutVerifying()->get("https://provinces.open-api.vn/api/d/{$district['code']}?depth=2");
                        if ($wardResponse->successful()) {
                            foreach ($wardResponse->json()['wards'] as $ward) {
                                Ward::updateOrCreate(
                                    ['name' => $ward['name'], 'district_id' => $newDistrict->id]
                                );
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Dữ liệu đã được cập nhật thành công!'], 200);
    }
}
