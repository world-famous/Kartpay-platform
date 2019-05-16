@extends('merchant.backend.layouts.app_firm_doc')

@section('content')

@push('styles')
<style type="text/css">

body
{
	font-size:18px;
	color:#000000;
}

a
{
}

.btn-kartpay{
  color: #fff;
  background: #ef4750;
  background: rgba(212,56,8,0.99);
  background: -moz-linear-gradient(left, rgba(212,56,8,0.99) 11%, rgba(218,75,8,0.99) 23%, rgba(255,186,10,1) 95%, rgba(255,186,10,1) 100%);
  background: -webkit-gradient(left top, right top, color-stop(11%, rgba(212,56,8,0.99)), color-stop(23%, rgba(218,75,8,0.99)), color-stop(95%, rgba(255,186,10,1)), color-stop(100%, rgba(255,186,10,1)));
  background: -webkit-linear-gradient(left, rgba(212,56,8,0.99) 11%, rgba(218,75,8,0.99) 23%, rgba(255,186,10,1) 95%, rgba(255,186,10,1) 100%);
  background: -o-linear-gradient(left, rgba(212,56,8,0.99) 11%, rgba(218,75,8,0.99) 23%, rgba(255,186,10,1) 95%, rgba(255,186,10,1) 100%);
  background: -ms-linear-gradient(left, rgba(212,56,8,0.99) 11%, rgba(218,75,8,0.99) 23%, rgba(255,186,10,1) 95%, rgba(255,186,10,1) 100%);
  border-bottom-left-radius:20px;
  border-bottom-right-radius:20px;
  padding: 12px 0px 12px 0px;
}
.btn-kartpay:hover{
  color: #f2f2f2;
  background-color: #e6923c;
  border-color: #e6923c;
}
.btn-kartpay:active:hover{
  background-color: #e6923c;
  border-color: #e6923c;
  color: #fff;
}
.btn-kartpay:active{
  background-color: #e6923c;
  border-color: #e6923c;
  color: #fff;
}
.btn-kartpay:focus{
  background-color: #e6923c;
  border-color: #e6923c;
  color: #fff;
}

.kartpay-box-step1
{
	border:solid 1px #2d2d2d;
	text-align:center; background:#ffffff;
	padding:0px 0px 0px 0px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	border:solid 1px #ef4750;
	-moz-border-radius-topleft: 20px;
	-moz-border-radius-topright:20px;
	-moz-border-radius-bottomleft:20px;
	-moz-border-radius-bottomright:20px;
	-webkit-border-top-left-radius:20px;
	-webkit-border-top-right-radius:20px;
	-webkit-border-bottom-left-radius:20px;
	-webkit-border-bottom-right-radius:20px;
	border-top-left-radius:20px;
	border-top-right-radius:20px;
	border-bottom-left-radius:20px;
	border-bottom-right-radius:20px;
}

.strikediag {
	background: linear-gradient(to left top, transparent 47.75%, currentColor 50.5%, currentColor 53.5%, transparent 54.25%);
}
.kartpay-button-step1
{
    display : table-row;
    vertical-align : bottom;
    height : 1px;
}

</style>
@endpush

<div class="">
	<div class="page-title">
		<div class="title_center">
			<h1 style="text-align: center;">Select Plan</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<br>
			<div class="col-md-4 col-sm-1 col-xs-1" style="text-align:center;">
				<!--Blank Row -->
			</div>
			<div class="kartpay-box-step1 col-md-4 col-sm-4 col-xs-12" style="text-align:center;">
				<h1>
					<div style="text-align:center;">
						<b style="font-size: 25px;">Startup Plan </b>
                        <i class="fa fa-inr" aria-hidden="true" style="font-size: 24px;padding-left: 6%;color: #f80000;">
                            <b class="strikediag" style="color: red;font-size: 23px;">
                                <b style="color: black;"> 4000.00</b>
                            </b>
                            <span style="color: green;font-size: 150%;"> → </span>
                            <span class="fa fa-inr" aria-hidden="true" style="font-size: 24px;color: #f80000;">
                                <b style="color: black;">  500.00</b><b>*</b>
                            </span>
                        </i>
					</div>
				</h1>
				<hr style="width: 91%;margin-left: 5%;">
				<br>
				<div>
					<div class="row">
						<div class="col-md-3 col-sm-1 col-xs-2">
							<p>
								<img src="https://merchant.kartpay.in/images/PO.jpg" class="site_logo_firm_doc img-responsive" alt="" style="margin-left: -50%;">
							</p>
						</div>
						<div class="col-md-3 col-sm-1 col-xs-4" style="text-align: right;width: 35%;margin-left: -7%;">
							<div class=" "><p><strong>Debit Card</strong></p></div>
							<div class=" "><p><strong>Credit Card</strong></p></div>
							<div class=" "><p><strong>Netbanking</strong></p></div>
							<div class=" "><p><strong>Cash Card</strong></p></div>
							<div class=" "><p><strong>Wallet</strong></p></div>
							<div class=" "><p><strong>E.M.I</strong></p></div>
							<div class=" "><p><strong>U.P.I</strong></p></div>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							<p style="margin-left: 0%;"><strong><b class="strikediag" style="color: red;"><b style="color: black;">2.0</b></b></strong></p>
							<p style="margin-left: 0%;"><strong><b class="strikediag" style="color: red;"><b style="color: black;">2.0</b></b></strong></p>
							<p style="margin-left: 0%;"><strong><b class="strikediag" style="color: red;"><b style="color: black;">2.0</b></b></strong></p>
							<p style="margin-left: 0%;"><strong><b class="strikediag" style="color: red;"><b style="color: black;">2.0</b></b></strong></p>
							<p style="margin-left: 0%;"><strong><b class="strikediag" style="color: red;"><b style="color: black;">2.0</b></b></strong></p>
							<p style="margin-left: 0%;"><strong><b class="strikediag" style="color: red;"><b style="color: black;">2.0</b></b></strong></p>
							<p style="margin-left: 0%;"><strong><b class="strikediag" style="color: red;"><b style="color: black;">2.0</b></b></strong></p>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1" style="margin-top: 2%;">
							<img src="https://merchant.kartpay.in/images/braces.png" class="site_logo_firm_doc img-responsive" alt="" style=" height: 232px;">
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>
								<strong><b style="color: green;font-size: 368%;margin-left:2px;">0%</b></strong>
							</p>
						</div>
					</div>
				</div>
				<br>
				<div class="btn-kartpay " style="background: orangered;">
					<a href="{{ route('merchants.activation.process_step1') }}" style="color:white;">SELECT STARTUP PLAN</a>
				</div>
			</div>
  
			{{--<div class="col-md-2 col-sm-1 col-xs-1" style="color: black;margin-left: -27%;">--}}
				{{--<div style="padding: 5px 12px 0px 0px;">--}}
					{{--<p>&nbsp;</p>--}}
				{{--</div>--}}

				{{--<br><br><br>--}}
				{{--<div class=" ">--}}
					{{--<p><strong>Debit Card</strong></p>--}}
				{{--</div>--}}
				{{--<div class=" ">--}}
					{{--<p><strong>Credit Card</strong></p>--}}
				{{--</div>--}}
				{{--<div class=" ">--}}
					{{--<p><strong>Netbanking</strong></p>--}}
				{{--</div>--}}
				{{--<div class=" ">--}}
					{{--<p><strong>Cash Card</strong></p>--}}
				{{--</div>--}}
				{{--<div class=" ">--}}
					{{--<p><strong>Wallet</strong></p>--}}
				{{--</div>--}}
				{{--<div class=" ">--}}
					{{--<p><strong>E.M.I</strong></p>--}}
				{{--</div>--}}
				{{--<div class=" ">--}}
					{{--<p><strong>U.P.I</strong></p>--}}
				{{--</div>--}}
			{{--</div>--}}
			{{--<div class="col-md-1 col-sm-1 col-xs-1" style="/*! top: 42%; */margin-left: -10%;margin-top: 0%;">--}}
				{{--<div style="padding: 5px 12px 0px 0px;">--}}
					{{--<p>&nbsp;</p>--}}
				{{--</div>--}}
				{{--<div style="padding: 5px 12px 0px 0px;">--}}
					{{--<p>&nbsp;</p>--}}
				{{--</div>--}}
				{{--<div style="padding: 5px 12px 0px 0px;">--}}
					{{--<p>&nbsp;</p>--}}
				{{--</div>--}}
				{{--<div style="padding: 5px 12px 0px 0px;">--}}
					{{--<p>&nbsp;</p>--}}
				{{--</div>--}}
				{{--<br>--}}
				{{--<p>--}}
                    {{--<strong><b class=" " style="color: green;font-size: 450%;">0%<div style="color: red;font-size: 34%;margin-top: -130%;margin-left: 112%;">*</div></b></strong>--}}
                {{--</p>--}}
			{{--</div>--}}

			<div class="col-md-1 col-sm-1 col-xs-1"></div>

			<!--<div class="kartpay-box-step1 col-md-4 col-sm-4 col-xs-4" style="text-align:center;">
				<h1> FIRST PLAN INR 1 </h1>
				<br>
				<p>Debit Card: 0.0%</p>
				<p>Credit Card: 0.0%</p>
				<p>Netbanking: 0.0%</p>
				<p>Cash Card: 0.0%</p>
				<p>Wallet: 0.0%</p>
				<p>E.M.I: 0.0%</p>
				<p>U.P.I: 0.0%</p>
				<br>
				<div class="btn-kartpay">
					<a href="{{ route('merchants.activation.process_step1') }}" style="color:white;">SELECT FIRST PLAN</a>
				</div>
			</div>-->

			<div class="col-md-1 col-sm-1 col-xs-1"></div>
		</div>
	</div>
</div>

@endsection
