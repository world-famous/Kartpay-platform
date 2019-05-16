@extends('layouts.app_verification')

@section('content')
<div class="register-box">
    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
            <div class="site--logo">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive" alt="" style="height: 45px;width: 292px;margin-top: 45px;">
            </div>
			<p style="color: black;">Verification Code is incorrect. Please try again from start of registration step.</p>
        </div>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style>
    .site--logo img
    {
        width: 250px;
        margin-bottom: 50px;
    }
</style>
@endpush
@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script>

//Alphabet Only
function alphaOnly(event)
{
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8);
}
//END Alphabet Only

function disabledFirstChar(e)
{
    if (e.keyCode == 8 && $('#country_code').is(":focus") && $('#country_code').val().length < 2)
    {
      e.preventDefault();
    }
}

function submitForm()
{
    $('#form_register').submit();
}
</script>
@endpush
