@extends('panel.backend.layouts.app')

@push('styles')
<style>
	.cropit-image-preview {
		background-color: #f8f8f8;
		border-radius: 50%;
		background-size: cover;
		border: 1px solid #ccc;
		width: 150px;
		height: 150px;
		cursor: move;
		float: none;
		margin: 0 auto;
	}
	/* Show load indicator when image is being loaded */
	.cropit-image-preview.cropit-image-loading .spinner {opacity: 1;}
	/* Show move cursor when image has been loaded */
	.cropit-image-preview.cropit-image-loaded {cursor: move;}
	/* Gray out zoom slider when the image cannot be zoomed */
	.cropit-image-zoom-input[disabled] {opacity: .2;}
	/* Hide default file input button if you want to use a custom button */
	input.cropit-image-input {visibility: hidden;}
	/* The following styles are only relevant to when background image is enabled */
	/* Translucent background image */
	.cropit-image-background {opacity: .1;}
	/* Style the background image differently when preview area is hovered */
	.cropit-image-background.cropit-preview-hovered {opacity: .2;}
	/**
     * If the slider or anything else is covered by the background image,
     * use non-static position on it
     */
	input.cropit-image-zoom-input {position: relative;}
	/* Limit the background image by adding overflow: hidden */
	#image-cropper {overflow: hidden;}
</style>
@endpush

@section('content')

	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Profile</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				Edit Profile
			</div>
		</div>
		@if (session('message'))
			<div class="row">
				<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
					{!! session('message') !!}
				</div>
			</div>
		@endif
		<div class="row" id="info">
			<!-- <div class="alert alert-success alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Success!</strong> Indicates a successful or positive action.
            </div> -->
		</div>
		<br><br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<button class="btn btn-default btn-sm select-image-btn pull-left"
								type="button">
							<i class="fa fa-image"></i> Select image
						</button>
						<button class="btn btn-default btn-sm pull-right"
								id="update_avatar" type="button">
							<i class="fa fa-save"></i> Update avatar
						</button>
					</div>
				</div>

                <?php
                if($user->avatar_file_name == '' || $user->avatar_file_name == NULL)
                    $profile_pic = asset('/images/vendor/dummy-profile-pic.png');
                else
                    $profile_pic = asset('/images/vendor/' . $user->avatar_file_name);
                ?>

				<div class="row">
					<div class="col-xs-12">
						<div id="image-cropper">
							<div class="cropit-image-preview"
								 style="cursor:default; background-image:url('{{ $profile_pic }}');
										 background-repeat:no-repeat;">
							</div>
							<input type="file" class="cropit-image-input"/>
							<div class="col-xs-2 text-right avatar_hidden_tools">
								<i class="fa fa-fw fa-search-minus"></i>
							</div>
							<div class="col-xs-8 text-right avatar_hidden_tools">
								<input type="range" class="cropit-image-zoom-input"/>
							</div>
							<div class="col-xs-2 text-left avatar_hidden_tools">
								<i class="fa fa-fw fa-search-plus"></i>
							</div>
						</div>
					</div>
				</div>

				<form role="form" action="{{ url('profile/update') }}" method="post">
					{!! csrf_field() !!}
					<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
						<label for="first_name">First Name</label>
						<input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control" id="first_name" onkeydown="return alphaOnly(event);" required>
						@if ($errors->has('first_name'))
							<span class="help-block">
							<strong>{{ $errors->first('first_name') }}</strong>
						</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
						<label for="last_name">Last Name</label>
						<input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control" id="last_name" onkeydown="return alphaOnly(event);" required>
						@if ($errors->has('last_name'))
							<span class="help-block">
							<strong>{{ $errors->first('last_name') }}</strong>
						</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
						<label for="country_code">Country Code</label>
						<input type="text" name="country_code" value="{{ $user->country_code }}" class="form-control" id="country_code" readonly>
						@if ($errors->has('country_code'))
							<span class="help-block">
							<strong>{{ $errors->first('country_code') }}</strong>
						</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
						<label for="contact_no">Contact No</label>
						<input type="text" name="contact_no" value="{{ $user->contact_no }}" class="form-control" id="contact_no" maxlength="10"
							   onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57">
						@if ($errors->has('contact_no'))
							<span class="help-block">
							<strong>{{ $errors->first('contact_no') }}</strong>
						</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<label for="email">Email</label>
						<input type="required" name="email" value="{{ $user->email }}" class="form-control" id="email" required>
						@if ($errors->has('email'))
							<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
						<label for="is_active">Status</label>
						<select class="form-control" name="is_active" id="is_active">
							@if($user->is_active == '1')
								<option value="1" selected>Active</option>
								<option value="0">Not Active</option>
							@else
								<option value="1">Active</option>
								<option value="0" selected>Not Active</option>
							@endif
						</select>
					</div>
					<br>
					<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
						<label for="new_password">Password <small>(Leave empty if you don't want to change password)</small></label><br>
						<label for="new_password">
							<small>
								<p>The password formats are:</p>
								<p>- Minimum has 1 uppercase.</p>
								<p>- Minimum has 1 lowercase.</p>
								<p>- Minimum has 1 symbol.</p>
								<p>- Minimum has 1 number.</p>
								<p>- Minimum length is 8 characters.</p>
								<p>- Maximum length is 16 characters.</p>
							</small>
						</label>
						<input type="password" name="new_password" value="" class="form-control" id="new_password" maxlength="16">
						@if ($errors->has('new_password'))
							<span class="help-block">
							<strong>{{ $errors->first('new_password') }}</strong>
						</span>
						@endif
					</div>

					<div id="div_contact_no_otp" class="form-inline" hidden>
						<label id="label_contact_no_otp"><font color="blue">SMS sent! Check your OTP (SMS is valid only 5 minutes)</font></label><br>

						<div class="input-group" id="group_contact_no_otp" hidden>
							<input type="text" name="contact_no_otp" placeholder="Enter OTP" class="form-control" id="contact_no_otp" maxlength="6" />
							<span class="input-group-btn">
							<a class="btn btn-primary" id="btn_resend_contact_no_otp" onclick="sendOTP('contact_no', 'resend')">Resend OTP</a>
							<a class="btn btn-success" id="btn_contact_no_otp" onclick="verifyOTP('contact_no')">Verify</a>
						</span>
						</div>
					</div>

					<div class="box-footer">
						<button type="submit" id="btn_submit_profile" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

@push('scripts')
<script>

    function alphaOnly(event) {
        var key = event.keyCode;
        return ((key >= 65 && key <= 90) || key == 8);
    }

    function disabledFirstChar(e)
    {
        if (e.keyCode == 8 && $('#country_code').is(":focus") && $('#country_code').val().length < 2) {
            e.preventDefault();
        }
    }

    function sendOTP(elementId, type)
    {
        var contact_no = $('#' + elementId).val();
        if(contact_no.trim() != '')
        {
            $.ajax({
                url:'{{ route("admins.send_otp") }}',
                type:'POST',
                data:{
                    '_token': '{{ csrf_token() }}',
                    'element_id': elementId,
                    'contact_no' : contact_no,
                    'type' : type
                },
                success: function (res) {
                    if (res.response == 'success') {
                        if(res.type == 'send') $('#label_' + res.element_id + '_otp').html('<font color="blue">SMS sent! Check your OTP (SMS is valid only 5 minutes)</font>');
                        else if(res.type == 'resend') $('#label_' + res.element_id + '_otp').html('<font color="blue">SMS re-sent! Check your OTP (SMS is valid only 5 minutes)</font>');
                        $('#div_' + res.element_id + '_otp').show();
                        $('#group_' + res.element_id + '_otp').show();
                    }
                },
                error: function(a, b, c){
                    showTemporaryMessage(c, 'error', 'Error');
                }
            });
        }
    }

    function verifyOTP(elementId)
    {
        var contact_no = $('#' + elementId).val();
        if(contact_no.trim() != '')
        {
            $.ajax({
                url:'{{ route("admins.verify_otp") }}',
                type:'POST',
                data:{
                    '_token': '{{ csrf_token() }}',
                    'element_id': elementId,
                    'contact_no' : contact_no,
                    'otp' : $('#' + elementId + '_otp').val()
                },
                success: function (res) {
                    if (res.response == 'success') {
                        $('#label_' + res.element_id + '_otp').html('<font color="green">' + res.message + '</font>');
                        $('#div_' + res.element_id + '_otp').show();
                        $('#group_' + res.element_id + '_otp').hide();
                    }
                    else if (res.response == 'error') {
                        $('#label_' + res.element_id + '_otp').html('<font color="red">' + res.message + '</font>');
                        $('#div_' + res.element_id + '_otp').show();
                        $('#group_' + res.element_id + '_otp').show();
                    }
                },
                error: function(a, b, c){
                    showTemporaryMessage(c, 'error', 'Error');
                }
            });
        }
    }

    $(function(){
        $('#image-cropper').cropit();

        $('#email').blur(function(){
            if($('#email').val() != '')
            {
                sendOTP('contact_no', 'send');
            }
        });

        $('#contact_no').blur(function(){
            if($('#contact_no').val() != '')
            {
                sendOTP($(this).attr('id'), 'send');
            }
        });

        $(document).on('click', '.select-image-btn', function () {
            $('.cropit-image-input').click();
        });

        $(document).on('change', '.cropit-image-input', function () {
            $('.cropit-image-preview').css('cursor', 'move');
            $('.avatar_hidden_tools').show();
        });

        $(document).on('click', '#update_avatar', function (e) {
            var el = $(this);
            var avatar = $('#image-cropper').cropit('export', {
                type: 'image/jpeg',
                quality: 0.33,
                originalSize: true,
            });
            var data = {'_method':'POST', '_token':'{{ csrf_token() }}', 'avatar':avatar};

            $.ajax({
                url: '{{ url("/profile/update_avatar") }}',
                type: 'POST',
                data: data,
                dataType: 'HTML',
                beforeSend: function () {
                    el.prop('disabled', true);
                },
                complete: function () {
                    el.prop('disabled', false);
                },
                success: function (res) {
                    if (res == 'AVATAR_UPDATED') {
                    } else {
                    }
                },
                error: function (a,b,c) {
                    return false;
                }
            });
        });

        $('#btn_submit_profile').click(function(event){

            //check valid OTP
            $.ajax({
                url:'{{ route("admins.check_valid_otp") }}',
                type:'POST',
                async: false,
                data:{
                    '_token': '{{ csrf_token() }}',
                    'contact_no': $('#contact_no').val()
                },
                success: function (res) {
                    if (res.response != 'success')
                    {
                        showTemporaryMessage(res.message, 'error', 'Error');
                        event.preventDefault();
                        $('html, body').animate({scrollTop: '0px'}, 300);
                    }
                },
                error: function(a, b, c){
                    showTemporaryMessage(c, 'error', 'Error');
                    event.preventDefault();
                }
            });
            //end check valid OTP

        });

    });
</script>
@endpush
