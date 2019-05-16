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
			<a href="{{ route('admins.gateway') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>
			
			<form role="form" action="{{ route('admins.store.gateway') }}" method="post">
			{!! csrf_field() !!}
				
				<div class="form-group{{ $errors->has('gateway_name') ? ' has-error' : '' }}">
					<label for="gateway_name">Name</label>
					<input type="required" name="gateway_name" value="" class="form-control" id="gateway_name" required>
					@if ($errors->has('gateway_name'))
						<span class="help-block">
							<strong>{{ $errors->first('gateway_name') }}</strong>
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
