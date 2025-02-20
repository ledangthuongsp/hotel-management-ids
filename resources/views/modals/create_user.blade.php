<!-- Modal for Creating a New User -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createUserForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Avatar -->
                    <div class="text-center mb-3">
                        <img id="user_avatar_preview" src="/images/default_avatar.png" class="rounded-circle" width="100" height="100">
                        <br>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="mt-2">
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" id="first_name">
                        <span class="error-message text-danger" id="first_name_error"></span>
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" id="last_name">
                        <span class="error-message text-danger" id="last_name_error"></span>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" id="user_name">
                        <span class="error-message text-danger" id="user_name_error"></span>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email">
                        <span class="error-message text-danger" id="email_error"></span>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password">
                        <span class="error-message text-danger" id="password_error"></span>
                    </div>

                    <div class="form-group">
                        <label>Birth Date</label>
                        <input type="date" class="form-control" id="day_of_birth">
                        <span class="error-message text-danger" id="dob_error"></span>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" id="role_id">
                            <option value="">--Select Role--</option>
                        </select>
                        <span class="error-message text-danger" id="role_id_error"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="createUser()">Save User</button>
            </div>
        </div>
    </div>
</div>

<script>
    // ✅ Preview ảnh khi chọn file
    document.getElementById('avatar').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            document.getElementById('user_avatar_preview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    });

    async function uploadUserAvatar(userId, file) {
        let formData = new FormData();
        formData.append('avatar', file);

        let response = await fetch(`/api/users/${userId}/upload-avatar`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: formData
        });

        let data = await response.json();
        return data.avatar_url;
    }

    async function createUser() {
        let avatarFile = document.getElementById('avatar').files[0];
        
        let userData = {
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            user_name: document.getElementById('user_name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            day_of_birth: document.getElementById('day_of_birth').value,
            role_id: document.getElementById('role_id').value
        };

        let response = await fetch('/api/users', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        });

        let data = await response.json();

        if (data.user) {
            let userId = data.user.id;

            // Nếu có avatar, upload sau khi tạo user
            if (avatarFile) {
                await uploadUserAvatar(userId, avatarFile);
            }

            $('#createUserModal').modal('hide');
            document.getElementById('createUserForm').reset();
            fetchUsers();
        } else {
            console.error("Error creating user:", data);
        }
    }
</script>

