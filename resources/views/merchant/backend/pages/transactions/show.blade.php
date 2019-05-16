@extends('merchant.backend.layouts.app')
@section('content')
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
					<h3>Transaction Details</h3>
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

</script>
@endpush
