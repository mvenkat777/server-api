<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Master Page</title>
</head>
<body>
<div style="font:14px/1.4285714 Arial,sans-serif;color:#333;max-width:920px;">
        <div style="background-color: #fff; border-radius: 4px; padding: 20px;"><strong>Reference Id: {{$data->id}}</strong><br /><br /> Hi,<br /> The payment request mail has been sent successfully to the customer with below details. <br /><br /> Customer Name : {{$data->name}} <br /> Order Date : {{$data->created_at}} <br /> Product : {{$data->product_name}} <br /> Total amount : ${{$data->amount}} <br /><br /> The customer is expected to pay the amount . <br /><br /> Thank you,<br /> Sourceeasy.Inc</div></div>
</body>
</html>