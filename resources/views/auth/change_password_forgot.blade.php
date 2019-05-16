@extends('layouts.app_verification')

@section('content')
<div class="container">
   <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
        <div class="col-lg-10 col-lg-offset-1 col-md-12" style="color: black;">
            <div class="site--logo">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive" alt="" style="margin-top: 45px;height: 45px; width: 292px;">
            </div>

			<p><h2 class="text-center"  style="color: black;">Change Password</h2></p>
      @if(session('verification_code') == '')
        <p style="text-align:center;">Session Invalid</p>
      @else
      <p>The password formats are:</p>
      <p>- Minimum has 1 uppercase. <font id="font_new_password_uppercase" color="red"><i id="i_new_password_uppercase" class=""></i></font></p>
      <p>- Minimum has 1 lowercase. <font id="font_new_password_lowercase" color="red"><i id="i_new_password_lowercase" class=""></i></font></p>
      <p>- Minimum has 1 symbol. <font id="font_new_password_symbol" color="red"><i id="i_new_password_symbol" class=""></i></font></p>
      <p>- Minimum has 1 number. <font id="font_new_password_number" color="red"><i id="i_new_password_number" class=""></i></font></p>
      <p>- Minimum length is 8 characters. <font id="font_new_password_min_char" color="red"><i id="i_new_password_min_char" class=""></i></font></p>
      <p>- Maximum length is 16 characters.</p>
      <p>- New password and confirm password must be same. <font id="font_confirm_password" color="red"><i id="i_confirm_password" class=""></i></font></p>
			</p>
			<form class="form-horizontal" id="form_change_password" role="form" method="POST" action="{{ url('/verification/forgot_password/change_password_forgot') }}">
				{{ csrf_field() }}

				<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">

						<input id="new_password" type="password" class="form-control form-control-kartpay input-lg" name="new_password" maxlength="16" placeholder="Enter new password" value="" required autofocus style=" box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);color: beige;color: black;font-size: 100%;height: 50%;border-radius: 10px;">

						@if ($errors->has('new_password'))
							<span class="help-block">
								<strong>{{ $errors->first('new_password') }}</strong>
							</span>
						@endif
				</div>

				<div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">

						<input id="confirm_password" type="password" class="form-control form-control-kartpay input-lg" name="confirm_password" maxlength="16" placeholder="Enter confirm password" value="" required autofocus style="font-size: 100%;height: 50%;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);color: black;border-radius: 10px;">

						@if ($errors->has('confirm_password'))
							<span class="help-block">
								<strong>{{ $errors->first('confirm_password') }}</strong>
							</span>
						@endif
				</div>

				<input id="verification_code" type="hidden" class="form-control form-control-kartpay input-lg" name="verification_code" value="{{ session('verification_code') }}" />

				<br><br>
				<button type="button" class="btn btn-block btn-default btn-lg btn-kartpay" id="btn_submit"  style="height: 50%;border-radius: 20px;background: orangered;font-size: 100%;width: 62%;margin-left: 15%;margin-top: -15%;" onclick="submitForm();">Submit</button>
			</form>
      @endif
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
    },
	.{
		align: center;
	}
</style>
@endpush

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script>
function ShowStatusInput(font_element, icon_element, valid)
{
	if(valid)
	{
		$('#' + font_element).attr('color', 'green');
		$('#' + icon_element).attr('class', 'glyphicon glyphicon-ok');
	}
	else
	{
		$('#' + font_element).attr('color', 'red');
		$('#' + icon_element).attr('class', 'glyphicon glyphicon-remove');
	}
}

function HideStatusInput(icon_element)
{
	$('#' + icon_element).attr('class', '');
}

function ValidPassword(str)
{
  var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!?])(?=.*[0-9]).*$/;
  if(!regex.test(str) && str.length < 8)
	{
    return false;
  }
	else if (regex.test(str) && str.length >= 8)
	{
    return true;
  }
}

function ValidUppercase(str)
{
  var regex = /^(?=.*[A-Z]).*$/;
  if(!regex.test(str))
	{
    return false;
  }
	else if (regex.test(str))
	{
    return true;
  }
}

function ValidLowercase(str)
{
  var regex = /^(?=.*[a-z]).*$/;
  if(!regex.test(str))
	{
    return false;
  }
	else if (regex.test(str))
	{
    return true;
  }
}

function ValidSymbol(str)
{
  var regex = /^(?=.*[@#$%^&+=!?]).*$/;
  if(!regex.test(str))
	{
    return false;
  }
	else if (regex.test(str))
	{
    return true;
  }
}

function ValidNumber(str)
{
  var regex = /^(?=.*[0-9]).*$/;
  if(!regex.test(str))
	{
    return false;
  }
	else if (regex.test(str))
	{
    return true;
  }
}

function ValidMinChar(str)
{
	if(str.length < 8)
	{
    return false;
  }
	else if (str.length >= 8)
	{
    return true;
  }
}

function ValidConfirmPassword(new_password, confirm_password)
{
	if(new_password != confirm_password)
	{
    return false;
  }
	else if (new_password == confirm_password)
	{
    return true;
  }
}


$(function(){
	$("#new_password").keyup(function(){
			if($("#new_password").val() == '')
			{
				HideStatusInput('i_new_password_uppercase');
				HideStatusInput('i_new_password_lowercase');
				HideStatusInput('i_new_password_symbol');
				HideStatusInput('i_new_password_number');
				HideStatusInput('i_new_password_min_char');
				HideStatusInput('i_confirm_password');
				return;
			}

			//Valid Uppercase
			if(ValidUppercase($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_uppercase', 'i_new_password_uppercase', true);
			}
			else if (!ValidUppercase($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_uppercase', 'i_new_password_uppercase', false);
			}
			//END Valid Uppercase

			//Valid Lowercase
			if(ValidLowercase($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_lowercase', 'i_new_password_lowercase', true);
			}
			else if (!ValidLowercase($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_lowercase', 'i_new_password_lowercase', false);
			}
			//END Valid Lowercase

			//Valid Symbol
			if(ValidSymbol($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_symbol', 'i_new_password_symbol', true);
			}
			else if (!ValidSymbol($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_symbol', 'i_new_password_symbol', false);
			}
			//END Valid Symbol

			//Valid Number
			if(ValidNumber($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_number', 'i_new_password_number', true);
			}
			else if (!ValidNumber($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_number', 'i_new_password_number', false);
			}
			//END Valid Number

			//Valid Min Char
			if(ValidMinChar($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_min_char', 'i_new_password_min_char', true);
			}
			else if (!ValidMinChar($("#new_password").val()))
			{
				ShowStatusInput('font_new_password_min_char', 'i_new_password_min_char', false);
			}
			//END Valid Number

			//Valid Confirm Password
			if(ValidConfirmPassword($("#new_password").val(), $("#confirm_password").val()))
			{
				ShowStatusInput('font_confirm_password', 'i_confirm_password', true);
			}
			else if (!ValidConfirmPassword($("#new_password").val()))
			{
				ShowStatusInput('font_confirm_password', 'i_confirm_password', false);
			}
			//END Valid Number
	});

	$("#confirm_password").keyup(function(){
		//Valid Confirm Password
		if(ValidConfirmPassword($("#new_password").val(), $("#confirm_password").val()))
		{
			ShowStatusInput('font_confirm_password', 'i_confirm_password', true);
		}
		else if (!ValidConfirmPassword($("#new_password").val()))
		{
			ShowStatusInput('font_confirm_password', 'i_confirm_password', false);
		}
		//END Valid Number
	});
});

function submitForm()
{
	$('#form_change_password').submit();
}
</script>
@endpush
