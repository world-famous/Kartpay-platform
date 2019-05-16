
<div class="">
    <div class="nav_menu">
			<img src="{{ asset('images/logo2.png') }}" class="site_logo_firm_doc img-responsive" style="margin-top: 6px;height: 35px;width: 200px;" alt="" />
        <nav>
            <ul class="nav navbar-nav navbar-right" style="font-size:16px; float:right; padding-right:20px; height:60px; line-height:60px;">
				<div>
					<a href="{{ route('admins.dashboard.panel') }}" style="color: #808080;"><span style="color: black;font-size: 123%;">Dashboard</span></a>
					&nbsp;&nbsp;&nbsp;

				</div>
            </ul>
        </nav>
    </div>
	
	<div class="row" style="background:#e3e3e3;text-align:center;">
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<div class="col-md-4 col-sm-4 col-xs-4">
				<h2>
					@if(!$merchantDocument)
						<a href="#">
					@else							
						<a href="{{ route('admins.merchant_administration.document_approval_step1', ['id' => $merchantDocument->merchant_id]) }}">
					@endif
						Step 1
						<br>
						Approve/Reject
					</a>
				</h2>
			</div>
			
			<div class="col-md-4 col-sm-4 col-xs-4">
				<h2>
					@if(!$merchantDocument)
						<a href="#">
					@else
					<a href="{{ route('admins.merchant_administration.document_approval_step2', ['id' => $merchantDocument->merchant_id]) }}">
					@endif
						Step 2
						<br>
						Received
					</a>
				</h2>
			</div>
			
			<div class="col-md-4 col-sm-4 col-xs-4">
				<h2>
					<a href="#">
						Step 3
						<br>
						Confirmation
					</a>
				</h2>
			</div>
		</div>
    </div>
</div>
