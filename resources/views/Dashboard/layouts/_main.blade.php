<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  @include('Dashboard.componenets._navbar')

  <div class="container-fluid">
    <div class="row">
      @include('Dashboard.componenets._sidebar')

      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @yield('content')
      </main>
    </div>
  </div>

  @include('Dashboard.componenets._footer')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  @yield('scripts')
</body>

</html>