<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Platform Report</title>
</head>
<body style="background-color: #ffffff; font-family: sans-serif; font-weight: 100; line-height: 25px; letter-spacing: 1px; font-size: 14px; -ms-word-break: break-all; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; padding: 40px;" bgcolor="rgb(234, 236, 237)">
    
    <div>
        <b>Total number of users : {{ count($users) }}</b>
        <br>
        <h4>Users email : </h4>
        @foreach($users as $user)
            <span>{{ $user }} &nbsp;&nbsp;</span>
        @endforeach
        <hr>
        <h3>Apps Report</h3>
        <ul>
            @foreach($data as $appName => $appData)
                <li>
                    <h4>{{ucwords($appName)}} : </h4>
                    <p>Created : {{ count($appData['created']) }}</p>
                    <p>Updated : {{ count($appData['updated']) }}</p>
                    <p>Total : {{ $appData['total'] }}</p>
                </li>
            @endforeach
        </ul>
    </div>

	<div style="padding:30px">
		<img style="float: right; width: 35%" alt="Sourceeasy" class="visible-xs" src="https://sourceeasycdn.s3.amazonaws.com/www/img/logo-blue-trans.png"/>
	</div>
</body>
</html>
