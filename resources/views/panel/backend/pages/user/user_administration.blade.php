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
	@if (session('message'))
	<div class="row">
		<div class="alert alert-success alert-dismissable">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
				{!! session('message') !!}
		</div>
	</div>
	@endif

	@if($user->type == 'panel')
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<a href="{{ route('admins.add_new_staff') }}" class="btn btn-success">Add New Staff</a>
			</div>
		</div>
	</div>
	<br>
	@endif
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<table class="table table-bordered table-striped" id="users_datatable">
			  <thead>
				<tr>
				  <th>SR No.</th>
				  <th>First Name</th>
				  <th>Last Name</th>
				  <th>Contact No.</th>
				  <th>Email Id</th>
				  <th>Type</th>
				  <th>Account Status</th>
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

function DeleteConfirm(email, deletelink)
{
	$('.modal-title').html('Confirmation');
	$('.modal-body').html('Are you sure want to delete user: ' + email + ' and all its data?');
	$('.modal-footer').html('\
									<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="DeleteUser(\'' + deletelink + '\');">Yes</button>\
									<button type="button" class="btn btn-default" data-dismiss="modal">No</button>\
									');
	$('#myModal').modal('show');
}

function DeleteUser(deletelink)
{
	window.location = deletelink;
}

$(function(){
	/*
       * @function      Load user
       * @type          GET
       */
      try{
        $('#users_datatable')
        .dataTable({
		"ajax": {
        		'type': 'GET',
        		'url' : "{{ url('/user_administration/display') }}",
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
          ],
           "initComplete": function(settings, json) {
	        $("input:submit").on('click', function () {
	          var value = $(this).val()
	          if(value == 'Block') {
	            var url = '{{ url()->current() }}/' + + $(this).data('id') + '/block'
	          } else {
	            var url = '{{ url()->current() }}/' + + $(this).data('id') + '/unblock'
	          }
	          $('<form action="' +url+ '" method="POST">{{ csrf_field() }}</form>').appendTo('body').submit()
	        })
	      }
        });
      }
      catch(err){
        //ShowNotification('Could not load data, please try to refresh the page.', 'error', 'Error');
      }
});
</script>
@endpush
