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

        @if(session('success'))
            <div class="row">
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
                        {{session('success')}}
                </div>
            </div>
        @endif


    	<form class="form-horizontal form-label-left" method="post" action="{{ route('admins.update.bin') }}">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    BinCodes API Key <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" value="{{ $bin_codes_api_key }}" name="bin_codes_api_key" class="form-control col-md-7 col-xs-12" style="border-radius: 16px;border: 1px solid ORANGERED;">
                </div>
            </div>

            <div class="form-group">
    		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
    		        	BinCodes Format <span class="required">*</span>
    		        </label>
    		        <div class="col-md-4 col-sm-4 col-xs-12">
                        <input type="text" value="@php if($bin_codes_format == '') echo 'json'; else echo $bin_codes_format; @endphp" name="bin_codes_format" class="form-control col-md-7 col-xs-12" style="border-radius: 16px;border: 1px solid ORANGERED;" disabled>
    		        </div>
    		    </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    BinCode Enable
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="radio">
                    <label><input type="radio" name="bin_codes_enable" value="enabled" <?php if($bin_codes_enable == 'enabled' || $bin_codes_enable != 'disabled') echo 'checked'; ?>>Enabled</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="bin_codes_enable" value="disabled" <?php if($bin_codes_enable == 'disabled') echo 'checked'; ?>>Disabled</label>
                  </div>
                </div>
            </div>

		      {!! csrf_field() !!}
		      <div class="ln_solid"></div>
		        <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success" style="border-radius: 15px;">Submit</button>
                    <a href="/" class="btn btn-primary" type="button" style="border-radius: 15px;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
