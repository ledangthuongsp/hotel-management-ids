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

            let formData = new FormData(this);
            let errorMessage = document.getElementById('error-message');

            fetch('{{ route('login.post') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200) {
                    localStorage.setItem('token', body.token);
                    window.location.href = "/hotels";
                } else if (status === 422) {
                    // Handle validation errors (email does not exist or incorrect password)
                    let errorText = '';
                    if (body.errors) {
                        if (body.errors.email) {
                            errorText += "This email does not exist.<br>";
                        }
                        if (body.errors.password) {
                            errorText += "Incorrect password.<br>";
                        }
                    }
                    errorMessage.innerHTML = errorText || "Invalid login credentials.";
                } else {
                    errorMessage.innerText = body.error || "Login failed! Please try again.";
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                errorMessage.innerText = "Server connection error. Please try again.";
            });
        });

    </script>
@endsection
