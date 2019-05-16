<!DOCTYPE html>
<html>
<head>
	<title>Test Form For Rest API</title>
</head>

	<h1>Test Form For RestApi</h1>
<body>
	<form method="post" action="{{route('testFormPost')}}">
		
		<table  width="">
			
			<tr>
				<td><input type="text" name="currency" placeholder="currency"></td>
			</tr>
			{{$errors->first('currency')}}

			<tr>
				<td><input type="text" name="merchant_order_amount" placeholder="merchant_order_amount"></td>
			</tr>
			
			<tr>
				<td><input type="text" name="merchant_order_id" placeholder="merchant_order_id"></td>
			</tr>
			
			<tr>
				<td><input type="text" name="Customer_email" placeholder="Customer_email"></td>
			</tr>
			
			<tr>
				<td><input type="text" name="customer_phone" placeholder="customer_phone"></td>
			</tr>
			
			<tr>
				<td><input type="submit" name="submit" value="submit"></td>
			</tr>
			{{csrf_field()}}
		</table>

	</form>

	<br>
	<br>

	<div class="well">
			
	</div>
</body>
</html>