@extends('merchant.backend.layouts.app_firm_doc')
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
	<br>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<p>Congratulations! Your Uploaded Document is Approved, Now its time to send the Physical Documents to us.</p>
				<p></p>
				<p>You have Uploaded the Following Documents in this Sequence.</p>
				<p></p>

				@if($document != null)
					@php
						$num = 1;
						if($document->proprietor_pan_card_file != null)
						{
							$urlDoc = url($filePath . $document->proprietor_pan_card_file);
							echo '<p>' . $num . '. Proprietor PAN Card'  . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Proprietor PAN Card\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->gumasta_file != null)
						{
							$urlDoc = url($filePath . $document->gumasta_file);
							echo '<p>' . $num . '. Shop and Establishment Certifcate.(Gumasta)' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Shop and Establishment Certifcate.(Gumasta)\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->gst_in_file != null)
						{
							$urlDoc = url($filePath . $document->gst_in_file);
							echo '<p>' . $num . '. GSTIN ID' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'GSTIN ID\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->importer_exporter_code_file != null)
						{
							$urlDoc = url($filePath . $document->importer_exporter_code_file);
							echo '<p>' . $num . '. Importer/Exporter Code' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Importer/Exporter Code\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->passport_file != null)
						{
							$urlDoc = url($filePath . $document->passport_file);
							echo '<p>' . $num . '. Passport' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Passport\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->aadhar_card_file != null)
						{
							$urlDoc = url($filePath . $document->aadhar_card_file);
							echo '<p>' . $num . '. Aadhar Card' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Aadhar Card\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->driving_license_file != null)
						{
							$urlDoc = url($filePath . $document->driving_license_file);
							echo '<p>' . $num . '. Driving License' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Driving License\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->voter_id_card_file != null)
						{
							$urlDoc = url($filePath . $document->voter_id_card_file);
							echo '<p>' . $num . '. Voter ID Card' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Voter ID Card\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->property_tax_receipt_file != null)
						{
							$urlDoc = url($filePath . $document->property_tax_receipt_file);
							echo '<p>' . $num . '. Property TAX Receipt in Proprietor Name' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Property TAX Receipt in Proprietor Name\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->bank_canceled_cheque_file != null)
						{
							$urlDoc = url($filePath . $document->bank_canceled_cheque_file);
							echo '<p>' . $num . '. Bank Canceled Cheque' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Bank Canceled Cheque\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->audited_balance_sheet_file != null)
						{
							$urlDoc = url($filePath . $document->audited_balance_sheet_file);
							echo '<p>' . $num . '. Audited Balance Sheet' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Audited Balance Sheet\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->current_account_statement_file != null)
						{
							$urlDoc = url($filePath . $document->current_account_statement_file);
							echo '<p>' . $num . '. Current Account Statement. (Last 3 Month)' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Current Account Statement. (Last 3 Month)\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->income_tax_return_file != null)
						{
							$urlDoc = url($filePath . $document->income_tax_return_file);
							echo '<p>' . $num . '. Income tax Returns. (Last Year)' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Income tax Returns. (Last Year)\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
						if($document->kartpay_merchant_agreement_file != null)
						{
							$urlDoc = url($filePath . $document->kartpay_merchant_agreement_file);
							echo '<p>' . $num . '. Kartpay Merchant Agreement' . '&nbsp;' . '(<a style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Kartpay Merchant Agreement\')"><small>View</small></a>)' . '</p>';
							$num++;
						}
					@endphp
				@else
					<p>You have no approved documents yet.</p>
				@endif

				<p></p>
				@if($document)
				<p>We Request you to kindly keep the Document in the Same Sequence and send to us by the Courier and update the Tracking ID Below.
				@endif
			</div>
		</div>


		@if($document)
		<form role="form" action="{{ route('merchants.activation.process_settlement') }}" method="post" id="form_settlement">
			{!! csrf_field() !!}
		<div class="row">
			<div class="col-md-3 col-sm-3 col-xs-3">
				Courier Name
			</div>
			<div class="col-md-3 col-sm-3 col-xs-3">
				<select class="form-control" name="courier_id" required>
						<option value="">-- SELECT COURIER --</option>
						@foreach($couriers as $courier)
						<option value="{{ $courier->id }}" @php
																if($merchantDocumentCourier != null)
																{
																	if($merchantDocumentCourier->courier_id == $courier->id) echo 'selected';
																}
															@endphp

															>{{ $courier->courier_name }}</option>
						@endforeach
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col-md-3 col-sm-3 col-xs-3">
				Courier Tracking ID
			</div>
			<div class="col-md-3 col-sm-3 col-xs-3">
				<input type="text" class="form-control" name="courier_tracking_id" id="courier_tracking_id" value="@php
																								if($merchantDocumentCourier != null)
																								{
																									echo $merchantDocumentCourier->courier_tracking_id;
																								}
																						  @endphp"

																						  @php
																						  if($merchantDocumentCourier != null)
																							{
																								if($merchantDocumentCourier->courier_tracking_id != null)
																								{
																									echo 'readonly';
																								}
																							}
																						  @endphp
																						  required />
			</div>
		</div>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<button class="btn btn-primary" id="btn_submit">SUBMIT</button>
				</div>
			</div>

		</form>
		@endif
</div>
@endsection

@push('scripts')
<script>

//Show Preview Document
function ShowViewDoc(url, title, elementName)
{
	$('#modalViewDocTitle').html(title);
	$('#modalViewDocBody').html('<iframe src="' + url + '" style="width:800px; height:800px;"></iframe>');
	$('#modalViewDocFooter').html('\
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
									');
	$('#modalViewDoc').modal('show');
}
//END Show Preview Document

$('#btn_submit').click(function(event)
{
	if($('#form_settlement')[0].checkValidity())
	{
		$('#modalViewDocTitle').html('Confirmation');
		$('#modalViewDocBody').html('Are you sure your input of Tracking ID "' + $('#courier_tracking_id').val() + '" is correct?');
		$('#modalViewDocFooter').html('\
										<button type="button" class="btn btn-success" data-dismiss="modal" onclick="SendForm();">Yes</button>\
										<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>\
										');
		$('#modalViewDoc').modal('show');
		event.preventDefault();
	}
});

function SendForm()
{
	$("#form_settlement").submit();
}

$(function()
{
	@php
		if(session('message'))
		{
	@endphp
			window.location = "{{ route('merchants.dashboard.merchant') }}";
	@php
		}
	@endphp
});
</script>
@endpush
