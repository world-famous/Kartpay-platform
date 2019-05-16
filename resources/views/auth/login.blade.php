<html>

<head>
  <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
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
      <div class="site--logo">
        <img src="{{ asset('images/logo2.png') }}" style="width: 292px;margin-top: 45px;" class="center-block img-responsive" alt="">
      </div>
        <div class="col-md-4 col-md-offset-4">
        <div class="col-md-10 col-md-offset-1" style="box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);height: 250px;padding: 6%;border-radius: 20px;">
          <form role="form" method="POST" action="{{ url('login') }}">
              {{ csrf_field() }}
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
              <input type="email" required class="form-control form-control-kartpay input-lg"
                placeholder="Email" id="email" name="email" style="border-color: #f3f2f1;font-size: 100%;color: black;border-radius: 10px;">
              @if($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>
            <div class="form-group">
              <input type="password" class="form-control form-control-kartpay input-lg"
                placeholder="Password" id="password" name="password" required style="border-color: #edebea;font-size: 100%;border-radius: 10px;">
            </div>
			<?php
			 $server = explode('.', Request::server('HTTP_HOST'));
			 $subdomain = $server[0];
			?>
			@if($subdomain == 'merchant')
            <a href="{{ url('register') }}"  style="width: 60%;margin-left: 8%;font-size: 100%;color: black;">Sign Up</a>
            <a href="{{ route('merchants.forgot_password') }}"  style="width: 60%;margin-left: 31%;height: 6%;font-size: 100%;color: black;;">Forgot Password</a>
			@else
            <a href="{{ route('admins.forgot_password') }}"  style="width: 60%;margin-left: 31%;height: 6%;font-size: 100%;color: black;;">Forgot Password</a>
      @endif
            <br><br>
            <button class="btn btn-block btn-default btn-lg btn-kartpay" style="border-radius: 20px;background: rgba(255, 53, 32, 0.83);font-size: 100%;width: 61%;margin-left: 18%;">Login</button>
            <br><br>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
</body>
</html>
