    @extends('adminlte::page')

    @section('title', 'Add New Hotel')

    @section('content_header')
        <h1>Add New Hotel</h1>
    @endsection

    @section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Hotel</h3>
            </div>
            <div class="card-body">
                <form id="create-hotel-form">
                    @csrf
                    <!-- Hiển thị thông báo -->
                    <div id="success-message" class="alert alert-success d-none"></div>
                    <div id="error-message" class="alert alert-danger d-none"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="hotel-name">Hotel Name</label>
                            <input type="text" class="form-control" name="name" id="hotel-name" placeholder="Enter Hotel Name">
                            <small class="text-danger error-message" id="error-hotel-name"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="hotel-code">Hotel Code</label>
                            <input type="text" class="form-control" name="code" id="hotel-code" placeholder="Enter Hotel Code">
                            <small class="text-danger error-message" id="error-hotel-code"></small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="hotel-city">City</label>
                            <select class="form-control" name="city_id" id="hotel-city" onchange="fetchDistricts(this.value)">
                                <option value="">--Select City--</option>
                            </select>
                            <small class="text-danger error-message" id="error-hotel-city"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="hotel-district">District</label>
                            <select class="form-control" name="district" id="hotel-district" onchange="fetchWards(this.value)">
                                <option value="">--Select District--</option>
                            </select>
                            <small class="text-danger error-message" id="error-hotel-district"></small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="hotel-ward">Ward</label>
                            <select class="form-control" name="ward" id="hotel-ward">
                                <option value="">--Select Ward--</option>
                            </select>
                            <small class="text-danger error-message" id="error-hotel-ward"></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hotel-address-1">Address (Street + Number)</label>
                        <input type="text" class="form-control" name="address_1" id="hotel-address-1" placeholder="Enter Street Address">
                        <small class="text-danger error-message" id="error-hotel-address-1"></small>
                    </div>

                    <div class="form-group">
                        <label for="hotel-email">Email</label>
                        <input type="email" class="form-control" name="email" id="hotel-email" placeholder="Enter Email">
                        <small class="text-danger error-message" id="error-hotel-email"></small>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="hotel-telephone">Telephone</label>
                            <input type="text" class="form-control" name="telephone" id="hotel-telephone" placeholder="Enter Telephone">
                            <small class="text-danger error-message" id="error-hotel-telephone"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="hotel-tax-code">Tax Code</label>
                            <input type="text" class="form-control" name="tax_code" id="hotel-tax-code" placeholder="Enter Tax Code">
                            <small class="text-danger error-message" id="error-hotel-tax-code"></small>
                        </div>
                    </div>

                    <!-- Optional Fields -->
                    <div class="form-group">
                        <label for="hotel-address-2">Address (Optional)</label>
                        <input type="text" class="form-control" name="address_2" id="hotel-address-2" placeholder="Enter Additional Address">
                    </div>

                    <div class="form-group">
                        <label for="hotel-fax">Fax (Optional)</label>
                        <input type="text" class="form-control" name="fax" id="hotel-fax" placeholder="Enter Fax">
                    </div>

                    <div class="form-group">
                        <label for="hotel-name-jp">Hotel Name (Japanese) (Optional)</label>
                        <input type="text" class="form-control" name="name_jp" id="hotel-name-jp" placeholder="Enter Hotel Name in Japanese">
                    </div>

                    <div class="form-group">
                        <label for="hotel-company-name">Company Name</label>
                        <input type="text" class="form-control" name="company_name" id="hotel-company-name" placeholder="Enter Company Name">
                        <small class="text-danger error-message" id="error-hotel-company-name"></small>
                    </div>

                    <button type="submit" class="btn btn-success" onclick="createHotel(event)">Save Hotel</button>
                    <a href="{{ route('hotels.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    @endsection

    @section('js')
    <script>
        let citiesMap = {};
        let districtsMap = {};
        let wardsMap = {};

        document.addEventListener('DOMContentLoaded', function () {
            fetchCities();
        });

        function fetchCities() {
            fetch('/api/cities', {
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(cities => {
                let cityDropdown = document.getElementById('hotel-city');
                cityDropdown.innerHTML = '<option value="">--Select City--</option>';
                
                cities.forEach(city => {
                    citiesMap[city.id] = city.name;
                    let option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    cityDropdown.appendChild(option);
                });
            })
            .catch(error => console.error("🚨 Error fetching cities:", error));
        }

        function fetchDistricts(cityId) {
            if (!cityId) return;

            fetch(`/api/districts/${cityId}`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(districts => {
                let districtDropdown = document.getElementById('hotel-district');
                districtDropdown.innerHTML = '<option value="">--Select District--</option>';

                districts.forEach(district => {
                    districtsMap[district.id] = district.name;
                    let option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtDropdown.appendChild(option);
                });
            })
            .catch(error => console.error("🚨 Error fetching districts:", error));
        }

        function fetchWards(districtId) {
            if (!districtId) return;

            fetch(`/api/wards/${districtId}`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(wards => {
                let wardDropdown = document.getElementById('hotel-ward');
                wardDropdown.innerHTML = '<option value="">--Select Ward--</option>';

                wards.forEach(ward => {
                    wardsMap[ward.id] = ward.name;
                    let option = document.createElement('option');
                    option.value = ward.id;
                    option.textContent = ward.name;
                    wardDropdown.appendChild(option);
                });
            })
            .catch(error => console.error("🚨 Error fetching wards:", error));
        }
        function createHotel(event) {
                event.preventDefault(); // Ngăn form gửi dữ liệu mặc định

                // Reset lỗi
                document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

                // Hiển thị loading (có thể thêm icon xoay nếu muốn)
                document.getElementById('success-message').classList.add('d-none');
                document.getElementById('error-message').classList.add('d-none');

                // Lấy dữ liệu nhập vào từ form
                let hotelData = {
                    name: document.getElementById('hotel-name').value.trim(),
                    name_jp: document.getElementById('hotel-name-jp').value.trim() || null,
                    code: document.getElementById('hotel-code').value.trim(),
                    city_id: document.getElementById('hotel-city').value.trim(),
                    district_id: document.getElementById('hotel-district').value.trim(),
                    ward_id: document.getElementById('hotel-ward').value.trim(),
                    email: document.getElementById('hotel-email').value.trim(),
                    telephone: document.getElementById('hotel-telephone').value.trim(),
                    fax: document.getElementById('hotel-fax').value.trim() || null,
                    address_1: document.getElementById('hotel-address-1').value.trim(),
                    address_2: document.getElementById('hotel-address-2').value.trim() || null,
                    tax_code: document.getElementById('hotel-tax-code').value.trim(),
                    company_name: document.getElementById('hotel-company-name').value.trim()
                };

                // Kiểm tra các trường bắt buộc
                let requiredFields = {
                    "hotel-name": hotelData.name,
                    "hotel-code": hotelData.code,
                    "hotel-city": hotelData.city_id,
                    "hotel-address-1": hotelData.address_1,
                    "hotel-email": hotelData.email,
                    "hotel-telephone": hotelData.telephone,
                    "hotel-tax-code": hotelData.tax_code,
                    "hotel-company-name": hotelData.company_name
                };

                if (!validateForm(requiredFields)) {
                    return;
                }

                // Gửi request tạo khách sạn
                fetch('/api/hotels', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(hotelData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        document.getElementById('success-message').innerText = "Hotel created successfully!";
                        document.getElementById('success-message').classList.remove('d-none');

                        setTimeout(() => {
                            window.location.href = "{{ route('hotels.index') }}";
                        }, 1500);
                    } else {
                        document.getElementById('error-message').innerText = "Failed to create hotel.";
                        document.getElementById('error-message').classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error creating hotel:', error);
                    document.getElementById('error-message').innerText = "Error creating hotel.";
                    document.getElementById('error-message').classList.remove('d-none');
                });
            }


            // Kiểm tra dữ liệu trước khi gửi
            function validateForm(fields) {
                let isValid = true;

                for (let fieldId in fields) {
                    let fieldElement = document.getElementById(fieldId);
                    let errorElement = document.getElementById(`error-${fieldId}`);

                    if (!fields[fieldId]) {
                        errorElement.innerText = "This field is required.";
                        fieldElement.classList.add("is-invalid"); // Thêm viền đỏ vào input
                        isValid = false;
                    } else {
                        errorElement.innerText = "";
                        fieldElement.classList.remove("is-invalid");
                    }
                }

                return isValid;
            }
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("create-hotel-form").addEventListener("submit", function (event) {
                    event.preventDefault(); // Ngăn form submit ngay lập tức
                    createHotel(event);
                });
    });
    </script>
    @endsection
