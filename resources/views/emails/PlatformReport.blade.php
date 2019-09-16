<div>
    @extends('emails.forms.layout')
@section('content')
    <p style="color:red;">This is heavily experimental. Please do not share with anybody.</p><br>
      Your weekly report on platform is here.
        <br><br>
        <b style="color:#f1582b">{{ count($users) }}</b> @if(count($users) == 1)</b> user   @else users @endif
        logged in to platform last week.      
        <br>
        <h3 style="color:#21225f"> List of users  </h3>
        <ul>
            @foreach($users as $user)
            <li><span> <a  style="color: gray">{{ $user }} </a></span></li>
            @endforeach
        </ul>
        
        <br>
        <h3 style="color:#21225f"> Platform Numbers </h3>
            <table style="background-color: whitesmoke;padding: 6px;border: 1px solid gainsboro;width: 60%;position: relative;left: 50%;transform: translateX(-50%);-webkit-transform: translateX(-50%);margin-bottom: 10px;">
                <tr >
                    @foreach($appsData as $appName => $appData)
                        @if(strtolower($appName) !== 'vendor')
                        <th style=" width: 150px;background-color:#21225f; padding: 10px;text-align:center;">
                            <span style="line-height:1;display:block;font-size: 30px; font-weight:100;color:#f1582b">{{ $appData['total'] }}</span>
                            <small style="color:white;display:block;">{{ $appName }}</small>
                        </th>
                        @endif
                    @endforeach
                </tr>
            </table>
        <br>
        <h3 style="color:#21225f">Apps Overview</h3>
            @foreach($appsData as $appName => $appData)
                <span style="line-height: 1.5;"><b>{{ ucwords($appName) }}</b> : 
                     @if($appData['created'] === 0 && $appData['updated'] === 0)
                                <small style="color: #f1582b; font-weight:100;">No activity.</small>
                    </span><br>
                     @else
                    </span>
                    <div style="margin-left: 20px;">
                            <ul>
                                @if($appData['created'] !== 0)
                                    <li><b style="color:#f1582b">{{ $appData['created'] }}</b> created</li>
                                @endif
                                @if(count($appData['updated']) !== 0)
                                    <li><b style="color:#f1582b">{{ $appData['updated'] }}</b> updated</li>
                                @endif
                            </ul>
                    </div>
                     @endif
            @endforeach
            <br>
        <hr style="border: 1px solid lightgray">
        <h3  style="color:#21225f">User Overview</h3>
            @foreach($userLog as $email => $userData)


            <table style="background-color: whitesmoke;padding: 6px;border: 1px solid gainsboro;width: 60%;position: relative;left: 50%;transform: translateX(-50%);-webkit-transform: translateX(-50%);margin-bottom: 10px;">
            <tr >
                <th style="color:#21225f; border-bottom: 1px solid #21225f;" colspan="3">
                  <a style="color:#21225f;" href="mailTo:{{$email}}">{{ $email }}</a> 
                </th>
                <th style="display:block; background-color:#21225f; padding: 10px;text-align:center;">
                    <span style="font-size: 30px; font-weight:100;color:#f1582b">{{ $userData['requestCount'] }}</span>
                    <small style="color:white;">interactions</small>
                </th>
            </tr>
             @if (count($userData['apps']) == 0) 
                <tr>
                    <th>
                        <small style="color: #f1582b; font-weight:100;">No records of creating, updating or deleting.</small>
                    </th>    
                </tr>
                    @else
                <tr>
                    <th style="border-bottom: 1px solid lightgray;"><small style="color:gray;">Apps</small></th>
                    <th style="border-bottom: 1px solid lightgray;"><small style="color:gray;">Created</small></th>
                    <th style="border-bottom: 1px solid lightgray;"><small style="color:gray;">Updated</small></th>
                    <th style="border-bottom: 1px solid lightgray;"><small style="color:gray;">Deleted</small></th>
                </tr>
                <tbody>
                 @foreach($userData['apps'] as $appName => $appData)
                    <tr>
                        <td style="border-bottom: 1px solid lightgray;color:black">{{ $appName }}</td>
                        <td style="border-bottom: 1px solid lightgray;color:gray">{{ $appData['createdCount'] }}</td>
                        <td style="border-bottom: 1px solid lightgray;color:gray">{{ $appData['updatedCount'] }}</td>
                        <td style="border-bottom: 1px solid lightgray;color:gray">{{ $appData['deletedCount'] }}</td>
                    </tr>
                     @endforeach
                      </tbody>
                    @endif
                
            </table>

            @endforeach

            
        
  <div  style="text-align: left;">
          <br><span style="text-align: left">Thanks!</span><br>
       <span style="text-align: left">The Sourceeasy Team</span>
      </div>
@stop

</div>
