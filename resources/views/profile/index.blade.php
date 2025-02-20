@extends('adminlte::page')

@section('title', 'Profile Settings')

@section('content_header')
    <h1>Profile Settings</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">User Profile</div>
                <div class="card-body">
                    <!-- View Mode -->
                    <div id="viewMode">
                        <div class="text-center mb-3">
                            <img id="avatar_preview" src="/images/default_avatar.png" class="rounded-circle" width="100" height="100">
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
                            <button type="button" class="btn btn-info btn-sm" onclick="updateAvatar()">Upload Avatar</button>
                        </div>
                        <div class="form-group">
                            <label for="edit_first_name">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name">
                        </div>
                        <div class="form-group">
                            <label for="edit_last_name">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name">
                        </div>
                        <div class="form-group">
                            <label for="edit_user_name">Username</label>
                            <input type="text" class="form-control" id="edit_user_name" name="user_name">
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="edit_day_of_birth">Date of Birth</label>
                            <input type="date" class="form-control" id="edit_day_of_birth" name="day_of_birth">
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="toggleEditMode(false)">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
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
                document.getElementById('avatar_preview').src = data.avatar_url || '/images/default_avatar.png';
                document.getElementById('view_first_name').innerText = data.first_name;
                document.getElementById('view_last_name').innerText = data.last_name;
                document.getElementById('view_user_name').innerText = data.user_name;
                document.getElementById('view_email').innerText = data.email;
                document.getElementById('view_day_of_birth').innerText = formatDate(data.day_of_birth);
                document.getElementById('view_role').innerText = (data.role_id == 1) ? "Admin" : "User"; 
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
            })
            .catch(error => console.error('Error fetching profile:', error));
        }

        function toggleEditMode(editing) {
            document.getElementById('viewMode').style.display = editing ? 'none' : 'block';
            document.getElementById('editMode').style.display = editing ? 'block' : 'none';
            if (editing) {
                fetchProfileForEdit();
            }
        }

        function saveProfile() {
            let profileData = {
                first_name: document.getElementById('edit_first_name').value,
                last_name: document.getElementById('edit_last_name').value,
                user_name: document.getElementById('edit_user_name').value,
                email: document.getElementById('edit_email').value,
                day_of_birth: document.getElementById('edit_day_of_birth').value
            };

            fetch('/api/profile', {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token'), 'Content-Type': 'application/json' },
                body: JSON.stringify(profileData)
            })
            .then(response => response.json())
            .then(() => {
                fetchProfileForView();
                toggleEditMode(false);
            })
            .catch(error => console.error('Error updating profile:', error));
        }

        function updateAvatar() {
            let formData = new FormData();
            formData.append('avatar', document.getElementById('avatar').files[0]);

            fetch('/api/profile/avatar', { 
                method: 'POST', 
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.avatar_url) {
                    document.getElementById('avatar_preview').src = data.avatar_url;
                    document.getElementById('edit_avatar_preview').src = data.avatar_url;
                }
            })
            .catch(error => console.error('Error updating avatar:', error));
        }
    </script>
@endsection
