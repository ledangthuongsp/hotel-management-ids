@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Tab Menu -->
            <ul class="nav nav-tabs" id="tab-menu">
                <li class="nav-item">
                    <a class="nav-link active" id="hotels-tab" data-toggle="tab" href="#hotels-tab-pane">Hotels</a>
                </li>
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
                                        <input type="text" id="search-city" class="form-control" placeholder="Search City" onkeyup="fetchHotels()">
                                        <select id="filter-city" class="form-control">
                                            <option value="">--Select City--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="filter-hotel-code" class="form-control" placeholder="Hotel Code" onkeyup="fetchHotels()">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="filter-hotel-name" class="form-control" placeholder="Hotel Name" onkeyup="fetchHotels()">
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

    <!-- Modals -->
    @include('modals.view_hotel')
    @include('modals.create_hotel')
    @include('modals.edit_hotel')

    <script>
        let currentPage = 1;
        let citiesMap = {}; // Chứa danh sách thành phố

        // Fetch danh sách thành phố
        function fetchCities() {
            fetch('/api/cities')
                .then(response => response.json())
                .then(cities => {
                    let cityDropdown = document.getElementById('filter-city');
                    cityDropdown.innerHTML = '<option value="">--Select City--</option>';
                    cities.forEach(city => {
                        citiesMap[city.id] = city.name; // Lưu vào object map
                        let option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        cityDropdown.appendChild(option);
                    });
                });
        }
        // Tạo khách sạn mới
        function openCreateHotelModal() {
            $('#createHotelModal').modal('show');  // Mở modal khi bấm vào nút
        }
        // Tạo khách sạn mới
        function createHotel() {
            // Lấy giá trị từ các input field, kiểm tra nếu null thì gán chuỗi rỗng
            let name = document.getElementById('hotel-name').value || '';
            let code = document.getElementById('hotel-code').value || '';
            let city = document.getElementById('hotel-city').value || '';
            let email = document.getElementById('hotel-email').value || '';
            let telephone = document.getElementById('hotel-telephone').value || '';
            let address_1 = document.getElementById('hotel-address-1').value || '';
            let address_2 = document.getElementById('hotel-address-2').value || '';
            let fax = document.getElementById('hotel-fax').value || '';
            let name_jp = document.getElementById('hotel-name-jp').value || ''; // Nếu name_jp null thì gán chuỗi rỗng

            // Kiểm tra xem các trường bắt buộc có rỗng không
            if (!name || !code || !city || !email || !telephone || !address_1) {
                alert("Please fill in all required fields.");
                return;
            }

            // Gửi thông tin khách sạn mới tới API
            fetch('/api/hotels', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    code: code,
                    city: city,
                    email: email,
                    telephone: telephone,
                    address_1: address_1,
                    address_2: address_2,  // Có thể là null hoặc chuỗi rỗng
                    fax: fax,               // Có thể là null hoặc chuỗi rỗng
                    name_jp: name_jp,       // Có thể là null hoặc chuỗi rỗng
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
                // Kiểm tra nếu phản hồi không phải là JSON hợp lệ
                if (!response.ok) {
                    throw new Error('Error fetching data: ' + response.status);
                }
                return response.json(); // Chuyển dữ liệu thành JSON
            })
            .then(data => {
                let hotelsList = document.getElementById('hotels-list');
                hotelsList.innerHTML = '';

                if (data.data.length === 0) {
                    hotelsList.innerHTML = '<tr><td colspan="7">No data to show</td></tr>';
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
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(${hotel.id})">Delete</button>
                        </td>
                    </tr>`;
                    hotelsList.innerHTML += row;
                });

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
    </script>
@endsection
