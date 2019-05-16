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
	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<a href="{{ route('admins.add.gateway') }}" class="btn btn-success">Add New Gateway</a>
			</div>
		</div>
	</div>
	<br>
	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<table class="table table-bordered table-striped" id="gateways_datatable">
			  <thead>
				<tr>
				  <th>No.</th>
				  <th>Name (Total)</th>
				  <th>Action</th>
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
   
   function DeleteGateway(deletelink)
   {
		CloseModalConfirm();
		window.location.href = deletelink;
   }
   
   $(document).on('click', '.delete_gateway', function(e) {
        e.preventDefault();

        var el = $(this);
        var name = el.data('name');
        var deletelink = el.data('deletelink');

        $('#myModalConfirm').find('.modal-title').html('Delete Confirmation');
		$('#myModalConfirm').find('.modal-body').attr('align', 'center');
		$('#myModalConfirm').find('.modal-body').html('Do you want to delete gateway ' + name + ' and its type that belong to?');
		$('#myModalConfirm').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal" onclick="CloseModalConfirm()">Close</button><button type="button" class="btn btn-danger" data-dismiss="modal" onclick="DeleteGateway(\'' + deletelink + '\')">Delete</button>');
		$('#myModalConfirm').modal('show');
		
   });

$(function(){
	/*
       * @function      Load gateway
       * @type          GET
       */
      try{
        $('#gateways_datatable')
        .dataTable({
		"ajax": {
        		'type': 'GET',
        		'url' : "{{ route('admins.display.gateway') }}",
        		'data': {
        			}
		},
          "aoColumns" : [
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
