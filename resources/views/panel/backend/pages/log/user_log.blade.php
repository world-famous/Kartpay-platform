@extends('panel.backend.layouts.app')

@section('content')

<div class="">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<table class="table table-bordered table-striped" id="logs_datatable">
			  <thead>
				<tr>
				  <th>Name</th>
				  <th>Description</th>
				  <th>User Name</th>
				  <th>Type</th>
				  <th>Date</th>
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
       * @function      Load Log
       * @type          GET
       */
      try{
        $('#logs_datatable')
        .dataTable({
		"ajax": {
        		'type': 'GET',
        		'url' : "{{ url('/log/display') }}",
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
