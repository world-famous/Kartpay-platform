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

  <div class="row pull-right">
    <div class="input-group">
      <div class="form-group">
        <div class="input-group">
            <div class="input-group-btn">
               <input type="text" class="form-control" maxlength="6" placeholder="Search BIN" id="bin_number" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" />
            </div>
            <div class="input-group-btn">
               <button class="btn btn-success" onclick="StoreBinEngine();">Submit</button>
            </div>
        </div>
      </div>
    </div>
	</div>
  <br>

  <div class="row">
      <div class="col-sm-5">
      </div>
      <div class="col-sm-12">
          <div class="col-sm-7">
              <!-- Blank Space-->
          </div>
          <div class="col-sm-5">
              <div class="input-group">

                  <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                  <select id="field" name="field" class="form-control">
                      <option value="bin_number">Bin Number</option>
                      <option value="card_issuer">Card Issuer</option>
                      <option value="card_type">Card Type</option>
                      <option value="bank_name">Bank</option>
                      <option value="country_name">Country</option>
                  </select>

                  <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                  <input type="search" id="search" name="search" class="form-control" placeholder="Search..." />

                  <span class="input-group-btn">
                      <button class="btn btn-submit" id="btn_search" onclick="SearchData();"><span class="fa fa-search"></span></button>
                    </span>

              </div>
          </div>
  </div>
</div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<table class="table table-bordered table-striped" id="bin_engines_datatable" style="text-align: center;">
			  <thead>
				<tr>
				  <th style="text-align: center;">Sr. No.</th>
				  <th style="text-align: center;">Bin Number</th>
				  <th style="text-align: center;">Card Issuer</th>
				  <th style="text-align: center;">Card Type</th>
				  <th style="text-align: center;">Bank</th>
				  <th style="text-align: center;">Country Name</th>
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

function DeleteBinEngine(id)
{
  CloseModalConfirm();
  $.ajax({
      url:'{{ route("admins.destroy_bin_engine") }}',
      type:'POST',
      data:{
        '_token':'{{ csrf_token() }}',
        'id': id
      },
      success: function (res) {
    		if(res.response == 'success')
    		{
    			showTemporaryMessage('Bin Number ' + res.bin_number + ' was deleted. ', 'success', 'Success');
          LoadData();
    		}
        else
        {
    			showTemporaryMessage(res.message, 'error', 'Error');
        }
      },
      error: function(a, b, c){
        //showTemporaryMessageHeader(c, 'error', 'Error');
      }
    });
}

$(document).on('click', '.delete_bin_engine', function(e)
{
  e.preventDefault();

  var el = $(this);
  var name = el.data('name');
  var id = el.data('id');

  $('#myModalConfirm').find('.modal-title').html('Delete Confirmation');
	$('#myModalConfirm').find('.modal-body').attr('align', 'center');
	$('#myModalConfirm').find('.modal-body').html('Do you want to delete bin number ' + name + '?');
	$('#myModalConfirm').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal" onclick="CloseModalConfirm()">Close</button><button type="button" class="btn btn-danger" data-dismiss="modal" onclick="DeleteBinEngine(\'' + id + '\')">Delete</button>');
	$('#myModalConfirm').modal('show');
});

function StoreBinEngine()
{
  if('{{ config('bin')['bin_codes_enable'] }}' == 'disabled' || '{{ config('bin')['bin_codes_enable'] }}' != 'enabled')
  {
    showTemporaryMessage('Please enable the Bin Codes from Bin Settings', 'error', 'Error');
    return false;
  }

  if($('#bin_number').val() == '')
  {
    showTemporaryMessage('Please insert Bin Number.', 'error', 'Error');
  }

	$.ajax({
      url:'{{ route("admins.store_bin_engine") }}',
      type:'POST',
      data:{
        '_token':'{{ csrf_token() }}',
        'bin_number': $('#bin_number').val()
      },
      success: function (res) {
    		if(res.response == 'success')
    		{
    			showTemporaryMessage('Bin Number ' + res.bin_number + ' was added. ', 'success', 'Success');
          LoadData();
          $('#bin_number').val('');
    		}
        else
        {
    			showTemporaryMessage(res.message, 'error', 'Error');
        }
      },
      error: function(a, b, c){
        //showTemporaryMessageHeader(c, 'error', 'Error');
      }
    });
}

function showTemporaryMessage(message, type, title)
{
	$('#info').html('\
						<div class="alert alert-' + type + ' alert-dismissable">\
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
						  <strong>' + title + '!</strong> ' + message + '.\
						</div>\
					');
}

function SearchData()
{
  /*
       * @function      Search bin_engine
       * @type          GET
       */
      try{
        $('#bin_engines_datatable')
        .dataTable({
            "searching": false,
            "destroy": true,
		        "ajax": {
        		'type': 'GET',
        		'url' : "{{ route('admins.search.bin_engine') }}",
        		'data': {
                      'field': $('#field').val(),
                      'search': $('#search').val()
        			       }
      		},
          "aoColumns" : [
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
        //showTemporaryMessageHeader('Could not load data, please try to refresh the page.', 'error', 'Error');
      }
}

function LoadData()
{
  /*
       * @function      Load bin_engine
       * @type          GET
       */
      try{
        $('#bin_engines_datatable')
        .dataTable({
            "searching": false,
            "destroy": true,
		        "ajax": {
        		'type': 'GET',
        		'url' : "{{ route('admins.display.bin_engine') }}",
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
            {}
          ]
        });
      }
      catch(err){
        //showTemporaryMessageHeader('Could not load data, please try to refresh the page.', 'error', 'Error');
      }
}

$(function(){
	 LoadData();
});
</script>
@endpush
