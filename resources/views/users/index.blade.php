@extends('adminlte::page')

@section('title', 'User List')

@section('content_header')
    <h1>User List</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List of Users</h3>
                    <button class="btn btn-success float-right" onclick="openCreateUserModal()">Add New User</button>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <div class="row mb-3">
                        <div class="col-mx-3">
                            <select id="role-filter-dropdown" class="form-control">
                                <option value="">--Select Role--</option>
                            </select>
                        </div>
                        
                        <div class="col-mx-6">
                            <input type="text" id="search-email" class="form-control" placeholder="Search Email">
                        </div>
                        <div class="col-mx-6">
                            <input type="text" id="search-fullname" class="form-control" placeholder="Search Fullname">
                        </div>
                        <div class="col-mx-6">
                            <input type="text" id="search-username" class="form-control" placeholder="Search Username">
                        </div>
                        <div class="col-mx-6">
                            <button class="btn btn-primary" onclick="fetchUsers()">Search</button>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Birth Date</th>
                                <th>Action</th>
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

    @include('modals.create_user')
    @include('modals.view_user')
    @include('modals.edit_user')
    @include('modals.delete_user')

    <script>
        let currentPage = 1;
        let roles = [];  // Biến lưu danh sách các vai trò
        // Fetch roles from API
        function fetchRoles() {
            fetch('/api/roles', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    roles = data;
                    populateRoleDropdown();  // Cập nhật dropdown
                }
            })
            .catch(error => console.error('Error fetching roles:', error));
        }
        // Populate the role dropdown for searching and creating users
        function populateRoleDropdown() {
            let roleDropdown = document.getElementById('role-filter-dropdown');
            roleDropdown.innerHTML = '<option value="">--Select Role--</option>';  // Reset dropdown

            roles.forEach(role => {
                roleDropdown.innerHTML += `<option value="${role.id}">${role.name}</option>`;
            });

            // Cập nhật các role trong modal tạo người dùng
            let roleCreateDropdown = document.getElementById('role_id');
            roleCreateDropdown.innerHTML = '<option value="">--Select Role--</option>';
            roles.forEach(role => {
                roleCreateDropdown.innerHTML += `<option value="${role.id}">${role.name}</option>`;
            });

            // Cập nhật các role trong modal chỉnh sửa người dùng
            let roleEditDropdown = document.getElementById('edit_role_id');
            roleEditDropdown.innerHTML = '<option value="">--Select Role--</option>';
            roles.forEach(role => {
                roleEditDropdown.innerHTML += `<option value="${role.id}">${role.name}</option>`;
            });
        }
        // Hàm mở modal view thông tin người dùng
        function viewUser(id) {
            fetch(`/api/users/${id}`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.user) {
                    const user = data.user;
                    const roleName = user.role ? user.role.name : 'N/A';

                    // Chuyển đổi ngày sinh sang dd/mm/yyyy
                    const birthDate = user.day_of_birth 
                        ? new Date(user.day_of_birth).toLocaleDateString('vi-VN') 
                        : 'N/A';

                    // Kiểm tra avatar (hiển thị default nếu không có)
                    const avatarUrl = (user.avatar_url === 'default_avatar.png' || !user.avatar_url) 
                        ? '/images/default_avatar.png' 
                        : user.avatar_url;

                    // Hiển thị thông tin trong modal View User
                    document.getElementById('view_first_name').innerText = user.first_name;
                    document.getElementById('view_last_name').innerText = user.last_name;
                    document.getElementById('view_user_name').innerText = user.user_name;
                    document.getElementById('view_email').innerText = user.email;
                    document.getElementById('view_role').innerText = roleName;
                    document.getElementById('view_day_of_birth').innerText = birthDate; // Hiển thị dd/mm/yyyy
                    document.getElementById('view_avatar').src = avatarUrl; // Hiển thị avatar

                    // Mở modal view
                    $('#viewUserModal').modal('show');
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
        }
        function openCreateUserModal(){
            $('#createUserModal').modal('show');  // Đóng modal sau khi tạo thành công
        }
        // Fetch Users
        // Fetch Users from API and display them
        function fetchUsers() {
            let name = document.getElementById('search-fullname').value;
            let email = document.getElementById('search-email').value;
            let username = document.getElementById('search-username').value;
            let roleId = document.getElementById('role-filter-dropdown').value;

            let url = `/api/users?page=${currentPage}`;
            if (name) url += `&name=${name}`;
            if (email) url += `&email=${email}`;
            if (username) url += `&user_name=${username}`;
            if (roleId) url += `&role_id=${roleId}`;

            fetch(url, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(data => {
                let usersList = document.getElementById('users-list');
                usersList.innerHTML = '';

                if (!data.data || !Array.isArray(data.data) || data.data.length === 0) {
                    usersList.innerHTML = '<tr><td colspan="6">No data found</td></tr>';
                    return;
                }

                data.data.forEach(user => {
                    // Format ngày tháng năm
                    const formattedDate = formatDate(user.day_of_birth || user.created_at);

                    // Kiểm tra avatar, nếu là default thì hiển thị avatar mặc định

                    let roleName = user.role ? user.role.name : 'N/A';  // Kiểm tra nếu role có tồn tại
                    let fullName = user.first_name + ' ' + user.last_name;
                    
                    usersList.innerHTML += `
                        <tr>
                            <td>${fullName}</td>
                            <td>${user.user_name}</td>
                            <td>${user.email}</td>
                            <td>${roleName}</td>
                            <td>${formattedDate}</td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="viewUser(${user.id})">View</button>
                                <button class="btn btn-warning btn-sm" onclick="editUser(${user.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(${user.id}, '${fullName}')">Delete</button>
                            </td>
                        </tr>`;
                });

                document.getElementById('current-page').innerText = `Page ${data.pagination.current_page}`;
            })
            .catch(error => console.error('Error fetching users:', error));
        }
        // Helper function to format date (only date, no time)
        function formatDate(dateString) {
            if (!dateString) return ''; // Nếu không có ngày, trả về chuỗi rỗng

            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Đảm bảo 2 chữ số
            const day = String(date.getDate()).padStart(2, '0'); // Đảm bảo 2 chữ số

            return `${year}-${month}-${day}`; // Trả về định dạng YYYY-MM-DD
        }
        // Hàm mở modal chỉnh sửa thông tin người dùng
        function editUser(id) {
            fetch(`/api/users/${id}`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.user) {
                    const user = data.user;
                    const avatarUrl = (user.avatar_url === 'default_avatar.png' || !user.avatar_url) 
                        ? '/images/default_avatar.png' 
                        : user.avatar_url;
                    document.getElementById('edit_user_id').value = user.id;
                    document.getElementById('edit_first_name').value = user.first_name;
                    document.getElementById('edit_last_name').value = user.last_name;
                    document.getElementById('edit_user_name').value = user.user_name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_role_id').value = user.role_id;
                    document.getElementById('edit_avatar').src = avatarUrl;
                    // ⚡ Fix lỗi ngày tháng - Chuyển về `YYYY-MM-DD`
                    if (user.day_of_birth) {
                        let date = new Date(user.day_of_birth);
                        let formattedDate = date.toISOString().split('T')[0]; // Chuyển thành `YYYY-MM-DD`
                        document.getElementById('edit_day_of_birth').value = formattedDate;
                    } else {
                        document.getElementById('edit_day_of_birth').value = ''; // Nếu null, hiển thị rỗng
                    }
                    // Mở modal chỉnh sửa
                    $('#editUserModal').modal('show');
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
        }


        // Hàm lưu thông tin chỉnh sửa người dùng
        function saveUser() {
            let userId = document.getElementById('edit_user_id').value; // Kiểm tra xem có user_id hay không
            let url = userId ? `/api/users/${userId}` : "/api/users";
            let method = userId ? "PUT" : "POST";

            let userData = {
                first_name: document.getElementById('edit_first_name').value,
                last_name: document.getElementById('edit_last_name').value,
                user_name: document.getElementById('edit_user_name').value,
                email: document.getElementById('edit_email').value,
                day_of_birth: formatDate(document.getElementById('edit_day_of_birth').value), // Chuyển về yyyy-mm-dd
                role_id: document.getElementById('edit_role_id').value
            };

            // Xóa thông báo lỗi trước khi gửi request
            document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

            fetch(url, {
                method: method,
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(userData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    for (const [field, messages] of Object.entries(data.errors)) {
                        document.getElementById(`edit_${field}_error`).innerText = messages.join(', ');
                    }
                } else {
                    // Đóng modal sau khi thành công
                    $('#editUserModal').modal('hide');
                    document.getElementById('editUserForm').reset();
                    fetchUsers(); // Load lại danh sách user
                }
            })
            .catch(error => console.error("Error saving user:", error));
        }



        // Confirm Delete User
        function confirmDelete(id, name) {
            if (confirm(`Are you sure you want to delete ${name}?`)) {
                fetch(`/api/users/${id}`, { method: 'DELETE', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') } })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    fetchUsers();
                });
            }
        }

        // Change Page
        function changePage(direction) {
            currentPage += direction;
            fetchUsers();
        }

        // Load Users on Page Load
        document.addEventListener('DOMContentLoaded', function() {
            fetchUsers();
            fetchRoles(); // Fetch roles to populate dropdowns
        });
    </script>
@endsection
