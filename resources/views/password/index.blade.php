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
                    <form id="changePasswordForm">
                        <div class="form-group">
                            <label for="old_password">Old Password</label>
                            <input type="password" class="form-control" id="old_password" name="old_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="changePassword()">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changePassword() {
            let formData = {
                old_password: document.getElementById('old_password').value,
                new_password: document.getElementById('new_password').value,
                new_password_confirmation: document.getElementById('new_password_confirmation').value
            };

            console.log("Sending Data:", formData);

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
                console.log("Response:", data);
                if (data.message) {
                    alert("Đổi mật khẩu thành công!");
                    document.getElementById('changePasswordForm').reset();
                } else {
                    alert(data.error || "Có lỗi xảy ra!");
                }
            })
            .catch(error => console.error('Error changing password:', error));
        }
    </script>
@endsection
