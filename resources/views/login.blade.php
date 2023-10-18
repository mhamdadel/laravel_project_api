@extends('Dashboard.layouts._main')

@section('title', 'login')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <label for="" class="alert danger"></label>

                        <div class="form-group">
                            <label for="email">Email or Phone</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const baseUrl = "http://localhost:8000";
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault();
                const formData = {
                    email: $('#email').val(),
                    password: $('#password').val()
                };

                $.ajax({
                    url: `${baseUrl}/api/auth/login`,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        const token = response.authorization.token;
                        const name = 'Mr. '+ response.user.first_name + ' ' + response.user.last_name;
                        localStorage.setItem('token', 'Bearer ' + token);
                        localStorage.setItem('name', name);
                        window.location.href = '/dashboard';
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                });
            });
        });
    </script>
@endsection