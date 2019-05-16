<!-- /sidebar menu -->
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('/') }}" class="site_title">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-lg img-responsive" style="max-width:200px;margin-top: 19px;height: 40px;width: 200px;margin-left: 13%;" alt="">
                <img src="{{ asset('images/kpp.png') }}" class="center-block img-sm img-responsive" style="height: 55%;margin-top: 12%;" alt="">
            </a>
        </div>
        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic" style="margin-top: -16%; margin-left: 24%;width: 70%;border: 1px white;">
                <img src="{{ Auth::guard('admin')->user()->avatar }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info" >

                <h2  style="margin-top: -5%;color: black;text-align: center;font-size: 110%;font-family: inherit;">{{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section active">
                <h3>Payment Gateway </h3>
                <ul class="nav side-menu">
        					<li><a href="{{ route('admins.merchant_administration') }}"><i class="fa fa-home"></i> Merchant Administration </a></li>
        					<li><a href="{{ route('admins.admin_administration') }}"><i class="fa fa-user"></i> Admin User Management </a></li>
                            <li>
                                <a><i class="fa fa-shopping-bag"></i> Transaction Masters<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('admins.transactions.index') }}"><i class="fa fa-shopping-bag"></i> Transaction </a></li>
                                    <li><a href="{{ route('admins.refunds.index') }}"><i class="fa fa-shopping-bag"></i> Refunds </a></li>
                                </ul>
                            </li>
        					<li id="li_application_settings">
        						<a><i class="fa fa-wrench"></i> Application Settings<span class="fa fa-chevron-down"></span></a>
        						<ul class="nav child_menu" id="ul_application_settings">
        							<li class="sub_menu">
        								<a href="/sms-settings">SMS Settings</a>
        							</li>
        							<li>
        								<a href="/email-settings">Email Settings</a>
        							</li>
        							<li>
        								<a href="/session-settings">Session Settings</a>
        							</li>
        							<li>
        								<a href="/country-settings" class="active">Country Settings</a>
        							</li>
        							<li>
        								<a href="/state-settings">State Settings</a>
        							</li>
        							<li>
        								<a href="/city-settings">City Settings</a>
        							</li>
        							<li>
        								<a href="/signup-settings">Sign Up Settings</a>
        							</li>
                      <li>
                          <a href="/aftership-settings">Aftership Settings</a>
                      </li>
                      <li>
                          <a href="{{ route('admins.firewall.setting') }}">Firewall Settings</a>
                      </li>
        							<li>
                          <a href="{{ route('admins.bank') }}">Bank Settings</a>
                      </li>
                      <li>
                          <a href="{{ route('admins.staging') }}">Staging Settings</a>
                      </li>
                      <li>
                          <a href="{{ route('admins.bin') }}">Bin Settings</a>
                      </li>
                      <li>
                          <a href="{{ route('admins.invitation_email') }}">Invitation Email Settings</a>
                      </li>
        						</ul>
        					</li>
                  <li>
                    <a href="{{ route('admins.bin_engine') }}"><i class="fa fa-home"></i> Bin Master </a>
                  </li>
                  <li>
                      <a><i class="fa fa-credit-card"></i>Gateway Settings<span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                          <li class="sub_menu"><a href="{{url('/payment_methods/list/credit_card')}}">Credit Card</a></li>
                          <li class="sub_menu"><a href="{{url('/payment_methods/list/debit_card')}}">Debit Card</a></li>
                          <li class="sub_menu"><a href="{{url('/payment_methods/list/net_banking')}}">Net Banking</a></li>
                          <li class="sub_menu"><a href="{{url('/payment_methods/list/wallet')}}">Wallet</a></li>
                      </ul>
                  </li>
                  <li>
                      <a><i class="fa fa-money"></i> Accounting Master<span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                          <li class="sub_menu">
                              <a href="#">CCAvenue Accounting Management</a>
                          </li>
                          <li>
                              <a href="#">Merchant Accounting Management</a>
                          </li>
                      </ul>
                  </li>
                  <li>
                      <a><i class="fa fa-shield"></i>Firewall Management Settings <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                          <li><a href="{{ route('admins.firewall.cache') }}">Securi Firewall Cache</a></li>
                          <li><a href="{{ route('admins.firewall.ipaddress') }}">Sucuri WhiteList/Blacklist IP</a></li>
                      </ul>
                  </li>
                </ul>
            </div>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
