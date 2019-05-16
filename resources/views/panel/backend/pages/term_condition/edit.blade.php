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
			
			<form role="form" action="{{ route('admins.update.term') }}" method="post">
			{!! csrf_field() !!}
				
				<div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
					<label for="message">Terms and Conditions Message</label>
					<input type="required" name="message" class="form-control" style="border: 1px solid #ff5422;border-radius: 10px;background: white;"  id="message" value="@php if(isset($termCondition)) echo $termCondition->message; @endphp" required>
					@if ($errors->has('message'))
						<span class="help-block">
							<strong>{{ $errors->first('message') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
					<label for="content">Content</label>
					@if ($errors->has('content'))
						<span class="help-block">
							<strong>{{ $errors->first('content') }}</strong>
						</span>
					@endif
					
					<textarea id="content" name="content"></textarea>
				</div>
				
				 <div class="box-footer">
					<button type="submit" class="btn btn-success" id="btn_submit" style="border-radius: 10px"><i class="fa fa-floppy-o"></i> Submit</button>
				</div>
			</form>
		 </div>
	</div>				
</div>

@endsection

@push('scripts')
<script>

$('#btn_submit').click(function(event){
	$("#content").html($(".Editor-editor").html());
});

$(function(){
	$("#content").Editor();
	$(".Editor-editor").html('@php if(isset($termCondition)) echo $termCondition->content; @endphp');
});
</script>
@endpush
