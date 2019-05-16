<!-- /sidebar menu -->
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('/') }}" class="site_title">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-lg img-responsive" style="max-width:200px; margin-top: 19px;height: 40px;width: 200px; margin-left: 13%;" alt="">
                <img src="{{ asset('images/kpp-logo.png') }}" class="center-block img-sm img-responsive" alt="" style="height: 57%;width: 100%;margin-top: 43%;">
            </a>
        </div>

        <div class="clearfix"></div>
        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ Auth::guard('merchant')->user()->avatar }}" alt="..." class="img-circle profile_img" style="width: 130%;margin-top: -7%;margin-left: 72%;border: 1px white;">
            </div>
            <div class="profile_info">
               <h2 style="font-size: 110%;margin-top:-5%;text-align: center;">{{ Auth::guard('merchant')->user()->name }}</h2>

            </div>
        </div>
        <!-- /menu profile quick info -->

        <br>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section active">
                <h3>Payment Gateway </h3>
                <ul class="nav side-menu">
					<li><a href="{{ route('merchants.transactions.index') }}"><i class="fa fa-shopping-bag"></i> Transaction </a></li>
					<li><a href="{{ route('merchants.refunds.index') }}"><i class="fa fa-money"></i> Refund </a></li>
					<li><a href="#"><i class="fa fa-money"></i> Chargeback </a></li>
					<li><a href="#"><i class="fa fa-money"></i> Settlement </a></li>
                </ul>

                <h3>Shipment Tracking</h3>
                <ul class="nav side-menu">
                    <li><a href="{{route('merchants.trackings.index')}}"><i class="fa fa-bullseye"></i> Trackings </a></li>
                </ul>
            </div>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
