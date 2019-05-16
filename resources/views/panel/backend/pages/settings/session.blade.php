@extends('panel.backend.layouts.app')
@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Session</h3>
            <hr>
        </div>
    </div>
</div>
<div>
    <div class="row">
        <div class="col-sm-12">
            <form action="/session-settings" class="form-horizontal" method="POST">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('timeout') ? 'has-error': '' }}">
                    <label for="timeout" class="control-label col-sm-3">Timeout Duration (minutes)</label>
                    <div class="col-sm-3">
                        <input type="number" min="1" name="timeout" style="border: 1px solid #fd4907;border-radius: 10px;"  value="{{ old('timeout', config('session.timeout')) }}" class="form-control" id="timeout">
                        @if($errors->has('timeout'))
                            <span class="help-block">{{ $errors->first('timeout') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('timeout_message') ? 'has-error': '' }}">
                    <label for="timeout_message" class="control-label col-sm-3">Timeout Message</label>
                    <div class="col-sm-3">
                        <textarea name="timeout_message" class="form-control" id="" cols="30" rows="10" style="border: 1px solid #fd4907;border-radius: 10px;">{{ old('timeout_message', config('session.timeout_message')) }}</textarea>
                        @if($errors->has('timeout_message'))
                            <span class="help-block">{{ $errors->first('timeout_message') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('timeout_delay') ? 'has-error': '' }}">
                    <label for="timeout" class="control-label col-sm-3">Timeout Message Delay (seconds)</label>
                    <div class="col-sm-3">
                        <input type="number" min="1" value="{{ old('timeout_delay', config('session.timeout_delay')) }}" class="form-control" id="timeout_delay" name="timeout_delay" style="border: 1px solid #fd4907;border-radius: 10px;">
                        @if($errors->has('timeout_delay'))
                            <span class="help-block">{{ $errors->first('timeout_delay') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm-3 col-sm-offset-3">
                    <button class="btn btn-success" style="border-radius: 10px; background: #169F85;">Save</button>
                    <a href="{{ url('/') }}" class="btn btn-primary" style="border-radius: 10px;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
