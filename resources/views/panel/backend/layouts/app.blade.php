<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        @yield('title', config('app.name'))
    </title>
    @include('panel.backend.partials.head')
    @stack('styles')
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">

            <!-- sidebar -->
            @include('panel.backend.partials.sidebar')
            <!-- /sidebar -->

            <!-- top navigation -->
            @include('panel.backend.partials.header')
            <!-- /top navigation -->

            <!-- page content -->
			<div>
            <div class="right_col" role="main"> <!-- style="min-height: 1672px;"> -->
			
				<!-- Modal Session Notification-->
                @include('partial.session_modal')
			
                @yield('content')
            </div>
			</div>
            <!-- /page content -->


            <!-- footer content -->
            @include('panel.backend.partials.footer')
            <!-- /footer content -->
        </div>
    </div>
    @include('panel.backend.partials.foot')
    @stack('scripts')
</body>
</html>
