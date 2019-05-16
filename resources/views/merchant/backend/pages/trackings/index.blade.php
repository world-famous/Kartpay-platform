@extends('merchant.backend.layouts.app')
@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Trackings</h3>
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
                        <th>Track Request ID</th>
                        <th>Tracing Number</th>
                        <th>Courier Name</th>
                        <th>Last Update On</th>
                        <th>Status</th>
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
