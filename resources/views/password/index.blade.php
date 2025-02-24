@extends('adminlte::page')

@section('title', 'Change Password')

@section('content_header')
    <h1>Change Password</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    <!-- Success & Error Messages -->
                    <div id="success-message" class="alert alert-success d-none"></div>
                    <div id="error-message" class="alert alert-danger d-none"></div>    

                    <form id="changePasswordForm">
                        <div class="form-group">
                            <label for="old_password">Old Password</label>
                            <input type="password" class="form-control" id="old_password" name="old_password" required>
                            <small class="text-danger d-none" id="error-old-password"></small>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <small class="text-danger d-none" id="error-new-password"></small>
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            <small class="text-danger d-none" id="error-new-password-confirmation"></small>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="changePassword()">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changePassword() {
            // Reset error messages
            document.getElementById('success-message').classList.add('d-none');
            document.getElementById('error-message').classList.add('d-none');
            document.querySelectorAll('.text-danger').forEach(el => el.classList.add('d-none'));

            let oldPassword = document.getElementById('old_password').value.trim();
            let newPassword = document.getElementById('new_password').value.trim();
            let confirmPassword = document.getElementById('new_password_confirmation').value.trim();

            let isValid = true;

            // 🔥 Validate required fields
            if (!oldPassword) {
                document.getElementById('error-old-password').innerText = "Old password is required.";
                document.getElementById('error-old-password').classList.remove('d-none');
                isValid = false;
            }
            if (!newPassword) {
                document.getElementById('error-new-password').innerText = "New password is required.";
                document.getElementById('error-new-password').classList.remove('d-none');
                isValid = false;
            }
            if (!confirmPassword) {
                document.getElementById('error-new-password-confirmation').innerText = "Confirm password is required.";
                document.getElementById('error-new-password-confirmation').classList.remove('d-none');
                isValid = false;
            }

            // 🔥 Validate password strength
            let passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/;
            if (newPassword.length < 8 || newPassword.length > 32 || !passwordRegex.test(newPassword)) {
                document.getElementById('error-new-password').innerText = 
                    "Password must be 8-32 characters long, contain at least one uppercase letter, one number, and one special character.";
                document.getElementById('error-new-password').classList.remove('d-none');
                isValid = false;
            }

            // 🔥 Validate new password confirmation
            if (newPassword !== confirmPassword) {
                document.getElementById('error-new-password-confirmation').innerText = "Passwords do not match.";
                document.getElementById('error-new-password-confirmation').classList.remove('d-none');
                isValid = false;
            }

            if (!isValid) return; // Dừng nếu có lỗi

            let formData = {
                old_password: oldPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            };

            fetch('/api/change-password', {
                method: 'POST',
                headers: { 
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    document.getElementById('success-message').innerText = data.message;
                    document.getElementById('success-message').classList.remove('d-none');
                    document.getElementById('changePasswordForm').reset();
                } else {
                    document.getElementById('error-message').innerText = data.error || "An error occurred!";
                    document.getElementById('error-message').classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error changing password:', error);
                document.getElementById('error-message').innerText = "Error changing password.";
                document.getElementById('error-message').classList.remove('d-none');
            });
        }
    </script>
@endsection
