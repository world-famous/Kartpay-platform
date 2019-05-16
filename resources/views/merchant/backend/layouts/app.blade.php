<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        @yield('title', config('app.name'))
    </title>
    @include('merchant.backend.partials.head')
    @stack('styles')
</head>
<body class="nav-md">
<div class="container body">
    <div class="main_container">

        <!-- sidebar -->
    @include('merchant.backend.partials.sidebar')
    <!-- /sidebar -->

        <!-- top navigation -->
    @include('merchant.backend.partials.header')
    <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main" style="min-height: 1672px;">

			<!-- Modal Session Notification-->
            @include('partial.session_modal')

            @yield('content')
        </div>
        <!-- /page content -->


        <!-- footer content -->
    @include('merchant.backend.partials.footer')
    <!-- /footer content -->
    </div>
</div>
@include('merchant.backend.partials.foot')
@stack('scripts')
</body>
</html>
