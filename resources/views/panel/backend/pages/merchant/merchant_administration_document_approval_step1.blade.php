@extends('panel.backend.layouts.app_firm_doc')

@push('styles')
<style type="text/css">
.kartpay-box-step1 
{ 
	border:solid 1px #2d2d2d;  
	text-align:center; background:#ffffff; 
	padding:0px 50px 0px 0px;  
	-moz-border-radius: 5px;  
	-webkit-border-radius: 5px; 
	border-radius: 5px; 
	border:solid 1px #000000;
	-moz-border-radius-topleft: 20px; 
	-moz-border-radius-topright:20px; 
	-moz-border-radius-bottomleft:20px; 
	-moz-border-radius-bottomright:20px; 
	-webkit-border-top-left-radius:20px; 
	-webkit-border-top-right-radius:20px; 
	-webkit-border-bottom-left-radius:20px;
	-webkit-border-bottom-right-radius:20px;
	border-top-left-radius:20px; 
	border-top-right-radius:20px; 
	border-bottom-left-radius:20px;
	border-bottom-right-radius:20px;
}

.kartpay-button-step1  
{
    display : table-row;
    vertical-align : bottom;
    height : 1px;
}

input[type="radio"] {
    -webkit-appearance: checkbox;
    -moz-appearance: checkbox;
    -ms-appearance: checkbox;     /* not currently supported */
    -o-appearance: checkbox;      /* not currently supported */
}

.radios {
	position: relative;
	display: block;
	border-radius: 100px;
	border: solid 2px #d8d8d8;
	margin: 10px 30px;
	width: 88px;
}

.boxed {
	border: 2px solid #ea0303;
	margin: 174px 0px 0px 0px;
	color: slategrey;
	border-radius: 100px;
}

.status{
	width: 82%;
	float: left;
	display: block;
	padding-left: 18%;
}

label.lab{
	margin-left: 18%;
	margin-bottom: 1%;
}

</style>
@endpush

@section('content')

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
	
	@if(!$merchantContactDetail || !$merchantBusinessDetail || !$merchantDocument)
		<div class="page-title">
			<div class="status">
				<div class="boxed">
					<h1 style="padding-left: 2%;">Merchant has not filled any required documents yet</h1>
				</div>
			</div>
		</div>
	@else	
		<div class="page-title" style="padding-left: 1%;color: darkgray;">
			<div class="title_left">
				<h1>Step 1</h1>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-1" style="text-align:center;">
				</div>
				<div class="kartpay-box-step1 col-md-8 col-sm-10 col-xs-10" style="text-align:center;margin-left: 4%;">
					<div class="row">
						<div class="col-md-7 col-sm-5 col-xs-5">
							<b>BUSINESS FILING STATUS*</b>
						</div>
						<div class="col-md-5 col-sm-5 col-xs-5">
							<label><b>Proprietor</b></label>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-7 col-sm-5 col-xs-5">
							OWNER NAME
						</div>
						<div class="col-md-5 col-sm-5 col-xs-5">
							<label>{{ $merchantContactDetail->owner_name }}</label>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-7 col-sm-5 col-xs-5">
							OWNER EMAIL
						</div>
						<div class="col-md-5 col-sm-5 col-xs-5">
							<label>{{ $merchantContactDetail->owner_email }}</label>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-7 col-sm-5 col-xs-5">
							MOBILE NUMBER
						</div>
						<div class="col-md-5 col-sm-5 col-xs-5">
							<label>{{ $merchantContactDetail->owner_mobile_no }}</label>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-7 col-sm-5 col-xs-5">
							BUSINESS LEGAL NAME
						</div>
						<div class="col-md-5 col-sm-5 col-xs-5">
							<label>{{ $merchantBusinessDetail->business_legal_name }}</label>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-7 col-sm-5 col-xs-5">
							REGISTERED ADDRESS
						</div>
						<div class="col-md-5 col-sm-5 col-xs-5">
							<label>{{ $merchantBusinessDetail->business_registered_address }}</label>
						</div>
					</div>
					
				</div>
				<div class="col-md-1 col-sm-1 col-xs-1" style="text-align:center;">
				</div>
			 </div>
		</div>
		<br><hr>
		<form role="form" action="{{ route('admins.merchant_administration.process_document_approval_step1', ['id' => $merchantDocument->merchant_id]) }}" method="post">
		{!! csrf_field() !!}
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-12" >
				</div>
				<div class="col-md-10 col-sm-10 col-xs-12">
					@if($merchantDocument->proprietor_pan_card_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							<b>01: Proprietor PAN Card</b>
							@php
							if($merchantDocument->proprietor_pan_card_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->proprietor_pan_card_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Proprietor Pan Card\', \'proprietor_pan_card_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-2 col-sm-3 col-xs-4" style="padding-left: 5%;">
							<div class="radios">
							  <label class="lab"><input type="radio" name="proprietor_pan_card_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->proprietor_pan_card_is_verified == 'APPROVE')
									echo 'checked';
							  
								if($merchantDocument->proprietor_pan_card_file == null)
									echo 'disabled';
							  @endphp							
							  required display="none" />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6" style="padding-left: 5%;">
							<div class="radios">
							  <label class="lab"><input type="radio" name="proprietor_pan_card_is_verified" value="REJECT"
							  @php
								if($merchantDocument->proprietor_pan_card_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->proprietor_pan_card_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
		<hr>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-12">
				</div>
				<div class="col-md-10 col-sm-10 col-xs-12">
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-12">
							<b>02: Government Issued Certifcate(Any-2)</b>
						</div>					
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-3 col-sm-1 col-xs-12">
				</div>
				<div class="kartpay-box-step1 col-md-7 col-sm-10 col-xs-12" style="padding-left: 2%;text-align: left;">
					
					@if($merchantDocument->gumasta_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Shop and Establishment Certifcate.(Gumasta)
							@php
							if($merchantDocument->gumasta_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->gumasta_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Shop and Establishment Certifcate.(Gumasta)\', \'gumasta_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="gumasta_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->gumasta_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->gumasta_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="gumasta_is_verified" value="REJECT"
							  @php
								if($merchantDocument->gumasta_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->gumasta_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
					<div class="row" hidden>
						<div class="col-md-5 col-sm-3 col-xs-12">
							Sales TAX Registration Number.
						</div>
					</div>
					
					@if($merchantDocument->vat_certificate_file != null)
					<div class="row" hidden>
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							i) VAT Certificate
							@php
							if($merchantDocument->vat_certificate_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->vat_certificate_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'VAT Certificate\', \'vat_certificate_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="vat_certificate_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->vat_certificate_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->vat_certificate_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="vat_certificate_is_verified" value="REJECT"
							  @php
								if($merchantDocument->vat_certificate_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->vat_certificate_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
					<!--
					@if($merchantDocument->cst_certificate_file != null)
					<div class="row" hidden>
						<div class="col-md-6 col-sm-3 col-xs-3" style="padding-top: 1%">
							i) CST Certificate
							@php
							if($merchantDocument->cst_certificate_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->cst_certificate_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'CST Certificate\', \'cst_certificate_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3">
							<div class="radios">
							  <label class="lab"><input type="radio" name="cst_certificate_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->cst_certificate_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->cst_certificate_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3">
							<div class="radios">
							  <label class="lab"><input type="radio" name="cst_certificate_is_verified" value="REJECT"
							  @php
								if($merchantDocument->cst_certificate_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->cst_certificate_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif				
					
					@if($merchantDocument->excise_registration_no_file != null)
					<div class="row" hidden>
						<div class="col-md-6 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Excise Registration Number.
							@php
							if($merchantDocument->excise_registration_no_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->excise_registration_no_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Excise Registration Number.\', \'excise_registration_no_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3">
							<div class="radios">
							  <label class="lab"><input type="radio" name="excise_registration_no_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->excise_registration_no_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->excise_registration_no_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3">
							<div class="radios">
							  <label class="lab"><input type="radio" name="excise_registration_no_is_verified" value="REJECT"
							  @php
								if($merchantDocument->excise_registration_no_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->excise_registration_no_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>				
					@endif
					-->

					@if($merchantDocument->gst_in_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							GSTIN ID
							@php
							if($merchantDocument->gst_in_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->gst_in_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'GSTIN ID\', \'gst_in_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="gst_in_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->gst_in_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->gst_in_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="gst_in_is_verified" value="REJECT"
							  @php
								if($merchantDocument->gst_in_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->gst_in_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif					
					
					@if($merchantDocument->importer_exporter_code_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Importer/Exporter Code.
							@php
							if($merchantDocument->importer_exporter_code_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->importer_exporter_code_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Importer/Exporter Code.\', \'importer_exporter_code_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="importer_exporter_code_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->importer_exporter_code_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->importer_exporter_code_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="importer_exporter_code_is_verified" value="REJECT"
							  @php
								if($merchantDocument->importer_exporter_code_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->importer_exporter_code_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
				</div>
			</div>
		</div>
		<hr>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-12">
				</div>
				<div class="col-md-10 col-sm-10 col-xs-12">
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-12">
							<b>03: Proprietor Address Proof(Any-1)</b>
						</div>					
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-3 col-sm-1 col-xs-12">
				</div>
				<div class="kartpay-box-step1 col-md-7 col-sm-10 col-xs-12" style="padding-left: 2%;text-align: left;">
					
					@if($merchantDocument->passport_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Passport
							@php
							if($merchantDocument->passport_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->passport_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Passport\', \'passport_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="passport_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->passport_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->passport_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="passport_is_verified" value="REJECT"
							  @php
								if($merchantDocument->passport_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->passport_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
					@if($merchantDocument->aadhar_card_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Aadhar Card
							@php
							if($merchantDocument->aadhar_card_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->aadhar_card_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Aadhar Card\', \'aadhar_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="aadhar_card_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->aadhar_card_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->aadhar_card_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="aadhar_card_is_verified" value="REJECT"
							  @php
								if($merchantDocument->aadhar_card_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->aadhar_card_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
					@if($merchantDocument->driving_license_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Driving License
							@php
							if($merchantDocument->driving_license_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->driving_license_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Driving License\', \'driving_license_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="driving_license_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->driving_license_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->driving_license_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="driving_license_is_verified" value="REJECT"
							  @php
								if($merchantDocument->driving_license_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->driving_license_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
					@if($merchantDocument->voter_id_card_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Voter ID Card
							@php
							if($merchantDocument->voter_id_card_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->voter_id_card_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Voter ID Card\', \'voter_id_card_file\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="voter_id_card_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->voter_id_card_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->voter_id_card_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="voter_id_card_is_verified" value="REJECT"
							  @php
								if($merchantDocument->voter_id_card_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->voter_id_card_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
					@if($merchantDocument->property_tax_receipt_file != null)				
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-:top 1%;">
							Property TAX Receipt in Proprietor Name
							@php
							if($merchantDocument->property_tax_receipt_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->property_tax_receipt_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Property TAX Receipt in Proprietor Name\', \'property_tax_receipt_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="property_tax_receipt_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->property_tax_receipt_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->property_tax_receipt_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="property_tax_receipt_is_verified" value="REJECT"
							  @php
								if($merchantDocument->property_tax_receipt_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->property_tax_receipt_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
				</div>
			</div>
		</div>
		<hr>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-12">
				</div>
				<div class="col-md-10 col-sm-10 col-xs-12">
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-12">
							<b>04: Business Related Documents (Any-2)</b>
						</div>					
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-3 col-sm-1 col-xs-12">
				</div>
				<div class="kartpay-box-step1 col-md-7 col-sm-10 col-xs-12" style="padding-left: 2%;text-align: left;">
					
					@if($merchantDocument->bank_canceled_cheque_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Bank Canceled Cheque.(Compulsory)
							@php
							if($merchantDocument->bank_canceled_cheque_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->bank_canceled_cheque_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Bank Canceled Cheque.(Compulsory)\', \'bank_canceled_cheque_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="bank_canceled_cheque_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->bank_canceled_cheque_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->bank_canceled_cheque_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="bank_canceled_cheque_is_verified" value="REJECT"
							  @php
								if($merchantDocument->bank_canceled_cheque_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->bank_canceled_cheque_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif				
					
					@if($merchantDocument->audited_balance_sheet_file != null)				
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top:1%;">
							Audited Balance Sheet
							@php
							if($merchantDocument->audited_balance_sheet_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->audited_balance_sheet_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Audited Balance Sheet\', \'audited_balance_sheet_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="audited_balance_sheet_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->audited_balance_sheet_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->audited_balance_sheet_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="audited_balance_sheet_is_verified" value="REJECT"
							  @php
								if($merchantDocument->audited_balance_sheet_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->audited_balance_sheet_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif				
					
					@if($merchantDocument->current_account_statement_file != null)
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Current Account Statement. (Last 3 Month)
							@php
							if($merchantDocument->current_account_statement_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->current_account_statement_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Current Account Statement. (Last 3 Month)\', \'current_account_statement_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="current_account_statement_is_verified" value="APPROVE"
							   @php
								if($merchantDocument->current_account_statement_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->current_account_statement_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="current_account_statement_is_verified" value="REJECT"
							  @php
								if($merchantDocument->current_account_statement_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->current_account_statement_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
					@if($merchantDocument->income_tax_return_file != null)				
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12" style="padding-top: 1%;">
							Income tax Returns. (Last Year)
							@php
							if($merchantDocument->income_tax_return_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->income_tax_return_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Income tax Returns. (Last Year)\', \'income_tax_return_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-4">
							<div class="radios">
							  <label class="lab"><input type="radio" name="income_tax_return_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->income_tax_return_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->income_tax_return_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6">
							<div class="radios">
							  <label class="lab"><input type="radio" name="income_tax_return_is_verified" value="REJECT"
							  @php
								if($merchantDocument->income_tax_return_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->income_tax_return_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
				</div>
			</div>
		</div>
		<hr>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-12" >
				</div>
				<div class="col-md-10 col-sm-10 col-xs-12">
				
					@if($merchantDocument->kartpay_merchant_agreement_file != null)		
					<div class="row">
						<div class="col-md-6 col-sm-3 col-xs-12">
							<b>05: Kartpay Merchant Agreement</b>
							@php
							if($merchantDocument->kartpay_merchant_agreement_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->kartpay_merchant_agreement_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Kartpay Merchant Agreement\', \'kartpay_merchant_agreement_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-2 col-sm-3 col-xs-4" style="padding-left: 5%;">
							<div class="radios">
							  <label class="lab"><input type="radio" name="kartpay_merchant_agreement_is_verified" value="APPROVE"
							  @php
								if($merchantDocument->kartpay_merchant_agreement_is_verified == 'APPROVE')
								  echo 'checked';
							  
								if($merchantDocument->kartpay_merchant_agreement_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-6" style="padding-left: 5%;">
							<div class="radios">
							  <label class="lab"><input type="radio" name="kartpay_merchant_agreement_is_verified" value="REJECT"
							  @php
								if($merchantDocument->kartpay_merchant_agreement_is_verified == 'REJECT')
								  echo 'checked';
							  
								if($merchantDocument->kartpay_merchant_agreement_file == null)
									echo 'disabled';
							  @endphp
							  required />Reject</label>
							</div>
						</div>
					</div>
					@endif
					
				</div>
			</div>
		</div>
		<hr>
		
		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12" style="text-align:right;">
				<button class="btn btn-primary" id="btn_submit">SUBMIT</button>
			</div>
		</div>
		
		</form>
		@endif
</div>

@endsection

@push('scripts')
<script>
function SetApprove(elementName)
{
	$('input[name=' + elementName + '][value="APPROVE"]').prop("checked", true);
}

function SetReject(elementName)
{
	$('input[name=' + elementName + '][value="REJECT"]').prop("checked", true);
}

function ShowViewDoc(url, title, elementName)
{
	$('#modalViewDocTitle').html(title);
	$('#modalViewDocBody').html('<iframe src="' + url + '" style="width:800px; height:800px;"></iframe>');
	$('#modalViewDocFooter').html('\
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
									&nbsp;&nbsp;\
									<button type="button" class="btn btn-success" data-dismiss="modal" onclick="SetApprove(\'' + elementName + '\')">APPROVE</button>\
									<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="SetReject(\'' + elementName + '\')">REJECT</button>\
									');
	$('#modalViewDoc').modal('show');
}

$(function(){
	
});	
</script>
@endpush
