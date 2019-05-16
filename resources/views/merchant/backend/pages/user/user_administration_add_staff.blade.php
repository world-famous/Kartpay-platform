@extends('merchant.backend.layouts.app')

@section('content')

<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>User Administration</h3>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			Add New Staff
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
	<br><br>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<a href="{{ url('/user_administration') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>
			
			<form role="form" action="{{ route('merchants.store_new_staff') }}" method="post">
			{!! csrf_field() !!}
				
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<label for="email">Email</label>
					<input type="required" name="email" value="" class="form-control" id="email" required>
					@if ($errors->has('email'))
						<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
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

$(function(){
	
});
</script>
@endpush
