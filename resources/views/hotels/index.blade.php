@extends('adminlte::page')

@section('title', 'Hotel List')

@section('content_header')
    <h1>Hotel List</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Tab Content -->
            <div class="tab-content mt-3" id="tab-content">
                <!-- Hotels Tab -->
                <div class="tab-pane fade show active" id="hotels-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List of Hotels</h3>
                            <button class="btn btn-success float-right" onclick="openCreateHotelModal()">Add New Hotel</button>
                        </div>
                        <div class="card-body">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="dropdown">
                                        <input type="text" id="search-city" class="form-control" placeholder="Search City" oninput="filterCities()">
                                        <select id="filter-city" class="form-control">
                                            <option value="">--Select City--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="filter-hotel-code" class="form-control" placeholder="Hotel Code">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="filter-hotel-name" class="form-control" placeholder="Hotel Name">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary" onclick="searchHotels()">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>City</th>
                                        <th>Hotel Code</th>
                                        <th>Hotel Name</th>
                                        <th>Email</th>
                                        <th>Telephone</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="hotels-list">
                                    <tr><td colspan="7">Loading...</td></tr>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="mt-3">
                                <button id="prev-page" class="btn btn-secondary" onclick="changePage(-1)">Previous</button>
                                <span id="current-page" class="mx-3">Page 1</span>
                                <button id="next-page" class="btn btn-secondary" onclick="changePage(1)">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createHotelModal" tabindex="-1" role="dialog" aria-labelledby="createHotelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createHotelModalLabel">Add New Hotel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createHotelForm">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="hotel-name">Hotel Name</label>
                                <input type="text" class="form-control" id="hotel-name" placeholder="Enter Hotel Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hotel-code">Hotel Code</label>
                                <input type="text" class="form-control" id="hotel-code" placeholder="Enter Hotel Code">
                            </div>
                        </div>
    
                        <!-- City -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="hotel-city">City</label>
                                <select class="form-control" id="hotel-city">
                                    <option value="">--Select City--</option>
                                </select>
                            </div>
    
                            <!-- District -->
                            <div class="form-group col-md-6">
                                <label for="hotel-district">District</label>
                                <select class="form-control" id="hotel-district">
                                    <option value="">--Select District--</option>
                                </select>
                            </div>
                        </div>
    
                        <!-- Ward -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="hotel-ward">Ward</label>
                                <select class="form-control" id="hotel-ward">
                                    <option value="">--Select Ward--</option>
                                </select>
                            </div>
                        </div>
    
                        <!-- Other Fields -->
                        <div class="form-group">
                            <label for="hotel-address-1">Address (Street + Number)</label>
                            <input type="text" class="form-control" id="hotel-address-1" placeholder="Enter Street Number and Name">
                        </div>
    
                        <div class="form-group">
                            <label for="hotel-address-2">Address (Optional)</label>
                            <input type="text" class="form-control" id="hotel-address-2" placeholder="Enter Additional Address">
                        </div>
    
                        <div class="form-group">
                            <label for="hotel-email">Email</label>
                            <input type="email" class="form-control" id="hotel-email" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label for="hotel-telephone">Telephone</label>
                            <input type="text" class="form-control" id="hotel-telephone" placeholder="Enter Telephone">
                        </div>
                        <div class="form-group">
                            <label for="hotel-fax">Fax (Optional)</label>
                            <input type="text" class="form-control" id="hotel-fax" placeholder="Enter Fax">
                        </div>
                        <div class="form-group">
                            <label for="hotel-name-jp">Hotel Name (Japanese)</label>
                            <input type="text" class="form-control" id="hotel-name-jp" placeholder="Enter Hotel Name in Japanese">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="createHotel()">Save Hotel</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modals -->
    @include('modals.view_hotel')
    @include('modals.edit_hotel')

    <script>
        let currentPage = 1;
        let citiesMap = {}; // Chứa thông tin các thành phố

        // Fetch danh sách thành phố
        // Hàm fetch danh sách thành phố và cập nhật dropdown
        function fetchCitiesForModal() {
            fetch('/api/cities', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch cities');
                }
                return response.json();
            })
            .then(cities => {
                const cityDropdown = document.getElementById('hotel-city');
                cityDropdown.innerHTML = '<option value="">--Select City--</option>';
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    cityDropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching cities:', error);
                alert('Unable to load cities. Please try again later.');
            });
        }
        // Mở modal và gọi hàm fetchCitiesForModal
        function openCreateHotelModal() {
            fetchCitiesForModal();
            $('#createHotelModal').modal('show');
        }
        function fetchCities() {
            console.log("🔍 Fetching cities...");

            fetch('/api/cities', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch cities');
                }
                return response.json();
            })
            .then(cities => {
                console.log("📌 Cities received:", cities);

                let filterCityDropdown = document.getElementById('filter-city');
                filterCityDropdown.innerHTML = '<option value="">--Select City--</option>';
                if (!Array.isArray(cities) || cities.length === 0) {
                    console.warn("⚠️ No cities found!");
                    return;
                }
                    
                cities.forEach(city => {
                    citiesMap[city.id] = city.name; // Lưu thành phố vào cityMap
                    let option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    filterCityDropdown.appendChild(option); // Thêm thành phố vào dropdown
                });

                console.log("🎯 Dropdowns updated successfully!");
            })
            .catch(error => {
                console.error("🚨 Error fetching cities:", error);
            });
        }
        // 🟢 Khi chọn City, tự động fetch danh sách Districts
        document.getElementById('hotel-city').addEventListener('change', function () {
            fetchDistricts(this.value);
        });

        // 🟢 Khi chọn District, tự động fetch danh sách Wards
        document.getElementById('hotel-district').addEventListener('change', function () {
            fetchWards(this.value);
        });

         // 🔵 Fetch danh sách Districts theo City ID
         function fetchDistricts(cityId) {
            if (!cityId) return; // Nếu chưa chọn City thì không fetch

            fetch(`/api/districts/${cityId}`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(districts => {
                console.log("📌 Districts received:", districts);

                let districtDropdown = document.getElementById('hotel-district');
                districtDropdown.innerHTML = '<option value="">--Select District--</option>';

                districts.forEach(district => {
                    let option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtDropdown.appendChild(option);
                });

                console.log("✅ Districts updated successfully!");
            })
            .catch(error => {
                console.error("🚨 Error fetching districts:", error);
            });
        }

        // 🔵 Fetch danh sách Wards theo District ID
        function fetchWards(districtId) {
            if (!districtId) return; // Nếu chưa chọn District thì không fetch

            fetch(`/api/wards/${districtId}`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(wards => {
                console.log("📌 Wards received:", wards);

                let wardDropdown = document.getElementById('hotel-ward');
                wardDropdown.innerHTML = '<option value="">--Select Ward--</option>';

                wards.forEach(ward => {
                    let option = document.createElement('option');
                    option.value = ward.id;
                    option.textContent = ward.name;
                    wardDropdown.appendChild(option);
                });

                console.log("✅ Wards updated successfully!");
            })
            .catch(error => {
                console.error("🚨 Error fetching wards:", error);
            });
        }

        function filterCities() {
            let searchTerm = document.getElementById('search-city').value.toLowerCase(); // Lấy từ khóa tìm kiếm
            let cityDropdown = document.getElementById('filter-city');
            
            // Reset lại các option
            cityDropdown.innerHTML = '<option value="">--Select City--</option>';
            
            // Lọc thành phố theo từ khóa tìm kiếm
            Object.keys(citiesMap).forEach(cityId => {
                let cityName = citiesMap[cityId].toLowerCase();
                if (cityName.includes(searchTerm)) {
                    let option = document.createElement('option');
                    option.value = cityId;
                    option.textContent = citiesMap[cityId];
                    cityDropdown.appendChild(option);
                }
            });
        }
        // Tạo khách sạn mới
        function createHotel() {
            // Lấy giá trị từ các input field, kiểm tra nếu null thì gán chuỗi rỗng
            let name = document.getElementById('hotel-name').value.trim() || '';
            let name_jp = document.getElementById('hotel-name-jp').value.trim() || '';
            let code = document.getElementById('hotel-code').value.trim() || '';
            let cityId = document.getElementById('hotel-city').value.trim() || '';
            let districtId = document.getElementById('hotel-district').value.trim() || '';
            let wardId = document.getElementById('hotel-ward').value.trim() || '';
            let email = document.getElementById('hotel-email').value.trim() || '';
            let telephone = document.getElementById('hotel-telephone').value.trim() || '';
            let streetAddress = document.getElementById('hotel-address-1').value.trim() || '';
            let address_2 = document.getElementById('hotel-address-2').value.trim() || '';
            let fax = document.getElementById('hotel-fax').value.trim() || '';

            // Kiểm tra nếu các trường bắt buộc bị thiếu
            if (!name || !name_jp || !code || !cityId || !districtId || !wardId || !email || !telephone || !streetAddress) {
                alert("Please fill in all required fields.");
                return;
            }

            // Ghép địa chỉ đầy đủ: "Số nhà, đường + Phường/Xã + Quận/Huyện + Tỉnh/Thành phố"
            let fullAddress = `${streetAddress}, ${citiesMap[wardId] || 'Ward'}, ${citiesMap[districtId] || 'District'}, ${citiesMap[cityId] || 'City'}`;

            // Gửi thông tin khách sạn mới tới API
            fetch('/api/hotels', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    name_jp: name_jp,  // Bắt buộc nhập
                    code: code,
                    city: cityId,
                    email: email,
                    telephone: telephone,
                    address_1: fullAddress,  // Sử dụng địa chỉ đầy đủ
                    address_2: address_2, 
                    fax: fax  
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert('Hotel created successfully!');
                    $('#createHotelModal').modal('hide');  // Đóng modal sau khi tạo thành công
                    fetchHotels();  // Tải lại danh sách khách sạn
                } else {
                    alert('Failed to create hotel');
                }
            })
            .catch(error => {
                console.error('Error creating hotel:', error);
                alert('Error creating hotel');
            });
        }


        // Fetch danh sách khách sạn
        function fetchHotels() {
            let city = document.getElementById('filter-city').value;
            let hotelCode = document.getElementById('filter-hotel-code').value;
            let hotelName = document.getElementById('filter-hotel-name').value;

            let url = `/api/hotels?page=${currentPage}&per_page=5`;

            if (city) url += `&city=${city}`;
            if (hotelCode) url += `&code=${hotelCode}`;
            if (hotelName) url += `&name=${hotelName}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error fetching data: ' + response.status);
                }
                return response.json(); // Chuyển dữ liệu thành JSON
            })
            .then(data => {
                let hotelsList = document.getElementById('hotels-list');
                hotelsList.innerHTML = '';

                // Kiểm tra nếu dữ liệu không có hoặc data.data không phải là mảng
                if (!data.data || !Array.isArray(data.data) || data.data.length === 0) {
                    hotelsList.innerHTML = '<tr><td colspan="7">No data to show</td></tr>';
                    return;
                }

                // Duyệt qua từng khách sạn trong data.data và hiển thị thông tin
                data.data.forEach(hotel => {
                    let cityName = citiesMap[hotel.city_id] || 'Unknown';

                    let row = `<tr>
                        <td>${cityName}</td>
                        <td><span class="badge badge-info">${hotel.code}</span></td>
                        <td>${hotel.name}</td>
                        <td>${hotel.email}</td>
                        <td>${hotel.telephone}</td>
                        <td>${hotel.address_1}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="viewHotel(${hotel.id})">View</button>
                            <button class="btn btn-warning btn-sm" onclick="editHotel(${hotel.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(${hotel.id})">Delete</button>
                        </td>
                    </tr>`;
                    hotelsList.innerHTML += row;
                });

                // Cập nhật trang hiện tại
                document.getElementById('current-page').innerText = `Page ${data.pagination.current_page}`;
                document.getElementById('prev-page').disabled = (data.pagination.current_page === 1);
                document.getElementById('next-page').disabled = (data.pagination.current_page === data.pagination.last_page);
            })
            .catch(error => {   
                console.error('Error fetching hotels:', error);
                document.getElementById('hotels-list').innerHTML = '<tr><td colspan="7">Failed to load data</td></tr>';
            });
        }

        // Chỉnh sửa khách sạn
        function editHotel(id) {
            fetch(`/api/hotels/${id}`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.status !== 200) {
                    throw new Error('Failed to fetch hotel data');
                }
                return response.json();
            })
            .then(hotel => {
                // Cập nhật các trường trong modal với dữ liệu khách sạn
                document.getElementById('edit-hotel-id').value = hotel.id;
                document.getElementById('edit-hotel-name').value = hotel.name;
                document.getElementById('edit-hotel-name-jp').value = hotel.name_jp;
                document.getElementById('edit-hotel-code').value = hotel.code;
                document.getElementById('edit-hotel-user-id').value = hotel.user_id;
                document.getElementById('edit-hotel-city-id').value = hotel.city_id;
                document.getElementById('edit-hotel-email').value = hotel.email;
                document.getElementById('edit-hotel-telephone').value = hotel.telephone;
                document.getElementById('edit-hotel-fax').value = hotel.fax;
                document.getElementById('edit-hotel-address-1').value = hotel.address_1;
                document.getElementById('edit-hotel-address-2').value = hotel.address_2;

                // Mở modal
                $('#editHotelModal').modal('show');
            })
            .catch(error => {
                alert(error.message);
                console.error('Error:', error);
            });
        }
                // Lưu thông tin khách sạn sau khi chỉnh sửa
        function saveHotel() {
            let id = document.getElementById('edit-hotel-id').value;
            let name = document.getElementById('edit-hotel-name').value;
            let name_jp = document.getElementById('edit-hotel-name-jp').value;
            let code = document.getElementById('edit-hotel-code').value;
            let user_id = document.getElementById('edit-hotel-user-id').value;
            let city_id = document.getElementById('edit-hotel-city-id').value;
            let email = document.getElementById('edit-hotel-email').value;
            let telephone = document.getElementById('edit-hotel-telephone').value;
            let fax = document.getElementById('edit-hotel-fax').value;
            let address_1 = document.getElementById('edit-hotel-address-1').value;
            let address_2 = document.getElementById('edit-hotel-address-2').value;

            fetch(`/api/hotels/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name, 
                    name_jp, 
                    code, 
                    user_id, 
                    city_id, 
                    email, 
                    telephone, 
                    fax, 
                    address_1, 
                    address_2
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(error => { // Nếu có lỗi, trả lại thông tin lỗi
                        throw new Error(error.message || "Failed to update hotel.");
                    });
                }
                return response.json(); // Chuyển đổi dữ liệu nếu thành công
            })
            .then(data => {
                // Đóng modal sau khi thành công
                $('#editHotelModal').modal('hide');  // Đóng modal

                // Đảm bảo modal đã đóng rồi mới reload danh sách khách sạn
                setTimeout(function() {
                    // Cập nhật lại danh sách khách sạn
                    fetchHotels();  // Reload danh sách khách sạn để hiển thị thông tin mới
                }, 500);  // Delay 500ms để modal đóng hoàn toàn
            })
            .catch(error => { 
                // Hiển thị thông báo lỗi nếu có lỗi
                alert('Error updating hotel: ' + error.message);
                console.error(error);
            });
        }
        // Xóa khách sạn
        function confirmDelete(hotelId) {
            if (confirm("Are you sure you want to delete this hotel?")) {
                fetch(`/api/hotels/${hotelId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json'
                    }
                }).then(() => fetchHotels());
            }
        }

        // Load dữ liệu khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            fetchCities();
            fetchHotels();
        });

        function searchHotels() {
            let cityId = document.getElementById('filter-city').value;
            let hotelCode = document.getElementById('filter-hotel-code').value;
            let hotelName = document.getElementById('filter-hotel-name').value;

            // Kiểm tra nếu tất cả các ô tìm kiếm đều trống
            if (!cityId && !hotelCode && !hotelName) {
                fetchHotels();  // Hàm tải lại danh sách khách sạn mặc định
                return;
            }

            let url = '/api/hotels/search?';

            // Thêm các tham số vào URL nếu có điều kiện tìm kiếm
            if (hotelCode) url += `code=${hotelCode}&`;
            if (hotelName) url += `name=${hotelName}&`;
            if (cityId) url += `city_id=${cityId}&`;

            // Loại bỏ dấu `&` cuối cùng nếu có
            url = url.slice(0, -1); // Loại bỏ dấu `&` cuối cùng nếu nó tồn tại

            fetch(url, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                // Kiểm tra nếu API trả về thành công
                if (!response.ok) {
                    console.error('API Error:', response.status, response.statusText);
                    throw new Error(`Failed to load data from API, Status: ${response.status}, StatusText: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log(data); // Kiểm tra dữ liệu trả về

                let hotelsList = document.getElementById('hotels-list');
                hotelsList.innerHTML = '';

                // Kiểm tra nếu dữ liệu không có hoặc data.data không phải là mảng
                if (!data || !Array.isArray(data) || data.length === 0) {
                    hotelsList.innerHTML = '<tr><td colspan="7">No data to show</td></tr>';
                    return;
                }

                // Duyệt qua từng khách sạn trong data và hiển thị thông tin
                data.forEach(hotel => {
                    let cityName = citiesMap[hotel.city_id] || 'Unknown';

                    let row = `<tr>
                        <td>${cityName}</td>
                        <td><span class="badge badge-info">${hotel.code}</span></td>
                        <td>${hotel.name}</td>
                        <td>${hotel.email}</td>
                        <td>${hotel.telephone}</td>
                        <td>${hotel.address_1}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="viewHotel(${hotel.id})">View</button>
                            <button class="btn btn-warning btn-sm" onclick="editHotel(${hotel.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(${hotel.id})">Delete</button>
                        </td>
                    </tr>`;
                    hotelsList.innerHTML += row;
                });

                // Kiểm tra nếu có dữ liệu phân trang (pagination) trước khi sử dụng
                if (data.pagination) {
                    document.getElementById('current-page').innerText = `Page ${data.pagination.current_page}`;
                    document.getElementById('prev-page').disabled = (data.pagination.current_page === 1);
                    document.getElementById('next-page').disabled = (data.pagination.current_page === data.pagination.last_page);
                } else {
                    console.warn('Pagination data is missing!');
                }
            })
        }
    </script>
@endsection
