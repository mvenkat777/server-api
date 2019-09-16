<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Notification Mail</title>
</head>
<body style="background-color: rgb(234, 236, 237); font-family: sans-serif; font-weight: 100; line-height: 25px; letter-spacing: 1px; font-size: 14px; -ms-word-break: break-all; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; padding: 40px;" bgcolor="rgb(234, 236, 237)">

	<div style="background-color: #fff;border-radius: 4px;padding: 20px;">
		Hello {{ $data['displayName'] }},<br>
		Below are your activities:<br>
		<ul>
		@foreach ($data as $key => $value)
			@if ($key == 'digest')
				@foreach ($value as $key1 => $value1)
				 	<li style="font-size:10px;">Total {{ $key1. ': ' . $value1 }}</li>
				@endforeach
			@endif
		@endforeach
		</ul>
		Thank you,<br/>
		Sourceeasy.Inc<br>
	</div>

	<div style="padding:30px">
		<img style="float: right; width: 35%" alt="Sourceeasy" class="visible-xs" src="https://sourceeasycdn.s3.amazonaws.com/www/img/logo-blue-trans.png"/>
	</div>
</body>
</html>


