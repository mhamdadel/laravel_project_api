@extends('Dashboard.layouts._main')

@section('title', 'Register')

@section('content')
    Redirect now to <a href="{{ url()->route('login') }}">login</a>
@endsection

@section('scripts')
    <script>
        localStorage.removeItem('token');
        localStorage.removeItem('name');

        window.location.href = '/login';
    </script>
@endsection
