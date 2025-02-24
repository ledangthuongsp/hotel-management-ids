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
                            <button class="btn btn-success float-right" onclick="window.location.href='{{ route('hotels.create') }}'">Add New Hotel</button>
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
                            <!-- Hiển thị thông báo -->
                            <div id="success-message" class="alert alert-success d-none mt-2"></div>
                            <div id="error-message" class="alert alert-danger d-none mt-2"></div>

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

    <!-- Modal Confirm Delete -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete Hotel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this hotel? Please type the hotel code <strong id="hotel-code-to-delete"></strong> to confirm.</p>
                    <input type="text" id="hotel-code-input" class="form-control" placeholder="Enter Hotel Code" required>
                    <small class="text-danger" id="error-message" style="display:none;">Hotel code does not match!</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteHotel()">Delete</button>
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
        let hotelToDelete = null; // Biến lưu thông tin khách sạn đang bị xóa
        let districtsMap = {}; // Lưu danh sách các quận/huyện theo ID
        let wardsMap = {};     // Lưu danh sách phường/xã theo ID
        let searchingFilters = {};
        let isSearching = false;

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
        // Fetch danh sách khách sạn
        function fetchHotels() {
            let url = isSearching
                ? `/api/hotels/search?page=${currentPage}&per_page=5`
                : `/api/hotels?page=${currentPage}&per_page=5`;

            if (isSearching) {
                if (searchFilters.code) url += `&code=${searchFilters.code}`;
                if (searchFilters.name) url += `&name=${searchFilters.name}`;
                if (searchFilters.city_id) url += `&city_id=${searchFilters.city_id}`;
            }

            fetch(url, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                let hotelsList = document.getElementById('hotels-list');
                hotelsList.innerHTML = '';

                if (!data.data || !Array.isArray(data.data) || data.data.length === 0) {
                    hotelsList.innerHTML = '<tr><td colspan="7">No results found</td></tr>';
                    return;
                }

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
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(${hotel.id}, '${hotel.code}')">Delete</button>
                        </td>
                    </tr>`;
                    hotelsList.innerHTML += row;
                });

                // Cập nhật số trang từ API
                document.getElementById('current-page').innerText = `Page ${data.pagination.current_page}`;
                
                // Kiểm tra xem có dữ liệu trang tiếp theo không
                let hasNextPage = data.pagination.current_page < data.pagination.last_page;

                // Kích hoạt / vô hiệu hóa nút Next / Previous
                document.getElementById('prev-page').disabled = (data.pagination.current_page === 1);
                document.getElementById('next-page').disabled = !hasNextPage;
            })
            .catch(error => {
                console.error('Search error:', error);
                document.getElementById('hotels-list').innerHTML = '<tr><td colspan="7">Error loading data</td></tr>';
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
            })
            .then(response => response.json())
            .then(hotel => {
                document.getElementById('edit-hotel-id').value = hotel.id;
                document.getElementById('edit-hotel-name').value = hotel.name;
                document.getElementById('edit-hotel-code').value = hotel.code;
                document.getElementById('edit-hotel-email').value = hotel.email;
                document.getElementById('edit-hotel-telephone').value = hotel.telephone;
                document.getElementById('edit-hotel-address-1').value = hotel.address_1;
                document.getElementById('edit-hotel-address-2').value = hotel.address_2 || "";
                document.getElementById('edit-hotel-tax-code').value = hotel.tax_code;
                document.getElementById('edit-hotel-company-name').value = hotel.company_name;
                document.getElementById('edit-hotel-fax').value = hotel.fax || "";
                document.getElementById('edit-hotel-name-jp').value = hotel.name_jp || "";

                // Fetch & populate city dropdown
                fetchCitiesForEdit(hotel.city_id);

                $('#editHotelModal').modal('show');
            })
            .catch(error => {
                console.error('Error fetching hotel:', error);
                alert('Failed to load hotel details');
            });
        }

        function fetchCitiesForEdit(selectedCityId) {
            fetch('/api/cities', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(cities => {
                let cityDropdown = document.getElementById('edit-hotel-city');
                cityDropdown.innerHTML = '<option value="">--Select City--</option>';

                cities.forEach(city => {
                    let option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    if (city.id === selectedCityId) {
                        option.selected = true;
                    }
                    cityDropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching cities:', error);
            });
        }
        // Lưu thông tin khách sạn sau khi chỉnh sửa
        function saveHotel() {
            let id = document.getElementById('edit-hotel-id').value;
            let user_id = localStorage.getItem('user_id'); // Lấy user_id từ localStorage
            if (!user_id) {
                alert("Error: User ID not found. Please log in again.");
                return;
            }

            let data = {
                name: document.getElementById('edit-hotel-name').value,
                code: document.getElementById('edit-hotel-code').value,
                user_id: user_id, // Gửi user_id
                city_id: document.getElementById('edit-hotel-city').value,
                email: document.getElementById('edit-hotel-email').value,
                telephone: document.getElementById('edit-hotel-telephone').value,
                address_1: document.getElementById('edit-hotel-address-1').value,
                tax_code: document.getElementById('edit-hotel-tax-code').value,
                company_name: document.getElementById('edit-hotel-company-name').value,
                address_2: document.getElementById('edit-hotel-address-2').value || null,
                fax: document.getElementById('edit-hotel-fax').value || null,
                name_jp: document.getElementById('edit-hotel-name-jp').value || null
            };

            fetch(`/api/hotels/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(() => {
                $('#editHotelModal').modal('hide');
                fetchHotels();
            })
            .catch(error => {
                console.error('Error updating hotel:', error);
            });
        }

        // Xóa khách sạn
        function confirmDelete(hotelId, hotelCode) {
            // Lưu thông tin khách sạn và mã khách sạn
            hotelToDelete = { id: hotelId, code: hotelCode };

            // Hiển thị mã khách sạn trong modal
            document.getElementById('hotel-code-to-delete').innerText = hotelCode;

            // Hiển thị modal
            $('#confirmDeleteModal').modal('show');
        }
        function confirmDeleteHotel() {
            const enteredCode = document.getElementById('hotel-code-input').value.trim();

            // Kiểm tra xem mã nhập vào có đúng không
            if (enteredCode === hotelToDelete.code) {
                // Gọi API xóa khách sạn
                fetch(`/api/hotels/${hotelToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert('Hotel deleted successfully!');
                        fetchHotels();  // Cập nhật lại danh sách khách sạn
                    } else {
                        alert('Failed to delete hotel!');
                    }
                    $('#confirmDeleteModal').modal('hide');  // Đóng modal
                })
                .catch(error => {
                    console.error('Error deleting hotel:', error);
                    alert('Error deleting hotel');
                    $('#confirmDeleteModal').modal('hide');
                });
            } else {
                // Hiển thị thông báo lỗi nếu mã không khớp
                document.getElementById('error-message').style.display = 'block';
            }
        }
        // Load dữ liệu khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            fetchCities();
            fetchHotels();
        });

        function viewHotel(hotelId) {
        // Gửi request đến API để lấy dữ liệu hotel
            fetch(`/api/hotels/${hotelId}`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }   
            })
            .then(response => {
                // Kiểm tra xem response có thành công không
                if (!response.ok) {
                    throw new Error('Failed to fetch hotel details');
                }
                return response.json(); // Chuyển response thành JSON
            })
            .then(hotel => {
                // Kiểm tra và hiển thị dữ liệu hotel vào các thẻ trong modal
                const cityName = citiesMap[hotel.city_id] || 'N/A';

                // Kiểm tra trước khi thay đổi các phần tử
                const elements = [
                    { id: 'view-hotel-city', value: cityName },
                    { id: 'view-hotel-code', value: hotel.code || 'N/A' },
                    { id: 'view-hotel-name-en', value: hotel.name || 'N/A' },
                    { id: 'view-hotel-name-jp', value: hotel.name_jp || 'N/A' },
                    { id: 'view-hotel-email', value: hotel.email || 'N/A' },
                    { id: 'view-hotel-telephone', value: hotel.telephone || 'N/A' },
                    { id: 'view-hotel-fax', value: hotel.fax || 'N/A' },
                    { id: 'view-hotel-address1', value: hotel.address_1 || 'N/A' },
                    { id: 'view-hotel-address2', value: hotel.address_2 || 'N/A' },
                    { id: 'view-hotel-company', value: hotel.company_name || 'N/A' },
                    { id: 'view-hotel-tax-code', value: hotel.tax_code || 'N/A' }
                ];

                // Cập nhật các phần tử
                elements.forEach(element => {
                    const el = document.getElementById(element.id);
                    if (el) {
                        el.innerText = element.value;
                    }
                });

                // Mở modal
                $('#viewHotelModal').modal('show');
            })
            .catch(error => {
                console.error('Error fetching hotel details:', error);
                alert('Failed to load hotel details');
            });
        }
        function searchHotels() {
            let cityId = document.getElementById('filter-city').value;
            let hotelCode = document.getElementById('filter-hotel-code').value;
            let hotelName = document.getElementById('filter-hotel-name').value;

            // Lưu trạng thái tìm kiếm vào biến toàn cục
            searchFilters = {
                city_id: cityId,
                code: hotelCode,
                name: hotelName
            };

            isSearching = true; // Đánh dấu đang tìm kiếm
            currentPage = 1; // Reset về trang 1 khi tìm kiếm mới
            fetchHotels();
        }

        function changePage(direction) {
            currentPage += direction;
            if (currentPage < 1) currentPage = 1;
            fetchHotels();
        }

    </script>
@endsection
