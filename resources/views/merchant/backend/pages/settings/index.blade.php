@extends('merchant.backend.layouts.app')
@section('content')
<div class="">
	<div class="row">
		<div class="page-title">
			<div class="title_left">
				<h3>API Settings</h3>
				<hr>
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
</div>

<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        API Keys
                    </h3>
                </div>				
	
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>Live</h2>
                            <div class="form-group">
                                <label for="merchant_id">Merchant ID</label>
                                <input type="text" readonly class="form-control" id="merchant_id" value="{{ $live_key->merchant_id }}">
                            </div>
                            <div class="form-group">
                                <label for="access_key">Access Key</label>
                                <input type="text" readonly class="form-control" id="access_key" value="{{ $live_key->access_key }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h2>Test</h2>
                            <div class="form-group">
                                <label for="merchant_id">Merchant ID</label>
                                <input type="text" readonly class="form-control" id="merchant_id" value="{{ $test_key->merchant_id }}">
                            </div>
                            <div class="form-group">
                                <label for="access_key">Access Key</label>
                                <input type="text" readonly class="form-control" id="access_key" value="{{ $test_key->access_key }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Webhook</h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('merchants.settings.webhook') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="webhook_url">Webhook URL</label>
                                    <input type="url" value="{{ $merchant->webhook }}" name="webhook_url" id="webhook_url" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection