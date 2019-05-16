<html>

<head>
  <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
      .login-box
      {
        margin-top: 100px;
      }
      .site--logo img
      {
        width: 250px;
        margin-bottom: 50px;
      }

    </style>
</head>
<body>
  <div class="content">
    <div class="login-box">
        <div class="col-md-4 col-md-offset-4">
        <div class="col-md-10 col-md-offset-1">
          <div class="site--logo">
            <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive" style="width: 292px;margin-top: 45px;"alt="">
          </div>
        </div>
        <h1 class="text-center" style="font-size: 130px">404</h1>
        <h1 class="text-center text-muted">Page not found</h1>
      </div>
    </div>
  </div>
</body>
</html>
