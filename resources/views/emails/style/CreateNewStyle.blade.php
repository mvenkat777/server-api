<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="background-color: rgb(234, 236, 237); font-family: Roboto, arial; font-weight: 100; line-height: 25px;
 letter-spacing: 1px; font-size: 16px; -ms-word-break: break-all; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto;" bgcolor="rgb(234, 236, 237)">
    <div style="margin: 0 auto; background-color: #fff; max-width: 600px; border-radius: 4px;">
        <div style="padding: 10px 20px 10px 20px;text-align:center;">
        <a href="www.sourceeasy.com"><img style="height: 22px; width: 161px;" src="https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/selogo.png"/></a>
        
                        <br><hr style="border: 1px solid lightgray;">
        </div>
        <div class="email-body" style="padding: 10px 20px 10px 20px;">
            <h4 style="color: #21225f; font-weight:500;">Hey {{$data->displayName}},</h4>
            <h4  style="color: #21225f;font-weight:500;">The style <b style="color: #f1582b;">{{$data->name}}</b> has been added to the line <b style="color: #f1582b;">{{$data->line->name}}</b> by <b style="color: #f1582b;">{{$data->actorName}} ({{$data->actorEmail}})</b></h4> 

            <span style="color: #21225f;display:block;font-weight:notmal;"> Style Code : {{$data->customer_style_code}}</span>
            <span style="color: #21225f;display:block;font-weight:notmal;">Customer: {{$data->line->customer_id['name']}} </span>
            <span style="color: #21225f;display:block;font-weight:notmal;">Line: {{$data->line->name}} </span>
            <span style="color: #21225f;display:block;font-weight:notmal;">PO Target Date : {{date('m-d-Y',strtotime($data->line->so_target_date))}}</span>
            <span style="color: #21225f;display:block;font-weight:notmal;">Delivery Date : {{date('m-d-Y',strtotime($data->line->delivery_target_date))}} </span>
        
        </div>
        <br><br>

        <div style="background-color: #21225f;min-height: 200px;width: 100%;text-align:center;">
            <br>
            <div style="text-align:center;padding-left: 10%; padding-right:10%;"><h3 style=" color: white;">Please log on the platform </h3></div>
            <br>
            <a href='{{$data->link}}' style="cursor:pointer;"><button style="background-color: #f1582b;color:white;width: 30%;position: relative;left: 50%;transform: translateX(-50%);-webkit-transform: translateX(-50%);height: 40px;border-radius: 10px;border: transparent; cursor: pointer;" type="button">View the line</button></a>
        </div>
        <div  style="padding: 10px 20px 10px 20px;text-align: center;">
            <h3 style="color: #21225f">Thank you, have a great day!</h3>   
            <hr style="border: 1px solid lightgray;"> 
            <span style="color:gray">Sent by <a href="https://www.sourceeasy.com" style="text-decoration: none; color: #21225f;">Sourceasy Inc</a><br><small>395 S Van Ness Ave, San Francisco, CA 94103</small></span>
        </div>
      <br>
    </div>
</body>
</html>


