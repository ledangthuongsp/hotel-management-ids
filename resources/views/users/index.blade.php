@extends('adminlte::page')

@section('title', 'User List')

@section('content_header')
    <h1>User List</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Tab Content -->
            <div class="tab-content mt-3" id="tab-content">
                <!-- User Tab -->
                <div class="tab-pane fade show active" id="users-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List of Users</h3>
                            <button class="btn btn-success float-right" onclick="openCreateUserModal()">Add New User</button>
                        </div>
                        <div class="card-body">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="dropdown">
                                        <select id="filter-role" class="form-control">
                                            <option value="">--Select Role--</option>
                                            <!-- Dynamically fill roles from backend -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="filter-user-name" class="form-control" placeholder="Name">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary" onclick="searchUsers()">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-list">
                                    <tr><td colspan="6">Loading...</td></tr>
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

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" class="form-control" id="first-name" placeholder="Enter first name">
                            <small id="first-name-error" class="form-text text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" class="form-control" id="last-name" placeholder="Enter last name">
                            <small id="last-name-error" class="form-text text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email">
                            <small id="email-error" class="form-text text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter password">
                            <small id="password-error" class="form-text text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role">
                                <option value="">--Select Role--</option>
                                <!-- Dynamically fill roles -->
                            </select>
                            <small id="role-error" class="form-text text-danger"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()">Save User</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;

        function openCreateUserModal() {
            $('#createUserModal').modal('show');
            fetchRoles(); // Load roles dynamically
        }
        // Fetch danh sách người dùng
        function fetchUsers() {
            let role = document.getElementById('filter-role').value;
            let userName = document.getElementById('filter-user-name').value;

            // Khởi tạo URL với các tham số tìm kiếm
            let url = `/api/users?page=${currentPage}&per_page=5`;

            if (role) url += `&role=${role}`;
            if (userName) url += `&name=${userName}`;

            // Gửi yêu cầu fetch
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
                let usersList = document.getElementById('users-list');
                usersList.innerHTML = '';

                // Kiểm tra nếu dữ liệu không có hoặc data.data không phải là mảng
                if (!data.data || !Array.isArray(data.data) || data.data.length === 0) {
                    usersList.innerHTML = '<tr><td colspan="6">No users found</td></tr>';
                    return;
                }

                // Duyệt qua từng người dùng trong data.data và hiển thị thông tin
                data.data.forEach(user => {
                    let roleName = user.role ? user.role.name : 'No role'; // Hiển thị tên vai trò

                    let row = `<tr>
                                <td>${user.id}</td>
                                <td>${user.first_name} ${user.last_name}</td>
                                <td>${user.user_name}</td>
                                <td>${user.email}</td>
                                <td>${roleName}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" onclick="viewUser(${user.id})">View</button>
                                    <button class="btn btn-warning btn-sm" onclick="editUser(${user.id})">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                                </td>
                            </tr>`;
                    usersList.innerHTML += row;
                });

                // Cập nhật phân trang
                document.getElementById('current-page').innerText = `Page ${data.pagination.current_page}`;
                document.getElementById('prev-page').disabled = (data.pagination.current_page === 1);
                document.getElementById('next-page').disabled = (data.pagination.current_page === data.pagination.last_page);
            })
            .catch(error => {   
                console.error('Error fetching users:', error);
                document.getElementById('users-list').innerHTML = '<tr><td colspan="6">Failed to load data</td></tr>';
            });
        }


        // Call the fetchUsers function to load data on page load
        document.addEventListener('DOMContentLoaded', function () {
            fetchUsers();
        });


        function fetchRoles() {
            fetch('/api/roles')
                .then(response => response.json())
                .then(data => {
                    const roleSelect = document.getElementById('role');
                    roleSelect.innerHTML = '<option value="">--Select Role--</option>';
                    data.forEach(role => {
                        const option = document.createElement('option');
                        option.value = role.id;
                        option.textContent = role.name;
                        roleSelect.appendChild(option);
                    });
                });
        }

        function searchUsers() {
            const role = document.getElementById('filter-role').value;
            const name = document.getElementById('filter-user-name').value;
            const url = `/api/users?role=${role}&name=${name}&page=${currentPage}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const usersList = document.getElementById('users-list');
                    usersList.innerHTML = '';
                    data.users.forEach(user => {
                        const row = `<tr>
                                        <td>${user.id}</td>
                                        <td>${user.first_name} ${user.last_name}</td>
                                        <td>${user.user_name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.role}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" onclick="viewUser(${user.id})">View</button>
                                            <button class="btn btn-warning btn-sm" onclick="editUser(${user.id})">Edit</button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                                        </td>
                                    </tr>`;
                        usersList.innerHTML += row;
                    });

                    document.getElementById('current-page').textContent = `Page ${data.pagination.current_page}`;
                    document.getElementById('prev-page').disabled = data.pagination.current_page === 1;
                    document.getElementById('next-page').disabled = data.pagination.current_page === data.pagination.last_page;
                });
        }

        function saveUser() {
            const form = document.getElementById('createUserForm');
            const formData = new FormData(form);
            const user = Object.fromEntries(formData);

            fetch('/api/users', {
                method: 'POST',
                body: JSON.stringify(user),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    handleFormErrors(data.errors);
                } else {
                    alert('User created successfully');
                    $('#createUserModal').modal('hide');
                    searchUsers();
                }
            })
            .catch(error => {
                console.error(error);
                alert('Error saving user');
            });
        }

        function handleFormErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorMessage = errors[field][0];
                document.getElementById(`${field}-error`).textContent = errorMessage;
            });
        }

        function viewUser(id) {
            alert('Viewing user ' + id);
        }

        function editUser(id) {
            alert('Editing user ' + id);
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/api/users/${id}`, { method: 'DELETE' })
                    .then(response => {
                        if (response.ok) {
                            alert('User deleted successfully');
                            searchUsers();
                        } else {
                            alert('Error deleting user');
                        }
                    })
                    .catch(error => console.error(error));
            }
        }
    </script>
@endsection
