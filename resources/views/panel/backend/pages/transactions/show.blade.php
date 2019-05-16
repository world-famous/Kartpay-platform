@extends('panel.backend.layouts.app')
@section('content')

<!-- Modal -->
<div id="modalChangeStatus" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modalChangeStatusTitle">Modal Header</h4>
      </div>
      <div class="modal-body" style="text-align:center;" id="modalChangeStatusBody">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer" id="modalChangeStatusFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Transaction <b>{{ $transaction->kartpay_id }}</b></h3>
            <hr>
        </div>
    </div>
</div>
<div>
    <div class="row">
        <div class="col-sm-12">

            <!-- top tiles -->
            <div class="row tile_count">
				<div class="col-md-3 col-sm-8 col-xs-12 tile_stats_count">
					<div class="count">{{ $transaction->kartpay_id }}</div>
					<span class="count_top">Kartpay ID</span>
				</div>
				<div class="col-md-3 col-sm-8 col-xs-12 tile_stats_count">
					<div class="count">{{ $transaction->order_id }}</div>
					<span class="count_top">Order ID</span>
				</div>
                <div class="col-md-3 col-sm-8 col-xs-12 tile_stats_count">
					<div class="count"><i class="fa fa-inr" style="color:#ea6c44"></i> {{ $transaction->order_amount }}</div>
                    <span class="count_top">Transaction Amount</span>
				</div>
                <div class="col-md-3 col-sm-8 col-xs-12 tile_stats_count">
                    <div class="count green">{{ $transaction->status }}</div>
                    <span class="count_top">Status</span>
                </div>
            </div>
            <!-- /top tiles -->

        </div>
    </div>

	 <div class="row">
         <div class="col-md-12 col-sm-12 col-xs-12 x-panel">
			<div class="row">
				<div class="col-md-12">
          <h3>Transaction Details @if($transaction->status == "Redirected" || $transaction->status == "Failed")<button id="btn_change_status" class="btn btn-success" onclick="ChangeStatus({{ $transaction->kartpay_id }})">Change Status</button>@endif</h3>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12 col-sm-6 col-xs-2">
					<div class="col-md-3">
						Order ID
					</div>
					<div class="col-md-3">
						<b>{{ $transaction->order_id }}</b>
					</div>
					<div class="col-md-3">
						Currency
					</div>
					<div class="col-md-3">
						<b>{{ $transaction->currency }}</b>
					</div>

					<br><br>
					<div class="col-md-3">
						Customer Email
					</div>
					<div class="col-md-3">
						<b>{{ $transaction->customer_email }}</b>
					</div>
					<div class="col-md-3">
						Customer Phone
					</div>
					<div class="col-md-3">
						<b>{{ $transaction->customer_phone }}</b>
					</div>

					<br><br>
					<div class="col-md-3">
						Success URL
					</div>
					<div class="col-md-3">
						<b>{{ $transaction->success_url }}</b>
					</div>
					<div class="col-md-3">
						Failed URL
					</div>
					<div class="col-md-3">
						<b>{{ $transaction->failed_url }}</b>
					</div>

					<br><br>
					<div class="col-md-3">
						Paid At
					</div>
					<div class="col-md-3">
						<b>
							@if($transaction->paid_at == null)
								<font color="red">- Not Paid Yet -</font>
							@else
							{{ $transaction->paid_at }}
							@endif
						</b>
					</div>
					<div class="col-md-3">
						Created At
					</div>
					<div class="col-md-3">
						<b>{{ $transaction->created_at }}</b>
					</div>

					<br><br>
					<div class="col-md-3">
						Updated At
					</div>
					<div class="col-md-3">
						<b>{{ $transaction->updated_at }}</b>
					</div>

				</div>
			</div>

		 </div>
	</div>

</div>
@endsection
@push('styles')
<style>
    #transactions th,td{
        text-align: center;
    }
</style>
@endpush
@push('scripts')
<script>
//Show Modal Change Status
function ChangeStatus(kartpay_id)
{
    $('#modalChangeStatusTitle').html('Change Status: ' + kartpay_id);

    $('#modalChangeStatusBody').html('<select id="status" class="form-control" required>\
                                        <option value="Success">Success</option>\
                                      </select>\
                                    ');
    $('#modalChangeStatusFooter').html('<button type="button" class="btn btn-success" data-dismiss="modal" onclick="SetStatus(\'' + kartpay_id + '\');">Yes</button>&nbsp;<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
    $('#modalChangeStatus').modal('show');
}
//END Show Modal Change Status

//Set Status
function SetStatus(kartpay_id)
{
  $.ajax({
      url:'{{ route("admins.transaction.set_status") }}',
      type:'POST',
      data:{
          '_token':'{{ csrf_token() }}',
          'kartpay_id': kartpay_id,
          'status': $('#status').val()
      },
      success: function (res) {
          if(res.response == 'Success')
          {
              location.reload();
          }
          else
          {
              ShowNotificationModal(res.message, 'error', 'Error');
          }
      },
      error: function(a, b, c){
          //ShowT(c, 'error', 'Error');
      }
  });
}
//END Set Status
</script>
@endpush
