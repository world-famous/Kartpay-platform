<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Live</title>
  @include('live.partials.head')
  @stack('styles')
</head>
<body>
  @include('live.partials.header')

  @yield('content')

  @include('live.partials.footer')

  @include('live.partials.foot')
  @stack('scripts')
</body>
</html>
