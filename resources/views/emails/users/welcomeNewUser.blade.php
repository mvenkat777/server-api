@extends('emails.layout')

@section('content')
	<h2 style="font-weight: 400; line-height: 25px; letter-spacing: 1px;">Your Sourceeasy Password</h2>
	<br><div class="content" style="text-align: left; border-bottom-color: #eee; border-bottom-width: 1px; border-bottom-style: solid;" align="left">
	<br><br>Hi.

	
	<br><br>

	Thanks for setting up your account on the Sourceeasy platform! You can now log in <a href="{{ $data['url'] }}">here</a>,  using this email address and your temporary password
	<br><br>Temporary password : {{ $data['password'] }}
	<br><br>Please remember to update your password after you log in!
	 <br><br><br>
	If you did not register for a Sourceeasy platform account, or if you have any questions or issues, please contact us at support@sourceeasy.com
	 <div  style="text-align: left;">
	  	<br><span style="text-align: left">Thanks!</span>
	   <br><span style="text-align: left">The Sourceeasy Team</span>
	  </div>
	  </div>
@stop