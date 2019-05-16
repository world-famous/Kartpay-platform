<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Test</title>
  @include('test.partials.head')
  @stack('styles')
</head>
<body>
  @include('test.partials.header')

  @yield('content')

  @include('test.partials.footer')

  @include('test.partials.foot')
  @stack('scripts')
</body>
</html>
