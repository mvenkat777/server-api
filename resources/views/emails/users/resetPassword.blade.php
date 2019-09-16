@extends('emails.layout')
@section('content')
	<h2 style="font-weight: 400; line-height: 25px; letter-spacing: 1px;">Sourceeasy Password Reset</h2>
	<br><div class="content" style="text-align: left; border-bottom-color: #eee; border-bottom-width: 1px; border-bottom-style: solid;" align="left">
	    Hi,

		<br><br>Your Sourceeasy platform password reset request has been received.

		
		<br><br>Click the link below to reset your Password: 

		<br><br><br>
		<a href="{{$data['appName']}}{{$data['email']}}&token={{$data['token']}}">Reset My Password Now</a>
		
	     <br><br><br>
	   If you have received this message in error, or did not request a new password, please reply  
	   to this mail or contact us at support@sourceeasy.com.
       <div  style="text-align: left;">
	  	<br><span style="text-align: left">Thanks!</span>
	   <br><span style="text-align: left">The Sourceeasy Team</span>
	  </div>

	</div>
@stop