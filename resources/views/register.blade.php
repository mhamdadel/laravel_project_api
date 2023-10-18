@extends('Dashboard.layouts._main')

@section('title', 'Register')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="registerForm" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" >
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" >
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" >
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" >
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const baseUrl = "http://localhost:8000/api/auth";

        $(document).ready(function() {
            $('#registerForm').submit(function(e) {
                e.preventDefault();

                const formData = {
                    first_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                    password: $('#password').val(),
                    email: $('#email').val(),
                    phone_number: $('#phone').val()
                };

                const url = formData.email ? `${baseUrl}/register/with_email` : `${baseUrl}/register/with_phone`;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        window.location.href = '/active.user';
                    },
                    error: function(xhr, status, error) {
                        const errorMessage = xhr.responseJSON.message;
                        const errorAlert = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${errorMessage}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        $('#registerForm').prepend(errorAlert);
                    }
                });
            });
        });
    </script>
@endsection