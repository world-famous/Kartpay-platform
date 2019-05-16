<!DOCTYPE html>
<html>
<head>
	<title>Test Payment Iframe</title>

	<script type="text/javascript">
	  window.onload = function() {

	  };

	function showPayment(url, mode) {
		var trans_id = document.getElementById('trans_id');
		var iframe = document.getElementById('myiframe');
		iframe.src = url + trans_id.value;
		document.getElementById('mode').innerHTML = mode;
	}
</script>

</head>
	<h1>Test Payment Iframe</h1>
<body>
	<input type="text" id="trans_id" placeholder="input transaction id" />

	<button id="btn_show_payment" onclick="showPayment('https://test.kartpay.com/api/v1/payments/', 'Test');">Show Test</button>
	<button id="btn_show_payment" onclick="showPayment('https://live.kartpay.com/api/v1/payments/', 'Live');">Show Live</button>

	<br>
	Mode: <b><span id="mode"></span></b>
	<br>

	<iframe id="myiframe" src="" width="100%" height="400px" style="background-color: transparent; border: 0px none transparent; padding: 0px; overflow: hidden;"></iframe>
	<div class="well">

	</div>
</body>
</html>
