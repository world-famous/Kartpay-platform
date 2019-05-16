@extends('panel.backend.layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12">
    @include('flash::message')
  </div>

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

  @if (session('message'))
	<div class="row">
		<div class="alert alert-success alert-dismissable">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
				{!! session('message') !!}
		</div>
	</div>
	@endif
	<br>

</div>
<div class="">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
			<table class="table table-bordered table-striped" id="merchants_datatable" style="text-align: center;">
			  <thead>
				<tr style="text-align: center;">
				  <th style="text-align: center;width: 53px;">SR No.</th>
				  <th style="text-align: center;">First Name</th>
				  <th style="text-align: center;">Last Name</th>
				  <th style="text-align: center;">Contact No.</th>
				  <th style="text-align: center;">Email Id</th>
				  <th style="text-align: center;">Login Status</th>
				  <th style="text-align: center;">Live Domain Status</th>
				  <th style="text-align: center;">Action</th>
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
	$('.modal-body').html('Are you sure want to delete merchant: ' + email + ' and all its data?');
	$('.modal-footer').html('\
									<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="DeleteMerchant(\'' + deletelink + '\');">Yes</button>\
									<button type="button" class="btn btn-default" data-dismiss="modal">No</button>\
									');
	$('#myModal').modal('show');
}

function DeleteMerchant(deletelink)
{
	window.location = deletelink;
}

$(function(){
	/*
   * @function      Load merchant
   * @type          GET
   */
  try{
    $('#merchants_datatable').dataTable({
  		"ajax": {
          		'type': 'GET',
          		'url' : "{{ url('/merchant_administration/display') }}",
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
