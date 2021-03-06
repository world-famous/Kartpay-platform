<html>

<head>
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
	<body>
	
		<div id='g-kartpay_login_export-box' class='ai2html'>
		<!-- Generated by ai2html v0.63 - 2017-04-02 - 12:51 -->
		<!-- ai file: kartpay_login_export -->

		<style type='text/css' media='screen,print'>
			.g-artboard {
				margin:0 auto;
			}
		</style>


		<!-- Artboard: Artboard_1 -->
		<div id='g-kartpay_login_export-Artboard_1' class='g-artboard g-artboard-v3 ' data-min-width='1920'>
			<style type='text/css' media='screen,print'>
				#g-kartpay_login_export-Artboard_1{
					position:relative;
					overflow:hidden;
					width:1920px;
				}
				.g-aiAbs{
					position:absolute;
				}
				.g-aiImg{
					display:block;
					width:100% !important;
				}
				#g-kartpay_login_export-Artboard_1 p{
					font-family:nyt-franklin,arial,helvetica,sans-serif;
					font-size:13px;
					line-height:18px;
					margin:0;
				}
				#g-kartpay_login_export-Artboard_1 .g-aiPstyle0 {
					font-size:18px;
					line-height:22px;
					color:#8d8d8d;
				}
				#g-kartpay_login_export-Artboard_1 .g-aiPstyle1 {
					font-size:20px;
					line-height:24px;
					color:#ffffff;
				}
				#g-kartpay_login_export-Artboard_1 .g-aiPstyle2 {
					font-size:20px;
					line-height:24px;
					color:#000000;
				}
				.g-aiPtransformed p { white-space: nowrap; }
			</style>
			<div id='g-kartpay_login_export-Artboard_1-graphic'>
				<img id='g-ai0-0'
					class='g-aiImg'
					src='{{ asset("/images/kartpay_login_export-Artboard_1.png") }}'
					/>

				<form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}					
				<div id='g-ai0-1' class='g-email_address g-aiAbs' style='top:31.2037%;left:40.8594%;'>
					<input type="text" id="email" name="email" placeholder="Email addresss" class='g-aiPstyle0' style="outline:none;border:none;border-color: transparent;top:31.2037%;left:40.8594%;" value="{{ old('email') }}" required autofocus></input>
				</div>
				<div id='g-ai0-2' class='g-password g-aiAbs' style='top:37.1296%;left:40.8594%;'>
					<input type="text" id="password" name="password" placeholder="Enter your password" class='g-aiPstyle0' style="outline:none;border:none;border-color: transparent;top:31.2037%;left:40.8594%;" required></input>
				</div>
				<a href="{{ route('login') }}">
					<div id='g-ai0-3' class='g-log_in_button g-aiAbs' style='top:47.5926%;left:48.5677%;'>
						<button type="submit" style="background:transparent; border: none !important;"><p class='g-aiPstyle1'>Log In</p></button>
					</div>
				</a>				
                </form>
				
				<a href="{{ route('register') }}">
					<div id='g-ai0-4' class='g-sign_up_button g-aiAbs' style='top:58.3333%;left:48.2552%;'>
						<p class='g-aiPstyle2'>Sign Up</p>
					</div>
				</a>
				<div id='g-ai0-5' class='g-email_address g-aiAbs' style='top:42.3333%;left:48.2552%;'>
					<p class='g-aiPstyle2' id="message">
					@if ($errors->has('email'))
						<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
					@endif
					</p>
				</div>
			</div>
		</div>


		<!-- End ai2html - 2017-04-02 - 12:51 -->
	</div>
	
	</body>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	
	<script>
	
		
	</script> 

</html>
