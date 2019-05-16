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
	  <li><a data-toggle="tab" href="#business_information">Business Information</a></li>
	  <li><a data-toggle="tab" href="#personal_information">Personal Information</a></li>
	  <li><a data-toggle="tab" href="#bank_details">Bank Details</a></li>
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
							if($userProfile->avatar_file_name == '' || $userProfile->avatar_file_name == NULL)
								$profile_pic = asset('/images/vendor/dummy-profile-pic.png');
							else
								$profile_pic = asset('images/vendor/' . $userProfile->avatar_file_name);
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
							<input type="text" name="first_name" value="{{ $userProfile->first_name }}" class="form-control" id="first_name" onkeydown="return alphaOnly(event);" required>
							@if ($errors->has('first_name'))
								<span class="help-block">
									<strong>{{ $errors->first('first_name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
							<label for="last_name">Last Name <font color="red">*</font></label>
							<input type="text" name="last_name" value="{{ $userProfile->last_name }}" class="form-control" id="last_name" onkeydown="return alphaOnly(event);" required>
							@if ($errors->has('last_name'))
								<span class="help-block">
									<strong>{{ $errors->first('last_name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
							<label for="country_code">Country Code <font color="red">*</font></label>
							<input type="text" name="country_code" value="{{ $userProfile->country_code }}" class="form-control" id="country_code" readonly>
							@if ($errors->has('country_code'))
								<span class="help-block">
									<strong>{{ $errors->first('country_code') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
							<label for="contact_no">Contact No <font color="red">*</font></label>
							<input type="text" name="contact_no" value="{{ $userProfile->contact_no }}" class="form-control" id="contact_no" maxlength="10"
										onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57">
							@if ($errors->has('contact_no'))
								<span class="help-block">
									<strong>{{ $errors->first('contact_no') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<label for="email">Email <font color="red">*</font></label>
							<input type="email" name="email" value="{{ $userProfile->email }}" class="form-control" id="email" required>
							@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
							<label for="is_active">Status</label>
							<select class="form-control" name="is_active" id="is_active">
							@if($userProfile->is_active == '1')
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
									<p>- New Password must not be the same with your recent 5 passwords.</p>
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
		<div id="business_information" class="tab-pane fade">

			<br>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<form role="form" id="form_business_information" action="{{ route('merchants.update_business_information') }}" method="post" enctype="multipart/form-data">
					{!! csrf_field() !!}
						<div class="form-group{{ $errors->has('firm_name') ? ' has-error' : '' }}">
							<label for="firm_name">Firm Name <font color="red">*</font></label>
							<input type="text" name="firm_name" value="{{ $user->firm_name }}" class="form-control" id="firm_name" maxlength="50" onkeydown="return alphaOnly(event);" required>
							@if ($errors->has('firm_name'))
								<span class="help-block">
									<strong>{{ $errors->first('firm_name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('firm_address') ? ' has-error' : '' }}">
							<label for="firm_address">Firm Address <font color="red">*</font></label>
							<input type="text" name="firm_address" value="{{ $user->firm_address }}" class="form-control" id="firm_address" maxlength="50" onkeydown="return alphaOnly(event);" required>
							@if ($errors->has('firm_address'))
								<span class="help-block">
									<strong>{{ $errors->first('firm_address') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
							<label for="city">City <font color="red">*</font></label>
							<input type="text" name="city" value="{{ $user->city }}" class="form-control" id="city" maxlength="20" onkeydown="return alphaOnly(event);" required>
							@if ($errors->has('city'))
								<span class="help-block">
									<strong>{{ $errors->first('city') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
							<label for="state">State <font color="red">*</font></label>
							<select class="form-control" name="state" id="state" required>
								<option value="" <?php if($user->state == NULL || $user->state == '') echo 'selected'; ?>>Select State</option>
								<option value="Andhra Pradesh" <?php if($user->state == 'Andhra Pradesh') echo 'selected'; ?>>Andhra Pradesh</option>
								<option value="Arunachal Pradesh" <?php if($user->state == 'Arunachal Pradesh') echo 'selected'; ?>>Arunachal Pradesh</option>
								<option value="Assam" <?php if($user->state == 'Assam') echo 'selected'; ?>>Assam</option>
								<option value="Bihar" <?php if($user->state == 'Bihar') echo 'selected'; ?>>Bihar</option>
								<option value="Chhattisgarh" <?php if($user->state == 'Chhattisgarh') echo 'selected'; ?>>Chhattisgarh</option>
								<option value="Dadra and Nagar Haveli" <?php if($user->state == 'Dadra and Nagar Haveli') echo 'selected'; ?>>Dadra and Nagar Haveli</option>
								<option value="Daman and Diu" <?php if($user->state == 'Daman and Diu') echo 'selected'; ?>>Daman and Diu</option>
								<option value="Delhi" <?php if($user->state == 'Delhi') echo 'selected'; ?>>Delhi</option>
								<option value="Goa" <?php if($user->state == 'Goa') echo 'selected'; ?>>Goa</option>
								<option value="Gujarat" <?php if($user->state == 'Gujarat') echo 'selected'; ?>>Gujarat</option>
								<option value="Haryana" <?php if($user->state == 'Haryana') echo 'selected'; ?>>Haryana</option>
								<option value="Himachal Pradesh" <?php if($user->state == 'Himachal Pradesh') echo 'selected'; ?>>Himachal Pradesh</option>
								<option value="Jammu and Kashmir" <?php if($user->state == 'Jammu and Kashmir') echo 'selected'; ?>>Jammu and Kashmir</option>
								<option value="Jharkhand" <?php if($user->state == 'Jharkhand') echo 'selected'; ?>>Jharkhand</option>
								<option value="Karnataka" <?php if($user->state == 'Karnataka') echo 'selected'; ?>>Karnataka</option>
								<option value="Kerala" <?php if($user->state == 'Kerala') echo 'selected'; ?>>Kerala</option>
								<option value="Madhya Pradesh" <?php if($user->state == 'Madhya Pradesh') echo 'selected'; ?>>Madhya Pradesh</option>
								<option value="Maharashtra" <?php if($user->state == 'Maharashtra') echo 'selected'; ?>>Maharashtra</option>
								<option value="Manipur" <?php if($user->state == 'Manipur') echo 'selected'; ?>>Manipur</option>
								<option value="Meghalaya" <?php if($user->state == 'Meghalaya') echo 'selected'; ?>>Meghalaya</option>
								<option value="Mizoram" <?php if($user->state == 'Mizoram') echo 'selected'; ?>>Mizoram</option>
								<option value="Nagaland" <?php if($user->state == 'Nagaland') echo 'selected'; ?>>Nagaland</option>
								<option value="Orissa" <?php if($user->state == 'Orissa') echo 'selected'; ?>>Orissa</option>
								<option value="Puducherry" <?php if($user->state == 'Puducherry') echo 'selected'; ?>>Puducherry</option>
								<option value="Punjab" <?php if($user->state == 'Punjab') echo 'selected'; ?>>Punjab</option>
								<option value="Rajasthan" <?php if($user->state == 'Rajasthan') echo 'selected'; ?>>Rajasthan</option>
								<option value="Sikkim" <?php if($user->state == 'Sikkim') echo 'selected'; ?>>Sikkim</option>
								<option value="Tamil Nadu" <?php if($user->state == 'Tamil Nadu') echo 'selected'; ?>>Tamil Nadu</option>
								<option value="Telangana" <?php if($user->state == 'Telangana') echo 'selected'; ?>>Telangana</option>
								<option value="Tripura" <?php if($user->state == 'Tripura') echo 'selected'; ?>>Tripura</option>
								<option value="Uttar Pradesh" <?php if($user->state == 'Uttar Pradesh') echo 'selected'; ?>>Uttar Pradesh</option>
								<option value="Uttarakhand" <?php if($user->state == 'Uttarakhand') echo 'selected'; ?>>Uttarakhand</option>
								<option value="West Bengal" <?php if($user->state == 'West Bengal') echo 'selected'; ?>>West Bengal</option>
							</select>
						</div>
						<div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
							<label for="country">Country <font color="red">*</font></label>
							<input type="text" name="country" value="India" class="form-control" id="country" readonly required>
							@if ($errors->has('country'))
								<span class="help-block">
									<strong>{{ $errors->first('country') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('business_contact_no') ? ' has-error' : '' }}">
							<label for="business_contact_no">Business Contact No <font color="red">*</font></label>
							<input type="text" name="business_contact_no" value="{{ $user->business_contact_no }}" class="form-control" id="business_contact_no" maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>

							<div id="div_business_contact_no_otp" class="form-inline" hidden>
								<label id="label_business_contact_no_otp"><font color="blue">SMS sent! Check your OTP (SMS is valid only 5 minutes)</font></label><br>

								<div class="input-group" id="group_business_contact_no_otp" hidden>
									<input type="text" name="business_contact_no_otp" placeholder="Enter OTP" class="form-control" id="business_contact_no_otp" maxlength="6" />
									<span class="input-group-btn">
										<a class="btn btn-primary" id="btn_resend_business_contact_no_otp" onclick="sendOTP('business_contact_no', 'resend')">Resend OTP</a>
										<a class="btn btn-success" id="btn_business_contact_no_otp" onclick="verifyOTP('business_contact_no')">Verify</a>
									</span>
								</div>
							</div>

							@if ($errors->has('business_contact_no'))
								<span class="help-block">
									<strong>{{ $errors->first('business_contact_no') }}</strong>
								</span>
							@endif
						</div>
						<br>
						<h4>Registration Certificate of the Firm</h4><small>(Please upload the files below. Accept ".jpg, .jpeg" only)</small>
						<div class="form-group{{ $errors->has('vat_doc_file') ? ' has-error' : '' }}">
							<div class="checkbox">
							  <label><input type="checkbox" id="vat_doc_check" name="vat_doc_check" value="<?php if($user->vat_doc_file_name != null || $user->vat_doc_file_name != '') echo '1'; else echo '0'; ?>" <?php if($user->vat_doc_file_name != null || $user->vat_doc_file_name != '') echo 'checked'; ?>>VAT</label>
							</div>
							<span class="label label-<?php if($user->vat_doc_is_verified == '1') echo 'success'; else if($user->vat_doc_is_verified == '0') echo 'warning'; else if($user->vat_doc_is_verified == '-1') echo 'danger'; ?>"><?php if($user->vat_doc_is_verified == '1') echo 'Verified'; else if($user->vat_doc_is_verified == '0') echo 'Unverified'; else if($user->vat_doc_is_verified == '-1') echo 'Rejected'; ?></span><br>
							<input type="file" name="vat_doc_file" value="" class="form-control" id="vat_doc_file" style="<?php if($user->vat_doc_file_name == null || $user->vat_doc_file_name == '') echo 'display:none;'; ?>" />
							<img src="<?php if($user->vat_doc_file_name != null || $user->vat_doc_file_name != '') { ?>{{ asset('images/merchant/document') }}/{{$user->vat_doc_file_name }} <?php } ?>" id="vat_doc_preview" style="max-width:200px; max-height:200px; cursor: pointer; <?php if($user->vat_doc_file_name == null || $user->vat_doc_file_name == '') echo 'display:none;'; ?>" onclick="showImage('vat_doc_preview', 'VAT')" />
							<span class="help-block" id="vat_doc_help_block" style="display:none;">
							</span>
						</div>
						<div class="form-group{{ $errors->has('cst_doc_file_name') ? ' has-error' : '' }}">
							<div class="checkbox">
							  <label><input type="checkbox" id="cst_doc_check" name="cst_doc_check" value="<?php if($user->cst_doc_file_name != null || $user->cst_doc_file_name != '') echo '1'; else echo '0'; ?>" <?php if($user->cst_doc_file_name != null || $user->cst_doc_file_name != '') echo 'checked'; ?>>CST</label>
							</div>
							<span class="label label-<?php if($user->cst_doc_is_verified == '1') echo 'success'; else if($user->cst_doc_is_verified == '0') echo 'warning'; else if($user->cst_doc_is_verified == '-1') echo 'danger'; ?>"><?php if($user->cst_doc_is_verified == '1') echo 'Verified'; else if($user->cst_doc_is_verified == '0') echo 'Unverified'; else if($user->cst_doc_is_verified == '-1') echo 'Rejected'; ?></span><br>
							<input type="file" name="cst_doc_file" value="" class="form-control" id="cst_doc_file" style="<?php if($user->cst_doc_file_name == null || $user->cst_doc_file_name == '') echo 'display:none;'; ?>">
							<img src="<?php if($user->cst_doc_file_name != null || $user->cst_doc_file_name != '') { ?>{{ asset('images/merchant/document') }}/{{$user->cst_doc_file_name }} <?php } ?>" id="cst_doc_preview" style="max-width:200px; max-height:200px; cursor: pointer; <?php if($user->cst_doc_file_name == null || $user->cst_doc_file_name == '') echo 'display:none;'; ?>" onclick="showImage('cst_doc_preview', 'CST')" />
							<span class="help-block" id="cst_doc_help_block" style="display:none;">
							</span>
						</div>
						<div class="form-group{{ $errors->has('service_tax_doc_file_name') ? ' has-error' : '' }}">
							<div class="checkbox">
							  <label><input type="checkbox" id="service_tax_doc_check" name="service_tax_doc_check" value="<?php if($user->service_tax_doc_file_name != null || $user->service_tax_doc_file_name != '') echo '1'; else echo '0'; ?>" <?php if($user->service_tax_doc_file_name != null || $user->service_tax_doc_file_name != '') echo 'checked'; ?>>Service Tax</label>
							</div>
							<span class="label label-<?php if($user->service_tax_doc_is_verified == '1') echo 'success'; else if($user->service_tax_doc_is_verified == '0') echo 'warning'; else if($user->service_tax_doc_is_verified == '-1') echo 'danger'; ?>"><?php if($user->service_tax_doc_is_verified == '1') echo 'Verified'; else if($user->service_tax_doc_is_verified == '0') echo 'Unverified'; else if($user->service_tax_doc_is_verified == '-1') echo 'Rejected'; ?></span><br>
							<input type="file" name="service_tax_doc_file" value="" class="form-control" id="service_tax_doc_file" style="<?php if($user->service_tax_doc_file_name == null || $user->service_tax_doc_file_name == '') echo 'display:none;'; ?>">
							<img src="<?php if($user->service_tax_doc_file_name != null || $user->service_tax_doc_file_name != '') { ?>{{ asset('images/merchant/document') }}/{{$user->service_tax_doc_file_name }} <?php } ?>" id="service_tax_doc_preview" style="max-width:200px; max-height:200px; cursor: pointer; <?php if($user->service_tax_doc_file_name == null || $user->service_tax_doc_file_name == '') echo 'display:none;'; ?>" onclick="showImage('service_tax_doc_preview', 'Service Tax')" />
							<span class="help-block" id="service_tax_doc_help_block" style="display:none;">
							</span>
						</div>

						<div class="form-group{{ $errors->has('gumasta_doc_file_name') ? ' has-error' : '' }}">
							<label for="gumasta_doc_file">Gumasta <font color="red">*</font></label><br>
							<span class="label label-<?php if($user->gumasta_doc_is_verified == '1') echo 'success'; else if($user->gumasta_doc_is_verified == '0') echo 'warning'; else if($user->gumasta_doc_is_verified == '-1') echo 'danger'; ?>"><?php if($user->gumasta_doc_is_verified == '1') echo 'Verified'; else if($user->gumasta_doc_is_verified == '0') echo 'Unverified'; else if($user->gumasta_doc_is_verified == '-1') echo 'Rejected'; ?></span><br>
							<input type="file" name="gumasta_doc_file" value="ada" class="form-control" id="gumasta_doc_file" style="" <?php if($user->gumasta_doc_file_name == null || $user->gumasta_doc_file_name == '') echo 'required="true";'; ?> />
							<img src="<?php if($user->gumasta_doc_file_name != null || $user->gumasta_doc_file_name != '') { ?>{{ asset('images/merchant/document') }}/{{$user->gumasta_doc_file_name }} <?php } ?>" id="gumasta_doc_preview" style="max-width:200px; max-height:200px; cursor: pointer; <?php if($user->gumasta_doc_file_name == null || $user->gumasta_doc_file_name == '') echo 'display:none;'; ?>" onclick="showImage('gumasta_doc_preview', 'Gumasta')" />
							<span class="help-block" id="gumasta_doc_help_block" style="display:none;">
							</span>
						</div>

						 <div class="box-footer">
							<button type="submit" id="btn_submit_business_information" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Submit</button>
						</div>
					</form>
				 </div>
			</div>

		</div>
		<div id="personal_information" class="tab-pane fade">
			<br>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<form role="form" id="form_personal_information" action="{{ route('merchants.update_personal_information') }}"  method="post" enctype="multipart/form-data">
					{!! csrf_field() !!}

					<?php $countPersonal = 0; ?>
					<div id="div_personal_information">
					@if(isset($merchantPersonalInfos))
						@foreach($merchantPersonalInfos as $merchantPersonalInfo)
						<?php $countPersonal++ ; ?>

						 <div class="x_panel" id="panel_person_{{ $countPersonal }}" style="height: auto;">
							  <div class="x_title">
								<h2>Person Details</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a href="#content_{{ $countPersonal }}" data-toggle="collapse" class="collapse-link"><button class="btn btn-info">Expand/Collapse</button></a>
								  </li>
								  <li><a class="close-link"><button class="btn btn-danger" onclick="removePerson('{{ $countPersonal }}')">Remove</button></i></a>
								  </li>
								</ul>
								<div class="clearfix"></div>
							  </div>
							  <div class="x_content" style="display: none;">
								<div class="form-group" id="div_owner_name_{{ $countPersonal }}"> <!-- has-error -->
									<label for="owner_name_{{ $countPersonal }}">Owner Name <font color="red">*</font></label>
									<input type="text" name="owner_name_{{ $countPersonal }}" value="{{ $merchantPersonalInfo->owner_name }}" class="form-control" id="owner_name_{{ $countPersonal }}" maxlength="255" onkeydown="return alphaOnly(event);" required />
									<span class="help-block" id="help_block_owner_name_{{ $countPersonal }}">
										<strong></strong>
									</span>
								</div>
								<div class="form-group" id="div_personal_address_{{ $countPersonal }}">
									<label for="personal_address">Personal Address <font color="red">*</font></label>
									<input type="text" name="personal_address_{{ $countPersonal }}" value="{{ $merchantPersonalInfo->personal_address }}" class="form-control" id="personal_address_{{ $countPersonal }}" maxlength="150" onkeydown="return alphaOnly(event);" required />
									@if ($errors->has('personal_address'))
										<span class="help-block">
											<strong>{{ $errors->first('personal_address') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group" id="div_personal_contact_no_{{ $countPersonal }}">
									<label for="personal_contact_no_{{ $countPersonal }}">Personal Contact Number <font color="red">*</font></label>
									<input type="text" name="personal_contact_no_{{ $countPersonal }}" value="{{ $merchantPersonalInfo->personal_contact_no }}" class="form-control" id="personal_contact_no_{{ $countPersonal }}" maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required />
									<div id="div_personal_contact_no_otp_{{ $countPersonal }}" class="form-inline" hidden>
										<label id="label_personal_contact_no_otp_{{ $countPersonal }}"><font color="blue">SMS sent! Check your OTP (SMS is valid only 5 minutes)</font></label><br>

										<div class="input-group" id="group_personal_contact_no_otp_{{ $countPersonal }}" hidden>
											<input type="text" name="personal_contact_no_otp_{{ $countPersonal }}" placeholder="Enter OTP" class="form-control" id="personal_contact_no_otp_{{ $countPersonal }}" maxlength="6" />
											<span class="input-group-btn">
												<a class="btn btn-primary" id="btn_resend_personal_contact_no_otp_{{ $countPersonal }}" onclick="sendOTP('personal_contact_no_otp_{{ $countPersonal }}', 'resend')">Resend OTP</a>
												<a class="btn btn-success" id="btn_personal_contact_no_otp_{{ $countPersonal }}" onclick="verifyOTP('personal_contact_no_otp_{{ $countPersonal }}')">Verify</a>
											</span>
										</div>
									</div>
									<span class="help-block">
										<strong></strong>
									</span>
								</div>
								<div class="form-group" id="div_personal_information_city_{{ $countPersonal }}">
									<label for="personal_information_city_{{ $countPersonal }}">City <font color="red">*</font></label>
									<input type="text" name="personal_information_city_{{ $countPersonal }}" value="<?php
																										if($merchantPersonalInfo->city != null || $merchantPersonalInfo->city != '')
																										{
																											echo $merchantPersonalInfo->city;
																										}
																										else
																										{
																											echo $user->city;
																										}
																								?>" class="form-control" id="personal_information_city_{{ $countPersonal }}" maxlength="20" onkeydown="return alphaOnly(event);" required />
										<span class="help-block" id="help_block_personal_information_city_{{ $countPersonal }}">
											<strong></strong>
										</span>
								</div>
								<div class="form-group" id="div_personal_information_state_{{ $countPersonal }}">
									<label for="personal_information_state_{{ $countPersonal }}">State <font color="red">*</font></label>
									<select class="form-control" name="personal_information_state_{{ $countPersonal }}" id="personal_information_state_{{ $countPersonal }}" required>
										<option value="" <?php if($merchantPersonalInfo->state == NULL || $merchantPersonalInfo->state == '') echo 'selected';?>>Select State</option>
										<option value="Andhra Pradesh" <?php
																			if($merchantPersonalInfo->state == 'Andhra Pradesh') echo 'selected';
														 ?>>Andhra Pradesh</option>
										<option value="Arunachal Pradesh" <?php
																	if($merchantPersonalInfo->state == 'Arunachal Pradesh') echo 'selected';
														 ?>>Arunachal Pradesh</option>
										<option value="Assam" <?php
																	if($merchantPersonalInfo->state == 'Assam') echo 'selected';
														 ?>>Assam</option>
										<option value="Bihar" <?php
																	if($merchantPersonalInfo->state == 'Bihar') echo 'selected';
														 ?>>Bihar</option>
										<option value="Chhattisgarh" <?php
																	if($merchantPersonalInfo->state == 'Chhattisgarh') echo 'selected';
														 ?>>Chhattisgarh</option>
										<option value="Dadra and Nagar Haveli" <?php
																	if($merchantPersonalInfo->state == 'Dadra and Nagar Haveli') echo 'selected';
														 ?>>Dadra and Nagar Haveli</option>
										<option value="Daman and Diu" <?php
																	if($merchantPersonalInfo->state == 'Daman and Diu') echo 'selected';
														 ?>>Daman and Diu</option>
										<option value="Delhi" <?php
																	if($merchantPersonalInfo->state == 'Delhi') echo 'selected';
														 ?>>Delhi</option>
										<option value="Goa" <?php
																	if($merchantPersonalInfo->state == 'Goa') echo 'selected';
														 ?>>Goa</option>
										<option value="Gujarat" <?php
																	if($merchantPersonalInfo->state == 'Gujarat') echo 'selected';
														 ?>>Gujarat</option>
										<option value="Haryana" <?php
																	if($merchantPersonalInfo->state == 'Haryana') echo 'selected';
														 ?>>Haryana</option>
										<option value="Himachal Pradesh" <?php
																	if($merchantPersonalInfo->state == 'Himachal Pradesh') echo 'selected';
														 ?>>Himachal Pradesh</option>
										<option value="Jammu and Kashmir" <?php
																	if($merchantPersonalInfo->state == 'Jammu and Kashmir') echo 'selected';
														 ?>>Jammu and Kashmir</option>
										<option value="Jharkhand" <?php
																	if($merchantPersonalInfo->state == 'Jharkhand') echo 'selected';
														 ?>>Jharkhand</option>
										<option value="Karnataka" <?php
																	if($merchantPersonalInfo->state == 'Karnataka') echo 'selected';
														 ?>>Karnataka</option>
										<option value="Kerala" <?php
																	if($merchantPersonalInfo->state == 'Kerala') echo 'selected';
														 ?>>Kerala</option>
										<option value="Madhya Pradesh" <?php
																	if($merchantPersonalInfo->state == 'Madhya Pradesh') echo 'selected';
														 ?>>Madhya Pradesh</option>
										<option value="Maharashtra" <?php
																	if($merchantPersonalInfo->state == 'Maharashtra') echo 'selected';
														 ?>>Maharashtra</option>
										<option value="Manipur" <?php
																	if($merchantPersonalInfo->state == 'Manipur') echo 'selected';
														 ?>>Manipur</option>
										<option value="Meghalaya" <?php
																	if($merchantPersonalInfo->state == 'Meghalaya') echo 'selected';
														 ?>>Meghalaya</option>
										<option value="Mizoram" <?php
																	if($merchantPersonalInfo->state == 'Mizoram') echo 'selected';
														 ?>>Mizoram</option>
										<option value="Nagaland" <?php
																	if($merchantPersonalInfo->state == 'Nagaland') echo 'selected';
														 ?>>Nagaland</option>
										<option value="Orissa" <?php
																	if($merchantPersonalInfo->state == 'Orissa') echo 'selected';
														 ?>>Orissa</option>
										<option value="Puducherry" <?php
																	if($merchantPersonalInfo->state == 'Puducherry') echo 'selected';
														 ?>>Puducherry</option>
										<option value="Punjab" <?php
																	if($merchantPersonalInfo->state == 'Punjab') echo 'selected';
														 ?>>Punjab</option>
										<option value="Rajasthan" <?php
																	if($merchantPersonalInfo->state == 'Rajasthan') echo 'selected';
														 ?>>Rajasthan</option>
										<option value="Sikkim" <?php
																	if($merchantPersonalInfo->state == 'Sikkim') echo 'selected';
														 ?>>Sikkim</option>
										<option value="Tamil Nadu" <?php
																	if($merchantPersonalInfo->state == 'Tamil Nadu') echo 'selected';
														 ?>>Tamil Nadu</option>
										<option value="Telangana" <?php
																	if($merchantPersonalInfo->state == 'Telangana') echo 'selected';
														 ?>>Telangana</option>
										<option value="Tripura" <?php
																	if($merchantPersonalInfo->state == 'Tripura') echo 'selected';
														 ?>>Tripura</option>
										<option value="Uttar Pradesh" <?php
																	if($merchantPersonalInfo->state == 'Uttar Pradesh') echo 'selected';
														 ?>>Uttar Pradesh</option>
										<option value="Uttarakhand" <?php
																	if($merchantPersonalInfo->state == 'Uttarakhand') echo 'selected';
														 ?>>Uttarakhand</option>
										<option value="West Bengal" <?php
																	if($merchantPersonalInfo->state == 'West Bengal') echo 'selected';
														 ?>>West Bengal</option>
									</select>
								</div>
								<div class="form-group" div="div_personal_information_country_{{ $countPersonal }}">
									<label for="personal_information_country_{{ $countPersonal }}">Country <font color="red">*</font></label>
									<input type="text" name="personal_information_country_{{ $countPersonal }}" value="<?php
																										if($merchantPersonalInfo->country != null || $merchantPersonalInfo->country != '')
																										{
																											echo $merchantPersonalInfo->country;
																										}
																										else
																										{
																											echo 'India';
																										}
																								?>" class="form-control" id="personal_information_country_{{ $countPersonal }}" readonly required>
										<span class="help-block" id="help_block_personal_information_country_{{ $countPersonal }}">
											<strong></strong>
										</span>
								</div>
								<br>
								<h4>Identity Proof</h4>

								<div class="form-group" id="div_personal_pan_card_check_{{ $countPersonal }}">
									<div class="checkbox">
									  <label><input type="checkbox" id="personal_pan_card_check_{{ $countPersonal }}" name="personal_pan_card_check_{{ $countPersonal }}" value="<?php if($merchantPersonalInfo->personal_pan_card != null || $merchantPersonalInfo->personal_pan_card != '') echo '1'; else echo '0'; ?>" <?php if($merchantPersonalInfo->personal_pan_card != null || $merchantPersonalInfo->personal_pan_card != '') echo 'checked'; ?>>Personal Pan Card <font color="red">*</font></label>
									</div>
									<input type="text" name="personal_pan_card_{{ $countPersonal }}" placeholder="Number of Personal Pan Card. ex: AAUCS6728E" value="<?php if(isset($merchantPersonalInfo)) echo $merchantPersonalInfo->personal_pan_card; ?>" class="form-control" id="personal_pan_card_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->personal_pan_card == null || $merchantPersonalInfo->personal_pan_card == '') echo 'display:none;'; ?>" maxlength="50" onkeypress="if(event.charCode == 32) return false;" maxlength="50" required>

									<span class="label label-<?php if($merchantPersonalInfo->personal_pan_card_is_verified == '1') echo 'success'; else if($merchantPersonalInfo->personal_pan_card_is_verified == '0') echo 'warning'; else if($merchantPersonalInfo->personal_pan_card_is_verified == '-1') echo 'danger'; ?>" id="personal_pan_card_label_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->personal_pan_card == null || $merchantPersonalInfo->personal_pan_card == '') echo 'display:none;'; ?>"><?php if($merchantPersonalInfo->personal_pan_card_is_verified == '1') echo 'Verified'; else if($merchantPersonalInfo->personal_pan_card_is_verified == '0') echo 'Unverified'; else if($merchantPersonalInfo->personal_pan_card_is_verified == '-1') echo 'Rejected'; ?></span>

									<input type="file" name="personal_pan_card_file_{{ $countPersonal }}" value="" class="form-control" id="personal_pan_card_file_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->personal_pan_card == null || $merchantPersonalInfo->personal_pan_card == '') echo 'display:none;'; ?> required="true" />

									<img src="<?php if($merchantPersonalInfo->personal_pan_card_filename != null || $merchantPersonalInfo->personal_pan_card_filename != '') { ?>{{ asset('images/merchant/document') }}/{{$merchantPersonalInfo->personal_pan_card_filename }} <?php } ?>" id="personal_pan_card_preview_{{ $countPersonal }}" style="max-width:200px; max-height:200px; cursor:pointer; <?php if($merchantPersonalInfo->personal_pan_card_filename == null || $merchantPersonalInfo->personal_pan_card_filename == '') echo 'display:none;'; ?>" onclick="showImage('personal_pan_card_preview_{{ $countPersonal }}', 'Personal Pan Card')" />

									<span class="help-block" id="help_block_personal_pan_card_{{ $countPersonal }}" style="display:none;">
									</span>

								</div>
								<div class="form-group" id="div_aadhar_no_check_{{ $countPersonal }}">
									<div class="checkbox">
									  <label><input type="checkbox" id="aadhar_no_check_{{ $countPersonal }}" name="aadhar_no_check_{{ $countPersonal }}" value="<?php if($merchantPersonalInfo->aadhar_no != null || $merchantPersonalInfo->aadhar_no != '') echo '1'; else echo '0'; ?>" <?php if($merchantPersonalInfo->aadhar_no != null || $merchantPersonalInfo->aadhar_no != '') echo 'checked'; ?>>Aadhar No</label>
									</div>
									<input type="text" name="aadhar_no_{{ $countPersonal }}" placeholder="Number of Aadhar: 12 digits" value="<?php if(isset($merchantPersonalInfo)) echo $merchantPersonalInfo->aadhar_no; ?>" class="form-control" id="aadhar_no_{{ $countPersonal }}" maxlength="12" style="<?php if($merchantPersonalInfo->aadhar_no == null || $merchantPersonalInfo->aadhar_no == '') echo 'display:none;'; ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57" />

									<span class="label label-<?php if($merchantPersonalInfo->aadhar_is_verified == '1') echo 'success'; else if($merchantPersonalInfo->aadhar_is_verified == '0') echo 'warning'; else if($merchantPersonalInfo->aadhar_is_verified == '-1') echo 'danger'; ?>" id="aadhar_no_label_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->aadhar_no == null || $merchantPersonalInfo->aadhar_no == '') echo 'display:none;'; ?>"><?php if($merchantPersonalInfo->aadhar_is_verified == '1') echo 'Verified'; else if($merchantPersonalInfo->aadhar_is_verified == '0') echo 'Unverified'; else if($merchantPersonalInfo->aadhar_is_verified == '-1') echo 'Rejected'; ?></span>

									<input type="file" name="aadhar_no_file_{{ $countPersonal }}" value="" class="form-control" id="aadhar_no_file_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->aadhar_no == null || $merchantPersonalInfo->aadhar_no == '') echo 'display:none;'; ?> required="true" />

									<img src="<?php if($merchantPersonalInfo->aadhar_filename != null || $merchantPersonalInfo->aadhar_filename != '') { ?>{{ asset('images/merchant/document') }}/{{$merchantPersonalInfo->aadhar_filename }} <?php } ?>" id="aadhar_no_preview_{{ $countPersonal }}" style="max-width:200px; max-height:200px; cursor:pointer; <?php if($merchantPersonalInfo->aadhar_filename == null || $merchantPersonalInfo->aadhar_filename == '') echo 'display:none;'; ?>" onclick="showImage('aadhar_no_preview_{{ $countPersonal }}', 'Aadhar Number')" />

									<span class="help-block" id="help_block_aadhar_no_{{ $countPersonal }}" style="display:none;">
									</span>

								</div>
								<div class="form-group" id="div_passport_no_check_{{ $countPersonal }}">
									<div class="checkbox">
									  <label><input type="checkbox" id="passport_no_check_{{ $countPersonal }}" name="passport_no_check_{{ $countPersonal }}" value="<?php if($merchantPersonalInfo->passport_no != null || $merchantPersonalInfo->passport_no != '') echo '1'; else echo '0'; ?>" <?php if($merchantPersonalInfo->passport_no != null || $merchantPersonalInfo->passport_no != '') echo 'checked'; ?>>Passport No</label>
									</div>
									<input type="text" name="passport_no_{{ $countPersonal }}" placeholder="Number of Passport: 8 alphanumeric" value="<?php if(isset($merchantPersonalInfo)) echo $merchantPersonalInfo->passport_no; ?>" class="form-control" id="passport_no_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->passport_no == null || $merchantPersonalInfo->passport_no == '') echo 'display:none;'; ?>" maxlength="8" onkeypress="if(event.charCode == 32) return false;" />

									<span class="label label-<?php if($merchantPersonalInfo->passport_is_verified == '1') echo 'success'; else if($merchantPersonalInfo->passport_is_verified == '0') echo 'warning'; else if($merchantPersonalInfo->passport_is_verified == '-1') echo 'danger'; ?>" id="passport_no_label_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->passport_no == null || $merchantPersonalInfo->passport_no == '') echo 'display:none;'; ?>"><?php if($merchantPersonalInfo->passport_is_verified == '1') echo 'Verified'; else if($merchantPersonalInfo->passport_is_verified == '0') echo 'Unverified'; else if($merchantPersonalInfo->passport_is_verified == '-1') echo 'Rejected'; ?></span>

									<input type="file" name="passport_no_file_{{ $countPersonal }}" value="" class="form-control" id="passport_no_file_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->passport_no == null || $merchantPersonalInfo->passport_no == '') echo 'display:none;'; ?> required="true" />

									<img src="<?php if($merchantPersonalInfo->passport_filename != null || $merchantPersonalInfo->passport_filename != '') { ?>{{ asset('images/merchant/document') }}/{{$merchantPersonalInfo->passport_filename }} <?php } ?>" id="passport_no_preview_{{ $countPersonal }}" style="max-width:200px; max-height:200px; cursor:pointer; <?php if($merchantPersonalInfo->passport_filename == null || $merchantPersonalInfo->passport_filename == '') echo 'display:none;'; ?>" onclick="showImage('passport_no_preview_{{ $countPersonal }}', 'Passport Number')" />

									<span class="help-block" id="help_block_passport_no_{{ $countPersonal }}" style="display:none;">
									</span>

								</div>
								<div class="form-group" id="div_voter_id_card_check_{{ $countPersonal }}">
									<div class="checkbox">
									  <label><input type="checkbox" id="voter_id_card_check_{{ $countPersonal }}" name="voter_id_card_check_{{ $countPersonal }}" value="<?php if($merchantPersonalInfo->voter_id_card_filename != null || $merchantPersonalInfo->voter_id_card_filename != '') echo '1'; else echo '0'; ?>" <?php if($merchantPersonalInfo->voter_id_card != null || $merchantPersonalInfo->voter_id_card_filename != '') echo 'checked'; ?>>Voter ID Card</label>
									</div>

									<span class="label label-<?php if($merchantPersonalInfo->voter_id_card_is_verified == '1') echo 'success'; else if($merchantPersonalInfo->voter_id_card_is_verified == '0') echo 'warning'; else if($merchantPersonalInfo->voter_id_card_is_verified == '-1') echo 'danger'; ?>" id="voter_id_label_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->voter_id_card_filename == null || $merchantPersonalInfo->voter_id_card_filename == '') echo 'display:none;'; ?>"><?php if($merchantPersonalInfo->voter_id_card_is_verified == '1') echo 'Verified'; else if($merchantPersonalInfo->voter_id_card_is_verified == '0') echo 'Unverified'; else if($merchantPersonalInfo->voter_id_card_is_verified == '-1') echo 'Rejected'; ?></span>

									<input type="file" name="voter_id_card_file_{{ $countPersonal }}" value="" class="form-control" id="voter_id_card_file_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->voter_id_card_filename == null || $merchantPersonalInfo->voter_id_card_filename == '') echo 'display:none;'; ?> required="true" />

									<img src="<?php if($merchantPersonalInfo->voter_id_card_filename != null || $merchantPersonalInfo->voter_id_card_filename != '') { ?>{{ asset('images/merchant/document') }}/{{$merchantPersonalInfo->voter_id_card_filename }} <?php } ?>" id="voter_id_card_preview_{{ $countPersonal }}" style="max-width:200px; max-height:200px; cursor:pointer; <?php if($merchantPersonalInfo->voter_id_card_filename == null || $merchantPersonalInfo->voter_id_card_filename == '') echo 'display:none;'; ?>" onclick="showImage('voter_id_card_preview_{{ $countPersonal }}', 'Voter ID Card')" />

									<span class="help-block" id="help_block_voter_id_card_{{ $countPersonal }}" style="display:none;">
									</span>

								</div>
								<br>
								<h4>Address Proof</h4>
								<div class="form-group" id="div_electricity_bill_check_{{ $countPersonal }}">
									<div class="checkbox">
									  <label><input type="checkbox" id="electricity_bill_check_{{ $countPersonal }}" name="electricity_bill_check_{{ $countPersonal }}" value="<?php if($merchantPersonalInfo->electricity_bill_filename != null || $merchantPersonalInfo->electricity_bill_filename != '') echo '1'; else echo '0'; ?>" <?php if($merchantPersonalInfo->electricity_bill_filename != null || $merchantPersonalInfo->electricity_bill_filename != '') echo 'checked'; ?>>Electricity Bill</label>
									</div>

									<span class="label label-<?php if($merchantPersonalInfo->electricity_bill_is_verified == '1') echo 'success'; else if($merchantPersonalInfo->electricity_bill_is_verified == '0') echo 'warning'; else if($merchantPersonalInfo->electricity_bill_is_verified == '-1') echo 'danger'; ?>" id="electricity_bill_label_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->electricity_bill_filename == null || $merchantPersonalInfo->electricity_bill_filename == '') echo 'display:none;'; ?>"><?php if($merchantPersonalInfo->electricity_bill_is_verified == '1') echo 'Verified'; else if($merchantPersonalInfo->electricity_bill_is_verified == '0') echo 'Unverified'; else if($merchantPersonalInfo->electricity_bill_is_verified == '-1') echo 'Rejected'; ?></span>

									<input type="file" name="electricity_bill_file_{{ $countPersonal }}" value="" class="form-control" id="electricity_bill_file_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->electricity_bill_filename == null || $merchantPersonalInfo->electricity_bill_filename == '') echo 'display:none;'; ?> required="true" />

									<img src="<?php if($merchantPersonalInfo->electricity_bill_filename != null || $merchantPersonalInfo->electricity_bill_filename != '') { ?>{{ asset('images/merchant/document') }}/{{$merchantPersonalInfo->electricity_bill_filename }} <?php } ?>" id="electricity_bill_preview_{{ $countPersonal }}" style="max-width:200px; max-height:200px; cursor:pointer; <?php if($merchantPersonalInfo->electricity_bill_filename == null || $merchantPersonalInfo->electricity_bill_filename == '') echo 'display:none;'; ?>" onclick="showImage('electricity_bill_preview_{{ $countPersonal }}', 'Electricity Bill')" />

									<span class="help-block" id="help_block_electricity_bill_{{ $countPersonal }}" style="display:none;">
									</span>

								</div>
								<div class="form-group" id="div_landline_bill_check_{{ $countPersonal }}">
									<div class="checkbox">
									  <label><input type="checkbox" id="landline_bill_check_{{ $countPersonal }}" name="landline_bill_check_{{ $countPersonal }}" value="<?php if($merchantPersonalInfo->landline_bill_filename != null || $merchantPersonalInfo->landline_bill_filename != '') echo '1'; else echo '0'; ?>" <?php if($merchantPersonalInfo->landline_bill_filename != null || $merchantPersonalInfo->landline_bill_filename != '') echo 'checked'; ?>>Landline Bill</label>
									</div>

									<span class="label label-<?php if($merchantPersonalInfo->landline_bill_is_verified == '1') echo 'success'; else if($merchantPersonalInfo->landline_bill_is_verified == '0') echo 'warning'; else if($merchantPersonalInfo->landline_bill_is_verified == '-1') echo 'danger'; ?>" id="landline_bill_label_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->landline_bill_filename == null || $merchantPersonalInfo->landline_bill_filename == '') echo 'display:none;'; ?>"><?php if($merchantPersonalInfo->landline_bill_is_verified == '1') echo 'Verified'; else if($merchantPersonalInfo->landline_bill_is_verified == '0') echo 'Unverified'; else if($merchantPersonalInfo->landline_bill_is_verified == '-1') echo 'Rejected'; ?></span>

									<input type="file" name="landline_bill_file_{{ $countPersonal }}" value="" class="form-control" id="landline_bill_file_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->landline_bill_filename == null || $merchantPersonalInfo->landline_bill_filename == '') echo 'display:none;'; ?> required="true" />

									<img src="<?php if($merchantPersonalInfo->landline_bill_filename != null || $merchantPersonalInfo->landline_bill_filename != '') { ?>{{ asset('images/merchant/document') }}/{{$merchantPersonalInfo->landline_bill_filename }} <?php } ?>" id="landline_bill_preview_{{ $countPersonal }}" style="max-width:200px; max-height:200px; cursor:pointer; <?php if($merchantPersonalInfo->landline_bill_filename == null || $merchantPersonalInfo->landline_bill_filename == '') echo 'display:none;'; ?>" onclick="showImage('landline_bill_preview_{{ $countPersonal }}', 'Voter ID Card')" />

									<span class="help-block" id="help_block_landline_bill_{{ $countPersonal }}" style="display:none;">
									</span>

								</div>
								<div class="form-group" id="div_bank_account_statement_check_{{ $countPersonal }}">
									<div class="checkbox">
									  <label><input type="checkbox" id="bank_account_statement_check_{{ $countPersonal }}" name="bank_account_statement_check_{{ $countPersonal }}" value="<?php if($merchantPersonalInfo->bank_account_statement_filename != null || $merchantPersonalInfo->bank_account_statement_filename != '') echo '1'; else echo '0'; ?>" <?php if($merchantPersonalInfo->bank_account_statement_filename != null || $merchantPersonalInfo->bank_account_statement_filename != '') echo 'checked'; ?>>Bank Account Statement</label>
									</div>

									<span class="label label-<?php if($merchantPersonalInfo->bank_account_statement_is_verified == '1') echo 'success'; else if($merchantPersonalInfo->bank_account_statement_is_verified == '0') echo 'warning'; else if($merchantPersonalInfo->bank_account_statement_is_verified == '-1') echo 'danger'; ?>" id="bank_account_statement_label_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->bank_account_statement_filename == null || $merchantPersonalInfo->bank_account_statement_filename == '') echo 'display:none;'; ?>"><?php if($merchantPersonalInfo->bank_account_statement_is_verified == '1') echo 'Verified'; else if($merchantPersonalInfo->bank_account_statement_is_verified == '0') echo 'Unverified'; else if($merchantPersonalInfo->bank_account_statement_is_verified == '-1') echo 'Rejected'; ?></span>

									<input type="file" name="bank_account_statement_file_{{ $countPersonal }}" value="" class="form-control" id="bank_account_statement_file_{{ $countPersonal }}" style="<?php if($merchantPersonalInfo->bank_account_statement_filename == null || $merchantPersonalInfo->bank_account_statement_filename == '') echo 'display:none;'; ?> required="true" />

									<img src="<?php if($merchantPersonalInfo->bank_account_statement_filename != null || $merchantPersonalInfo->bank_account_statement_filename != '') { ?>{{ asset('images/merchant/document') }}/{{$merchantPersonalInfo->bank_account_statement_filename }} <?php } ?>" id="bank_account_statement_preview_{{ $countPersonal }}" style="max-width:200px; max-height:200px; cursor:pointer; <?php if($merchantPersonalInfo->bank_account_statement_filename == null || $merchantPersonalInfo->bank_account_statement_filename == '') echo 'display:none;'; ?>" onclick="showImage('bank_account_statement_preview_{{ $countPersonal }}', 'Voter ID Card')" />

									<span class="help-block" id="help_block_bank_account_statement_{{ $countPersonal }}" style="display:none;">
									</span>

								</div>
							 </div>
						 </div>
						@endforeach
					@endif
					</div>

						 <button type="button" class="btn btn-success" onclick="addPerson()">Add New Person</button><br>
						 <input type="hidden" id="total_person" name="total_person" value="{{ $countPersonal }}" />
						 <div class="box-footer">
							<button type="submit" id="btn_submit_personal_information" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Submit</button>
						</div>
					</form>
				 </div>
			</div>

		</div>
		<div id="bank_details" class="tab-pane fade">
			<br>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<form role="form" action="{{ route('merchants.update_bank_details') }}" method="post">
					{!! csrf_field() !!}
						<div class="form-group{{ $errors->has('bank_firm_name') ? ' has-error' : '' }}">
							<label for="bank_firm_name">Firm Name</label>
							<input type="text" name="bank_firm_name" value="{{ $user->firm_name }}" class="form-control" id="bank_firm_name" maxlength="50" onkeydown="return alphaOnly(event);" readonly>
							@if ($errors->has('bank_firm_name'))
								<span class="help-block">
									<strong>{{ $errors->first('bank_firm_name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
							<label for="bank_name">Bank Name <font color="red">*</font></label>
							<input type="text" name="bank_name" value="{{ $user->bank_name }}" class="form-control" id="bank_name" maxlength="255" onkeydown="return alphaOnlyWithSpace(event);" required>
							@if ($errors->has('bank_name'))
								<span class="help-block">
									<strong>{{ $errors->first('bank_name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('account_number') ? ' has-error' : '' }}">
							<label for="account_number">Account Number <font color="red">*</font></label>
							<input type="text" name="account_number" value="{{ $user->account_number }}" class="form-control" id="account_number" maxlength="255" onkeypress="return event.charCode >= 48 && event.charCode <= 57 " required>
							@if ($errors->has('account_number'))
								<span class="help-block">
									<strong>{{ $errors->first('account_number') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('bank_ifsc_code') ? ' has-error' : '' }}">
							<label for="bank_ifsc_code">Bank IFSC Code <font color="red">*</font></label>
							<input type="text" name="bank_ifsc_code" value="{{ $user->bank_ifsc_code }}" class="form-control" id="bank_ifsc_code" maxlength="255" required>
							@if ($errors->has('bank_ifsc_code'))
								<span class="help-block">
									<strong>{{ $errors->first('bank_ifsc_code') }}</strong>
								</span>
							@endif
						</div>

						 <div class="box-footer">
							<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Submit</button>
						</div>
					</form>
				 </div>
			</div>
		</div>
	</div>


</div>

@endsection

@push('scripts')
<script>

function alphaOnly(event)
{
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8);
}

function alphaOnlyWithSpace(event)
{
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8 || key == 32);
}

function disabledFirstChar(e)
{
    if (e.keyCode == 8 && $('#country_code').is(":focus") && $('#country_code').val().length < 2)
		{
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
									<img src="" id="personal_pan_card_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImage(\'personal_pan_card_preview_' + x + '\', \'Personal Pan Card\')" />\
									\
									<span class="help-block" id="personal_pan_card_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
								<div class="form-group" id="div_aadhar_no_check_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="aadhar_no_check_' + x + '" name="aadhar_no_check_' + x + '" value="">Aadhar Number</label>\
									</div>\
									<input type="text" name="aadhar_no_' + x + '" placeholder="Number of Aadhar: 12 digits" value="" class="form-control" id="aadhar_no_' + x + '" maxlength="12" onkeypress="return event.charCode >= 48 && event.charCode <= 57" style="display:none;" >\
									\
									<input type="file" name="aadhar_no_file_' + x + '" value="" class="form-control" id="aadhar_no_file_' + x + '" style="display:none;" />\
									\
									<img src="" id="aadhar_no_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImage(\'aadhar_no_preview_' + x + '\', \'Aadhar Number\')" />\
									\
									<span class="help-block" id="aadhar_no_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
								<div class="form-group" id="div_passport_no_check_' + x + '">\
									<div class="checkbox">\
									  <label><input type="checkbox" id="passport_no_check_' + x + '" name="passport_no_check_' + x + '" value="">Passport Number</label>\
									</div>\
									<input type="text" name="passport_no_' + x + '" placeholder="Number of Passport: 8 alphanumeric" value="" class="form-control" id="passport_no_' + x + '" onkeypress="if(event.charCode == 32) return false;" maxlength="8" style="display:none;" >\
									\
									<input type="file" name="passport_no_file_' + x + '" value="" class="form-control" id="passport_no_file_' + x + '" style="display:none;" />\
									\
									<img src="" id="passport_no_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImage(\'passport_no_preview_' + x + '\', \'Passpor Number\')" />\
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
									<img src="" id="voter_id_card_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImage(\'voter_id_card_preview_' + x + '\', \'Voter ID Card\')" />\
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
									<img src="" id="electricity_bill_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImage(\'electricity_bill_preview_' + x + '\', \'Electricity Bill\')" />\
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
									<img src="" id="landline_bill_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImage(\'landline_bill_preview_' + x + '\', \'Landline Bill\')" />\
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
									<img src="" id="bank_account_statement_preview_' + x + '" style="max-width:200px; max-height:200px; display:none;" onclick="showImage(\'bank_account_statement_preview_' + x + '\', \'Bank Account Statement\')" />\
									\
									<span class="help-block" id="bank_account_statement_help_block_' + x + '" style="display:none;">\
									</span>\
								</div>\
							  </div>\
						 </div>\
						 ');

	$('#personal_contact_no_' + x).blur(function()
	{
			if($('#personal_contact_no_' + x).val() != '')
			{
				sendOTPPersonal($(this).attr('id'), 'personal_contact_no', 'send', x);
			}
	});

	$('#personal_pan_card_check_' + x).change(function()
	{
			if($('#personal_pan_card_check_' + x).is(':checked'))
			{
				enableImageInput('personal_pan_card_file_' + x, 'personal_pan_card_preview_' + x, 'personal_pan_card_help_block_' + x);
				$('#personal_pan_card_' + x).attr('style', '');
				$('#personal_pan_card_check_').val('1');
			}
			else
			{
				clearImageInput('personal_pan_card_file_' + x, 'personal_pan_card_preview_' + x, 'personal_pan_card_help_block_' + x);
				$('#personal_pan_card_' + x).val('');
				$('#personal_pan_card_' + x).attr('style', 'display: none;');
				$('#personal_pan_card_check_').val('0');
			}
		});

	$('#personal_pan_card_file_' + x).change(function()
	{
		previewImage(this, 'personal_pan_card_preview_' + x, 'personal_pan_card_file_' + x, 'personal_pan_card_help_block_' + x);
	});

	$('#aadhar_no_check_' + x).change(function()
	{
			if($('#aadhar_no_check_' + x).is(':checked'))
			{
				enableImageInput('aadhar_no_file_' + x, 'aadhar_no_preview_' + x, 'aadhar_no_help_block_' + x);
				$('#aadhar_no_' + x).attr('style', '');
				$('#aadhar_no_check_').val('1');
			}
			else
			{
				clearImageInput('aadhar_no_file_' + x, 'aadhar_no_preview_' + x, 'aadhar_no_help_block_' + x);
				$('#aadhar_no_' + x).val('');
				$('#aadhar_no_' + x).attr('style', 'display: none;');
				$('#aadhar_no_check_').val('0');
			}
		});

	$('#aadhar_no_file_' + x).change(function()
	{
		previewImage(this, 'aadhar_no_preview_' + x, 'aadhar_no_file_' + x, 'aadhar_no_help_block_' + x);
	});

	$('#passport_no_check_' + x).change(function()
	{
			if($('#passport_no_check_' + x).is(':checked'))
			{
				enableImageInput('passport_no_file_' + x, 'passport_no_preview_' + x, 'passport_no_help_block_' + x);
				$('#passport_no_' + x).attr('style', '');
				$('#passport_no_check_').val('1');
			}
			else
			{
				clearImageInput('passport_no_file_' + x, 'passport_no_preview_' + x, 'passport_no_help_block_' + x);
				$('#passport_no_' + x).val('');
				$('#passport_no_' + x).attr('style', 'display: none;');
				$('#passport_no_check_').val('0');
			}
		});

	$('#passport_no_file_' + x).change(function()
	{
		previewImage(this, 'passport_no_preview_' + x, 'passport_no_file_' + x, 'passport_no_help_block_' + x);
	});

	$('#voter_id_card_check_' + x).change(function()
	{
			if($('#voter_id_card_check_' + x).is(':checked'))
			{
				enableImageInput('voter_id_card_file_' + x, 'voter_id_card_preview_' + x, 'voter_id_card_help_block_' + x);
				$('#voter_id_card_' + x).attr('style', '');
				$('#voter_id_card_check_').val('1');
			}
			else
			{
				clearImageInput('voter_id_card_file_' + x, 'voter_id_card_preview_' + x, 'voter_id_card_help_block_' + x);
				$('#voter_id_card_' + x).attr('style', 'display: none;');
				$('#voter_id_card_check_').val('0');
			}
		});

	$('#voter_id_card_file_' + x).change(function()
	{
		previewImage(this, 'voter_id_card_preview_' + x, 'voter_id_card_file_' + x, 'voter_id_card_help_block_' + x);
	});

	$('#electricity_bill_check_' + x).change(function()
	{
			if($('#electricity_bill_check_' + x).is(':checked'))
			{
				enableImageInput('electricity_bill_file_' + x, 'electricity_bill_preview_' + x, 'electricity_bill_help_block_' + x);
				$('#electricity_bill_' + x).attr('style', '');
				$('#electricity_bill_check_').val('1');
			}
			else
			{
				clearImageInput('electricity_bill_file_' + x, 'electricity_bill_preview_' + x, 'electricity_bill_help_block_' + x);
				$('#electricity_bill_' + x).attr('style', 'display: none;');
				$('#electricity_bill_check_').val('0');
			}
		});

	$('#electricity_bill_file_' + x).change(function()
	{
		previewImage(this, 'electricity_bill_preview_' + x, 'electricity_bill_file_' + x, 'electricity_bill_help_block_' + x);
	});

	$('#landline_bill_check_' + x).change(function()
	{
			if($('#landline_bill_check_' + x).is(':checked'))
			{
				enableImageInput('landline_bill_file_' + x, 'landline_bill_preview_' + x, 'landline_bill_help_block_' + x);
				$('#landline_bill_' + x).attr('style', '');
				$('#landline_bill_check_').val('1');
			}
			else
			{
				clearImageInput('landline_bill_file_' + x, 'landline_bill_preview_' + x, 'landline_bill_help_block_' + x);
				$('#landline_bill_' + x).attr('style', 'display: none;');
				$('#landline_bill_check_').val('0');
			}
		});

	$('#landline_bill_file_' + x).change(function()
	{
		previewImage(this, 'landline_bill_preview_' + x, 'landline_bill_file_' + x, 'landline_bill_help_block_' + x);
	});

	$('#bank_account_statement_check_' + x).change(function()
	{
			if($('#bank_account_statement_check_' + x).is(':checked'))
			{
				enableImageInput('bank_account_statement_file_' + x, 'bank_account_statement_preview_' + x, 'bank_account_statement_help_block_' + x);
				$('#bank_account_statement_' + x).attr('style', '');
				$('#bank_account_statement_check_').val('1');
			}
			else
			{
				clearImageInput('bank_account_statement_file_' + x, 'bank_account_statement_preview_' + x, 'bank_account_statement_help_block_' + x);
				$('#bank_account_statement_' + x).attr('style', 'display: none;');
				$('#bank_account_statement_check_').val('0');
			}
		});

	$('#bank_account_statement_file_' + x).change(function()
	{
		previewImage(this, 'bank_account_statement_preview_' + x, 'bank_account_statement_file_' + x, 'bank_account_statement_help_block_' + x);
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
		  url:'{{ route("merchants.send_otp.merchant") }}',
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
		  url:'{{ route("merchants.send_otp.merchant") }}',
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
		  url:'{{ route("merchants.verify_otp.merchant") }}',
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
		  url:'{{ route("merchants.verify_otp.merchant") }}',
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

function previewImage(input, imageId, elementInputId, elementHelpBlockId)
{
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
    if (input.files && input.files[0])
		{
        var reader = new FileReader();
        reader.onload = function (e)
				{
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


$(function()
{
		// Start Preview Image (Business Information)
		$("#vat_doc_check").change(function()
		{
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

		$("#vat_doc_file").change(function()
		{
			previewImage(this, 'vat_doc_preview', 'vat_doc_file', 'vat_doc_help_block');
		});

		$("#cst_doc_check").change(function()
		{
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

		$("#cst_doc_file").change(function()
		{
			previewImage(this, 'cst_doc_preview', 'cst_doc_file', 'cst_doc_help_block');
		});

		$("#service_tax_doc_check").change(function()
		{
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
		$("#service_tax_doc_file").change(function()
		{
			previewImage(this, 'service_tax_doc_preview', 'service_tax_doc_file', 'service_tax_doc_help_block');
		});

		$("#gumasta_doc_file").change(function()
		{
			previewImage(this, 'gumasta_doc_preview', 'gumasta_doc_file', 'gumasta_doc_help_block');
		});
		// End Preview Image (Business Information)

    $('#image-cropper').cropit();

		$('#email').blur(function()
		{
			if($('#email').val() != '')
			{
				sendOTP('contact_no', 'send');
			}
		});

		$('#contact_no').blur(function()
		{
			if($('#contact_no').val() != '')
			{
				sendOTP($(this).attr('id'), 'send');
			}
		});

		$('#business_contact_no').blur(function()
		{
			if($('#business_contact_no').val() != '')
			{
				sendOTP($(this).attr('id'), 'send');
			}
		});

		$('#personal_pan_card').keyup(function()
		{
			this.value = this.value.toUpperCase();
		});

		$('#bank_ifsc_code').keyup(function()
		{
			this.value = this.value.toUpperCase();
		});

		$('#passport_no').keyup(function()
		{
			this.value = this.value.toUpperCase();
		});

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

		//Profile
		$('#btn_submit_profile').click(function(event)
		{
			//check valid OTP
			$.ajax({
			  url:'{{ route("merchants.check_valid_otp") }}',
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
		 $('#btn_submit_business_information').click(function(event)
		 {
			//check valid OTP
			$.ajax({
			  url:'{{ route("merchants.check_valid_otp") }}',
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

		//Personal Information
		$("input[id^='personal_contact_no_']").each( function()
		{
			var x = $(this).attr('id').replace(/\D/g,'')
			$(this).blur( function() {
				sendOTPPersonal($(this).attr('id'), 'personal_contact_no', 'send', x);
			});
		});

		$("input[id^='personal_pan_card_check_']").each( function()
		{
			$(this).change( function()
			{
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

		$("input[id^='personal_pan_card_file_']").change( function()
		{
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'personal_pan_card_preview_' + x, 'personal_pan_card_file_' + x, 'help_block_personal_pan_card_' + x);
		});

		$("input[id^='aadhar_no_check_']").each( function()
		{
			$(this).change( function()
			{
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

		$("input[id^='aadhar_no_file_']").change( function()
		{
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'aadhar_no_preview_' + x, 'aadhar_no_file_' + x, 'help_block_aadhar_no_' + x);
		});

		$("input[id^='passport_no_check_']").each( function()
		{
			$(this).change( function()
			{
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

		$("input[id^='passport_no_file_']").change( function()
		{
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'passport_no_preview_' + x, 'passport_no_file_' + x, 'help_block_passport_no_' + x);
		});

		$("input[id^='voter_id_card_check_']").each( function()
		{
			$(this).change( function()
			{
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

		$("input[id^='voter_id_card_file_']").change( function()
		{
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'voter_id_card_preview_' + x, 'voter_id_card_file_' + x, 'help_block_voter_id_card_' + x);
		});

		$("input[id^='electricity_bill_check_']").each( function()
		{
			$(this).change( function()
			{
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

		$("input[id^='electricity_bill_file_']").change( function()
		{
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'electricity_bill_preview_' + x, 'electricity_bill_file_' + x, 'help_block_electricity_bill_' + x);
		});

		$("input[id^='landline_bill_check_']").each( function()
		{
			$(this).change( function()
			{
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

		$("input[id^='landline_bill_file_']").change( function()
		{
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'landline_bill_preview_' + x, 'landline_bill_file_' + x, 'help_block_landline_bill_' + x);
		});

		$("input[id^='bank_account_statement_check_']").each( function()
		{
			$(this).change( function()
			{
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

		$("input[id^='bank_account_statement_file_']").change( function()
		{
			var x = $(this).attr('id').replace(/\D/g,'')
			previewImage(this, 'bank_account_statement_preview_' + x, 'bank_account_statement_file_' + x, 'help_block_bank_account_statement_' + x);
		});

		$('#btn_submit_personal_information').click(function(event)
		{
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
					  url:'{{ route("merchants.check_valid_otp") }}',
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
});
</script>
@endpush
