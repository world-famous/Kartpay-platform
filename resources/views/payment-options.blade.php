<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8"/>
    <title>Panel Kartpay | Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('panel/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('panel/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('panel/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('panel/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ asset('panel/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('panel/assets/global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('panel/assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('panel/assets/global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{ asset('panel/assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{ asset('panel/assets/global/css/components-md.min.css')}}" rel="stylesheet" id="style_components"
          type="text/css"/>

    <link href="{{ asset('panel/assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{ asset('panel/assets/layouts/layout/css/layout.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('panel/assets/layouts/layout/css/themes/light.min.css') }}" rel="stylesheet" type="text/css"
          id="style_color"/>
    <link href="{{ asset('panel/assets/layouts/layout/css/custom.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('panel/assets/layouts/layout/css/front-style.css') }}">
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
    <style>
        .wallet.radio-inline input[type="radio"] {
            margin-top: 15%;
        }
    </style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
<div class="page-wrapper">
    <div class="container">

        <div class="page-header navbar payment-options">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    @if (isset($tnx->user->logo->logo))
                        <img src="{{url('uploads/'. $tnx->user->logo->logo)}}" alt="logo" class="logo-default"/>
                @endif
                <!--<div class="menu-toggler sidebar-toggler">
                        <span></span>
                    </div>-->
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                   data-target=".navbar-collapse">
                    <span></span>
                </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                    <!-- END NOTIFICATION DROPDOWN -->
                    <!-- BEGIN INBOX DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                    <!-- END TODO DROPDOWN -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <div class="page-logo">
                        <img src="{{ asset('panel/assets/layouts/layout/img/2.jpg') }}" alt="logo"
                             class="logo-default"/>
                    </div>
                    <!-- END USER LOGIN DROPDOWN -->
                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                    <!-- END QUICK SIDEBAR TOGGLER -->
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <div class="page-container payment-options">
            <div class="portlet box orange">
                <div class="portlet-title">
                    <div class="caption payment-options">
                        <i class="fa fa-gift"></i>Amount: Rs. {{$amount}}
                    </div>
                    <div class="tools">
                        Transaction ID: {{$tnx_id}}
                        {{--<a href="javascript:;" class="collapse"> </a>--}}
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="row">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                @foreach($errors->all() as $error)
                                    <span>{{$error}}</span><br/>
                                @endforeach
                            </div>
                        @endif
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <ul class="nav nav-tabs tabs-left">
                                @foreach($paymentTypes as $paymentType)
                                    <li {{($loop->first)? 'class=active' : '' }}>
                                        <a href="#tab_6_{{$paymentType->id}}"
                                           data-toggle="tab"> {{$paymentType->paymenttype}} </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-9">
                            <div class="tab-content">
                                @foreach($paymentTypes as $paymentType)
                                    <div class="tab-pane {{($loop->first)? 'active' : '' }}"
                                         id="tab_6_{{$paymentType->id}}">
                                        {{--}} <ul>
                                             @foreach($paymentType->paymentOption as $paymentOption)
                                                 <li>
                                                     {{$paymentOption->paymentoption}}
                                                 </li>
                                             @endforeach
                                         </ul>--}}
                                        {{ Form::hidden('payment_type_id', $paymentType->id) }}
                                        @if (trim($paymentType->shortapicode) == 'CC' and   trim($paymentType->paymenttype) == 'Credit Card')
                                            @include('api.tnx._partials.creditcard',['tnx_id'=>$tnx_id]) {{--($paymentType->paymenttype)--}}
                                        @endif
                                        @if (trim($paymentType->shortapicode) == 'DC' and  trim($paymentType->paymenttype) == 'Debit Card')
                                            {{--$paymentType->paymenttype--}}
                                            @include('api.tnx._partials.debitcard',['tnx_id'=>$tnx_id])
                                        @endif
                                        @if (trim($paymentType->shortapicode) == 'NBK' or  trim($paymentType->paymenttype) == 'Net Banking')
                                            @include('api.tnx._partials.netbanking',['tnx_id'=>$tnx_id,'amount'=>$amount,'redirect_url'=>$redirect_url,'tnx'=>$tnx])
                                        @endif
                                        @if (trim($paymentType->shortapicode) == 'WLT' or  trim($paymentType->paymenttype) == 'Wallets')
                                            @include('api.tnx._partials.wallets',['tnx_id'=>$tnx_id,'amount'=>$amount,'redirect_url'=>$redirect_url,'tnx'=>$tnx])
                                        @endif
                                        @if (trim($paymentType->shortapicode) == 'CASHC' or  trim($paymentType->paymenttype) == 'Cash Card')
                                            @include('api.tnx._partials.cashcard',['tnx_id'=>$tnx_id,'amount'=>$amount,'redirect_url'=>$redirect_url,'tnx'=>$tnx])
                                        @endif
                                    </div>
                                @endforeach
                                <div class="col-xs-12" style="height:20px;"></div>
                                <div class="row">
                                    <div class="col-md-9 col-md-push-3">Return back to <a
                                                id="cancelHref">{{$cancel_exploded[2]}}</a>
                                        {{ Form::open(['route' => 'request.cancel', "files"=>"true", 'id'=>'form-cancel']) }}
                                        {{ Form::hidden('cancel_url',urlencode($cancel_url)) }}
                                        {{ Form::hidden('kartpay_id',$tnx->id) }}
                                        {{ Form::hidden('order_id',$tnx_id) }}
                                        {{ Form::hidden('amount',$amount) }}
                                        {{ Form::hidden('order_status','canceled') }}
                                        {{Form::Close()}}
                                    </div>
                                    <div class="col-md-3 col-md-pull-9"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--[if lt IE 9]>
<script src="{{ asset('panel/assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('panel/assets/global/plugins/excanvas.min.js') }}"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('panel/assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"
        type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{ asset('panel/assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/counterup/jquery.waypoints.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/counterup/jquery.counterup.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/amcharts/amcharts.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/amcharts/serial.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/amcharts/pie.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/amcharts/radar.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/amcharts/themes/light.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/amcharts/themes/patterns.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/amcharts/themes/chalk.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/ammap/ammap.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/amcharts/amstockcharts/amstock.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/fullcalendar/fullcalendar.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/horizontal-timeline/horozontal-timeline.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/flot/jquery.flot.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/flot/jquery.flot.resize.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/flot/jquery.flot.categories.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('panel/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js') }}"
        type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('panel/assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ asset('panel/assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/layouts/layout/scripts/demo.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('panel/assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#cancelHref').on('click', function (e) {
            e.preventDefault();
            $("#form-cancel").submit();
        });
        $('input[type=radio][class=dc_issuing_bank]').on('change', function () {
            if (this.value == 'visa') {
                $(".div-card-number").fadeIn();
                $(".div-card-name").fadeIn();
                $(".div-card-cvv").fadeIn();
            }
            else if (this.value == 'mastercard') {
                $(".div-card-number").fadeIn();
                $(".div-card-name").fadeIn();
                $(".div-card-cvv").fadeIn();
            }
            else if (this.value == 'maestrocard') {
                $(".div-card-number").fadeIn();
                $(".div-card-name").fadeIn();
                $(".div-card-cvv").fadeOut();
            }
            else if (this.value == 'rupay') {
                $(".div-card-number").fadeOut();
                $(".div-card-name").fadeOut();
                $(".div-card-cvv").fadeOut();
            }
        });

        $("#expiry_date_cc").on("input", function () {
            var expiryLength = $("#expiry_date_cc").val().length;
            if (expiryLength <= 3 && expiryLength != 0) {
                if (expiryLength % 2 == 0) {
                    $("#expiry_date_cc").val(this.value + '/');
                }
            }
        });
        $("#expiry_date_dc").on("input", function () {
            var expiryLength = $("#expiry_date_dc").val().length;
            if (expiryLength <= 3 && expiryLength != 0) {
                if (expiryLength % 2 == 0) {
                    $("#expiry_date_dc").val(this.value + '/');
                }
            }
        });

        $("#card_number_cc").on("keypress", function () {
            var expiryLength = $("#card_number_cc").val().length + 1;
            if (expiryLength < 18) {
                if (expiryLength % 5 == 0) {
                    $("#card_number_cc").val(this.value + ' ');
                }
            }
        });
        $("#card_number_dc").on("keypress", function () {
            var expiryLength = $("#card_number_dc").val().length + 1;
            if (expiryLength < 18) {
                if (expiryLength % 5 == 0) {
                    $("#card_number_dc").val(this.value + ' ');
                }
            }
        });
    });
</script>

@yield('scripts')
</body>