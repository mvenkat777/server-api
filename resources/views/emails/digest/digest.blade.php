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
                        <br>
        </div>
         <div style="background-color: #21225f;padding: 10px;text-align:center;">
                            <h1 style="font-family:serif!important;color:white;font-weight: 500;">Here's your daily digest!</h1>
                        </div>
        <div class="email-body" style="padding: 10px 20px 10px 60px;">
        
            <h4 style="color: gray; font-weight:500;">Hey {{$data['user']->display_name}},</h4>
            <?php if(count($data['tasks'])){ ?>
            <h4  style="color: #21225f;font-weight:500;"><b style="color: #f1582b;">You have <?php echo count($data['tasks']); ?> task to-do for today: </b></h4>
            <?php } ?>

                <ul style="color:gray;">
                @foreach($data['tasks'] as $task)
                    <li><span style="display:block;font-weight:notmal;">Task Title : {{$task['title']}} </span></li>
                    @endforeach 
                </ul>
            

            </div>
            <xhr>
            <div class="email-body" style="padding: 10px 20px 10px 60px;">
            <?php if(count($data['taskSubmitted']) || count($data['taskCompleted']) || count($data['lineCreated']) || count($data['lineArchived']) || count($data['styleCreated']) || count($data['styleArchived']) || count($data['sampleCreated'])|| count($data['calendarCreated'])) { ?>
                <h4  style="color: #21225f;font-weight:500;"><b style="color: #f1582b;">Here's what happened yesterday : </b></h4>
            <?php } ?>
            <?php if(count($data['taskSubmitted']) || count($data['taskCompleted'])) { ?>
                <h4  style="color: #21225f;font-weight:300;"><b style="color: #f1582b;">Task : </b></h4>
                <?php if(count($data['taskSubmitted'])) { ?>
                    <h4  style="color: #21225f;font-weight:500;"> Task Submitted </h4>
                    <ul style="color:gray;">
                         @foreach($data['taskSubmitted'] as $task)
                            <li><span style="display:block;font-weight:notmal;">Task Title : {{$task['title']}} </span></li>
                        @endforeach
                    </ul>
               <?php } ?>

               <?php if(count($data['taskCompleted'])) { ?>
                    <h4  style="color: #21225f;font-weight:500;"> Task Completed </h4>
                    <ul style="color:gray;">
                        @foreach($data['taskCompleted'] as $task)
                            <li><span style="display:block;font-weight:notmal;">Task Title : {{$task['title']}} </span></li>
                        @endforeach
                        </ul>
                <?php } ?>
            <?php } ?>

            <?php if(count($data['lineCreated']) || count($data['lineArchived'])) { ?>
            <h4  style="color: #21225f;font-weight:300;"><b style="color: #f1582b;">Line : </b></h4>
                <?php if(count($data['lineCreated'])){ ?>
                    <h4  style="color: #21225f;font-weight:500;"> Line Created </h4>
                    <ul style="color:gray;">
                        @foreach($data['lineCreated'] as $line)
                            <li><span style="display:block;font-weight:notmal;">Line Name : {{$line['name']}} </span></li>
                        @endforeach
                    </ul>
                <?php } ?>
                <?php if(count($data['lineArchived'])){ ?>
                    <h4  style="color: #21225f;font-weight:500;"> Line Archived </h4>
                    <ul style="color:gray;">
                        @foreach($data['lineArchived'] as $line)
                            <li><span style="display:block;font-weight:notmal;">Line Name : {{$line['name']}} </span></li>
                        @endforeach
                    </ul>
                <?php } ?>
            <?php } ?>

            <?php if(count($data['styleCreated']) || count($data['styleArchived'])) { ?>
                <h4  style="color: #21225f;font-weight:300;"><b style="color: #f1582b;">Style : </b></h4>
                <?php if(count($data['styleCreated'])){ ?>
                    <h4  style="color: #21225f;font-weight:500;"> Style Created </h4>
                    <ul style="color:gray;">
                        @foreach($data['styleCreated'] as $style)
                            <li><span style="display:block;font-weight:notmal;">Style Name : {{$style['styleName']}} </span></li>
                        @endforeach
                    </ul>
                <?php } ?>
                <?php if(count($data['styleArchived'])){ ?>
                    <h4  style="color: #21225f;font-weight:500;"> Style Archived </h4>
                    <ul style="color:gray;">
                        @foreach($data['styleArchived'] as $style)
                            <li><span style="display:block;font-weight:notmal;">Style Name : {{$style['styleName']}} </span></li>
                        @endforeach
                    </ul>
                <?php } ?>
            <?php } ?>

            <?php if(isset($data['styleApproval']['styleProduction']) || isset($data['styleApproval']['styleDevelopment']) || isset($data['styleApproval']['styleReview']) || isset($data['styleApproval']['styleShipped'])) { ?>
                <h4  style="color: #21225f;font-weight:300;"><b style="color: #f1582b;">Style Approvals: </b></h4>
                <?php if(isset($data['styleApproval']['styleProduction'])){ ?>
                    <h4  style="color: #21225f;font-weight:500;"> Style Production Approved </h4>
                    <ul style="color:gray;">
                        @foreach($data['styleApproval']['styleProduction'] as $prod)
                            <li><span style="display:block;font-weight:notmal;">Name: {{$prod['styleProduction']['name']}} </span></li>
                        @endforeach
                    </ul>
                <?php } ?>
                <?php if(isset($data['styleApproval']['styleDevelopment'])){ ?>
                    <h4  style="color: #21225f;font-weight:500;"> Style Development Approved </h4>
                    <ul style="color:gray;">
                        @foreach($data['styleApproval']['styleDevelopment'] as $dev)
                            <li><span style="display:block;font-weight:notmal;">Name: {{$dev['styleDevelopment']['name']}} </span></li>
                        @endforeach
                    </ul>
                <?php } ?>
                <?php if(isset($data['styleApproval']['styleReview'])){ ?>
                    <h4  style="color: #21225f;font-weight:500;"> Style Review Approved </h4>
                    <ul style="color:gray;">
                        @foreach($data['styleApproval']['styleReview'] as $rev)
                            <li><span style="display:block;font-weight:notmal;">Name: {{$rev['styleReview']['name']}} </span></li>
                        @endforeach
                    </ul>
                <?php } ?>
                <?php if(isset($data['styleApproval']['styleShipped'])){ ?>
                    <h4  style="color: #21225f;font-weight:500;"> Style Shipped Approved </h4>
                    <ul style="color:gray;">
                        @foreach($data['styleApproval']['styleShipped'] as $ship)
                            <li><span style="display:block;font-weight:notmal;">Name: {{$ship['styleShipped']['name']}} </span></li>
                        @endforeach
                    </ul>
                <?php } ?>
            <?php } ?>

            <?php if(count($data['sampleCreated'])){ ?>
                <h4  style="color: #21225f;font-weight:300;"><b style="color: #f1582b;"> Sample Created </b></h4>
                <ul style="color:gray;">
                    @foreach($data['sampleCreated'] as $sample)
                        <li><span style="display:block;font-weight:notmal;">Title: {{$sample['title']}} </span></li>
                    @endforeach
                </ul>
            <?php } ?>

            <?php if(count($data['calendarCreated'])){ ?>
                <h4  style="color: #21225f;font-weight:300;"><b style="color: #f1582b;"> Calendar Created </b></h4>
                <ul style="color:gray;">
                    @foreach($data['calendarCreated'] as $calendar)
                        <li><span style="display:block;font-weight:notmal;">Title: {{$calendar['title']}} </span></li>
                    @endforeach
                </ul>
            <?php } ?>
        </div>
        <br><br>
        <div style="min-height: 200px;width: 100%;text-align:center;">
            <br>
            <div style="text-align:center;padding-left: 10%; padding-right:10%;"><h4 style=" color: #21225f;">Login to <a href="http://platform.sourceeasy.com/#/auth/login">platform.sourceeasy.com</a> </h4></div>
            <br>
            <a href="{{$data['link']}}" style="cursor:pointer;"><button style="background-color: #f1582b;color:white;width: 30%;position: relative;left: 50%;transform: translateX(-50%);-webkit-transform: translateX(-50%);height: 40px;border-radius: 10px;border: transparent; cursor: pointer;" type="button">Go To Platform</button></a>
        </div>

        <div  style="padding: 10px 20px 10px 20px;text-align: center;">
            <hr style="border: 1px solid lightgray;"> 
            <h3 style="color: #21225f">Thank you, have a great day!</h3>   
            <hr style="border: 1px solid lightgray;"> 
            <span style="color:gray">Sent by <a href="https://www.sourceeasy.com" style="text-decoration: none; color: #21225f;">Sourceasy Inc</a><br><small>395 S Van Ness Ave, San Francisco, CA 94103</small></span>
        </div>
      <br>
    </div>
</body>
</html>


