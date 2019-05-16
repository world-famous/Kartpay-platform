@extends('merchant.backend.layouts.app_firm_doc')

@section('content')

<div class="">
	<div class="page-title">
		<div class="title_center" align="center">
			<h3>Step 4</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" align="center">
			Document Process is Successful. Approval from Compliance Team is Pending.
			<br>
			<span id="timer"></span>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
<?php if (isset($user))
	{?>
	var remainingTime = 1;
	var time = setInterval(function()
		{
			var timeLeft = 10 - remainingTime;
			if(timeLeft <= 0)
			{
				window.location = '{{ route('merchants.dashboard.merchant') }}';
			}
			else
			{
				$('#timer').html('You will redirect to dashboard in <b>' + timeLeft + '</b> seconds.');
			}

			remainingTime++;
		}, 1000);
<?php }?>
</script>
@endpush
