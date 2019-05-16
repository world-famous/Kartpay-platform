@extends('panel.backend.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    	@if (count($errors) > 0)
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                @foreach($errors->all() as $error)
                    <span>{{$error}}</span><br/>
                @endforeach
            </div>
        @endif
    	<form class="form-horizontal form-label-left" method="post" action="email-settings">
			<div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Mail Driver <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="driver" class="form-control col-md-7 col-xs-12" value="{{ $driver }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Mail Host <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="host" class="form-control col-md-7 col-xs-12" value="{{ $host }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Mail Post <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="port" class="form-control col-md-7 col-xs-12" value="{{ $port }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Mail Encryption <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="encryption" class="form-control col-md-7 col-xs-12" value="{{ $encryption }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Mail From <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="address" class="form-control col-md-7 col-xs-12" value="{{ $from['address'] }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Mail Name <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{ $from['name'] }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Username <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="username" class="form-control col-md-7 col-xs-12" value="{{ $username }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Password <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="password" class="form-control col-md-7 col-xs-12" value="{{ $password }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    {!! csrf_field() !!}
		    <div class="ln_solid"></div>
		    <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
					<button type="submit" class="btn btn-success" style="border-radius: 10px;">Submit</button>
                    <a href="" class="btn btn-primary" type="button" style="border-radius: 10px;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection