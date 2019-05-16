@extends('merchant.backend.layouts.app')

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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body" style="text-align:center;">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="modalViewDoc" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modalViewDocTitle">Modal Header</h4>
      </div>
      <div class="modal-body" style="text-align:center;" id="modalViewDocBody">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer" id="modalViewDocFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div class="">
	<div class="row">
		<div class="page-title">
			<div class="title_left">
				<h3>Merchant Details</h3>
			</div>
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

	<ul class="nav nav-tabs">
	  <li class="active"><a data-toggle="tab" href="#profile">Profile</a></li>
	  <li><a data-toggle="tab" href="#business_details">Business Details</a></li>
	  <li><a data-toggle="tab" href="#documents">Documents</a></li>
	</ul>

	<div class="tab-content">
		<div id="profile" class="tab-pane fade in active">
			<br>
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
								$profile_pic = asset('images/vendor/' . $user->avatar_file_name);
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
							<label for="first_name">First Name <font color="red">*</font></label>
							<input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control" id="first_name" onkeydown="return alphaOnly(event);" required>
							@if ($errors->has('first_name'))
								<span class="help-block">
									<strong>{{ $errors->first('first_name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
							<label for="last_name">Last Name <font color="red">*</font></label>
							<input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control" id="last_name" onkeydown="return alphaOnly(event);" required>
							@if ($errors->has('last_name'))
								<span class="help-block">
									<strong>{{ $errors->first('last_name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
							<label for="country_code">Country Code <font color="red">*</font></label>
							<input type="text" name="country_code" value="{{ $user->country_code }}" class="form-control" id="country_code" readonly>
							@if ($errors->has('country_code'))
								<span class="help-block">
									<strong>{{ $errors->first('country_code') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
							<label for="contact_no">Contact No <font color="red">*</font></label>
							<input type="text" name="contact_no" value="{{ $user->contact_no }}" class="form-control" id="contact_no" maxlength="10"
										onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57">
							@if ($errors->has('contact_no'))
								<span class="help-block">
									<strong>{{ $errors->first('contact_no') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<label for="email">Email <font color="red">*</font></label>
							<input type="email" name="email" value="{{ $user->email }}" class="form-control" id="email" required>
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
		<div id="business_details" class="tab-pane fade">
			<br>
			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-3 col-sm-1 col-xs-1">
						<b style="font-size: 20px;">A. Business Details</b>
					</div>
				</div>
			</div>

			<br>
			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						BUSINESS LEGAL NAME
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantBusinessDetail){{ $merchantBusinessDetail->business_legal_name }}@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						BUSINESS TRADING NAME
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantBusinessDetail){{ $merchantBusinessDetail->business_trading_name }}@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						REGISTERED ADDRESS
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantBusinessDetail){{ $merchantBusinessDetail->business_registered_address }}@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@if($merchantBusinessDetail)
						@php
							$country = \App\Country::where('id', $merchantBusinessDetail->business_country)->first();
							if($country) echo $country->country_name;
						@endphp
					@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@if($merchantBusinessDetail)
						@php
							$state = \App\State::where('id', $merchantBusinessDetail->business_state)->first();
							if($state) echo $state->state_name;
						@endphp
					@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@if($merchantBusinessDetail)
						@php
							$city = \App\City::where('id', $merchantBusinessDetail->business_city)->first();
							if($city) echo $city->city_name;
						@endphp
					@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						@if($merchantBusinessDetail){{ $merchantBusinessDetail->business_pin_code }}@endif
					</div>
				</div>
			</div>
			<hr style="width: 92%;">
			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-3 col-sm-1 col-xs-1">
						<b style="font-size: 20px;">B. Contact Details</b>
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						OWNER NAME
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantContactDetail){{ $merchantContactDetail->owner_name }}@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						OWNER EMAIL
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
					`	@if($merchantContactDetail){{ $merchantContactDetail->owner_email }}@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						ADDRESS
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantContactDetail){{ $merchantContactDetail->owner_address }}@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						MOBILE NUMBER
					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@if($merchantContactDetail){{ $merchantContactDetail->owner_mobile_no }}@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@if($merchantContactDetail)
						@php
							$country = \App\Country::where('id', $merchantContactDetail->owner_country)->first();
							if($country) echo $country->country_name;
						@endphp
					@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@if($merchantContactDetail)
						@php
							$state = \App\State::where('id', $merchantContactDetail->owner_state)->first();
							if($state) echo $state->state_name;
						@endphp
					@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@if($merchantContactDetail)
						@php
							$city = \App\City::where('id', $merchantContactDetail->owner_city)->first();
							if($city) echo $city->city_name;
						@endphp
					@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						@if($merchantContactDetail){{ $merchantContactDetail->owner_pin_code }}@endif
					</div>
				</div>
			</div>

			<hr style="width: 92%;">

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-3 col-sm-1 col-xs-1">
						<b style="font-size: 20px;">C. Bank Details</b>
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						ACCOUNT NUMBER
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantBankDetail){{ $merchantBankDetail->account_number }}@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						BANK NAME
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantBankDetail)
							@if($merchantBankDetail->bank->bank_name != null)
								{{ $merchantBankDetail->bank->bank_name }}
							@endif
						@endif
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						BANK IFSC CODE
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantBankDetail){{ $merchantBankDetail->bank_ifsc_code }}@endif
					</div>
				</div>
			</div>

			<hr style="width: 92%;">

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-3 col-sm-1 col-xs-1">
						<b style="font-size: 20px;">D. Website Details</b>
						<div class="row" id="website_details_info">

						</div>
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-2 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						DOMAIN NAME
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						@if($merchantWebsiteDetail){{ $merchantWebsiteDetail->domain_name }}@endif
					</div>
				</div>
			</div>
		</div>

		<div id="documents" class="tab-pane fade">
			<br>
			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-3 col-sm-1 col-xs-1">
						<b style="font-size: 20px;">Uploaded Documents</b>
					</div>
				</div>
			</div>
			<br>
			<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">

				@if($merchantDocument)
					@php
						$num = 1;
						if($merchantDocument->proprietor_pan_card_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->proprietor_pan_card_file);
							echo '<p>' . $num . '. Proprietor PAN Card'  . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Proprietor PAN Card\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->gumasta_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->gumasta_file);
							echo '<p>' . $num . '. Shop and Establishment Certifcate.(Gumasta)' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Shop and Establishment Certifcate.(Gumasta)\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->gst_in_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->gst_in_file);
							echo '<p>' . $num . '. GSTIN ID' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'GSTIN ID\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->importer_exporter_code_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->importer_exporter_code_file);
							echo '<p>' . $num . '. Importer/Exporter Code' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Importer/Exporter Code\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->passport_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->passport_file);
							echo '<p>' . $num . '. Passport' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Passport\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->aadhar_card_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->aadhar_card_file);
							echo '<p>' . $num . '. Aadhar Card' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Aadhar Card\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->driving_license_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->driving_license_file);
							echo '<p>' . $num . '. Driving License' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Driving License\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->voter_id_card_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->voter_id_card_file);
							echo '<p>' . $num . '. Voter ID Card' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Voter ID Card\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->property_tax_receipt_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->property_tax_receipt_file);
							echo '<p>' . $num . '. Property TAX Receipt in Proprietor Name' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Property TAX Receipt in Proprietor Name\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->bank_canceled_cheque_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->bank_canceled_cheque_file);
							echo '<p>' . $num . '. Bank Canceled Cheque' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Bank Canceled Cheque\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->audited_balance_sheet_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->audited_balance_sheet_file);
							echo '<p>' . $num . '. Audited Balance Sheet' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Audited Balance Sheet\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->current_account_statement_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->current_account_statement_file);
							echo '<p>' . $num . '. Current Account Statement. (Last 3 Month)' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Current Account Statement. (Last 3 Month)\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->income_tax_return_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->income_tax_return_file);
							echo '<p>' . $num . '. Income tax Returns. (Last Year)' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Income tax Returns. (Last Year)\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($merchantDocument->kartpay_merchant_agreement_file != null)
						{
							$urlDoc = url($filePath . $merchantDocument->kartpay_merchant_agreement_file);
							echo '<p>' . $num . '. Kartpay Merchant Agreement' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Kartpay Merchant Agreement\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
					@endphp
				@else
					<p>You have no uploaded documents yet.</p>
				@endif

				<p></p>

			</div>
		</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>

$(function() {
	$('#new_password').val();
});
function ShowViewDoc(url, title, elementName)
{
	$('#modalViewDocTitle').html(title);
	$('#modalViewDocBody').html('<iframe src="' + url + '" style="width:800px; height:800px;"></iframe>');
	$('#modalViewDocFooter').html('\
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
									');
	$('#modalViewDoc').modal('show');
}

$('#image-cropper').cropit();

$(document).on('click', '.select-image-btn', function ()
{
    $('.cropit-image-input').click();
});

$(document).on('change', '.cropit-image-input', function ()
{
    $('.cropit-image-preview').css('cursor', 'move');
    $('.avatar_hidden_tools').show();
});

$(document).on('click', '#update_avatar', function (e)
{
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

function previewImage(input, imageId, elementInputId, elementHelpBlockId)
{
    //Validation Image
    var file = input.files[0];
    var fileType = file["type"];

    var ValidImageTypes = ["image/jpg", "image/jpeg"];
    if ($.inArray(fileType, ValidImageTypes) < 0)
		{
        clearImageInput(elementInputId, imageId, elementHelpBlockId);
        $('#' + elementInputId).attr('style', '');
        $('#' + elementHelpBlockId).html('<strong><font color="red">Image must be ".jpg or .jpeg" only</font></strong>');
        $('#' + elementHelpBlockId).attr('style', '');
        return false;
    }

    var fileSize = file["size"];
    if(fileSize > 2000000) //Size > 2 MB
    {
        clearImageInput(elementInputId, imageId, elementHelpBlockId);
        $('#' + elementInputId).attr('style', '');
        $('#' + elementHelpBlockId).html('<strong><font color="red">Image must not greater than 2 MB</font></strong>');
        $('#' + elementHelpBlockId).attr('style', '');
        return false;
    }
    //End Validation Image

    //Render Image
    if (input.files && input.files[0])
		{
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#' + imageId).attr('style', 'max-width:200px; max-height:200px; cursor: pointer;');
            $('#' + imageId).attr('src', e.target.result);
            $('#' + elementHelpBlockId).html('');
            $('#' + elementHelpBlockId).attr('style', 'display:none;');
        }
        reader.readAsDataURL(input.files[0]);
    }
    //End Render Image
}

function showImage(elementId, title)
{
    $('.modal-title').html('Show Image ' + title);
    $('.modal-body').html('<img src="' + $('#' + elementId).attr('src') + '" style="max-width:800px; max-height:800px;"></img>');
    $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
    $('#myModal').modal('show');
}

function enableImageInput(elementInputId, elementPreviewId, elementHelpBlockId)
{
    $('#' + elementInputId).attr('style', '');
    $('#' + elementPreviewId).attr('style', 'max-width:200px; max-height:200px; cursor: pointer;');
    $('#' + elementHelpBlockId).html('');
    $('#' + elementHelpBlockId).attr('style', 'display:none;');
}

function clearImageInput(elementInputId, elementPreviewId, elementHelpBlockId)
{
    $('#' + elementInputId).val(null);
    $('#' + elementInputId).attr('style', 'display:none;');
    $('#' + elementPreviewId).attr('src', '');
    $('#' + elementPreviewId).attr('style', 'display:none;');
    $('#' + elementHelpBlockId).html('');
    $('#' + elementHelpBlockId).attr('style', 'display:none;');
}
</script>
@endpush
