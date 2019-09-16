@extends('emails.forms.layout')
@section('content')
 Hi,
       <br><br>
 {{$data->data['actor']->display_name}} has rejected the form request.
 Please click below link to get redirected <br> <a href="{{$data->data['link']}}"> {{$data->data['formType']}} - {{$data->data['formTitle']}} </a><br><br>
  <div  style="text-align: left;">
          <br><span style="text-align: left">Thanks!</span>
       <br><span style="text-align: left">The Sourceeasy Team</span>
      </div>
@stop