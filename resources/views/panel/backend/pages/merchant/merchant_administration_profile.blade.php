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

<div class="">
	<div class="row">
		<div class="page-title">
			<div class="title_left">
				<h3>Merchant Details</h3>
			</div>
		</div>
	</div>
	<a href="{{ url('/merchant_administration') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>
	
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
	  <li><a data-toggle="tab" href="#business_information">Business Information</a></li>
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
							if(Auth::user()->avatar_file_name == '' || Auth::user()->avatar_file_name == NULL)
								$profile_pic = asset('images/vendor/dummy-profile-pic.png');
							else
								$profile_pic = asset('images/vendor/' . Auth::user()->avatar_file_name);
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
					
					<form role="form" action="{{ route('admins.update_merchant_profile', ['id' => $user->id]) }}" method="post">
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
						
						<div class="form-group{{ $errors->has('live_domain_active') ? ' has-error' : '' }}">
							<label for="live_domain_active">Live Domain Status</label>
							<select class="form-control" name="live_domain_active" id="live_domain_active">
							@if($user->live_domain_active == '1')
								<option value="1" selected>Active</option>
								<option value="0">Not Active</option>
							@else
								<option value="1">Active</option>
								<option value="0" selected>Not Active</option>
							@endif
							</select>
						</div>
						
						<br>
						<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}" hidden>
							<label for="new_password">Password <small>(Leave empty if you don't want to change password)</small></label><br>
							<label for="new_password">
								<small>
									<p>The password formats are:</p>
									<p>- Minimum has 1 uppercase.</p>
									<p>- Minimum has 1 lowercase.</p>
									<p>- Minimum has 1 symbol.</p>
									<p>- Minimum has 1 number.</p>
									<p>- Maximum length is 8 characters.</p>
								</small>
							</label>
							<input type="password" name="new_password" value="" class="form-control" id="new_password" maxlength="8">
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
							<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Submit</button>
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
						{{ $merchantBusinessDetail->business_legal_name }}
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
						{{ $merchantBusinessDetail->business_trading_name }}
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
						{{ $merchantBusinessDetail->business_registered_address }} 
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@php
						$country = \App\Country::where('id', $merchantBusinessDetail->business_country)->first();
						if($country) echo $country->country_name;
					@endphp
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">
						
					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@php
						$state = \App\State::where('id', $merchantBusinessDetail->business_state)->first();
						if($state) echo $state->state_name;
					@endphp
					</div>
				</div>
			</div>

			</br>
			
			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">
						
					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@php
						$city = \App\City::where('id', $merchantBusinessDetail->business_city)->first();
						if($city) echo $city->city_name;
					@endphp
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">
						
					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						{{ $merchantBusinessDetail->business_pin_code }}
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
						{{ $merchantContactDetail->owner_name }}
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
					`	{{ $merchantContactDetail->owner_email }}
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
						{{ $merchantContactDetail->owner_address }} 
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
					{{ $merchantContactDetail->owner_mobile_no }}
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">

					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@php
						$country = \App\Country::where('id', $merchantContactDetail->owner_country)->first();
						if($country) echo $country->country_name;
					@endphp
					</div>
				</div>
			</div>

			</br>
			
			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">
						
					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">	
					@php
						$state = \App\State::where('id', $merchantContactDetail->owner_state)->first();
						if($state) echo $state->state_name;
					@endphp
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">
					
					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
					@php
						$city = \App\City::where('id', $merchantContactDetail->owner_city)->first();
						if($city) echo $city->city_name;
					@endphp
					</div>
				</div>
			</div>

			</br>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-4 col-sm-1 col-xs-1">
					
					</div>
					<div class="col-md-2 col-sm-1 col-xs-1">
						{{ $merchantContactDetail->owner_pin_code }}
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
						{{ $merchantBankDetail->account_number }}
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
						{{ $merchantBankDetail->bank_name }}
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
						{{ $merchantBankDetail->bank_ifsc_code }}
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
						@if($merchantWebsiteDetail->domain_name != null ) {{ $merchantWebsiteDetail->domain_name }} @endif
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
				
				@if($merchantDocument != null)
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

/*
function alphaOnly(event) {
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8);
}

function alphaOnlyWithSpace(event) {
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8 || key == 32);
}

function disabledFirstChar(e)
{
    if (e.keyCode == 8 && $('#country_code').is(":focus") && $('#country_code').val().length < 2) {
      e.preventDefault();
  }
}

function addPerson()
{
	$('#total_person').val(parseInt($('#total_person').val()) + 1);
	var x = $('#total_person').val();
	
	$('#div_personal_information').append('\
							<div class="x_panel" id="panel_person_' + x + '">\
							  <div class="x_title">\
								<h2>Person Details</h2>\
								<ul class="nav navbar-right panel_toolbox">\
								  <li><a href="#content_' + x + '" data-toggle="collapse" class="collapse-link"><button class="btn btn-info">Expand/Collapse</button></a>\
								  </li>\
								  <li><a class="close-link"><button class="btn btn-danger" onclick="removePerson(\'' + x + '\')">Remove</button></i></a>\
								  </li>\
								</ul>\
								<div class="clearfix"></div>\
							  </div>\
							  <div class="x_content collapse" id="content_' + x + '">\
								<div class="form-group" id="div_owner_name_' + x + '">\
									<label for="owner_name_' + x + '">Owner Name</label>\
									<input type="text" name="owner_name_' + x + '" value="{{ $user->first_name }} {{ $user->last_name }}" class="form-control" id="owner_name_' + x + '" maxlength="255" onkeydown="return alphaOnly(event);" required>\
									<span class="help-block" id="help_block_owner_name_' + x + '">\
										<strong></strong>\
									</span>\
								</div>\
								<div class="form-group" id="div_personal_address_' + x + '">\
									<label for="personal_address">Personal Address <font color="red">*</font></label>\
									<input type="text" name="personal_address_' + x + '" value="" class="form-control" id="personal_address_' + x + '" maxlength="150" onkeydown="return alphaOnly(event);" required>\
										<span class="help-block">\
											<strong></strong>\
										</span>\
								</div>\
								<div class="form-group" id="div_personal_contact_no_' + x + '">\
									<label for="personal_contact_no_' + x + '">Personal Contact Number <font color="red">*</font></label>\
									<input type="text" name="personal_contact_no_' + x + '" value="" class="form-control" id="personal_contact_no_' + x + '" maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>\
									<div id="div_personal_contact_no_otp_' + x + '" class="form-inline" hidden>\
										<label id="label_personal_contact_no_otp_' + x + '"><font color="blue">SMS sent! Check your OTP (SMS is valid only 5 minutes)</font></label><br>\
										\
										<div class="input-group" id="group_personal_contact_no_otp_' + x + '" hidden>\
											<input type="text" name="personal_contact_no_otp_' + x + '" placeholder="Enter OTP" class="form-control" id="personal_contact_no_otp_' + x + '" maxlength="6" />\
											<span class="input-group-btn">\
											<a class="btn btn-primary" id="btn_resend_personal_contact_no_otp_{{ $countPersonal }}" onclick="sendOTPPersonal(\'personal_contact_no_' + x + '\', \'personal_contact_no\', \'resend\', ' + x + ')">Resend OTP</a>\
												<a class="btn btn-success" id="btn_personal_contact_no_otp_' + x + '" onclick="verifyOTPPersonal(\'personal_contact_no_' + x + '\', \'personal_contact_no_otp_\', ' + x + ')">Verify</a>\
											</span>\
										</div>\
									</div>\
									<span class="help-block">\
										<strong></strong>\
									</span>\
								</div>\
								<div class="form-group" id="div_personal_information_city_' + x + '">\
									<label for="personal_information_city_' + x + '">City <font color="red">*</font></label>\
									<input type="text" name="personal_information_city_' + x + '" value="" class="form-control" id="personal_information_city_' + x + '" maxlength="20" onkeydown="return alphaOnly(event);" required>\
										<span class="help-block" id="help_block_personal_information_city_' + x + '">\
											<strong></strong>\
										</span>\
								</div>\
								<div class="form-group" id="div_personal_information_state_' + x + '">\
									<label for="personal_information_state_' + x + '">State <font color="red">*</font></label>\
									<select class="form-control" name="personal_information_state_' + x + '" id="personal_information_state_' + x + '" required>\
										<option value="">Select State</option>\
										<option value="Andhra Pradesh">Andhra Pradesh</option>\
										<option value="Arunachal Pradesh" >Arunachal Pradesh</option>\
										<option value="Assam">Assam</option>\
										<option value="Bihar">Bihar</option>\
										<option value="Chhattisgarh">Chhattisgarh</option>\
										<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>\
										<option value="Daman and Diu">Daman and Diu</option>\
										<option value="Delhi">Delhi</option>\
										<option value="Goa">Goa</option>\
										<option value="Gujarat">Gujarat</option>\
										<option value="Haryana">Haryana</option>\
										<option value="Himachal Pradesh">Himachal Pradesh</option>\
										<option value="Jammu and Kashmir">Jammu and Kashmir</option>\
										<option value="Jharkhand">Jharkhand</option>\
										<option value="Karnataka">Karnataka</option>\
										<option value="Kerala">Kerala</option>\
										<option value="Madhya Pradesh">Madhya Pradesh</option>\
										<option value="Maharashtra">Maharashtra</option>\
										<option value="Manipur">Manipur</option>\
										<option value="Meghalaya">Meghalaya</option>\
										<option value="Mizoram">Mizoram</option>\
										<option value="Nagaland">Nagaland</option>\
										<option value="Orissa">Orissa</option>\
										<option value="Puducherry">Puducherry</option>\
										<option value="Punjab">Punjab</option>\
										<option value="Rajasthan">Rajasthan</option>\
										<option value="Sikkim">Sikkim</option>\
										<option value="Tamil Nadu">Tamil Nadu</option>\
										<option value="Telangana">Telangana</option>\
										<option value="Tripura">Tripura</option>\
										<option value="Uttar Pradesh">Uttar Pradesh</option>\
										<option value="Uttarakhand">Uttarakhand</option>\
										<option value="West Bengal">West Bengal</option>\
									</select>\
								</div>\
								<div class="form-group" div="div_personal_information_country_' + x + '">\
									<label for="personal_information_country_' + x + '">Country <font color="red">*</font></label>\
									<input type="text" name="personal_information_country_' + x + '" value="India" class="form-control" id="personal_information_country_' + x + '" readonly required>\
										<span class="help-block" id="help_block_personal_information_country_' + x + '">\
											<strong></strong>\
										</span>\
								</div>\
								<br>\
								<h4>Identity Proof</h4>\
								<div class="form-group" id="div_personal_pan_card_check_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="personal_pan_card_check_' + x + '" name="personal_pan_card_check_' + x + '" value="">Personal Pan Card <font color="red">*</font></label>\
									</div>\
									<input type="text" name="personal_pan_card_' + x + '" placeholder="Number of Personal Pan Card. ex: AAUCS6728E" value="" class="form-control" id="personal_pan_card_' + x + '" onkeypress="if(event.charCode == 32) return false;" style="display:none;" maxlength="50" required>\
									\
									<input type="file" name="personal_pan_card_file_' + x + '" value="" class="form-control" id="personal_pan_card_file_' + x + '" style="display:none;" required="true" />\
									\
									<img src="" id="personal_pan_card_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImageOnly(\'personal_pan_card_preview_' + x + '\', \'Personal Pan Card\')" />\
									\
									<span class="help-block" id="personal_pan_card_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
								<div class="form-group" id="div_aadhar_no_check_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="aadhar_no_check_' + x + '" name="aadhar_no_check_' + x + '" value="">Aadhar Number</label>\
									</div>\
									<input type="text" name="aadhar_no_' + x + '" placeholder="Number of Aadhar: 12 digits" value="" class="form-control" id="aadhar_no_' + x + '" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="12" style="display:none;" >\
									\
									<input type="file" name="aadhar_no_file_' + x + '" value="" class="form-control" id="aadhar_no_file_' + x + '" style="display:none;" />\
									\
									<img src="" id="aadhar_no_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImageOnly(\'aadhar_no_preview_' + x + '\', \'Aadhar Number\')" />\
									\
									<span class="help-block" id="aadhar_no_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
								<div class="form-group" id="div_passport_no_check_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="passport_no_check_' + x + '" name="passport_no_check_' + x + '" value="">Passport Number</label>\
									</div>\
									<input type="text" name="passport_no_' + x + '" placeholder="Number of Passport: 8 alphanumeric" value="" class="form-control" id="passport_no_' + x + '" maxlength="8" onkeypress="if(event.charCode == 32) return false;" style="display:none;" >\
									\
									<input type="file" name="passport_no_file_' + x + '" value="" class="form-control" id="passport_no_file_' + x + '" style="display:none;" />\
									\
									<img src="" id="passport_no_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImageOnly(\'passport_no_preview_' + x + '\', \'Passpor Number\')" />\
									\
									<span class="help-block" id="passport_no_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
								<div class="form-group" id="div_voter_id_card_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="voter_id_card_check_' + x + '" name="voter_id_card_check_' + x + '" value="">Voter ID Card</label>\
									</div>\
									<input type="file" name="voter_id_card_file_' + x + '" value="" class="form-control" id="voter_id_card_file_' + x + '" style="display:none;" />\
									\
									<img src="" id="voter_id_card_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImageOnly(\'voter_id_card_preview_' + x + '\', \'Voter ID Card\')" />\
									\
									<span class="help-block" id="voter_id_card_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
								<br>\
								<h4>Address Proof</h4>\
								<div class="form-group" id="div_electricity_bill_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="electricity_bill_check_' + x + '" name="electricity_bill_check_' + x + '" value="">Electricity Bill</label>\
									</div>\
									<input type="file" name="electricity_bill_file_' + x + '" value="" class="form-control" id="electricity_bill_file_' + x + '" style="display:none;" />\
									\
									<img src="" id="electricity_bill_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImageOnly(\'electricity_bill_preview_' + x + '\', \'Electricity Bill\')" />\
									\
									<span class="help-block" id="electricity_bill_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
								<div class="form-group" id="div_landline_bill_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="landline_bill_check_' + x + '" name="landline_bill_check_' + x + '" value="">Landline Bill</label>\
									</div>\
									<input type="file" name="landline_bill_file_' + x + '" value="" class="form-control" id="landline_bill_file_' + x + '" style="display:none;" />\
									\
									<img src="" id="landline_bill_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImageOnly(\'landline_bill_preview_' + x + '\', \'Landline Bill\')" />\
									\
									<span class="help-block" id="landline_bill_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
								<div class="form-group" id="div_bank_account_statement_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="bank_account_statement_check_' + x + '" name="bank_account_statement_check_' + x + '" value="">Bank Account Statement</label>\
									</div>\
									<input type="file" name="bank_account_statement_file_' + x + '" value="" class="form-control" id="bank_account_statement_file_' + x + '" style="display:none;" />\
									\
									<img src="" id="bank_account_statement_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImageOnly(\'bank_account_statement_preview_' + x + '\', \'Bank Account Statement\')" />\
									\
									<span class="help-block" id="bank_account_statement_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
							  </div>\
						 </div>\
						 ');
						 
	$('#personal_contact_no_' + x).blur(function(){
			if($('#personal_contact_no_' + x).val() != '')
			{
				sendOTPPersonal($(this).attr('id'), 'personal_contact_no', 'send', x);
			}
	});
	
	$('#personal_pan_card_check_' + x).change(function(){
			if($('#personal_pan_card_check_' + x).is(':checked'))
			{
				enableImageInput('personal_pan_card_file_' + x, 'personal_pan_card_preview_' + x, 'help_block_personal_pan_card_' + x);
				$('#personal_pan_card_' + x).attr('style', '');
				$('#personal_pan_card_check_').val('1');
			}
			else
			{
				clearImageInput('personal_pan_card_file_' + x, 'personal_pan_card_preview_' + x, 'help_block_personal_pan_card_' + x);
				$('#personal_pan_card_' + x).val('');
				$('#personal_pan_card_' + x).attr('style', 'display: none;');
				$('#personal_pan_card_check_').val('0');
			}		
		});
	$('#personal_pan_card_file_' + x).change(function(){
		previewImage(this, 'personal_pan_card_preview_' + x, 'personal_pan_card_file_' + x, 'help_block_personal_pan_card_' + x);
	});
	
	$('#aadhar_no_check_' + x).change(function(){
			if($('#aadhar_no_check_' + x).is(':checked'))
			{
				enableImageInput('aadhar_no_file_' + x, 'aadhar_no_preview_' + x, 'help_block_aadhar_no_' + x);
				$('#aadhar_no_' + x).attr('style', '');
				$('#aadhar_no_check_').val('1');
			}
			else
			{
				clearImageInput('aadhar_no_file_' + x, 'aadhar_no_preview_' + x, 'help_block_aadhar_no_' + x);
				$('#aadhar_no_' + x).val('');
				$('#aadhar_no_' + x).attr('style', 'display: none;');
				$('#aadhar_no_check_').val('0');
			}		
		});
	$('#aadhar_no_file_' + x).change(function(){
		previewImage(this, 'aadhar_no_preview_' + x, 'aadhar_no_file_' + x, 'help_block_aadhar_no_' + x);
	});
	
	$('#passport_no_check_' + x).change(function(){
			if($('#passport_no_check_' + x).is(':checked'))
			{
				enableImageInput('passport_no_file_' + x, 'passport_no_preview_' + x, 'help_block_passport_no_' + x);
				$('#passport_no_' + x).attr('style', '');
				$('#passport_no_check_').val('1');
			}
			else
			{
				clearImageInput('passport_no_file_' + x, 'passport_no_preview_' + x, 'help_block_passport_no_' + x);
				$('#passport_no_' + x).val('');
				$('#passport_no_' + x).attr('style', 'display: none;');
				$('#passport_no_check_').val('0');
			}		
		});
	$('#passport_no_file_' + x).change(function(){
		previewImage(this, 'passport_no_preview_' + x, 'passport_no_file_' + x, 'help_block_passport_no_' + x);
	});
	
	$('#voter_id_card_check_' + x).change(function(){
			if($('#voter_id_card_check_' + x).is(':checked'))
			{
				enableImageInput('voter_id_card_file_' + x, 'voter_id_card_preview_' + x, 'help_block_voter_id_card_' + x);
				$('#voter_id_card_' + x).attr('style', '');
				$('#voter_id_card_check_').val('1');
			}
			else
			{
				clearImageInput('voter_id_card_file_' + x, 'voter_id_card_preview_' + x, 'help_block_voter_id_card_' + x);
				$('#voter_id_card_' + x).attr('style', 'display: none;');
				$('#voter_id_card_check_').val('0');
			}		
		});
	$('#voter_id_card_file_' + x).change(function(){
		previewImage(this, 'voter_id_card_preview_' + x, 'voter_id_card_file_' + x, 'help_block_voter_id_card_' + x);
	});
	
	$('#electricity_bill_check_' + x).change(function(){
			if($('#electricity_bill_check_' + x).is(':checked'))
			{
				enableImageInput('electricity_bill_file_' + x, 'electricity_bill_preview_' + x, 'help_block_electricity_bill_' + x);
				$('#electricity_bill_' + x).attr('style', '');
				$('#electricity_bill_check_').val('1');
			}
			else
			{
				clearImageInput('electricity_bill_file_' + x, 'electricity_bill_preview_' + x, 'help_block_electricity_bill_' + x);
				$('#electricity_bill_' + x).attr('style', 'display: none;');
				$('#electricity_bill_check_').val('0');
			}		
		});
	$('#electricity_bill_file_' + x).change(function(){
		previewImage(this, 'electricity_bill_preview_' + x, 'electricity_bill_file_' + x, 'help_block_electricity_bill_' + x);
	});
	
	$('#landline_bill_check_' + x).change(function(){
			if($('#landline_bill_check_' + x).is(':checked'))
			{
				enableImageInput('landline_bill_file_' + x, 'landline_bill_preview_' + x, 'help_block_landline_bill_' + x);
				$('#landline_bill_' + x).attr('style', '');
				$('#landline_bill_check_').val('1');
			}
			else
			{
				clearImageInput('landline_bill_file_' + x, 'landline_bill_preview_' + x, 'help_block_landline_bill_' + x);
				$('#landline_bill_' + x).attr('style', 'display: none;');
				$('#landline_bill_check_').val('0');
			}		
		});
	$('#landline_bill_file_' + x).change(function(){
		previewImage(this, 'landline_bill_preview_' + x, 'landline_bill_file_' + x, 'help_block_landline_bill_' + x);
	});
	
	$('#bank_account_statement_check_' + x).change(function(){
			if($('#bank_account_statement_check_' + x).is(':checked'))
			{
				enableImageInput('bank_account_statement_file_' + x, 'bank_account_statement_preview_' + x, 'help_block_bank_account_statement_' + x);
				$('#bank_account_statement_' + x).attr('style', '');
				$('#bank_account_statement_check_').val('1');
			}
			else
			{
				clearImageInput('bank_account_statement_file_' + x, 'bank_account_statement_preview_' + x, 'help_block_bank_account_statement_' + x);
				$('#bank_account_statement_' + x).attr('style', 'display: none;');
				$('#bank_account_statement_check_').val('0');
			}		
		});
	$('#bank_account_statement_file_' + x).change(function(){
		previewImage(this, 'bank_account_statement_preview_' + x, 'bank_account_statement_file_' + x, 'help_block_bank_account_statement_' + x);
	});
}

function removePerson(x)
{
	$('#panel_person_' + x).remove();	
}

function sendOTP(elementId, type)
{
	var contact_no = $('#' + elementId).val();
	if(contact_no.trim() != '')
	{
		$.ajax({
		  url:'{{ route("admins.send_merchant_otp", ["id" => $user->id]) }}',
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

function sendOTPPersonal(elementId, elementOtpId, type, number)
{
	var contact_no = $('#' + elementId).val();
	if(contact_no.trim() != '')
	{
		$.ajax({
		  url:'{{ route("admins.send_merchant_otp", ["id" => $user->id]) }}',
		  type:'POST',
		  data:{
				'_token': '{{ csrf_token() }}', 
				'element_id': elementId,
				'contact_no' : contact_no,
				'type' : type
		  },
		  success: function (res) {
			if (res.response == 'success') {
				if(res.type == 'send') $('#label_' + elementOtpId + '_otp_' + number).html('<font color="blue">SMS sent! Check your OTP (SMS is valid only 5 minutes)</font>');
				else if(res.type == 'resend') $('#label_' + elementOtpId + '_otp_' + number).html('<font color="blue">SMS re-sent! Check your OTP (SMS is valid only 5 minutes)</font>');
				$('#div_' + elementOtpId + '_otp_' + number).show();
				$('#group_' + elementOtpId + '_otp_' + number).show();
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
		  url:'{{ route("admins.verify_merchant_otp", ["id" => $user->id]) }}',
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

function verifyOTPPersonal(elementId, elementOtpId, number)
{
	var contact_no = $('#' + elementId).val();
	if(contact_no.trim() != '')
	{
		$.ajax({
		  url:'{{ route("admins.verify_merchant_otp", ["id" => $user->id]) }}',
		  type:'POST',
		  data:{
				'_token': '{{ csrf_token() }}', 
				'element_id': elementId,
				'contact_no' : contact_no,
				'otp' : $('#' + elementOtpId + number).val()
		  },
		  success: function (res) {
			if (res.response == 'success') {
				$('#label_' + elementOtpId + number).html('<font color="green">' + res.message + '</font>');			
				$('#div_' + elementOtpId + number).show();
				$('#group_' + elementOtpId + number).hide();
			}
			else if (res.response == 'error') {
				$('#label_' + elementOtpId + number).html('<font color="red">' + res.message + '</font>');			
				$('#div_' + elementOtpId + number).show();
				$('#group_' + elementOtpId + number).show();
			}
		  },
		  error: function(a, b, c){
			showTemporaryMessage(c, 'error', 'Error');
		  }
		});
	}
}

function previewImage(input, imageId, elementInputId, elementHelpBlockId) {

	//Validation Image
	var file = input.files[0];
	var fileType = file["type"];	
	
	var ValidImageTypes = ["image/jpg", "image/jpeg"];
	if ($.inArray(fileType, ValidImageTypes) < 0) {
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
    if (input.files && input.files[0]) {
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

function showImageOnly(elementId, title)
{
	$('.modal-title').html('Show Image ' + title);
	
	if(is_verified == '-1') 
	{
		verifiedElm = '<span class="label label-danger">Rejected</span>';
		rejectedBtn = '';	
	}
	else if(is_verified == '1') 
	{
		verifiedElm = '<span class="label label-success">Verified</span>';
		verifiedBtn = '';
	}
	else if(is_verified == '0') 
	{
		verifiedElm = '<span class="label label-warning">Unverified</span>';
		unverifiedBtn = '';
	}
	
	$('.modal-body').html('\
							<img src="' + $('#' + elementId).attr('src') + '" style="max-width:800px; max-height:800px;"></img>\
						  ');
	$('.modal-footer').html('\
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
							');
	$('#myModal').modal('show');
}

function showImage(elementId, title, is_verified, document_id)
{
	$('.modal-title').html('Show Image ' + title);
	
	var verifiedElm = '';
	
	var verifiedBtn = '<button type="button" class="btn btn-success" data-dismiss="modal" onclick="markVerification(1, \'' + document_id + '\')">Mark \'Verified\'</button>';
	var rejectedBtn = '<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="markVerification(-1, \'' + document_id + '\')">Mark \'Rejected\'</button>';
	var unverifiedBtn = '<button type="button" class="btn btn-warning" data-dismiss="modal" onclick="markVerification(0, \'' + document_id + '\')">Mark \'Unverified\'</button>';
	
	if(is_verified == '-1') 
	{
		verifiedElm = '<span class="label label-danger">Rejected</span>';
		rejectedBtn = '';	
	}
	else if(is_verified == '1') 
	{
		verifiedElm = '<span class="label label-success">Verified</span>';
		verifiedBtn = '';
	}
	else if(is_verified == '0') 
	{
		verifiedElm = '<span class="label label-warning">Unverified</span>';
		unverifiedBtn = '';
	}
	
	$('.modal-body').html('\
							<h3>' + verifiedElm + '</h3><br><br>\
							<img src="' + $('#' + elementId).attr('src') + '" style="max-width:800px; max-height:800px;"></img>\
						  ');
	$('.modal-footer').html('\
								' + verifiedBtn +'\
								' + unverifiedBtn +'\
								' + rejectedBtn +'\
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
							');
	$('#myModal').modal('show');
}

function showImagePersonal(elementId, title, is_verified, document_id, personal_id, num_element)
{
	$('.modal-title').html('Show Image ' + title);
	
	var verifiedElm = '';
	
	var verifiedBtn = '<button type="button" class="btn btn-success" data-dismiss="modal" onclick="markVerificationPersonal(1, \'' + document_id + '\', \'' + personal_id + '\', \'' + num_element + '\')">Mark \'Verified\'</button>';
	var rejectedBtn = '<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="markVerificationPersonal(-1, \'' + document_id + '\', \'' + personal_id + '\', \'' + num_element + '\')">Mark \'Rejected\'</button>';
	var unverifiedBtn = '<button type="button" class="btn btn-warning" data-dismiss="modal" onclick="markVerificationPersonal(0, \'' + document_id + '\', \'' + personal_id + '\', \'' + num_element + '\')">Mark \'Unverified\'</button>';
	
	if(is_verified == '-1') 
	{
		verifiedElm = '<span class="label label-danger">Rejected</span>';
		rejectedBtn = '';	
	}
	else if(is_verified == '1') 
	{
		verifiedElm = '<span class="label label-success">Verified</span>';
		verifiedBtn = '';
	}
	else if(is_verified == '0') 
	{
		verifiedElm = '<span class="label label-warning">Unverified</span>';
		unverifiedBtn = '';
	}
	
	$('.modal-body').html('\
							<h3>' + verifiedElm + '</h3><br><br>\
							<img src="' + $('#' + elementId).attr('src') + '" style="max-width:800px; max-height:800px;"></img>\
						  ');
	$('.modal-footer').html('\
								' + verifiedBtn +'\
								' + unverifiedBtn +'\
								' + rejectedBtn +'\
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
							');
	$('#myModal').modal('show');
}

function markSpanElement(elementId, elementPreviewId, mark, title, documentId)
{
	var type = 'success';
	var is_verified = 0;
	if(mark == 'Verified') 
	{
		type = 'success';
		is_verified = '1';
	}
	else if(mark == 'Unverified') 
	{
		type = 'warning';
		is_verified = '0';
	}
	else if(mark == 'Rejected') 
	{
		type = 'danger';
		is_verified = '-1';
	}
	$('#' + elementId).attr('class', 'label label-' + type);
	$('#' + elementId).html(mark);	
	
	$('#' + elementPreviewId).attr('onclick', "showImage('" + elementPreviewId + "', '" + title + "', '" + is_verified + "', '" + documentId + "')");
}

function markSpanElementPersonal(elementId, elementPreviewId, mark, title, documentId, personal_id, numElement)
{
	var type = 'success';
	var is_verified = 0;
	if(mark == 'Verified') 
	{
		type = 'success';
		is_verified = '1';
	}
	else if(mark == 'Unverified') 
	{
		type = 'warning';
		is_verified = '0';
	}
	else if(mark == 'Rejected') 
	{
		type = 'danger';
		is_verified = '-1';
	}
	$('#' + elementId).attr('class', 'label label-' + type);
	$('#' + elementId).html(mark);	
	
	$('#' + elementPreviewId).attr('onclick', "showImagePersonal('" + elementPreviewId + "', '" + title + "', '" + is_verified + "', '" + documentId + "', '" + personal_id + "', '" + numElement + "')");
}

function markVerification(is_verified, document_id)
{
	$.ajax({
      url:'{{ route("admins.mark_merchant_document_verification", ["id" => $user->id]) }}',
      type:'POST',
      data:{
			'_token': '{{ csrf_token() }}', 
			'document_id': document_id,
			'is_verified': is_verified
	  },
      success: function (res) {
        if (res.response == 'success') 
		{
          showTemporaryMessage(res.message, 'success', 'Success');
		  if(res.document_id == 'vat_doc') markSpanElement('span_vat_doc', 'vat_doc_preview', res.mark, 'VAT', res.document_id);
		  if(res.document_id == 'cst_doc') markSpanElement('span_cst_doc', 'cst_doc_preview', res.mark, 'CST', res.document_id);
		  if(res.document_id == 'service_tax_doc') markSpanElement('span_service_tax_doc', 'service_tax_doc_preview', res.mark, 'Service Tax', res.document_id);
		  if(res.document_id == 'gumasta_doc') markSpanElement('span_gumasta_doc', 'gumasta_doc_preview', res.mark, 'Gumasta', res.document_id);
		  
		}
		else 
		{
			showTemporaryMessage(res.error, 'error', 'Error'); 
		}

		$('#myModal').modal('hide');
		
      },
      error: function(a, b, c){
        showTemporaryMessage(c, 'error', 'Error');
      }
    });
}

function markVerificationPersonal(is_verified, document_id, personal_id, num_element)
{
	$.ajax({
      url:'{{ route("admins.mark_merchant_personal_document_verification", ["id" => $user->id]) }}',
      type:'POST',
      data:{
			'_token': '{{ csrf_token() }}', 
			'document_id': document_id,
			'personal_id': personal_id,
			'is_verified': is_verified,
			'num_element': num_element
	  },
      success: function (res) {
        if (res.response == 'success') 
		{
          showTemporaryMessage(res.message, 'success', 'Success');
		  if(res.document_id == 'personal_pan_card') markSpanElementPersonal('span_personal_pan_card_' + res.num_element, 'personal_pan_card_preview_' + res.num_element, res.mark, 'Personal Pan Card', res.document_id, res.personal_id, res.num_element);
		  if(res.document_id == 'aadhar_no') markSpanElementPersonal('span_aadhar_no_' + res.num_element, 'aadhar_no_preview_' + res.num_element, res.mark, 'Aadhar Number', res.document_id, res.personal_id, res.num_element);
		  if(res.document_id == 'passport_no') markSpanElementPersonal('span_passport_no_' + res.num_element, 'passport_no_preview_' + res.num_element, res.mark, 'Passport Number', res.document_id, res.personal_id, res.num_element);
		  if(res.document_id == 'voter_id_card') markSpanElementPersonal('span_voter_id_card_' + res.num_element, 'voter_id_card_preview_' + res.num_element, res.mark, 'Voter ID Card', res.document_id, res.personal_id, res.num_element);
		  if(res.document_id == 'electricity_bill') markSpanElementPersonal('span_electricity_bill_' + res.num_element, 'electricity_bill_preview_' + res.num_element, res.mark, 'Electricity Bill', res.document_id, res.personal_id, res.num_element);
		  if(res.document_id == 'landline_bill') markSpanElementPersonal('span_landline_bill_' + res.num_element, 'landline_bill_preview_' + res.num_element, res.mark, 'Landline Bill', res.document_id, res.personal_id, res.num_element);
		  if(res.document_id == 'bank_account_statement') markSpanElementPersonal('span_bank_account_statement_' + res.num_element, 'bank_account_statement_preview_' + res.num_element, res.mark, 'Bank Account Statement', res.document_id, res.personal_id, res.num_element);
		}
		else 
		{
			showTemporaryMessage(res.error, 'error', 'Error'); 
		}

		$('#myModal').modal('hide');
		
      },
      error: function(a, b, c){
        showTemporaryMessage(c, 'error', 'Error');
      }
    });
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


$(function(){
	
		// Start Preview Image (Business Information)
		$("#vat_doc_check").change(function(){
			if($('#vat_doc_check').is(':checked'))
			{
				enableImageInput('vat_doc_file', 'vat_doc_preview', 'vat_doc_help_block');
				$('#vat_doc_check').val('1');
			}
			else
			{
				clearImageInput('vat_doc_file', 'vat_doc_preview', 'vat_doc_help_block');
				$('#vat_doc_check').val('0');
			}		
		});
		$("#vat_doc_file").change(function(){
			previewImage(this, 'vat_doc_preview', 'vat_doc_file', 'vat_doc_help_block');
		});
		
		$("#cst_doc_check").change(function(){
			if($('#cst_doc_check').is(':checked'))
			{
				enableImageInput('cst_doc_file', 'cst_doc_preview', 'cst_doc_help_block');
				$('#cst_doc_check').val('1');
			}
			else
			{
				clearImageInput('cst_doc_file', 'cst_doc_preview', 'cst_doc_help_block');
				$('#cst_doc_check').val('0');
			}		
		});
		$("#cst_doc_file").change(function(){
			previewImage(this, 'cst_doc_preview', 'cst_doc_file', 'cst_doc_help_block');
		});
		
		$("#service_tax_doc_check").change(function(){
			if($('#service_tax_doc_check').is(':checked'))
			{
				enableImageInput('service_tax_doc_file', 'service_tax_doc_preview', 'service_tax_doc_help_block');
				$('#service_tax_doc_check').val('1');
			}
			else
			{
				clearImageInput('service_tax_doc_file', 'service_tax_doc_preview', 'service_tax_doc_help_block');
				$('#service_tax_doc_check').val('0');
			}		
		});
		$("#service_tax_doc_file").change(function(){
			previewImage(this, 'service_tax_doc_preview', 'service_tax_doc_file', 'service_tax_doc_help_block');
		});
		
		$("#gumasta_doc_file").change(function(){
			previewImage(this, 'gumasta_doc_preview', 'gumasta_doc_file', 'gumasta_doc_help_block');
		});
		// End Preview Image (Business Information)

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
		
		$('#business_contact_no').blur(function(){
			if($('#business_contact_no').val() != '')
			{
				sendOTP($(this).attr('id'), 'send');
			}
		});
		
		$('#personal_pan_card').keyup(function(){
			this.value = this.value.toUpperCase();
		});
		
		$('#bank_ifsc_code').keyup(function(){
			this.value = this.value.toUpperCase();
		});
		
		$('#passport_no').keyup(function(){
			this.value = this.value.toUpperCase();
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
                url: '{{ route("admins.update_merchant_profile", ["id" => $user->id]) }}',
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
		
		//Profile
		$('#btn_submit_profile').click(function(event){
			
			//check valid OTP
			$.ajax({
			  url:'{{ route("admins.check_merchant_valid_otp", ["id" => $user->id]) }}',
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
		//End Profile
		
		//Business Information
		 $('#btn_submit_business_information').click(function(event){
			
			//check valid OTP
			$.ajax({
			  url:'{{ route("admins.check_merchant_valid_otp", ["id" => $user->id]) }}',
			  type:'POST',
			  async: false,
			  data:{
					'_token': '{{ csrf_token() }}', 
					'contact_no': $('#business_contact_no').val()
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
		//End Business Information
		  
		//Personal Information
		$("input[id^='personal_contact_no_']").each( function() {			
			var x = $(this).attr('id').replace(/\D/g,'')
			$(this).blur( function() {		
				sendOTPPersonal($(this).attr('id'), 'personal_contact_no', 'send', x);
			});
		});
		
		$("input[id^='personal_pan_card_check_']").each( function() {
			$(this).change( function() {
				var x = $(this).attr('id').replace(/\D/g,'')
				if($(this).is(':checked'))
				{
					enableImageInput('personal_pan_card_file_' + x, 'personal_pan_card_preview_' + x, 'help_block_personal_pan_card_' + x);
					$('#personal_pan_card_' + x).attr('style', '');
					$('#personal_pan_card_label_' + x).attr('style', 'display:none;');
					$('#personal_pan_card_check_').val('1');
				}
				else
				{
					clearImageInput('personal_pan_card_file_' + x, 'personal_pan_card_preview_' + x, 'help_block_personal_pan_card_' + x);
					$('#personal_pan_card_' + x).val('');
					$('#personal_pan_card_' + x).attr('style', 'display: none;');
					$('#personal_pan_card_label_' + x).attr('style', 'display:none;');
					$('#personal_pan_card_check_').val('0');
				}
			});
		});
		
		$("input[id^='personal_pan_card_file_']").change( function() {
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'personal_pan_card_preview_' + x, 'personal_pan_card_file_' + x, 'help_block_personal_pan_card_' + x);
		});
		
		$("input[id^='aadhar_no_check_']").each( function() {
			$(this).change( function() {
				var x = $(this).attr('id').replace(/\D/g,'')
				if($(this).is(':checked'))
				{
					enableImageInput('aadhar_no_file_' + x, 'aadhar_no_preview_' + x, 'help_block_aadhar_no_' + x);
					$('#aadhar_no_' + x).attr('style', '');
					$('#aadhar_no_label_' + x).attr('style', 'display:none;');
					$('#aadhar_no_check_').val('1');
				}
				else
				{
					clearImageInput('aadhar_no_file_' + x, 'aadhar_no_preview_' + x, 'help_block_aadhar_no_' + x);
					$('#aadhar_no_' + x).val('');
					$('#aadhar_no_' + x).attr('style', 'display: none;');
					$('#aadhar_no_label_' + x).attr('style', 'display:none;');
					$('#aadhar_no_check_').val('0');
				}	
			});
		});
		
		$("input[id^='aadhar_no_file_']").change( function() {
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'aadhar_no_preview_' + x, 'aadhar_no_file_' + x, 'help_block_aadhar_no_' + x);
		});
		
		$("input[id^='passport_no_check_']").each( function() {
			$(this).change( function() {
				var x = $(this).attr('id').replace(/\D/g,'')
				if($(this).is(':checked'))
				{
					enableImageInput('passport_no_file_' + x, 'passport_no_preview_' + x, 'help_block_passport_no_' + x);
					$('#passport_no_' + x).attr('style', '');
					$('#passport_no_label_' + x).attr('style', 'display:none;');
					$('#passport_no_check_').val('1');
				}
				else
				{
					clearImageInput('passport_no_file_' + x, 'passport_no_preview_' + x, 'help_block_passport_no_' + x);
					$('#passport_no_' + x).val('');
					$('#passport_no_' + x).attr('style', 'display: none;');
					$('#passport_no_label_' + x).attr('style', 'display:none;');
					$('#passport_no_check_').val('0');
				}	
			});
		});
		
		$("input[id^='passport_no_file_']").change( function() {
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'passport_no_preview_' + x, 'passport_no_file_' + x, 'help_block_passport_no_' + x);
		});
		
		$("input[id^='voter_id_card_check_']").each( function() {
			$(this).change( function() {
				var x = $(this).attr('id').replace(/\D/g,'')
				if($(this).is(':checked'))
				{
					enableImageInput('voter_id_card_file_' + x, 'voter_id_card_preview_' + x, 'help_block_voter_id_card_' + x);
					$('#voter_id_card_' + x).attr('style', '');
					$('#voter_id_card_label_' + x).attr('style', 'display:none;');
					$('#voter_id_card_check_').val('1');
				}
				else
				{
					clearImageInput('voter_id_card_file_' + x, 'voter_id_card_preview_' + x, 'help_block_voter_id_card_' + x);
					$('#voter_id_card_' + x).attr('style', 'display: none;');
					$('#voter_id_card_label_' + x).attr('style', 'display:none;');
					$('#voter_id_card_check_').val('0');
				}	
			});
		});
		
		$("input[id^='voter_id_card_file_']").change( function() {
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'voter_id_card_preview_' + x, 'voter_id_card_file_' + x, 'help_block_voter_id_card_' + x);
		});
		
		$("input[id^='electricity_bill_check_']").each( function() {
			$(this).change( function() {
				var x = $(this).attr('id').replace(/\D/g,'')
				if($(this).is(':checked'))
				{
					enableImageInput('electricity_bill_file_' + x, 'electricity_bill_preview_' + x, 'help_block_electricity_bill_' + x);
					$('#electricity_bill_' + x).attr('style', '');
					$('#electricity_bill_label_' + x).attr('style', 'display:none;');
					$('#electricity_bill_check_').val('1');
				}
				else
				{
					clearImageInput('electricity_bill_file_' + x, 'electricity_bill_preview_' + x, 'help_block_electricity_bill_' + x);
					$('#electricity_bill_' + x).attr('style', 'display: none;');
					$('#electricity_bill_label_' + x).attr('style', 'display:none;');
					$('#electricity_bill_check_').val('0');
				}
			});
		});
		
		$("input[id^='electricity_bill_file_']").change( function() {
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'electricity_bill_preview_' + x, 'electricity_bill_file_' + x, 'help_block_electricity_bill_' + x);
		});
		
		$("input[id^='landline_bill_check_']").each( function() {
			$(this).change( function() {
				var x = $(this).attr('id').replace(/\D/g,'')
				if($(this).is(':checked'))
				{
					enableImageInput('landline_bill_file_' + x, 'landline_bill_preview_' + x, 'help_block_landline_bill_' + x);
					$('#landline_bill_' + x).attr('style', '');
					$('#landline_bill_label_' + x).attr('style', 'display:none;');
					$('#landline_bill_check_').val('1');
				}
				else
				{
					clearImageInput('landline_bill_file_' + x, 'landline_bill_preview_' + x, 'help_block_landline_bill_' + x);
					$('#landline_bill_' + x).attr('style', 'display: none;');
					$('#landline_bill_label_' + x).attr('style', 'display:none;');
					$('#landline_bill_check_').val('0');
				}	
			});
		});
		
		$("input[id^='landline_bill_file_']").change( function() {
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'landline_bill_preview_' + x, 'landline_bill_file_' + x, 'help_block_landline_bill_' + x);
		});
		
		$("input[id^='bank_account_statement_check_']").each( function() {
			$(this).change( function() {
				var x = $(this).attr('id').replace(/\D/g,'')
				if($(this).is(':checked'))
				{
					enableImageInput('bank_account_statement_file_' + x, 'bank_account_statement_preview_' + x, 'help_block_bank_account_statement_' + x);
					$('#bank_account_statement_label_' + x).attr('style', 'display:none;');
					$('#bank_account_statement_check_').val('1');
				}
				else
				{
					clearImageInput('bank_account_statement_file_' + x, 'bank_account_statement_preview_' + x, 'help_block_bank_account_statement_' + x);
					$('#bank_account_statement_' + x).attr('style', 'display: none;');
					$('#bank_account_statement_label_' + x).attr('style', 'display:none;');
					$('#bank_account_statement_check_').val('0');
				}
			});
		});
		
		$("input[id^='bank_account_statement_file_']").change( function() {
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'bank_account_statement_preview_' + x, 'bank_account_statement_file_' + x, 'help_block_bank_account_statement_' + x);
		});

		$('#btn_submit_personal_information').click(function(event){
			
			if($('#total_person').val() == '0')
			{
				showTemporaryMessage('Please fill minimum 1 person.', 'error', 'Error');						
				event.preventDefault();
				$('html, body').animate({scrollTop: '0px'}, 300);
				return false;
			}
			
			$countUndefined = 0;
			for(var z = 1; z <= $('#total_person').val(); z++) 
			{
				if(typeof $('#owner_name_' + z).val() != "undefined")
				{
					//Validation Personal Pan Card Mandatory
					if(!$('#personal_pan_card_check_' + z).is(':checked') || $('#personal_pan_card_' + z).val() == '' || $('#personal_pan_card_file_' + z).val() == null)
					{
						showTemporaryMessage('Personal Pan Card must be filled', 'error', 'Error');						
						event.preventDefault();
						$('html, body').animate({scrollTop: '0px'}, 300);
						return false;
					}
					//End Validation Personal Pan Card Mandatory
					
					//Validation Address Proof
					if((!$('#aadhar_no_check_' + z).is(':checked') || $('#aadhar_no_' + z).val() == '' || $('#aadhar_no_file_' + z).val() == null) && (!$('#passport_no_check_' + z).is(':checked') || $('#passport_no_' + z).val() == '' || $('#passport_no_file_' + z).val() == null))
					{
						if((!$('#electricity_bill_check_' + z).is(':checked') || $('#electricity_bill_file_' + z).val() == null) && (!$('#landline_bill_check_' + z).is(':checked') || $('#landline_bill_file_' + z).val() == null) && (!$('#bank_account_statement_check_' + z).is(':checked') || $('#bank_account_statement_file_' + z).val() == null))
						{
							showTemporaryMessage('Aadhar No or Passport No or at least 1 of Address Proof document must me filled.', 'error', 'Error');					
							event.preventDefault();
							$('html, body').animate({scrollTop: '0px'}, 300);
							return false;
						}
					}
					//End Validation Address Proof
					
					//check valid OTP
					$.ajax({
					  url:'{{ route("admins.check_merchant_valid_otp", ["id" => $user->id]) }}',
					  type:'POST',
					  async: false,
					  data:{
							'_token': '{{ csrf_token() }}', 
							'contact_no': $('#personal_contact_no_' + z).val()
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
				}
				else
				{
					$countUndefined++;
				}
				
				if($countUndefined == $('#total_person').val())
				{
					showTemporaryMessage('Please fill minimum 1 person.', 'error', 'Error');						
					event.preventDefault();
					$('html, body').animate({scrollTop: '0px'}, 300);
					return false;
				}
			}
			
			
			
		  });
		//End Personal Information
}); */
</script>
@endpush
