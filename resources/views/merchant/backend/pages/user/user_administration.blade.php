@extends('merchant.backend.layouts.app')

@section('content')

<div class="">
	@if (session('message'))
	<div class="row">
		<div class="alert alert-success alert-dismissable">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
				{!! session('message') !!}
		</div>
	</div>
	@endif

	@if($user->type == 'merchant')
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<a href="{{ route('merchants.add_new_staff') }}" class="btn btn-success">Add New Staff</a>
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
          ]
        });
      }
      catch(err){
        //ShowNotification('Could not load data, please try to refresh the page.', 'error', 'Error');
      }
});
</script>
@endpush
