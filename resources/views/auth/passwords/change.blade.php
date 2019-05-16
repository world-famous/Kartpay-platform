@extends('layouts.app_verification')

@section('content')
<div class="container">
    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
            <div class="site--logo">
                <img src="{{ asset('images/logo.jpg') }}" class="center-block img-responsive" alt="">
            </div>
                <p>
                    <h2 class="text-center">Password Change</h2>
                    <i>You have not changed your password for the last 90 days.</i> <br>
                </p>
                <p>The password formats are:</p>
                <p>- Minimum has 1 uppercase.</p>
                <p>- Minimum has 1 lowercase.</p>
                <p>- Minimum has 1 symbol.</p>
                <p>- Minimum has 1 number.</p>
                <p>- Minimum length is 8 characters.</p>
                <p>- Maximum length is 16 characters.</p>
                </p>

                <form id="form_create_password" role="form" method="POST" action="{{ url('/password/change') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
                        <input id="old_password" type="password" class="form-control form-control-kartpay input-lg" name="old_password" maxlength="16" placeholder="Enter old password" value="" required autofocus>
                        @if ($errors->has('old_password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('old_password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                        <input id="new_password" type="password" class="form-control form-control-kartpay input-lg" name="new_password" maxlength="16" placeholder="Enter new password" value="" required autofocus>
                        @if ($errors->has('new_password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('new_password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                        <input id="confirm_password" type="password" class="form-control form-control-kartpay input-lg" name="confirm_password" maxlength="16" placeholder="Enter confirm password" value="" required autofocus>
                        @if ($errors->has('confirm_password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('confirm_password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <br>
                    <button type="button" class="btn btn-block btn-default btn-lg btn-kartpay" id="btn_submit" onclick="submitForm();">
                        Submit
                    </button>
                </form>
        </div>
    </div>
</div>
@endsection
 <!--Style and Color Format -->
@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style>
    .site--logo img
    {
        width: 250px;
        margin-bottom: 50px;
    },
    .{
        align: center;
    }
</style>
@endpush

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script>
    function submitForm()
    {
        $('#form_create_password').submit();
    }
</script>
@endpush
