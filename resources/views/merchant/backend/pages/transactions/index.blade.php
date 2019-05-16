@extends('merchant.backend.layouts.app')
@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Transactions</h3>
            <hr>
        </div>
    </div>
</div>
<div>
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
                        <option value="kartpay_id">Kartpay ID</option>
                        <option value="created_at">Date - Time</option>
                        <option value="order_id">Order ID</option>
                        <option value="order_amount">Order Amount</option>
                        <option value="status">Status</option>
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
	<br>
	<div class="row">
        <div class="col-sm-12" style="text-align: center;">
            <table class="table table table-bordered table-condensed" id="transactions">
                <thead>
                    <tr>
                        <th style="text-align: center;">Sr No.</th>
                        <th style="text-align: center;">ID</th>
                        <th style="text-align: center;">Date - Time</th>
                        <th style="text-align: center;">Order ID</th>
                        <th style="text-align: center;">Order Amount</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Type</th>
                        <th style="text-align: center;">View More</th>
                    </tr>
                </thead>
                <tbody>
					@php
						$x = 0;
					@endphp
                    @foreach($transactions as $index => $transaction)
                    @php
                        $x++;
                    @endphp
                        <tr>
                            <td>{{ $x }}</td>
                            <td>{{ $transaction->kartpay_id }}</td>
                            <td>{{ $transaction->created_at->format("d/M/Y H:i") }}</td>
                            <td>{{ $transaction->order_id }}</td>
                            <td>{{ $transaction->order_amount }}</td>
                            <td>{{ $transaction->status }}</td>
                            <td>{{ $transaction->type }}</td>
                            <td>
                                <a href="{{ route('merchants.transactions.show', $transaction->kartpay_id) }}" class="btn btn-link text-muted">
                                    View More
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    #transactions th,td
    {
        text-align: center;
    }
</style>
@endpush
@push('scripts')
<script>

function SearchData()
{
	var datatable = $('#transactions').dataTable().api();

	$.get('{{ route("merchants.transaction.search") }}', { field: $('#field').val(), search: $('#search').val() }, function(newDataArray)
  {
		datatable.clear();
		datatable.rows.add(newDataArray);
		datatable.draw();
	});
}

$(function ()
{
	$("#transactions").DataTable(
  {
		"searching": false
	});
})
</script>
@endpush
