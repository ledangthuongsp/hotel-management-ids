@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_body')
    <form id="loginForm" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>

        <div id="error-message" class="text-danger mb-2"></div> {{-- Hiển thị lỗi nếu có --}}

        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this); // Lấy toàn bộ dữ liệu form
            let errorMessage = document.getElementById('error-message');

            fetch('{{ route('login.post') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json' // Yêu cầu máy chủ trả về JSON
                },
                body: new FormData(document.getElementById('loginForm'))
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    return response.text().then(text => { throw new Error(text); });
                }
            })
            .then(data => {
                if (data.token) {
                    localStorage.setItem('token', data.token);
                    window.location.href = "/dashboard";
                } else {
                    document.getElementById('error-message').innerText = data.error || "Đăng nhập thất bại!";
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                document.getElementById('error-message').innerText = "Có lỗi xảy ra, vui lòng thử lại.";
            });

        });
    </script>
@endsection
