<div class="top_nav">
    <div class="nav_menu"  style="height: 54px;">
        @if(config('app_env')['app_env'] == 'staging')
        <span class="nav_menu" style="background:#26cc18;float:right;font-size:20px;text-align:center;color:white;">
          You are the Test Environment
        </span>
        @endif
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar }}" alt="">{{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="{{ url('/profile') }}"> Profile</a></li>
                        <li><a href="{{ url('/log') }}"> Logs</a></li>
                        <li><a href="{{ url('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
													 <i class="fa fa-sign-out pull-right"></i> Log Out</a></li>

						<form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
                    </ul>
                </li>

        				<li class="navbar-left">
        					<a href="javascript:;" class="dropdown-toggle info-number">
        					<ul class="form-inline">
                                <select class="form-control dropdown-toggle" id="search_option" name="search_option" style="border: 1px solid;border-radius: 10px;">
        							<option value="courier_tracking_id">Tracking Number</option>
        						</select>
                                <input type="text" id="search_value" name="search_value" class="form-control dropdown-toggle" placeholder="search here..." value="" onkeypress="SubmitSearch(event)" style="border-radius: 10px; border: 1px solid;">					</ul>
        					</a>
        				</li>

            </ul>
        </nav>
    </div>
</div>



<script>
function SubmitSearch(e)
{
	 if(e.keyCode === 13){
		e.preventDefault(); // Ensure it is only this code that run
		DoSearch($('#search_option').val(), $('#search_value').val());
	}
}

function DoSearch(option, value)
{
	$.ajax({
      url:'{{ route("admins.search") }}',
      type:'POST',
      data:{
        '_token':'{{ csrf_token() }}',
        'search_option': option,
        'search_value': value
      },
      success: function (res) {
		if(res.response == 'Success')
		{
			window.location = "merchant_administration/" + res.merchant_id + "/document_approval_step2";
		}
		else
		{
			showTemporaryMessageHeader(res.message, 'error', 'Error');
		}
      },
      error: function(a, b, c){
        showTemporaryMessageHeader(c, 'error', 'Error');
      }
    });
}

function showTemporaryMessageHeader(message, type, title)
{
	$('#info_header').html('\
						<div class="alert alert-' + type + ' alert-dismissable">\
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
						  <strong>' + title + '!</strong> ' + message + '.\
						</div>\
					');
}
</script>
