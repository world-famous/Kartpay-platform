@extends('merchant.backend.layouts.app_firm_doc')

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
	padding-left: 2%;
	text-align: left;
}

.kartpay-button-step1
{
    display : table-row;
    vertical-align : bottom;
    height : 1px;
}

input[type="radio"]
{
    -webkit-appearance: checkbox;
    -moz-appearance: checkbox;
    -ms-appearance: checkbox;     /* not currently supported */
    -o-appearance: checkbox;      /* not currently supported */
}

.radios
{
	position: relative;
	display: block;
	border-radius: 100px;
	border: solid 2px #d8d8d8;
	margin: 10px 30px;
	width: 88px;
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
		<div class="page-title" style="color: darkgray;padding-left: 1%;">
			<div class="title_left">
				<h1>Document Status</h1>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-1" style="text-align:center;">
				</div>
				<div class="kartpay-box-step1 col-md-10 col-sm-10 col-xs-10" style="text-align:center;">
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
		<br>
		<hr>

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-1" >
				</div>
				<div class="col-md-10 col-sm-10 col-xs-10">
					@if($merchantDocument->proprietor_pan_card_file != null)
					<div class="row">
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							<b>01: Proprietor PAN Card</b>
							@php
							if($merchantDocument->proprietor_pan_card_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->proprietor_pan_card_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Proprietor Pan Card\', \'proprietor_pan_card_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-2 col-sm-3 col-xs-3" style="padding-left: 3%;">
							<div class="radios">
							  <label><input type="radio" name="proprietor_pan_card_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->proprietor_pan_card_is_verified == 'APPROVE')
									echo 'checked';

								if($merchantDocument->proprietor_pan_card_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="padding-left: 3%;">
							<div class="radios">
							  <label><input type="radio" name="proprietor_pan_card_is_verified" value="REJECT" disabled
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
		<hr style="width: 90%;">

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-1">
				</div>
				<div class="col-md-10 col-sm-10 col-xs-10">
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-3">
							<b>02: Government Issued Certifcate(Any-2)</b>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-1">
				</div>
				<div class="kartpay-box-step1 col-md-7 col-sm-10 col-xs-10">

					@if($merchantDocument->gumasta_file != null)
					<div class="row">
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Shop and Establishment Certifcate.(Gumasta)
							@php
							if($merchantDocument->gumasta_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->gumasta_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Shop and Establishment Certifcate.(Gumasta)\', \'gumasta_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;padding-top: 1%;">
							<div class="radios">
							  <label><input type="radio" name="gumasta_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->gumasta_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->gumasta_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;padding-top: 1%;">
							<div class="radios">
							  <label><input type="radio" name="gumasta_is_verified" value="REJECT" disabled
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

					<div class="row">
						<div class="col-md-5 col-sm-3 col-xs-3">
							Sales TAX Registration Number.
						</div>
					</div>

					@if($merchantDocument->excise_registration_no_file != null)
					<div class="row">
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Excise Registration Number.
							@php
							if($merchantDocument->excise_registration_no_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->excise_registration_no_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Excise Registration Number.\', \'excise_registration_no_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="excise_registration_no_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->excise_registration_no_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->excise_registration_no_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="excise_registration_no_is_verified" value="REJECT" disabled
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

					@if($merchantDocument->importer_exporter_code_file != null)
					<div class="row">
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Importer/Exporter Code.
							@php
							if($merchantDocument->importer_exporter_code_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->importer_exporter_code_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Importer/Exporter Code.\', \'importer_exporter_code_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%">
							<div class="radios">
							  <label><input type="radio" name="importer_exporter_code_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->importer_exporter_code_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->importer_exporter_code_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="importer_exporter_code_is_verified" value="REJECT" disabled
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
		<hr style="width: 90%;">

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-1">
				</div>
				<div class="col-md-10 col-sm-10 col-xs-10">
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-3">
							<b>03: Proprietor Address Proof(Any-1)</b>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-1">
				</div>
				<div class="kartpay-box-step1 col-md-7 col-sm-10 col-xs-10">

					@if($merchantDocument->passport_file != null)
					<div class="row">
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Passport
							@php
							if($merchantDocument->passport_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->passport_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Passport\', \'passport_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="passport_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->passport_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->passport_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="passport_is_verified" value="REJECT" disabled
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
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Aadhar Card
							@php
							if($merchantDocument->aadhar_card_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->aadhar_card_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Aadhar Card\', \'aadhar_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="aadhar_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->aadhar_card_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->aadhar_card_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="aadhar_is_verified" value="REJECT" disabled
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
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Driving License
							@php
							if($merchantDocument->driving_license_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->driving_license_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Driving License\', \'driving_license_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="driving_license_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->driving_license_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->driving_license_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="driving_license_is_verified" value="REJECT" disabled
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
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Voter ID Card
							@php
							if($merchantDocument->voter_id_card_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->voter_id_card_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Voter ID Card\', \'voter_id_card_file\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="voter_id_card_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->voter_id_card_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->voter_id_card_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="voter_id_card_is_verified" value="REJECT" disabled
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
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Property TAX Receipt in Proprietor Name
							@php
							if($merchantDocument->property_tax_receipt_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->property_tax_receipt_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Property TAX Receipt in Proprietor Name\', \'property_tax_receipt_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="property_tax_receipt_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->property_tax_receipt_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->property_tax_receipt_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="property_tax_receipt_is_verified" value="REJECT" disabled
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
		<hr style="width: 90%;">

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-1">
				</div>
				<div class="col-md-10 col-sm-10 col-xs-10">
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-3">
							<b>04: Business Related Documents (Any-2)</b>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-2 col-sm-1 col-xs-1">
				</div>
				<div class="kartpay-box-step1 col-md-7 col-sm-10 col-xs-10">

					@if($merchantDocument->bank_canceled_cheque_file != null)
					<div class="row">
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Bank Canceled Cheque.(Compulsory)
							@php
							if($merchantDocument->bank_canceled_cheque_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->bank_canceled_cheque_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Bank Canceled Cheque.(Compulsory)\', \'bank_canceled_cheque_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="bank_canceled_cheque_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->bank_canceled_cheque_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->bank_canceled_cheque_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="bank_canceled_cheque_is_verified" value="REJECT"  disabled
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
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Audited Balance Sheet
							@php
							if($merchantDocument->audited_balance_sheet_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->audited_balance_sheet_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Audited Balance Sheet\', \'audited_balance_sheet_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="audited_balance_sheet_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->audited_balance_sheet_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->audited_balance_sheet_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="audited_balance_sheet_is_verified" value="REJECT" disabled
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
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Current Account Statement. (Last 3 Month)
							@php
							if($merchantDocument->current_account_statement_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->current_account_statement_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Current Account Statement. (Last 3 Month)\', \'current_account_statement_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="current_account_statement_is_verified" value="APPROVE" disabled
							   @php
								if($merchantDocument->current_account_statement_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->current_account_statement_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="current_account_statement_is_verified" value="REJECT" disabled
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
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							Income tax Returns. (Last Year)
							@php
							if($merchantDocument->income_tax_return_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->income_tax_return_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Income tax Returns. (Last Year)\', \'income_tax_return_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 8%;">
							<div class="radios">
							  <label><input type="radio" name="income_tax_return_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->income_tax_return_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->income_tax_return_is_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="left: 9%;">
							<div class="radios">
							  <label><input type="radio" name="income_tax_return_is_verified" value="REJECT" disabled
							  @php
								if($merchantDocument->income_tax_return_is_verified == 'REJECT')
								  echo 'checked';

								if($merchantDocument->income_tax_return_is_file == null)
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
		<hr style="width: 90%;">

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-1 col-sm-1 col-xs-1" >
				</div>
				<div class="col-md-10 col-sm-10 col-xs-10">

					@if($merchantDocument->kartpay_merchant_agreement_file != null)
					<div class="row">
						<div class="col-md-5 col-sm-3 col-xs-3" style="padding-top: 1%;">
							<b>05: Kartpay Merchant Agreement</b>
							@php
							if($merchantDocument->kartpay_merchant_agreement_file != null)
							{
								$urlDoc = url($filePath . $merchantDocument->kartpay_merchant_agreement_file);
								echo '<font color="green"><a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Kartpay Merchant Agreement\', \'kartpay_merchant_agreement_is_verified\')"><small>(view)</small></a></font>';
							}
							@endphp
						</div>
						<div class="col-md-2 col-sm-3 col-xs-3" style="padding-left: 3%;">
							<div class="radios">
							  <label><input type="radio" name="kartpay_merchant_agreement_is_verified" value="APPROVE" disabled
							  @php
								if($merchantDocument->kartpay_merchant_agreement_is_verified == 'APPROVE')
								  echo 'checked';

								if($merchantDocument->kartpay_merchant_agreement_file == null)
									echo 'disabled';
							  @endphp
							  required />Approve</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3" style="padding-left: 3%;">
							<div class="radios">
							  <label><input type="radio" name="kartpay_merchant_agreement_is_verified" value="REJECT" disabled
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

</div>

@endsection

@push('scripts')
<script>

function ShowViewDoc(url, title, elementName)
{
	$('#modalViewDocTitle').html(title);
	$('#modalViewDocBody').html('<iframe src="' + url + '" style="width:800px; height:800px;"></iframe>');
	$('#modalViewDocFooter').html('\
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
									');
	$('#modalViewDoc').modal('show');
}

$(function(){

});
</script>
@endpush
