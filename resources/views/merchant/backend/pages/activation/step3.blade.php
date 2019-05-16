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

		<div class="page-title">
			<div class="title_left">
				<h3 style="padding-left: 1%;">Step 3</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<p><b><i class="fa fa-file" aria-hidden="true"></i> Documents</b></p>
				<ul>
					<li><p>Please upload the required documents as per your Business Filling Status.</p></li>
					<li><p>To view your on-boarding progress any time during the on-boarding process,please check the 'Status' tab.</p></li>
					<li><p>Please upload scanned copies of your documents for verifcation. While we verify,you may proceed with the</p></li>
				</ul>
			</div>
		</div>

		@php
			$disabled_change = '';
      if($user->last_activation_step == 'step3' || $user->last_activation_step == 'step4')
      {
          $disabled_change = 'disabled';
      }
		@endphp

		<form role="form" action="{{ route('merchants.activation.process_step3') }}" method="post" enctype="multipart/form-data">
			{!! csrf_field() !!}

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b>BUSINESS FILING STATUS*</b>
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<select class="form-control" name="business_filling_status" id="business_filling_status" disabled>
							<option value="proprietor">PROPRIETOR</option>
						</select>
					</div>
				</div>
			</div>
			<hr>

			<div class="row">
				<p>
					<b>
						Please upload scanned copies of your documents for verification. While we verify, you may proceed with the Agreement.
					</b>
				</p>
				<p>Place scans into document(.DOC)file page wise and then upload,in case you have multiple scans of single document.</p>
				<p>File format accepted: </p>
				<ul>
					<li><p>Document Files(.pdf)</p></li>
					<li><p>Image Files(.jpg/.jpeg/.png/.bmp)</p></li>
				</ul>
			</div>
			<hr>

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-4 col-sm-1 col-xs-12">
						<b>01: Proprietor PAN Card</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="proprietor_pan_card_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->proprietor_pan_card_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_proprietor_pan_card_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->proprietor_pan_card_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="proprietor_pan_card_file_upload_progress" >
								<span id="proprietor_pan_card_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_proprietor_pan_card" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="proprietor_pan_card_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->proprietor_pan_card_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->proprietor_pan_card_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Proprietor Pan Card\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Proprietor Pan Card\', \'proprietor_pan_card\', \'help_block_proprietor_pan_card\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>
			</div>
			<hr style="width: 90%;">

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-4 col-sm-1 col-xs-12">
						<b id="goverment_issued_certificate_title">02: Government Issued Certifcate (Any-2)</b>
						<div class="row" id="goverment_issued_certificate_info">
						</div>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-1">
					</div>
					<div class="col-md-4 col-sm-1 col-xs-1">
					</div>
				</div>
				<br><br>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> A. Shop and Establishment Certifcate.(Gumasta)</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="gumasta_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->gumasta_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_gumasta_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->gumasta_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="gumasta_file_upload_progress">
								<span id="gumasta_file_upload_progress_text"></span>
							</div>
						</div>
						<span class="help-block" id="help_block_gumasta" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="gumasta_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->gumasta_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->gumasta_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Shop and Establishment Certifcate.(Gumasta)\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Shop and Establishment Certifcate.(Gumasta)\', \'gumasta\', \'help_block_gumasta\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-1">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> B. GSTIN ID</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="gst_in_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->gst_in_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_gst_in_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->gst_in_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="gst_in_file_upload_progress">
								<span id="gst_in_file_upload_progress_text"></span>
							</div>
						</div>
						<span class="help-block" id="help_block_gst_in" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="gst_in_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->gst_in_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->gst_in_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'GSTIN ID\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'GSTIN ID\', \'gst_in\', \'help_block_gst_in\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> C. Importer/Exporter Code.</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="importer_exporter_code_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->importer_exporter_code_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_importer_exporter_code_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->importer_exporter_code_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="importer_exporter_code_file_upload_progress">
								<span id="importer_exporter_code_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_importer_exporter_code" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="importer_exporter_code_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->importer_exporter_code_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->importer_exporter_code_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Importer/Exporter Code\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Importer/Exporter Code\', \'importer_exporter_code\', \'help_block_importer_exporter_code\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>
			</div>
			<hr style="width: 90%;">

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-4 col-sm-1 col-xs-12">
						<b id="proprietor_address_proof_title">03: Proprietor Address proof (Any-1)</b>
						<div class="row" id="proprietor_address_proof_info">
						</div>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-1">
					</div>
					<div class="col-md-4 col-sm-1 col-xs-1">
					</div>
				</div>
				<br><br>
				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> A. Passport.</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="passport_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->passport_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_passport_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->passport_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="passport_file_upload_progress">
								<span id="passport_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_passport" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="passport_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->passport_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->passport_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Passport\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Passport\', \'passport\', \'help_block_passport\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> B. Aadhar Card.</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="aadhar_card_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->aadhar_card_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_aadhar_card_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->aadhar_card_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="aadhar_card_file_upload_progress">
								<span id="aadhar_card_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_aadhar_card" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="aadhar_card_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->aadhar_card_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->aadhar_card_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Aadhar Card\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Aadhar Card\', \'aadhar_card\', \'help_block_aadhar_card\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> C. Driving License.</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="driving_license_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->driving_license_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_driving_license_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->driving_license_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="driving_license_file_upload_progress">
								<span id="driving_license_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_driving_license" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="driving_license_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->driving_license_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->driving_license_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Driving License\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Driving License\', \'driving_license\', \'help_block_driving_license\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> D. Voter ID Card.</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="voter_id_card_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->voter_id_card_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_voter_id_card_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->voter_id_card_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="voter_id_card_file_upload_progress">
								<span id="voter_id_card_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_voter_id_card" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="voter_id_card_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->voter_id_card_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->voter_id_card_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Voter ID Card\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Voter ID Card\', \'voter_id_card\', \'help_block_voter_id_card\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> E. Property TAX Receipt in Proprietor Name</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="property_tax_receipt_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->property_tax_receipt_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_property_tax_receipt_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->property_tax_receipt_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="property_tax_receipt_file_upload_progress">
								<span id="property_tax_receipt_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_property_tax_receipt" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="property_tax_receipt_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->property_tax_receipt_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->property_tax_receipt_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Property TAX Receipt in Proprietor Name\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Property TAX Receipt in Proprietor Name\', \'property_tax_receipt\', \'help_block_property_tax_receipt\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

			</div>
			<hr style="width: 90%;">

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-4 col-sm-1 col-xs-12">
						<b id="business_related_documents_title">04: Business Related Documents (Any-2)</b>
						<div class="row" id="business_related_documents_info">

						</div>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-1">
					</div>
					<div class="col-md-4 col-sm-1 col-xs-1">
					</div>
				</div>
				<br><br>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-1">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> A. Bank Canceled Cheque.(Compulsory).</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="bank_canceled_cheque_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->bank_canceled_cheque_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_bank_canceled_cheque_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->bank_canceled_cheque_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="bank_canceled_cheque_file_upload_progress">
								<span id="bank_canceled_cheque_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_bank_canceled_cheque" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="bank_canceled_cheque_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->bank_canceled_cheque_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->bank_canceled_cheque_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Bank Canceled Cheque.(Compulsory)\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Bank Canceled Cheque.(Compulsory)\', \'bank_canceled_cheque\', \'help_block_bank_canceled_cheque\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> B. Audited Balance Sheet</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="audited_balance_sheet_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->audited_balance_sheet_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_audited_balance_sheet_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->audited_balance_sheet_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="audited_balance_sheet_file_upload_progress">
								<span id="audited_balance_sheet_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_audited_balance_sheet" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-1" id="audited_balance_sheet_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->audited_balance_sheet_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->audited_balance_sheet_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Audited Balance Sheet\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Audited Balance Sheet\', \'audited_balance_sheet\', \'help_block_audited_balance_sheet\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-12">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> C. Current Account Statement. (Last 3 Month)</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="current_account_statement_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->current_account_statement_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_current_account_statement_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->current_account_statement_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="current_account_statement_file_upload_progress">
								<span id="current_account_statement_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_current_account_statement" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="current_account_statement_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->current_account_statement_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->current_account_statement_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Current Account Statement. (Last 3 Month)\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Current Account Statement. (Last 3 Month)\', \'current_account_statement\', \'help_block_current_account_statement\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-1 col-sm-1 col-xs-1">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-12">
						<b> D. Income tax Returns. (Last Year)</b>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="income_tax_return_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->income_tax_return_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_income_tax_return_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->income_tax_return_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="income_tax_return_file_upload_progress">
								<span id="income_tax_return_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_income_tax_return" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="income_tax_return_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->income_tax_return_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->income_tax_return_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Income tax Returns. (Last Year)\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Income tax Returns. (Last Year)\', \'income_tax_return\', \'help_block_income_tax_return\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

			</div>
			<hr style="width: 90%;">

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="col-md-4 col-sm-1 col-xs-12">
						<b id="kartpay_merchant_agreement_title">05: Kartpay Merchant Agreement (Compulsory)</b>
						<div class="row" id="kartpay_merchant_agreement_info">

						</div>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12">

						<div id="kartpay_merchant_agreement_file_upload" class="well" style="cursor:pointer;" @php if($merchantDocument) { if($merchantDocument->kartpay_merchant_agreement_file != null) { echo 'hidden'; } } @endphp><div class="dz-message" data-dz-message><span><h2>Drag and drop file here or click here to upload</h2></span></div></div>
						<div class="progress" id="div_kartpay_merchant_agreement_file_upload_progress" @php if($merchantDocument) { if($merchantDocument->kartpay_merchant_agreement_file != null) { echo 'hidden'; } } @endphp>
							<div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress id="kartpay_merchant_agreement_file_upload_progress">
								<span id="kartpay_merchant_agreement_file_upload_progress_text"></span>
							</div>
						</div>

						<span class="help-block" id="help_block_kartpay_merchant_agreement" style="display:none;"></span>
					</div>
					<div class="col-md-4 col-sm-1 col-xs-12" id="kartpay_merchant_agreement_status">
						@php
							if($merchantDocument)
              {
                  if($merchantDocument->kartpay_merchant_agreement_file != null)
                  {
                      $urlDoc = url($filePath . $merchantDocument->kartpay_merchant_agreement_file);
                      echo '<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' . $urlDoc . '\', \'Kartpay Merchant Agreement\')"><small>View</small></a>&nbsp;<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' . $urlDoc . '\', \'Kartpay Merchant Agreement\', \'kartpay_merchant_agreement\', \'help_block_kartpay_merchant_agreement\')"><small>Remove</small></a>';
                  }
                  else
                  {
                      echo '<span class="fa fa-ban"> </span> Not Uploaded';
                  }
              }
              else
              {
                  echo '<span class="fa fa-ban"> </span> Not Uploaded';
              }
						@endphp
					</div>
				</div>

			</div>
			<hr style="width: 90%;">

			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-12">
					@if($disabled_change == '')
						<button class="btn btn-primary" id="btn_submit">SUBMIT</button>
						<a href="{{ route('merchants.activation.step2') }}" class="btn btn-warning" id="btn_submit" style="float:right;" ><i class="fa fa-arrow-left"> Go back to Step 2</i></a>
					@endif
				</div>
			</div>

		</form>
	</div>

@endsection

@push('scripts')
<script>

    $('#btn_submit').click(function(event)
		{
        //Validate Total Document

        //Government Issued Certifcate (Any-2)
        var totalGovermentIssue = 0;
        var totalGumasta = 0;

        $.ajax({
            url:'{{ route("merchants.get.merchant_document") }}',
            type:'GET',
            async: false,
            data:{

            },
            success: function (res)
						{
                if(res.response == 'Error')
                {
                    showTemporaryMessage('You are not upload any document yet.', 'error', 'Error');
                    event.preventDefault();
                    window.scrollTo(0, 0);
                    return false;
                }
                else if(res.response == 'Success')
                {
                    //Validate Total Document
                    //Government Issued Certifcate (Any-2)
                    var totalGovermentIssue = 0;
                    var totalGumasta = 0;

                    if(res.gumasta_file != null)
                    {
                        totalGovermentIssue++;
                        totalGumasta++;
                    }

										if(res.gst_in_file != null)
                    {
                        totalGovermentIssue++;
                    }

                    if(res.importer_exporter_code_file != null)
                    {
                        totalGovermentIssue++;
                    }

                    if(totalGumasta < 1)
                    {
                        showTemporaryMessageWithElementId('Please upload Shop and Establishment Certifcate.(Gumasta) - Compulsory', 'error', 'Error', 'goverment_issued_certificate_info');
                        event.preventDefault();
                        location.hash = '#goverment_issued_certificate_title';
                        return false;
                    }

                    if(totalGovermentIssue < 2)
                    {
                        showTemporaryMessageWithElementId('Please upload minimum 2 Government Issued Certifcate', 'error', 'Error', 'goverment_issued_certificate_info');
                        event.preventDefault();
                        location.hash = '#goverment_issued_certificate_title';
                        return false;
                    }
                    //END Government Issued Certifcate (Any-2)

                    //Proprietor Address proof (Any-1)
                    var totalProprietorAddress = 0;

                    if(res.passport_file != null)
                    {
                        totalProprietorAddress++;
                    }

                    if(res.aadhar_card_file != null)
                    {
                        totalProprietorAddress++;
                    }

                    if(res.driving_license_file != null)
                    {
                        totalProprietorAddress++;
                    }

                    if(res.voter_id_card_file != null)
                    {
                        totalProprietorAddress++;
                    }

                    if(res.property_tax_receipt_file != null)
                    {
                        totalProprietorAddress++;
                    }

                    if(totalProprietorAddress < 1)
                    {
                        showTemporaryMessageWithElementId('Please upload minimum 1 Proprietor Address proof', 'error', 'Error', 'proprietor_address_proof_info');
                        event.preventDefault();
                        location.hash = '#proprietor_address_proof_title';
                        return false;
                    }
                    //END Proprietor Address proof (Any-1)

                    //Business Related Documents (Any-2)
                    var totalBusinessRelatedDocument = 0;
                    var totalBankCanceledCheque = 0;

                    if(res.bank_canceled_cheque_file != null)
                    {
                        totalBusinessRelatedDocument++;
                        totalBankCanceledCheque++;
                    }

                    if(res.audited_balance_sheet_file != null)
                    {
                        totalBusinessRelatedDocument++;
                    }

                    if(res.current_account_statement_file != null)
                    {
                        totalBusinessRelatedDocument++;
                    }

                    if(res.income_tax_return_file != null)
                    {
                        totalBusinessRelatedDocument++;
                    }

                    if(totalBankCanceledCheque < 1)
                    {
                        showTemporaryMessageWithElementId('Please upload Bank Canceled Cheque - Compulsory', 'error', 'Error', 'business_related_documents_info');
                        event.preventDefault();
                        location.hash = '#business_related_documents_title';
                        return false;
                    }

                    if(totalBusinessRelatedDocument < 2)
                    {
                        showTemporaryMessageWithElementId('Please upload minimum 2 Business Related Document', 'error', 'Error', 'business_related_documents_info');
                        event.preventDefault();
                        location.hash = '#business_related_documents_title';
                        return false;
                    }
                    //END Business Related Documents (Any-2)

										//Kartpay Merchant Agreement
                    var totalKartpayMerchantAgreement = 0;

                    if(res.kartpay_merchant_agreement_file != null)
                    {
                        totalKartpayMerchantAgreement++;
                    }

										if(totalKartpayMerchantAgreement < 1)
                    {
                        showTemporaryMessageWithElementId('Please upload Kartpay Merchant Agreement Document', 'error', 'Error', 'kartpay_merchant_agreement_info');
                        event.preventDefault();
                        location.hash = '#kartpay_merchant_agreement_title';
                        return false;
                    }
										//END Kartpay Merchant Agreement

                    //END Validate Total Document
                }
            },
            error: function(a, b, c){
                //ShowT(c, 'error', 'Error');
            }
        });

    });

		//Show Preview Document
    function ShowViewDoc(url, title)
    {
        $('#modalViewDocTitle').html(title);

        $('#modalViewDocBody').html('<iframe src="' + url + '" style="width:800px; height:800px;"></iframe>');
        $('#modalViewDocFooter').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#modalViewDoc').modal('show');
    }
		//END Show Preview Document

		//Show Remove Confirmation of Preview Document
    function RemoveConfirmDoc(url, title, item, elementId)
    {
        $('#modalViewDocTitle').html(title);
        $('#modalViewDocBody').html('Do you want to remove this document?<br><iframe src="' + url + '" style="width:800px; height:800px;"></iframe>');
        $('#modalViewDocFooter').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>&nbsp;<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="RemoveDoc(\'' + item + '\', \'' + elementId + '\')">Remove</button>');
        $('#modalViewDoc').modal('show');
    }
		//END Show Remove Confirmation of Preview Document

		//Remove Document
    function RemoveDoc(item, element)
    {
        $.ajax({
            url:'{{ route("merchants.remove.merchant_document") }}',
            type:'POST',
            data:{
                '_token':'{{ csrf_token() }}',
                'type': item
            },
            success: function (res) {
                if(res.response == 'Success')
                {
                    SetNotUploaded(res.type);
										hideErrorMessage(element);
                }
                else
                {
                    //ShowNotification(res.message, 'error', 'Error');
                }
            },
            error: function(a, b, c){
                //ShowT(c, 'error', 'Error');
            }
        });
    }
		//END Remove Document

		//Set Not Uploaded State
    function SetNotUploaded(item)
    {
        setProgressBar(item, 0);
        $('#' + item + '_status').html('<span class="fa fa-ban"> </span> Not Uploaded');
        $('#' + item + '_file_upload').show();
        $('#div_' + item + '_file_upload_progress').show();
    }
		//END Set Not Uploaded State

		//Remove Mark (message)
    function removeMark()
    {
        $('.dz-image').remove();
        $('.dz-details').remove();
        $('.dz-success-mark').remove();
        $('.dz-error-mark').remove();
    }
		//END Remove Mark (message)

		//Show Error Message
    function showErrorMessage(elementHelpBlockId, errorMessage)
    {
        $('#' + elementHelpBlockId).html('<strong><font color="red">' + errorMessage + '</font></strong>');
        $('#' + elementHelpBlockId).attr('style', '');
    }
		//END Show Error Message

		//Hide Error Message
    function hideErrorMessage(elementHelpBlockId)
    {
        $('#' + elementHelpBlockId).html('');
        $('#' + elementHelpBlockId).attr('style', 'display:none;');
    }
		//END Hide Error Message

		//Set Progress Bar
    function setProgressBar(element, progress)
    {
        $('#' + element + '_file_upload_progress').attr('style', 'width:' + progress + '%;');
        $('#' + element + '_file_upload_progress_text').html(progress + '%');
    }
		//END Set Progress Bar

		//Initialization Dropzone (Enable Drag And Drop)
    function enableDropZone(element)
    {
        $("div#" + element + "_file_upload").dropzone({
            acceptedFiles: "image/jpg, image/jpeg, image/png, image/bmp, application/pdf",
            url: '{{ route("merchants.upload.merchant_document") }} ',
            headers: {
            },
            params: {
                '_token': '{{ csrf_token() }}',
                'type': element
            },
            parallelUploads: 1,
            maxFiles: 1, // Number of files at a time
            maxFilesize: 2, //in MB
            createImageThumbnails: false,
            dictDefaultMessage: "Drag and drop file here or click here to upload",
            init: function() {
                removeMark();
								hideErrorMessage('help_block_' + element);
                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
                this.on("accept", function(file){
                    this.removeAllFiles();
                    hideErrorMessage('help_block_' + element);
                });
            },
            processing: function(file) {
								hideErrorMessage('help_block_' + element);
                removeMark();
                this.removeAllFiles();
            },
            uploadprogress: function(file, progress, bytesSent) {
                removeMark();
                setProgressBar(element, progress);
            },
            error: function(file, errorMessage) {
                this.removeAllFiles();
                removeMark();
                showErrorMessage('help_block_' + element, errorMessage);
                setProgressBar(element, 0);
            },
            success: function (file, response) {
                this.removeAllFiles();
                removeMark();
                var urlDoc = '{{ url($filePath) }}/' + response.file;
                $('#' + response.type + '_status').html('\<span class="fa fa-check"> </span> Uploaded &nbsp;&nbsp;<a class="btn btn-success" style="cursor:pointer;" onclick="ShowViewDoc(\'' + urlDoc + '\', \'' + response.title + '\')"><small>View</small></a>\
													\
													<a class="btn btn-danger" style="cursor:pointer;" onclick="RemoveConfirmDoc(\'' + urlDoc + '\', \'' + response.title + '\', \'' + response.type + '\', \'' + 'help_block_' + element + '\')"><small>Remove</small></a>\
													\
													');

                $('#' + response.type + '_file_upload').hide();
                $('#div_' + response.type + '_file_upload_progress').hide();
            }
        });
    }
		//END Initialization Dropzone (Enable Drag And Drop)

    $(function()
		{
        enableDropZone('proprietor_pan_card');
        enableDropZone('gumasta');
        enableDropZone('gst_in');
        enableDropZone('excise_registration_no');
        enableDropZone('importer_exporter_code');
        enableDropZone('passport');
        enableDropZone('aadhar_card');
        enableDropZone('driving_license');
        enableDropZone('voter_id_card');
        enableDropZone('property_tax_receipt');
        enableDropZone('bank_canceled_cheque');
        enableDropZone('audited_balance_sheet');
        enableDropZone('current_account_statement');
        enableDropZone('income_tax_return');
        enableDropZone('kartpay_merchant_agreement');

        @if($disabled_change == 'disabled')
					$(".dz-hidden-input").prop("disabled",true);
        	$(".dz-message").html("<span><h2>Upload Disabled</h2></span>");
				@endif;
    });

</script>
@endpush
