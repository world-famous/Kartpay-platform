@extends('panel.backend.layouts.app')

@section('content')

<div class="">
	@if (session('message'))
	<div class="row">
		<div class="alert alert-success alert-dismissable">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
				{!! session('message') !!}
		</div>
	</div>
	@endif
	<br><br>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<a href="{{ route('admins.bank') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>

			<form role="form" action="{{ route('admins.update.bank', ['id' => $bank->id]) }}" method="post">
			{!! csrf_field() !!}

				<div class="form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
					<label for="bank_name"> Bank Name</label>
					<input type="required" name="bank_name" class="form-control" id="bank_name" value="{{ $bank->bank_name }}" size="20" required>
					@if ($errors->has('bank_name'))
						<span class="help-block">
							<strong>{{ $errors->first('bank_name') }}</strong>
						</span>
					@endif
				</div>

				<div class="form-group{{ $errors->has('bank_code') ? ' has-error' : '' }}">
					<label for="bank_code">Code</label>
					<input type="required" name="bank_code" class="form-control" id="bank_code"  value="{{ $bank->bank_code }}" size="20">
					@if ($errors->has('bank_code'))
						<span class="help-block">
							<strong>{{ $errors->first('bank_code') }}</strong>
						</span>
					@endif
				</div>

				<div class="form-group{{ $errors->has('bank_status') ? ' has-error' : '' }}">
					<label for="bank_status">Status</label>
					<select name="bank_status" id="bank_status" class="form-control" required>
						<option value=''>--Select Status--</option>
						<option value='Active' @php if($bank->bank_status == 'Active') echo 'selected'; @endphp>Active</option>
						<option value='Disabled' @php if($bank->bank_status == 'Disabled') echo 'selected'; @endphp>Disabled</option>
					</select>
					@if ($errors->has('bank_status'))
						<span class="help-block">
							<strong>{{ $errors->first('bank_status') }}</strong>
						</span>
					@endif
				</div>

				 <div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Submit</button>
				</div>
			</form>
		 </div>
	</div>
</div>

@endsection

@push('scripts')
<script>

$(function()
{
	$('#ul_application_settings').toggle();
	$('#li_application_settings').addClass('active');
});
</script>
@endpush
