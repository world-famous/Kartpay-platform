@extends('panel.backend.layouts.app')

@section('content')

<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>User Administration</h3>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			This is your user administration
		</div>
	</div>
	<br><br>
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
				  <th>Bank MID</th>
				  <th>Account Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody></tbody>
			  <tfoot>
				<tr>
				  <th>SR No.</th>
				  <th>First Name</th>
				  <th>Last Name</th>
				  <th>Contact No.</th>
				  <th>Email Id</th>
				  <th>Bank MID</th>
				  <th>Account Status</th>
				  <th>Action</th>
				</tr>
			  </tfoot>
			</table>
		 </div>
	</div>
				
</div>

@endsection

@push('script')
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
        		'url' : "{{ url('/user/display') }}",
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
        ShowNotification('Could not load data, please try to refresh the page.', 'error', 'Error');
      }
});
</script>
@endpush
