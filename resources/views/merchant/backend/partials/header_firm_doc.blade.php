
<div class="">
    <div class="nav_menu">
			<img src="{{ asset('images/logo2.png') }}" class="site_logo_firm_doc img-responsive" alt=""   style="margin-top: 10px;height: 40px;width: 160px;"/>
        <nav>
            <ul class="nav navbar-nav navbar-right" style="font-size:16px; float:right; padding-right:20px; height:60px; line-height:60px;">
				<div>
					<a href="{{ route('merchants.dashboard.merchant') }}" style="color:white;font-size: 20px;"><span style="color:#797575;">Dashboard</span></a>
				</div>
            </ul>
        </nav>
    </div>
	
	@if(!isset($status))
		<div class="row" style="background:#e3e3e3;text-align:center;">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-3 col-sm-3 col-xs-12">
					<h2>
						<p>
							<a href="{{ route('merchants.activation.check_step') }}">
								Step 1
								 <br>
								Plan Selection
							</a>
						</p>
					</h2>
				</div>
				
				<div class="col-md-3 col-sm-3 col-xs-12">
					<h2>
						<p>
							<a href="{{ route('merchants.activation.step2') }}">
								Step 2
								 <br>
								Merchant Information
							</a>
						</p>
					</h2>
				</div>
				
				<div class="col-md-3 col-sm-3 col-xs-12">
					<h2>
						<p>
							<a href="{{ route('merchants.activation.step3') }}">
								Step 3
								 <br>
								Document
							</a>
						</p>
					</h2>
				</div>
				
				<div class="col-md-3 col-sm-3 col-xs-12">
					<h2>
						<p>
							<a href="{{ route('merchants.activation.step4') }}">
								Step 4
								 <br>
								Status
							</a>
						</p>
					</h2>
				</div>
			</div>
		</div>
	@endif
</div>
