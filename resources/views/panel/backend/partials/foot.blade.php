<script async="" src="https://www.google-analytics.com/analytics.js"></script>

<script src="{{asset('js/vendor/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('js/vendor/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('js/vendor/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('js/vendor/nprogress.js')}}"></script>
<!-- Chart.js -->
<script src="{{asset('js/vendor/Chart.min.js')}}"></script>
<!-- gauge.js -->
<script src="{{asset('js/vendor/gauge.min.js')}}"></script>
<!-- bootstrap-progressbar -->
<script src="{{asset('js/vendor/bootstrap-progressbar.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('js/vendor/icheck.min.js')}}"></script>
<!-- Skycons -->
<script src="{{asset('js/vendor/skycons.js')}}"></script>
<!-- Flot -->
<script src="{{asset('js/vendor/jquery.flot.js')}}"></script>
<script src="{{asset('js/vendor/jquery.flot.pie.js')}}"></script>
<script src="{{asset('js/vendor/jquery.flot.time.js')}}"></script>
<script src="{{asset('js/vendor/jquery.flot.stack.js')}}"></script>
<script src="{{asset('js/vendor/jquery.flot.resize.js')}}"></script>
<!-- Flot plugins -->
<script src="{{asset('js/vendor/jquery.flot.orderBars.js')}}"></script>
<script src="{{asset('js/vendor/jquery.flot.spline.min.js')}}"></script>
<script src="{{asset('js/vendor/curvedLines.js')}}"></script>
<!-- DateJS -->
<script src="{{asset('js/vendor/date.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('js/vendor/jquery.vmap.js')}}"></script>
<script src="{{asset('js/vendor/jquery.vmap.world.js')}}"></script>
<script src="{{asset('js/vendor/jquery.vmap.sampledata.js')}}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{asset('js/vendor/moment.min.js')}}"></script>
<script src="{{asset('js/vendor/daterangepicker.js')}}"></script>

<!-- Custom Theme Scripts -->
<script src="{{asset('js/vendor/custom.min.js')}}"></script>

<!-- Datatables CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js"></script>
<!-- Datatables Bootstrap CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/dataTables.bootstrap.min.js"></script>

<!-- Cropit -->
<script src="{{asset('js/vendor/jquery.cropit.js')}}"></script>

<!-- jQuery-UI 1.11.4 -->
<script src="{{ asset('/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{ asset('/plugins/fileupload/js/jquery.iframe-transport.js') }}"></script>
<!-- The basic File Upload plugin -->
<script src="{{ asset('/plugins/fileupload/js/jquery.fileupload.js') }}"></script>

<!-- Editor -->
<script src="{{asset('js/vendor/editor.js')}}"></script>

<script src="{{asset('js/angular.min.js')}}"></script>


<!-- PNotify -->
    <!-- <script src="{{asset('js/vendor/pnotify.js')}}"></script>
    <script src="{{asset('js/vendor/pnotify.buttons.js')}}"></script>
    <script src="{{asset('js/vendor/pnotify.nonblock.js')}}"></script> -->

<!-- Google Analytics -->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-23581568-13', 'auto');
    ga('send', 'pageview');

</script>

<!-- Show Temprary Message -->
<script>
function showTemporaryMessage(message, type, title)
{
	$('#info').html('\
						<div class="alert alert-' + type + ' alert-dismissable">\
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
						  <strong>' + title + '!</strong> ' + message + '.\
						</div>\
					');
}

function showTemporaryMessageWithElementId(message, type, title, elementId)
{
	$('#' + elementId).html('\
						<div class="alert alert-' + type + ' alert-dismissable">\
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
						  <strong>' + title + '!</strong> ' + message + '.\
						</div>\
					');
}


</script>

<div class="jqvmap-label" style="display: none;"></div><div class="daterangepicker dropdown-menu ltr opensleft"><div class="calendar left"><div class="daterangepicker_input"><input class="input-mini form-control" type="text" name="daterangepicker_start" value=""><i class="fa fa-calendar glyphicon glyphicon-calendar"></i><div class="calendar-time" style="display: none;"><div></div><i class="fa fa-clock-o glyphicon glyphicon-time"></i></div></div><div class="calendar-table"></div></div><div class="calendar right"><div class="daterangepicker_input"><input class="input-mini form-control" type="text" name="daterangepicker_end" value=""><i class="fa fa-calendar glyphicon glyphicon-calendar"></i><div class="calendar-time" style="display: none;"><div></div><i class="fa fa-clock-o glyphicon glyphicon-time"></i></div></div><div class="calendar-table"></div></div><div class="ranges"><ul><li data-range-key="Today">Today</li><li data-range-key="Yesterday">Yesterday</li><li data-range-key="Last 7 Days">Last 7 Days</li><li data-range-key="Last 30 Days">Last 30 Days</li><li data-range-key="This Month">This Month</li><li data-range-key="Last Month">Last Month</li><li data-range-key="Custom">Custom</li></ul><div class="range_inputs"><button class="applyBtn btn btn-default btn-small btn-primary" disabled="disabled" type="button">Submit</button> <button class="cancelBtn btn btn-default btn-small" type="button">Clear</button></div></div></div></body></html>
