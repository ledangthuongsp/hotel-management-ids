@extends('adminlte::page')

@section('title', 'Profile Settings')

@section('content_header')
    <h1>Profile Settings</h1>
@endsection

@section('content')
    <div id="loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">User Profile</div>
                <div class="card-body">
                    <div id="successMessage" class="alert alert-success" style="display: none;">Profile updated successfully!</div>
                    <!-- View Mode -->
                    <div id="viewMode">
                        <div class="text-center mb-3">
                            <img id="avatar_preview" src="/images/default_avatar.png" class="rounded-circle" width="200" height="200">
                        </div>
                        <p><strong>First Name:</strong> <span id="view_first_name"></span></p>
                        <p><strong>Last Name:</strong> <span id="view_last_name"></span></p>
                        <p><strong>Username:</strong> <span id="view_user_name"></span></p>
                        <p><strong>Email:</strong> <span id="view_email"></span></p>
                        <p><strong>Day of Birth:</strong> <span id="view_day_of_birth"></span></p>
                        <p><strong>Role:</strong> <span id="view_role"></span></p>
                        <button class="btn btn-warning" onclick="toggleEditMode(true)">Edit</button>
                    </div>

                    <!-- Edit Mode -->
                    <form id="editMode" style="display: none;">
                        <div class="text-center mb-3">
                            <img id="edit_avatar_preview" src="/images/default_avatar.png" class="rounded-circle" width="100" height="100">
                            <br>
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="mt-2">
                        </div>
                        <div class="form-group">
                            <label for="edit_first_name">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name">
                            <small id="error_first_name" class="text-danger error-message"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_last_name">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name">
                            <small id="error_last_name" class="text-danger error-message"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_user_name">Username</label>
                            <input type="text" class="form-control" id="edit_user_name" name="user_name">
                            <small id="error_user_name" class="text-danger error-message"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                            <small id="error_email" class="text-danger error-message"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_day_of_birth">Date of Birth</label>
                            <input type="date" class="form-control" id="edit_day_of_birth" name="day_of_birth">
                            <small id="error_day_of_birth" class="text-danger error-message"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_role_id">Role</label>
                            <input type="text" class="form-control" id="edit_role_id" readonly> <!-- Chỉ hiển thị -->
                            <input type="hidden" name="role_id" id="edit_role_hidden"> <!-- Gửi role_id -->
                        </div>
                        
                        <button type="button" class="btn btn-secondary" onclick="toggleEditMode(false)">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
                        <!-- Loading Spinner -->
                        <div id="loading" class="spinner-border text-primary mt-3" role="status" style="display: none;">
                            <span class="sr-only">Saving...</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchProfileForView();
        });

        function formatDate(dateString) {
            if (!dateString) return ''; 
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return '';

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function fetchProfileForView() {
            fetch('/api/profile', {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('avatar_preview').src = data.avatar_url ? data.avatar_url : '/images/default_avatar.png';
                document.getElementById('view_first_name').innerText = data.first_name || 'N/A';
                document.getElementById('view_last_name').innerText = data.last_name || 'N/A';
                document.getElementById('view_user_name').innerText = data.user_name || 'N/A';
                document.getElementById('view_email').innerText = data.email || 'N/A';
                document.getElementById('view_day_of_birth').innerText = data.day_of_birth ? formatDate(data.day_of_birth) : 'N/A';
                document.getElementById('view_role').innerText = data.role || 'N/A'; // ✅ Hiển thị tên Role
            })
            .catch(error => console.error('Error fetching profile:', error));
        }


        function fetchProfileForEdit() {
            fetch('/api/profile', {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_avatar_preview').src = data.avatar_url || '/images/default_avatar.png';
                document.getElementById('edit_first_name').value = data.first_name;
                document.getElementById('edit_last_name').value = data.last_name;
                document.getElementById('edit_user_name').value = data.user_name;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_day_of_birth').value = formatDate(data.day_of_birth);
                document.getElementById('edit_role_id').value = data.role;
                document.getElementById('edit_role_hidden').value = data.role_id;
            })
            .catch(error => console.error('Error fetching profile:', error));
        }
        function toggleEditMode(editing) {
            // Hiển thị hoặc ẩn chế độ Edit/View
            document.getElementById('viewMode').style.display = editing ? 'none' : 'block';
            document.getElementById('editMode').style.display = editing ? 'block' : 'none';

            if (editing) {
                // Nếu chuyển sang chế độ Edit, fetch dữ liệu để hiển thị
                fetchProfileForEdit();

                // Xóa toàn bộ thông báo lỗi trước khi chỉnh sửa
                document.querySelectorAll('.error-message').forEach(el => el.innerText = '');
            } else {
                // Nếu bấm Cancel, reset input file và preview avatar
                document.getElementById('avatar').value = ''; // Clear input file
                document.getElementById('edit_avatar_preview').src = '/images/default_avatar.png'; // Reset preview avatar
            }
        }
        async function saveProfile() {
            try {
                // Hiển thị loading
                document.getElementById('loading').style.display = 'block';

                // Kiểm tra xem có file avatar mới không
                const avatarFile = document.getElementById('avatar').files[0];
                let avatarUrl = null;

                // Nếu có file avatar mới, upload lên server
                if (avatarFile) {
                    const formData = new FormData();
                    formData.append('avatar', avatarFile);

                    const uploadResponse = await fetch('/api/profile/avatar', {
                        method: 'POST',
                        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                        body: formData,
                    });

                    if (!uploadResponse.ok) {
                        throw await uploadResponse.json();
                    }

                    const uploadData = await uploadResponse.json();
                    avatarUrl = uploadData.avatar_url; // Lấy URL avatar mới
                }

                // Chuẩn bị dữ liệu profile để cập nhật
                const profileData = {
                    first_name: document.getElementById('edit_first_name').value,
                    last_name: document.getElementById('edit_last_name').value,
                    user_name: document.getElementById('edit_user_name').value,
                    email: document.getElementById('edit_email').value,
                    day_of_birth: document.getElementById('edit_day_of_birth').value,
                    role_id: document.getElementById('edit_role_hidden').value,
                    avatar_url: avatarUrl, // Thêm avatar_url nếu có
                };

                // Gọi API cập nhật profile
                const profileResponse = await fetch('/api/profile', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(profileData),
                });

                if (!profileResponse.ok) {
                    throw await profileResponse.json();
                }

                const profileDataResponse = await profileResponse.json();

                // Clear input file và preview avatar sau khi lưu thành công
                document.getElementById('avatar').value = ''; // Clear input file
                document.getElementById('edit_avatar_preview').src = '/images/default_avatar.png'; // Reset preview avatar

                // Cập nhật giao diện sau khi lưu thành công
                fetchProfileForView();
                toggleEditMode(false);

                // Hiển thị thông báo thành công
                document.getElementById('successMessage').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('successMessage').style.display = 'none';
                }, 3000); // Ẩn thông báo sau 3 giây
            } catch (error) {
                if (error.errors) {
                    // Hiển thị lỗi validation
                    Object.keys(error.errors).forEach(field => {
                        const errorElement = document.getElementById(`error_${field}`);
                        if (errorElement) {
                            errorElement.innerText = error.errors[field][0]; // Hiển thị lỗi đầu tiên
                        }
                    });
                } else {
                    console.error('Error updating profile:', error);
                }
            } finally {
                // Ẩn loading
                document.getElementById('loading').style.display = 'none';
            }
        }

        async function updateAvatar() {
            const fileInput = document.getElementById('avatar');
            if (!fileInput.files.length) {
                return null; // Không có file mới
            }

            const formData = new FormData();
            formData.append('avatar', fileInput.files[0]);

            const response = await fetch('/api/profile/avatar', {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                body: formData,
            });

            if (!response.ok) {
                throw await response.json();
            }   
            const data = await response.json();
            return data.avatar_url; // Trả về URL avatar mới
        }
        // Preview avatar khi chọn file trong chế độ Edit
        document.getElementById('avatar').addEventListener('change', function(event) {
            let reader = new FileReader();
            reader.onload = function() {
                document.getElementById('edit_avatar_preview').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        });

    </script>
@endsection
