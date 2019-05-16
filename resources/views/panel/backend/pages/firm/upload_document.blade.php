@extends('panel.backend.layouts.app')

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
	<div class="page-title">
        <div class="title_left">
            <h3>Documents Verification</h3>
        </div>
    </div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<table class="table table-bordered table-striped" id="merchants_datatable">
			  <thead>
				<tr>
				  <th>No.</th>
				  <th>First Name</th>
				  <th>Last Name</th>
				  <th>Contact No.</th>
				  <th>Email Id</th>
				  <th>Account Status</th>
				  <th>Live Domain Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			</table>
		 </div>
	</div>	
	<br>
	<div class="row" id="info">
		<!-- <div class="alert alert-success alert-dismissable">
		  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  <strong>Success!</strong> Indicates a successful or positive action.
		</div> -->
	</div>
	<br>
    <div class="row">
	  <div class="box-body" align="center">
		<div id="drop_zone" style="width:50%; min-height:150px;; border:5px dashed gray; text-align:center; vertical-align:center; line-height:150px;">
			<div class="form-group">
				<b>Drag and Drop Files Here or click &nbsp;</b>
				  <span class="btn btn-warning btn-sm fileinput-button">
					<span>Upload documents</span>
					<input id="upload_document" name="upload_document" type="file" multiple>
					<input type="hidden" id="merchant_id" />
				  </span>
			</div>
		</div>
      </div>
	</div>
	<br>
    <div class="row">	
      <div class="box-body" align="center">
        Maximum file upload is: 2 MB. File upload supported '.jpg/.jpeg' format only
      </div>   
		<div class="box-body"><br>
			<div class="progress progress" style="margin-top:5px;">
				<div style="width: 0%; height: 100px;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-warning progress-bar-striped" id="progress_bar_document">
				<span class="sr-only"></span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="text-align:center;padding-right:0" align="center">
			Merchant <b><span id="merchant_name"></span></b> Documents
			<p id="result_message"><b>No Document</b><br><small>Select merchant on the table above to see the documents</small></p>
			<p id="mark_merchant_button"></p>
			<br>
			<div id="upload_result" style="text-align:center;" align="center">	
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>

function showTemporaryMessage(message, type, title)
{
	$('#info').html('\
						<div class="alert alert-' + type + ' alert-dismissable">\
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
						  <strong>' + title + '!</strong> ' + message + '.\
						</div>\
					');
}

function getExisitingDocuments(merchant_id)
  {
	  $('#merchant_id').val(merchant_id);
	  
	  $('#upload_result').html('');
	  $('#result_message').html('Loading....');
	  $.get('{{ route("admins.get_existing_file.admin") }}', { merchant_id: merchant_id }, 
		function (res) {
		  if (res.response) {
			  $('#result_message').html('');
			  $('#merchant_name').html(res.merchant_name);
			  
			  if(res.merchant_live_domain_active == 0) $('#mark_merchant_button').html('<h1><a class="label label-success" onclick="markMerchantLiveDomain(\'' + merchant_id + '\', \'1\')">Activate Live Domain</a></h1>');
			  else if(res.merchant_live_domain_active == 1) $('#mark_merchant_button').html('<h1><a class="label label-danger" onclick="markMerchantLiveDomain(\'' + merchant_id + '\', \'0\')">Deactivate Live Domain</a></h1>');
			  
			if (res.data.length > 0) { 			
			  $.each(res.data, function(k, v){ 
					k++;										
					var elm = '\
								<div id="document_' + v.document_id + '" class="col-md-3 col-sm-6 col-xs-12" style="position:relative; text-align:center;" align="center">\
								<img src="' + v.imageUrl + '" style="max-width:200px;max-height:200px;min-width:200px;min-height:200px;"></a>\
								<br>\
							  ';
					if(v.is_verified == '0') 
						elm += '<span class="label label-warning">Unverified</span>';
					else if(v.is_verified == '1') 
						elm += '<span class="label label-success">Verified</span>';
					else if(v.is_verified == '-1') 
						elm += '<span class="label label-danger">Rejected</span>';
						
					elm += '\
								<br>\
								<h4>\
								<a class="label label-success" onclick="showFile(\'' + v.imageUrl + '\', \'' + v.is_verified + '\', \'' + v.document_id + '\')">Show</a>\
								&nbsp;\
								<a class="label label-danger" onclick="removeFileConfirm(\'' + v.imageUrl + '\', \'' + v.document_id + '\')">Remove</a>\
								</h4>\
							   </div>\
							  '; 
					$('#upload_result').prepend(elm);
					$('#result_message').html('');		
			  });      
			} else {
				$('#result_message').html('<b>No Document Found</b><br><small></small>');
			}
		} else {
				showTemporaryMessage(res.error, 'error', 'Error');
			}
		});    
	}

function showFile(imageUrl, is_verified, document_id)
{
	$('.modal-title').html('Show Image');
	
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
							<img src="' + imageUrl + '"></img>\
						  ');
	$('.modal-footer').html('\
								' + verifiedBtn +'\
								' + unverifiedBtn +'\
								' + rejectedBtn +'\
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
							');
	$('#myModal').modal('show');
}

function removeFileConfirm(imageUrl, document_id)
{
	$('.modal-title').html('Delete Image Confirmation');
	$('.modal-body').html('\
							<p>Do you want to remove this file?</p>\
							<p><img src="' + imageUrl + '" style="max-width:300px;max-height:300px;"></img></p>\
						  ');
	$('.modal-footer').html('\
								<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="removeFile(\'' + document_id + '\')">Delete</button>\
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
							');
	$('#myModal').modal('show');
}

function removeFile(document_id)
{
	$.ajax({
      url:'{{ route("admins.delete_file.admin") }}',
      type:'POST',
      data:{
			'_token': '{{ csrf_token() }}', 
			'document_id': document_id
	  },
      success: function (res) {
        if (res.response == 'success') 
		{
          $('#document_'+document_id).remove();
          showTemporaryMessage('File Deleted..', 'success', 'Success');
		
		  if(res.countMerchant == '0') 
		  {			  
				$('#merchant_name').html('');
				$('#result_message').html('<b>No Document Found</b><br><small>Select merchant on the table above to see the documents</small>');		  
		  }
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

function addItem(filename, document_id, is_verified)
{
	var imageUrl = "{{ asset('images/merchant/document') }}/" + filename;
	var elm = '<div id="document_' + document_id + '" class="col-md-3 col-sm-6 col-xs-12" style="position:relative; text-align:center;" align="center">\
					<img src="' + imageUrl + '" style="max-width:200px;max-height:200px;min-width:200px;min-height:200px;"></a>\
					<br>\
					<span class="label label-warning">Unverified</span>\
					<br>\
					<h4>\
					<a class="label label-success" onclick="showFile(\'' + imageUrl + '\', \'' + is_verified + '\', \'' + document_id + '\')">Show</a>\
					&nbsp;\
					<a class="label label-danger" onclick="removeFileConfirm(\'' + imageUrl + '\', \'' + document_id + '\')">Remove</a>\
					</h4>\
			   </div>\
			   '; 
	$('#upload_result').prepend(elm);
	$('#result_message').html('');
}

function getMerchants()
{
	/*
       * @function      Load merchant
       * @type          GET
       */
      try{
        $('#merchants_datatable')
        .dataTable({
		"ajax": {
        		'type': 'GET',
        		'url' : "{{ route('admins.upload_document_display.admin') }}",
        		'data': {
        			}
		},
          "aoColumns" : [
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {}
          ]
        });
      }
      catch(err){
        showTemporaryMessage('Could not load data, please try to refresh the page.', 'error', 'Error');
      }
}

function markVerification(is_verified, document_id)
{
	$.ajax({
      url:'{{ route("admins.mark_verification.admin") }}',
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
		  getExisitingDocuments($('#merchant_id').val());
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

function markMerchantLiveDomain(merchant_id, live_domain_active)
{
	$.ajax({
      url:'{{ route("admins.mark_live_domain_active.admin") }}',
      type:'POST',
      data:{
			'_token': '{{ csrf_token() }}', 
			'merchant_id': merchant_id,
			'live_domain_active': live_domain_active
	  },
      success: function (res) {
        if (res.response == 'success') 
		{
          showTemporaryMessage(res.message, 'success', 'Success');
		  if(res.live_domain_active == '0') $('#mark_merchant_button').html('<h1><a class="label label-success" onclick="markMerchantLiveDomain(\'' + merchant_id + '\', \'1\')">Activate Live Domain</a></h1>');
		  else if(res.live_domain_active == '1') $('#mark_merchant_button').html('<h1><a class="label label-danger" onclick="markMerchantLiveDomain(\'' + merchant_id + '\', \'0\')">Deactivate Live Domain</a></h1>');
		  $("#merchants_datatable").DataTable().ajax.reload();
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

$(function(){
	
	getMerchants();
	
	$('#upload_document').bind('fileuploadsubmit', function (e, data) {
		data.formData = {merchant_id: $('#merchant_id').val()};
	});
	
	$('#upload_document').fileupload({		
		
		add: function(e, data) {
			if($('#merchant_id').val() == '')
			{
				showTemporaryMessage('Please select merchant on the table before upload.', 'error', 'Error');
				return false;
			}
			
			var uploadErrors = [];
			var acceptFileTypes = data.files[0].name.split('.').pop(), allowdtypes = 'jpeg,jpg';
            if (allowdtypes.indexOf(acceptFileTypes) < 0) {
                showTemporaryMessage('File type not allowed. Allow "jpg, jpeg" only.', 'error', 'Error');
                return false;
            }
			if (data.files[0].size > 2000000) {
				showTemporaryMessage('Filesize is too big.', 'error', 'Error');
				return false;
            }
				
			else
			{
				data.submit();							
			}
		},
		
		url: '{{ route("admins.upload_file.admin") }}',
        dataType: 'json',
        beforeSend: function(){				
			$('#progress_bar_document').removeClass("progress-bar-success");
			$('#progress_bar_document').addClass("progress-bar-striped");
			  showTemporaryMessage('Uploading documents...', 'info', 'Info');
        },
        done: function (e, data) {
          if(data.result.response == 'success'){
		    showTemporaryMessage('Documents uploaded...', 'success', 'Success');
			
			addItem(data.result.document_file_name, data.result.document_id, data.result.is_verified);
		  }
		},
        progressall: function (e, data) {		  
          var progress = parseInt(data.loaded / data.total * 100, 10);
          $('#progress_bar_document').css(
            'width',
            progress + '%'
          );
          $('#progress_bar_document').attr('aria-valuenow', progress);
		  
		  if(progress < 100)
			$('#progress_bar_document').html(progress + "% - uploading file...");
		  else
			$('#progress_bar_document').html(progress + "% - files uploaded...");			
        },
        error: function(a,b,c){
          showTemporaryMessage(c, 'error', 'Error');
          return false;
        }
      }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
@endpush
