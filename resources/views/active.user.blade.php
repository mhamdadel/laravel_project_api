@extends('Dashboard.layouts._main')

@section('title', 'login')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Active User</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <label for="" class="alert danger"></label>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="activeCode">active code</label>
                            <input type="text" class="form-control" id="activeCode" name="activeCode" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Active User</button>
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
                    password: $('#activeCode').val()
                };

                $.ajax({
                    url: `${baseUrl}/api/auth/active`,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        window.location.href = '/login';
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                });
            });
        });
    </script>
@endsection