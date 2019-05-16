<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{asset('images/logo2.png')}}"/>

    <title>{{config('app.name')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>







    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color:#ffffff;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        .carousel-inner img {
            width: 100%;
            margin: auto;
        }


        .slideanim {
            visibility:hidden;
        }

        .slide {
            animation-name: slide;
            -webkit-animation-name: slide;
            animation-duration: 7s;
            -webkit-animation-duration: 7s;
            visibility: visible;
        }

        .carousel-control.right, .carousel-control.left {
            background-image: none;
            color: ;
        }

        .carousel-indicators li {
            border-color: #ffffff;
        }

        .carousel-indicators li.active {
            background-color: ;
        }


    </style>
</head>
<body>
<div class="nav" background="gray" style="background: rgb(248, 248, 248);padding: 3%;height: 21%;">
    <ul class="nav navbar-nav navbar-right" >

        <div class="col-sm-4" style="margin-left: -37%;margin-top: -23%;font-size: 31px;color: white;">
            <a href="#" style="font-size: 25px;">
                <b style="background-color: orangered;padding: 54px 22px 35px 19px;margin-left: -263px;color: white;">LOGIN
                </b>
            </a>
        </div>
        <div class="col-sm-4" style="margin-left: -17%;margin-top: -20%;font-size: 25px;color:white;">
            <b style="background-color: orangered;padding: 53px 25px 33px 19px;margin-left: -94px;">SIGNUP</b>
        </div>


    </ul>


    <ul class="nav navbar-nav navbar-left">
        <img src="images/kartpay_logo.png" style="margin-left: -4%;margin-top: -4%;height: 83%;width: 28%;">
    </ul>


    <ul class="nav navbar-nav navbar-center" style="color: black;margin-left: 22%;">
        <div style="font-family: inherit;font-size: 23px;margin-top: -30%;margin-left: 74%;color: #484445;">
            <b>DEVELOPERS</b>

        </div>
        <div style="font-family: inherit;font-size: 23px;margin-left: 235%;margin-top: -21%;color: #484445;">
            <b>SOLUTIONS</b>

        </div>
    </ul>


</div>
<div id="myCarousel" class="carousel slide text-center" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>

    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="{{ asset('images/kartpay-comp.gif') }}" style="width: 100;height: 500px;width: 100%;margin-top: -4%;">
        </div>
        <div class="item">
            <img src="{{ asset('images/front2.png') }}" style="width: 100;height: 850px;width: 100%;margin-top: -4%;">
        </div>

    </div>


</div>

















<!--
    </div>
        <div class="flex-center position-ref full-height"  >
            @if (Route::has('login'))
    <div class="top-right links">
@if (Auth::check())
        <a href="{{ url('/home') }}">Home</a>
                    @else
        <!--  <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif

@endif
        --> -->




<div class="container">
    <div class="row">

        <div class="col-sm-4" style="margin-left: -8%;height: 537px;wi;width: 32%;border-radius: 23px;margin-top: 4%;background: white;box-shadow: azure;box-shadow: 4px 4px 4px 4px #ff5b00;">
            <img src="images/speed.png" style="width: 202px;height: 205px;margin-top: 6%;margin-left: 21%;">
            <center><br>
                <h1 style="
    color: black;
    font-size: 185%;
    font-weight: bold;
">Speedy</h1>
            </center>


            <center>
                <h3 style="
    color: black;
    font-size: 143%;
"><br>
                    Simple Faster than counting the cash! Send and receive payment in just a couple of minutes. Anywhere. Anytime </h3>
        </div>



        <div class="col-sm-4" style="margin-left: 8%;height: 537px;width: 33%;border-radius: 23px;margin-top: 4%;background: white;box-shadow: azure;box-shadow: 4px 4px 4px 4px #ff5b00;">
            <img src="images/safty.png" style="width: 202px;height: 205px;margin-top: 6%;margin-left: 21%;">
            <center><br>
                <h1 style="
    color: black;
    font-size: 185%;
    font-weight: bold;
">Safty</h1>
            </center>

            <center>
                <h3 style="
    color: black;
    font-size: 143%;
"><br>

                    Our Additional service do the work of full-time staff and you to find fraud,store the data securely and more
                </h3>
            </center>
        </div>


        <div class="col-sm-4" style="margin-left: 74%;margin-top: -46%;height: 536px;width: 33%;border-radius: 23px;background: white;box-shadow:  4px 4px 4px 4px #ff5b00;">
            <img src="images/simple.png" style="width: 202px;height: 205px;margin-top: 6%;margin-left: 21%;">
            <br><center>
                <br><h1 style="
    color: black;
    font-size: 185%;
    font-weight: bold;
">Simple</h1>
                <br><h3 style="
    color: black;
    font-size: 143%;
">Our competitive pricing, multiple payment modes and intuitive checkout form makes accepting payments simpler</h3>
            </center>
        </div>



    </div>
</div>
<div class="col-sm-4" style="">
    <img src="images/fast.png " style="width: 707px;height: 429px;margin-top: 21%;margin-left: -13%;">
</div>
<div class="col-sm-8" style="margin-top: 6%;margin-left: 4%;">
    <div style="color: black;margin-left: 68%;height: 99%;font-weight: 27%;width: 70%;margin-top: -54%;"> <br>
        <h1 style="font-size: 39px;"> Fast Onboarding</h1><br>
        <h2 style="text-align: justify;width: 99%;font-size: 188%;">No Physical Documents Required. You just need to fill online form and get activated in 30 min. This is the facility coming up first time in India
        </h2>
    </div>
</div>





<div class="col-sm-8" style="margin-top: 6%;margin-left: 4%;">
    <div style="color: black;margin-left: -51%;height: 75%;font-weight: 27%;width: 147%;margin-top: 8%;"><br>

        <center>
            <h1 style="font-size:50px;font-size: 39px;margin-top: -13%;margin-left: -14%;width:54%;"> Fast Response</h1><br><br>
        </center>
        <h2 style="text-align: justify;margin-left: 34%;margin-top: 1%;width: 52%;font-size: 188%;">you want to make it easy to get paid and pay.That is why we built a payments platform tailored to your needs.Everything you want,nothing you don't
        </h2>

    </div>

</div>

<div class="col-sm-4" style="">
    <img src="images/fast2.png " style="width: 684px;height: 430px;margin-left: 170%;margin-top: -86%;">
</div>
<img src="images/lastbar.png" style="height: 260px;width: 100%;margin-top: 7%;">
<div  style="background: rgb(51, 47, 48);">
    <div style="font-size: 39px;margin-left: 29%;margin-top: -1%;color: WHITE;margin-bottom: -5%;">
        <b>Sign up now and try it yourself</b>
    </div>
    <form action="" style="margin-top: 10%;margin-left: 19%;" ;="">
        <input type="text" name="firstname" value="" placeholder="Email Address" style="width: 53%;height: 60px;font-size: x-large;">
        <input type="submit" value="Get Started" style="height: 60px;width: 302px;margin-left: 5%;color: white;font-size: xx-large;border-radius: 17px;background: orangered;font-weight: 500;">
    </form>





    <div class="" style="">

        <img src="images/kartpay_logo.png " style="height: 76px;width: 304px;margin-left: 19%;margin-top: 5%;">
    </div>
    <div class="" style="font-size: 26%;color: white;margin-top: 2%;margin-right: 0%;">
        <div style="color: white;margin-left: 47%;font-size: 22px;margin-top: -5%;color: white;">
            <a href="#" style="color: white;"><b>DEVELOPER</b>
                &nbsp;  |&nbsp;</a>
            <a href="#" style="color: white;"> <b> SOLUTIONS</b>  &nbsp; |</a>
            <a href="#" style="color: white;"> &nbsp; <b>LOGIN</b> &nbsp; |</a>
            <a href="#" style="color: white;"> <b>SIGN UP</b>  </a>
        </div>
        <br>
        <br>
        <br>

        <hr>
        <center style="font-size: 12px;margin-top: 0%;color: white;font-family: sans-serif;margin-right: 2%;">
            Salasar E-Commerce Total Solution Privated Limited<br>
            235,Old Bagadganj layout,Shastri Nagar Chowk,Nagpur,Maharashtra,India
        </center>
    </div>
</body>
</html>
