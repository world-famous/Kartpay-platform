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

        <!-- top navigation -->
    @include('panel.backend.partials.header_firm_doc')
    <!-- /top navigation -->

        <!-- page content -->
        <div class="container" role="main"> <!-- style="min-height: 1672px;"> -->
		
			<!-- Modal Session Notification-->
			<div id="myModalSession" class="modal fade" role="dialog">
			  <div class="modal-dialog modal-lg">

				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="session_modal_title">Modal Header</h4>
				  </div>
				  <div class="modal-body" style="text-align:center;" id="session_modal_body">
					<p>Some text in the modal.</p>
				  </div>
				  <div class="modal-footer" id="session_modal_footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				</div>

			  </div>
			</div>

            @yield('content')
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
