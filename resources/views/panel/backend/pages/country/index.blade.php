@extends('panel.backend.layouts.app')

@section('content')

<div id="myModalConfirm" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick="CloseModal()">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body" align="center">
        <p>Some text in the modal.</p>
      </div>
	  <div class="modal-footer">
      </div>
    </div>

  </div>
</div>

<div class="">
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
	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<a href="{{ route('admins.add.country') }}" class="btn btn-success" style="border-radius: 25px;width: 140px;height: 39px;">Add New Country</a>
			</div>
		</div>
	</div>
	<br>
	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<table class="table table-bordered table-striped" id="countrys_datatable" style="text-align: center;">
			  <thead>
				<tr>
				  <th style="text-align: center;">Sr. No.</th>
				  <th style="text-align: center;">Country Name</th>
				  <th style="text-align: center;">Country Code</th>
				  <th style="text-align: center;">Status</th>
				  <th >Action</th>
				</tr>
			  </thead>
			  <tbody></tbody>
			</table>
		 </div>
	</div>				
</div>

@endsection

@push('scripts')
<script>

function CloseModalConfirm()
   {
		$('#myModalConfirm').modal('hide');
   }
   
   function DeleteCountry(deletelink)
   {
		CloseModalConfirm();
		window.location.href = deletelink;
   }
   
   $(document).on('click', '.delete_country', function(e) {
        e.preventDefault();

        var el = $(this);
        var name = el.data('name');
        var deletelink = el.data('deletelink');

        $('#myModalConfirm').find('.modal-title').html('Delete Confirmation');
		$('#myModalConfirm').find('.modal-body').attr('align', 'center');
		$('#myModalConfirm').find('.modal-body').html('Do you want to delete country ' + name + ' and their states and cities that belong to?');
		$('#myModalConfirm').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal" onclick="CloseModalConfirm()">Close</button><button type="button" class="btn btn-danger" data-dismiss="modal" onclick="DeleteCountry(\'' + deletelink + '\')">Delete</button>');
		$('#myModalConfirm').modal('show');
		
   });
   
function SetCountryStatus(selectElement, id)
{
	$.ajax({
      url:'{{ route("admins.set.country_status") }}', 
      type:'POST',
      data:{
        '_token':'{{ csrf_token() }}', 
        'id': id,
        'country_status': selectElement.value		
      },
      success: function (res) {
		if(res.response == 'Success')
		{
			showTemporaryMessage('Country ' + res.country_name + ' set to ' + res.country_status, 'success', 'Success');		
		}
      },
      error: function(a, b, c){
        //ShowT(c, 'error', 'Error');
      }
    });
}

$(function(){
	/*
       * @function      Load country
       * @type          GET
       */
      try{
        $('#countrys_datatable')
        .dataTable({
		"ajax": {
        		'type': 'GET',
        		'url' : "{{ route('admins.display.country') }}",
        		'data': {
        			}
		},
          "aoColumns" : [
            {},
            {},
            {},
            {},
            {}
          ]
        });
      }
      catch(err){
        //ShowNotification('Could not load data, please try to refresh the page.', 'error', 'Error');
      }
});
</script>
@endpush
