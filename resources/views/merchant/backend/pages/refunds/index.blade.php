@extends('merchant.backend.layouts.app')
@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Refunds</h3>
            <hr>
        </div>
    </div>
</div>
<div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table table-bordered table-condensed" id="refunds">
                <thead>
                    <tr>
                        <th>Sr No.</th>
                        <th>Kartpay ID</th>
                        <th>Order ID</th>
                        <th>Refund Amount</th>
                        <th>Status</th>
                        <th>View More</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    #refunds th,td
    {
        text-align: center;
    }
</style>
@endpush
@push('scripts')
<script>
    $(function ()
    {
        $("#refunds").DataTable()
    })
</script>
@endpush
