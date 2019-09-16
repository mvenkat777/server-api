<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Task Status</title>
</head>
<body style="background-color: rgb(234, 236, 237); font-family: sans-serif; font-weight: 100; line-height: 25px; letter-spacing: 1px; font-size: 14px; -ms-word-break: break-all; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; padding: 40px;" bgcolor="rgb(234, 236, 237)">

	<div style="background-color: #fff;border-radius: 4px;padding: 20px;">
		<strong>Task Id: {{$data->id}}</strong><br><br>
		Hello {{$data->assignee->display_name}},<br>
		{{$data->creator->display_name}}, has {{$data->status->status}} the task which was created by you .<br><br>
		Task Subject : {{$data->title}} <br>
		Due Date : {{$data->due_date}} <br>

		Check Link to view the task status <button style="background:#0090c6;color:#ffffff;font-size: 16px;padding:6px 15px 6px 15px;text-decoration:none;height:30px;margin-left:40%;"><a style="text-decoration: none; color:#fff;" href=" {{$data->taskLink}} ">Go To Task</a>
        </button>
        <br>
		You are expected to take action on in as minimum time as possible(If Required) . <br><br>
		Thank you,<br/>
		By: {{$data->creator->display_name}} <br/>
		Sourceeasy.Inc<br>
	</div>

	<div style="padding:30px">
		<img style="float: right; width: 35%" alt="Sourceeasy" class="visible-xs" src="https://sourceeasycdn.s3.amazonaws.com/www/img/logo-blue-trans.png"/>
	</div>
</body>
</html>


