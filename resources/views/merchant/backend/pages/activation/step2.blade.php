@extends('merchant.backend.layouts.app_firm_doc')

@section('content')

<style>
	p.details
	{
		padding-left: 1%;
	}
</style>
<!-- Modal -->
<div id="modalViewDoc" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modalViewDocTitle">Modal Header</h4>
      </div>
      <div class="modal-body" id="modalViewDocBody">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer" id="modalViewDocFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div class="container">

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

	<div class="page-title">
		<div class="title_left">
			<h3>Step 2</h3>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<p class="details"><b><i class="fa fa-file" aria-hidden="true"></i> Provide following details to get started</b></p>
			<p class="details">1. Please complete Step2 (information) and Step3 (Documents) to complete your on-boarding process.</p>
			<p class="details">2. You can complete these steps in parralel as per your convenience.</p>
		</div>
	</div>
	<hr style="width: 92%;">

	@php
		$disabled_change = '';
		$hide = 'display:none';
		if($user->last_activation_step == 'step3' || $user->last_activation_step == 'step4')
		{
			$disabled_change = 'disabled';
			$hide = '';
		}
	@endphp

	<form role="form" action="{{ route('merchants.activation.process_step2') }}" id="form_step2" method="post">
	{!! csrf_field() !!}
	<div class="row">
		<div class="col-md-12 col-sm-6 col-xs-12">
			<div class="col-md-2 col-sm-1 col-xs-12">

			</div>
			<div class="col-md-2 col-sm-1 col-xs-12">
				BUSINESS FILING STATUS*
			</div>
			<div class="col-md-3 col-sm-1 col-xs-12">
				<select class="form-control" name="business_filling_status" id="business_filling_status" {{ $disabled_change }} required>
					<option value="">--SELECT BUSINESS FILLING STATUS--</option>
					<option value="proprietor">PROPRIETOR</option>
				</select>
			</div>
		</div>
	</div>

	</br>

	<div id="div_proprietor" hidden>
		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-3 col-sm-1 col-xs-12">
					<b style="font-size: 20px;">A. Business Details</b>
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					BUSINESS LEGAL NAME*
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<input type="text" name="business_legal_name" id="business_legal_name" class="form-control" onkeydown="return alphaOnlyWithSpace(event);" value="@php
								if($merchantBusinessDetail)
								{
									if($merchantBusinessDetail->business_legal_name != null)
									{
										echo $merchantBusinessDetail->business_legal_name;
									}
								}
							@endphp" {{ $disabled_change }} required />
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					BUSINESS TRADING NAME*
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<input type="text" name="business_trading_name" id="business_trading_name" class="form-control" onkeydown="return alphaOnlyWithSpace(event);" value="@php
								if($merchantBusinessDetail)
								{
									if($merchantBusinessDetail->business_trading_name != null)
									{
										echo $merchantBusinessDetail->business_trading_name;
									}
								}
							@endphp" {{ $disabled_change }} required />
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					REGISTERED ADDRESS*
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<textarea name="business_registered_address" id="business_registered_address" class="form-control" {{ $disabled_change }} required>@php
								if($merchantBusinessDetail)
								{
									if($merchantBusinessDetail->business_registered_address != null)
									{
										echo $merchantBusinessDetail->business_registered_address;
									}
								}
							@endphp</textarea>
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-4 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<select name="business_country" id="business_country" class="form-control" placeholder="COUNTRY" {{ $disabled_change }} required />
					<option value="">--SELECT COUNTRY--</option>
					@foreach($countrys as $country)
						<option value="{{ $country->id }}"
								@php
									if($merchantBusinessDetail)
									   {
										  if($merchantBusinessDetail->business_country != null)
										   {
											  if($merchantBusinessDetail->business_country == $country->id)
												{
													echo 'selected';
												}
										   }
										}
									else
									   {
										  if($country->name == 'India') echo 'selected';
									   }
								@endphp
						>{{ $country->country_name }}</option>
					@endforeach
					</select>
					@if($disabled_change != '')
						<input type="hidden" name="business_country" value="{{ $merchantBusinessDetail->business_country }}" />
					@endif
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-4 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<select name="business_state" id="business_state" class="form-control" placeholder="STATE" {{ $disabled_change }} required >
						<option value="">--SELECT STATE--</option>
						@foreach($businessStates as $businessState)
							<option value="{{ $businessState->id }}"
								@php
									if($merchantBusinessDetail)
									{
										if($merchantBusinessDetail->business_state != null)
										{
											if($merchantBusinessDetail->business_state == $businessState->id)
											{
												echo 'selected';
											}
										}
									}
								@endphp
							>{{ $businessState->state_name }}</option>
						@endforeach
					</select>
					@if($disabled_change != '')
						<input type="hidden" name="business_state" value="{{ $merchantBusinessDetail->business_state }}" />
					@endif
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-4 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<input type="text" class="form-control" name="business_city" id="business_city" placeholder="CITY" value="@php
									if($merchantBusinessDetail)
									{
										if($merchantBusinessDetail->business_city != null)
										{
											$city = \App\City::where('id', $merchantBusinessDetail->business_city)->first();
											echo $city->city_name;
										}
									}
						@endphp" {{ $disabled_change }} required />
					<!-- <select name="business_city" id="business_city" class="form-control" placeholder="CITY" {{ $disabled_change }} required />
						<option value="">--SELECT CITY--</option>
						@foreach($businessCitys as $businessCity)
							<option value="{{ $businessCity->id }}"
								@php
									if($merchantBusinessDetail)
									{
										if($merchantBusinessDetail->business_city != null)
										{
											if($merchantBusinessDetail->business_city == $businessCity->id)
											{
												echo 'selected';
											}
										}
									}
								@endphp
							>{{ $businessCity->city_name }}</option>
						@endforeach
					</select> -->
					@if($disabled_change != '')
						<input type="hidden" name="business_city" value="{{ $merchantBusinessDetail->business_city }}" />
					@endif
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-4 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<input type="text" name="business_pin_code" id="business_pin_code" class="form-control" placeholder="PIN CODE" {{ $disabled_change }} onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" maxlength="6" value="@php
								if($merchantBusinessDetail)
								{
									if($merchantBusinessDetail->business_pin_code != null)
									{
										echo $merchantBusinessDetail->business_pin_code;
									}
								}
							@endphp" required />
				</div>
			</div>
		</div>
		<hr style="width: 92%;">
		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-3 col-sm-1 col-xs-12">
					<b style="font-size: 20px;">B. Contact Details</b>
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					OWNER NAME*
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<input type="text" name="owner_name" id="owner_name" class="form-control" value="@php
								if($merchantContactDetail)
								{
									if($merchantContactDetail->owner_name != null)
									{
										echo $merchantContactDetail->owner_name;
									}
								}
							@endphp" {{ $disabled_change }} required />
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					OWNER EMAIL
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<input type="email" name="owner_email" id="owner_email" class="form-control" value="@php
						if($merchantContactDetail)
						{
							if($merchantContactDetail->owner_email != null)
							{
								echo $merchantContactDetail->owner_email;
							}
						}
						else
						{
							echo $user->email;
						}
					@endphp" {{ $disabled_change }} />
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					ADDRESS*
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<textarea name="owner_address" id="owner_address" class="form-control" {{ $disabled_change }} required >@php
							if($merchantContactDetail)
							{
								if($merchantContactDetail->owner_address != null)
								{
									echo $merchantContactDetail->owner_address;
								}
							}
						@endphp</textarea>
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					MOBILE NUMBER*
				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<input type="text" name="owner_mobile_no" id="owner_mobile_no" class="form-control" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" maxlength="10" value="@php
						if($merchantContactDetail)
							{
								if($merchantContactDetail->owner_mobile_no != null)
								{
									echo $merchantContactDetail->owner_mobile_no;
								}
							}
						else
						{
							echo $user->contact_no;
						}
					@endphp" {{ $disabled_change }} required />
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-4 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<select name="owner_country" id="owner_country" class="form-control" placeholder="COUNTRY" required />
					<option value="">--SELECT COUNTRY</option>
						 @foreach($countrys as $country)
							<option value="{{ $country->id }}"
								@php
										if($merchantContactDetail)
										   {
											  if($merchantContactDetail->owner_country != null)
												{
												  if($merchantContactDetail->owner_country == $country->id)
													{
													  echo 'selected';
													}
												}
										   }
										else
											{
											  if($country->id == 'India') echo 'selected';
											}
									@endphp
							>{{ $country->country_name }}</option>
						@endforeach
						</select>
						@if($disabled_change != '')
							<input type="hidden" name="owner_country" value="{{ $merchantBusinessDetail->owner_country }}" />
						@endif
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-4 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<select name="owner_state" id="owner_state" class="form-control" placeholder="STATE" {{ $disabled_change }} required >
						<option value="">--SELECT STATE--</option>
						@foreach($contactDetailStates as $contactDetailState)
							<option value="{{ $contactDetailState->id }}"
								@php
									if($merchantContactDetail)
									{
										if($merchantContactDetail->owner_state != null)
										{
											if($merchantContactDetail->owner_state == $contactDetailState->id)
											{
												echo 'selected';
											}
										}
									}
								@endphp
							>{{ $contactDetailState->state_name }}</option>
						@endforeach
					</select>
					@if($disabled_change != '')
						<input type="hidden" name="owner_state" value="{{ $merchantBusinessDetail->owner_state }}" />
					@endif
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-4 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<input type="text" class="form-control" name="owner_city" id="owner_city" placeholder="CITY" value="@php
									if($merchantContactDetail)
									{
										if($merchantContactDetail->owner_city != null)
										{
											$city = \App\City::where('id', $merchantContactDetail->owner_city)->first();
											echo $city->city_name;
										}
									}
						@endphp" {{ $disabled_change }} required />

					<!--
					<select name="owner_city" id="owner_city" class="form-control" placeholder="CITY" {{ $disabled_change }} required />
						<option value="">--SELECT CITY--</option>
						@foreach($contactDetailCitys as $contactDetailCity)
							<option value="{{ $contactDetailCity->id }}"
								@php
									if($merchantContactDetail)
									{
										if($merchantContactDetail->owner_city != null)
										{
											if($merchantContactDetail->owner_city == $contactDetailCity->id)
											{
												echo 'selected';
											}
										}
									}
								@endphp
							>{{ $contactDetailCity->city_name }}</option>
						@endforeach
					</select> -->
					@if($disabled_change != '')
						<input type="hidden" name="owner_city" value="{{ $merchantBusinessDetail->owner_city }}" />
					@endif
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-4 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					<input type="text" name="owner_pin_code" id="owner_pin_code" class="form-control" placeholder="PIN CODE" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" maxlength="6" value="@php
								if($merchantContactDetail)
								{
									if($merchantContactDetail->owner_pin_code != null)
									{
										echo $merchantContactDetail->owner_pin_code;
									}
								}
							@endphp" {{ $disabled_change }} required />
				</div>
			</div>
		</div>

		<hr style="width: 92%;">

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-3 col-sm-1 col-xs-12">
					<b style="font-size: 20px;">C. Bank Details</b>
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					ACCOUNT NUMBER*
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<input type="text" name="account_number" id="account_number" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="255" value="@php
						if($merchantBankDetail)
						{
							if($merchantBankDetail->account_number != null)
							{
								echo $merchantBankDetail->account_number;
							}
						}
					@endphp" {{ $disabled_change }} required />
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					BANK NAME*
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<select name="bank_id" id="bank_id" class="form-control" {{ $disabled_change }} required>
						<option value="">--SELECT BANK--</option>
						@foreach($banks as $bank)
						<option value="{{ $bank->id }}" @php
															if($merchantBankDetail)
															{
																if($merchantBankDetail->bank_id == $bank->id)
																{
																	echo "selected";
																}
															}
														@endphp
														>{{ $bank->bank_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					BANK IFSC CODE*
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<input type="text" name="bank_ifsc_code" id="bank_ifsc_code" class="form-control" maxlength="255" value="@php
								if($merchantBankDetail)
								{
									if($merchantBankDetail->bank_ifsc_code != null)
									{
										echo $merchantBankDetail->bank_ifsc_code;
									}
								}
							@endphp" onkeyup="toUppercase(this)" {{ $disabled_change }} required />
				</div>
			</div>
		</div>

		<hr style="width: 92%;">

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-3 col-sm-1 col-xs-12">
					<b style="font-size: 20px;">D. Website Details</b>
					<div class="row" id="website_details_info">

					</div>
				</div>
			</div>
		</div>

		</br>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-12">

				</div>
				<div class="col-md-2 col-sm-1 col-xs-12">
					Domain Name
				</div>
				<div class="col-md-3 col-sm-1 col-xs-12">
					<input type="text" name="domain_name" id="domain_name" class="form-control" maxlength="255" value="@php
								if($merchantWebsiteDetail)
								{
									if($merchantWebsiteDetail->domain_name != null)
									{
										echo $merchantWebsiteDetail->domain_name;
									}
								}
							@endphp" placeholder="example: http://domain.com or http://subdomain.domain.com"{{ $disabled_change }} required />
				</div>
			</div>
		</div>

		<hr style="width: 92%;">

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<b style="margin-left: 1%;"> Please cross check all information before proceeding to next step.</b>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="col-md-3 col-sm-1 col-xs-12">
					<div class="checkbox">
						<label style="margin-left: 5%;"><input type="checkbox" id="check_agree" value="1"
						@if($disabled_change != '')
							checked
						@endif
						{{ $disabled_change }} required><small>I agree to Kartpay <a style="cursor:pointer;" onclick="ShowViewDoc('Terms and Conditions', '@php if($termCondition) echo htmlentities($termCondition->message); @endphp', '@php if($termCondition) echo htmlentities($termCondition->content); @endphp')">Terms & Conditions</a></small></label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-sm-6 col-xs-12">
			@if($disabled_change == '')
			<button class="btn btn-primary" id="btn_submit">SUBMIT</button>
			<a href="{{ route('merchants.activation.step1') }}" class="btn btn-warning" style="float:right;" ><i class="fa fa-arrow-left"> Go back to Step 1</i></a>
			@endif
		</div>
	</div>
	</form>

</div>

@endsection

@push('scripts')
<script>

function toUppercase(obj)
{
  obj.value=obj.value.toUpperCase();
}

function alphaOnly(event)
{
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8 || key == 9);
}

function alphaOnlyWithSpace(event)
{
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8 || key == 9 || key == 32);
}

function getStateByCountry(country_id, el_state, el_city)
{
	$.ajax({
		url : '{{ route("merchants.get_state_by_country") }}',
		type: 'GET',
		data: {'country_id':country_id},
		dataType: 'JSON',
		beforeSend: function()
		{
			el_state.prop('disabled', true);
			el_city.prop('disabled', true);
			el_city.val('');
		},
		complete: function()
		{
		},
		success: function(res)
		{
		  if(res.response == 'Success')
			{
				var keys = Object.keys(res.states);
				el_state.html('');
				el_state.append('<option value="">--SELECT STATE--</option>');
				for(var x = 0; x < keys.length; x++)
				{
					el_state.append('<option value="'+res.states[x].id+'">'+res.states[x].state_name+'</option>');
				}
				el_state.prop('disabled', false);
		  }
		},
		error: function(a, b, c)
		{
		  showTemporaryMessage(c, 'error', 'Error');
		  return false;
		}
	  });
}

function getCityByState(state_id, el_city)
{
	$.ajax({
		url : '{{ route("merchants.get_city_by_state") }}',
		type: 'GET',
		data: {'state_id':state_id},
		dataType: 'JSON',
		beforeSend: function(){
			el_city.prop('disabled', true);
			el_city.val('');
		},
		complete: function(){
		},
		success: function(res){
		  if(res.response == 'Success'){
			var keys = Object.keys(res.cities);
			el_city.prop('disabled', false);
		  }
		},
		error: function(a, b, c){
		  showTemporaryMessage(c, 'error', 'Error');
		  return false;
		}
	  });
}

$('#business_country').change(function(event)
{
	getStateByCountry($('#business_country').val(), $('#business_state'), $('#business_city'));
});

$('#business_state').change(function(event)
{
	getCityByState($('#business_state').val(), $('#business_city'));
});

$('#owner_country').change(function(event)
{
	getStateByCountry($('#owner_country').val(), $('#owner_state'), $('#owner_city'));
});

$('#owner_state').change(function(event)
{
	getCityByState($('#owner_state').val(), $('#owner_city'));
});

function ShowViewDoc(title, message, content)
{
	$('#modalViewDocTitle').html(title);
	$('#modalViewDocBody').html('\
									<div class="row">\
										<div class="col-md-12 col-sm-6 col-xs-2">\
											<div class="col-md-2 col-sm-1 col-xs-1 well">\
												' + message + ' \
											</div>\
											<div class="col-md-10 col-sm-11 col-xs-11 well">\
												' + content + ' \
											</div>\
										</div>\
									</div>\
								');
	$('#modalViewDocFooter').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
	$('#modalViewDoc').modal('show');
}

function ValidURL(str)
{
  var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
  if(!regex .test(str))
	{
    return false;
  }
	else
	{
    return true;
  }
}

$('#btn_submit').click(function(event)
{
	if($('#form_step2')[0].checkValidity())
	{
		if(!ValidURL($('#domain_name').val()))
		{
			showTemporaryMessageWithElementId('The domain name is not in valid format', 'error', 'Error', 'website_details_info');
			event.preventDefault();
			window.scrollTo(0, 1500);
			return false;
		}
	}
});

$('#business_filling_status').change(function(event)
{
	if($('#business_filling_status').val() == '')
	{
		$('#div_proprietor').hide();
	}
	else if($('#business_filling_status').val() == 'proprietor')
	{
		$('#div_proprietor').show();
	}
});


$(function(){

	//getStateByCountry('India', $('#business_state'));
	var getCityFromBusinessState = function (request, response)
	{
		$.getJSON(
			"{{ route('merchants.get_city_by_state_autocomplete') }}?city_name=" + request.term + '&state_id=' + $('#business_state').val(),
			function (data) {
				response(data);
			});
	};

	$("#business_city").autocomplete(
	{
			source: getCityFromBusinessState
	});

	var getCityFromOwnerState = function (request, response)
	{
		$.getJSON(
			"{{ route('merchants.get_city_by_state_autocomplete') }}?city_name=" + request.term + '&state_id=' + $('#owner_state').val(),
			function (data) {
				response(data);
			});
	};

	$("#owner_city").autocomplete(
	{
			source: getCityFromOwnerState
	});

});
</script>
@endpush
