<!DOCTYPE html>
<html>
<head>
	<title>Home Page</title>
</head>
<body>
Welcome <span style=""><a href="logout">Logout</a></span>
<br>
<h3>Edit Profile</h3>
<form method="post" action="user/{{1}}">
	<input name="_token" type="hidden" value="{{ csrf_token() }}">
	<input type="hidden" name="_method" value="put" />
	First Name: <input type="text" name="firstName"><br><br>
	Last Name: <input type="text" name="lastName"><br><br>
	Country: <input type="text" name="country"><br><br>
	State: <input type="text" name="state"><br><br>
	City: <input type="text" name="city"><br><br>
	Mobile No.: <input type="text" name="mobileNumber"><br><br>
	<input type="submit" value="Update">
</form>
</body>
</html>