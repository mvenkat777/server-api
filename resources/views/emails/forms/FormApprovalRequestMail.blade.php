@extends('emails.forms.layout')
@section('content')
 Hi ,
       <br><br>
 {{$data->data['actor']->display_name}} has requested for approval.
 Please click the link to get redirected <a href="{{$data->data['link']}}"> {{$data->data['formType']}} - {{$data->data['formTitle']}} </a><br><br>
  <div  style="text-align: left;">
          <br><span style="text-align: left">Thanks!</span>
       <br><span style="text-align: left">The Sourceeasy Team</span>
      </div>
@stop