@extends('live.layouts.app')
@section('content')
<div class="container">
    <br><br><br><br><br><br><br><br><br>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <img src="{{ asset('images/logo2.png') }}" alt="" style="width: 292px;margin-top: 45px;"class="site_logo img-responsive center-block">
            <br><br>
        </div>
    </div>
</div>
<form id="retry-form" method="POST" action="{{ route('live.payments.retry', encrypt($transaction->kartpay_id)) }}">
    {{ csrf_field() }}
</form>
@stop
@push('scripts')
<script>
    $(function()
    {
        if(confirm("Do you wish to retry the transaction?"))
        {
            $("#retry-form").submit()
        }
        else
        {
            window.location = '{!! $url !!}'
        }
    })
</script>
@endpush
