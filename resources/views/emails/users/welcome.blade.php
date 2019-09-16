@extends('emails.layout')

@section('content')
	<h2 style="font-weight: 400; line-height: 25px; letter-spacing: 1px;">Welcome to Sourceeasy</h2>
	<br><div class="content" style="text-align: left; border-bottom-color: #eee; border-bottom-width: 1px; border-bottom-style: solid;" align="left">
	We have set up a Techpack.io account for you.
	
	<p>Wondering what is a techpack?</p>

	Techpack.io is a service of Sourcceeasy, Inc. We’ve created this open source project as a service to apparel tech designers, fashion designers and brands. Quickly and easily create a techpack. Share. Connect. Comment. Create.
	<br><br>

	You can login using this mail id <a href="http://techpack.io/app/#/auth/login">here and change the password.</a>
	Temporary password : {{ $data }}<br><br>
@stop