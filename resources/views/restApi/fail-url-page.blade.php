<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>KartPay</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{asset('css/errorPageCss.css')}}" rel="stylesheet" type="text/css" media="all"/>
<link href='http://fonts.googleapis.com/css?family=Fenix' rel='stylesheet' type='text/css'>
</head>
<body>
  <div class="wrap">
	 <div class="main">
		<h3>kartPay</h3>
		<h1>Invalid Input</h1>
		
		@foreach($errors->all() as $error)
		<p>
			<span>
					{{$error}}
			</span>
		</p>
		@endforeach

		<p>
			<span>
					<a href="#">Go Back</a>
			</span>
		</p>
		<div class="icons">
		<p>Follow us on:</p>
		  <ul>
		  	 <li><a href="#"><img src="{{asset('images/failedUrl/img1.png')}}"></a></li>
		     <li><a href="#"><img src="{{asset('images/failedUrl/img2.png')}}"></a></li>
		     <li><a href="#"><img src="{{asset('images/failedUrl/img3.png')}}"></a></li>
		     <li><a href="#"><img src="{{asset('images/failedUrl/img4.png')}}"></a></li>
		     <li><a href="#"><img src="{{asset('images/failedUrl/img5.png')}}"></a></li>
		  </ul>	
	   </div>
   </div>
	<div class="footer">
		<p>© All rights Reseverd | Design by  <a href="http://w3layouts.com/">W3Layouts</a></p>
    </div>
  </div>
</body>
</html>

