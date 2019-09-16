<!DOCTYPE html>
<html>
<head>
	<title>Auth Test</title>
</head>
<body>

	<div style="float:left;margin-left:200px;">
		<h2>Sign Up Form</h2>
		<form name="signUpForm" method="post" action="{{URL::to('users')}}">
			<input name="_token" type="hidden" value="{{ csrf_token() }}">
			Display Name: <input type="text" name="displayName"><br><br>
			EMail: <input type="text" name="email"><br><br>
			Password: <input type="password" name="password"><br><br>
			Re-Password: <input type="password" name="repassword"><br><br>
			<input type="submit" value="Sign Up"><br><br>
		</form>
	</div>

	<div style="float:right;margin-right:200px;">
		<h3>Login Form</h3>
		<form method="post" action="auth/legacy">
			
			Email: <input type="text" name="email"><br><br>
			Password: <input type="password" name="password"><br><br>
			<input type="submit" value="Login">
		</form>
	</div>

</body>
</html>