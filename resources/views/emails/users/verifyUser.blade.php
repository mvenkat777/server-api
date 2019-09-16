@extends('emails.layout')

@section('content')
	<h2 style="font-weight: 400; line-height: 25px; letter-spacing: 1px;">Welcome to Sourceeasy</h2>
	<br><div class="content" style="text-align: left; border-bottom-color: #eee; border-bottom-width: 1px; border-bottom-style: solid;" align="left">
	Hi.
	<br><br>
	 Thank you for registering an account on the Sourceeasy platform!
	 <br><br>
	 With Sourceeasy, you can collaborate on your design concepts, approve materials and styles with a single
     click, and track the progress of your project calendar, all from a single unified dashboard. 
	 <br><br>
	 <b> Simply click here to activate your account:</b>

	<br><br><br><br><div class="text-center" style="text-align: center;" align="center">
	  <a class="button" href="{{$data['appName']}}{{ $data['confirmationCode'] }}" style="text-decoration: none; color: #ffffff; -webkit-border-radius: 20px; border-radius: 20px; letter-spacing: 0.125em; text-transform: uppercase; font-size: 13px; font-family: 'Open Sans', Arial, sans-serif; font-weight: 400; display: inline-block; line-height: 10px !important; -webkit-text-size-adjust: none; mso-hide: all; cursor: pointer; background-color: #f1582b; padding: 10px 15px;">
	  	Verify your email
	  </a>
	  <br><br><br>
	If you did not register for a Sourceeasy platform account, or if you have any questions or issues, please contact us at support@sourceeasy.com
	  <br><br><br>
	  Or use this link:
	  <br>
	  {{$data['appName']}}{{ $data['confirmationCode'] }}
	  <div  style="text-align: left;">
	  	<br><span style="text-align: left">Thanks!</span>
	   <br><span style="text-align: left">The Sourceeasy Team</span>
	  </div>
	  
	</div>
@stop
