@extends('emails.layout')

@section('content')
  Hi  {{$name}},
  <br><br>
  You are receiving this mail since you have requested for a sample export. Please find the attachment.

  <p>Please ignore if you didn't make the request.</p>
   <div  style="text-align: left;">
	  	<br><span style="text-align: left">Thanks!</span>
	   <br><span style="text-align: left">The Sourceeasy Team</span>
	  </div>
@stop
