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
        <table style="width: 100%; border-collapse: collapse;">
<tbody>
<tr>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 0; background-color: #ffffff; border-radius: 5px;">
<div style="border: 1px solid #cccccc; border-radius: 5px; padding: 20px;">
<table style="width: 100%; border-collapse: collapse;">
<tbody>
<tr>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 0;">
<table style="width: 100%; border-collapse: collapse;">
<tbody>
<tr>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 0 0 0 10px;">
<table style="width: 100%; border-collapse: collapse;">
<tbody>
<tr>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 0; line-height: 1;">
<h3>Your Sourceeasy Invoice {!!$data->product_name!!} of ${!!$data->amount!!}</h3>
</td>
</tr>
<tr>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 5px 0 0; line-height: 1.2;">
<p style="font-size: 16px;">Dear {{$data->name}},<br /><br /> Thank you for ordering {{$data->product_name}} at Sourceeasy.Your order details are below</p>
</td>
</tr>
<tr>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 10px 0 20px;">
<table style="width: 100%; border-collapse: collapse;">
<tbody>
<tr>
<th style="border-bottom: 1px solid #ccc; text-align: left; font-weight: bold; padding: 5px; width: 25%;">Order Id</th>
<th style="border-bottom: 1px solid #ccc; text-align: left; font-weight: bold; padding: 5px; width: 25%;">Product Name</th>
<th style="border-bottom: 1px solid #ccc; text-align: left; font-weight: bold; padding: 5px; width: 25%;">Order Date</th>
<th style="border-bottom: 1px solid #ccc; text-align: left; font-weight: bold; padding: 5px; width: 25%;">Amount(in $)</th>
</tr>
<tr>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 5px; border-bottom: 1px solid #ccc; line-height: 24px; color: #707070; width: 132px;"><span style="padding: 0 0 0 5px;">{{$data->id}}</span></td>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 5px; border-bottom: 1px solid #ccc; line-height: 24px; color: #707070; width: 50px; font-family: Monaco,monospace; font-size: 12px;">{{$data->product_name}}</td>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 5px; border-bottom: 1px solid #ccc; line-height: 24px; color: #707070;">{{$data->created_at}}</td>
<td style="font: 14px/1.4285714 Arial,sans-serif; padding: 5px; border-bottom: 1px solid #ccc; line-height: 24px; color: #707070; width: 100px;">
<div>{{$data->amount}}$</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td>
<p style="font-size: 16px;">You are requested to pay the amount to process your product.</p>
</td>
</tr>
<tr>
<td><button style="background: #0090c6; color: #ffffff; font-size: 16px; padding: 6px 15px 6px 15px; text-decoration: none; height: 30px; margin-left: 40%;"> <a style="text-decoration: none; color: #fff;" href="{{$data->product_link}}">Make Payment</a> </button></td>
</tr>
<tr>
<td>
<div style="margin-top: 5px;"><span style="font-size: 15px;">We thank you for being with us and keep visiting us.</span><br /><br /> <span style="font-weight: bold;">With Regards,</span><br /> <span style="font-size: 15px;">{{$data->sender_name}}({{$data->sender_email}})</span> <br /> <span style="font-size: 15px;">Team </span><br /> <span style="font-size: 15px;">Sourceeasy.Inc</span></div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table></div>
</body>
</html>