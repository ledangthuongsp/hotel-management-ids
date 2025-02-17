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
                <li class="nav-item">
                    <a class="nav-link" id="users-tab" data-toggle="tab" href="#users-tab-pane">User List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile-tab-pane">Profile Settings</a>
                </li>
            </ul>

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

                <!-- User List Tab -->
                <div class="tab-pane fade" id="users-tab-pane">
                    <h3>User List</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="users-list">
                            <tr><td colspan="4">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Profile Settings Tab -->
                <div class="tab-pane fade" id="profile-tab-pane">
                    <h3>Profile Settings</h3>
                    <!-- Add profile settings form here -->
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
            fetch(`/api/hotels/${id}`)
                .then(response => {
                    if (response.status !== 200) {
                        throw new Error('Failed to fetch hotel data');
                    }
                    return response.json();
                })
                .then(hotel => {
                    document.getElementById('edit-hotel-id').value = hotel.id;
                    document.getElementById('edit-hotel-name').value = hotel.name;
                    document.getElementById('edit-hotel-email').value = hotel.email;
                    document.getElementById('edit-hotel-telephone').value = hotel.telephone;
                    document.getElementById('edit-hotel-address').value = hotel.address_1;
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
            let email = document.getElementById('edit-hotel-email').value;
            let telephone = document.getElementById('edit-hotel-telephone').value;
            let address = document.getElementById('edit-hotel-address').value;

            fetch(`/api/hotels/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name, email, telephone, address })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Hotel updated successfully!');
                    $('#editHotelModal').modal('hide');
                    fetchHotels();
                } else {
                    alert('Failed to update hotel!');
                }
            })
            .catch(error => {
                alert('Error updating hotel!');
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
