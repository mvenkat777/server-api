<!DOCTYPE html>
<html>
<head>
	<title>Queue Show</title>
	<script   src="https://code.jquery.com/jquery-1.9.1.min.js"   integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ="   crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
<script>
	var flag=1;
	function toggle(el)
	{

		console.log(flag);
		if(document.getElementById(el).style.visibility == "hidden")
		{
			document.getElementById(el).style.visibility = "visible";
		}
		else
		{
			document.getElementById(el).style.visibility = "hidden";
		}
	}
</script>
</head>
<body>
	
    <br>
    <br>
    <br>
	<div class="container">
		<div class="row">
			
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Jobs : Medium</h3>
					</div>
  					<div class="panel-body" style="word-wrap: break-word; height: 500px; overflow-y:auto;" >
    					@if($medium)
    						<ul class="list-group">
	    					@foreach ($medium as $key=> $job)	
 								<li class="list-group-item" onclick="toggle('qjobs-{{ $key+1 }}')">
 									{{ $job['job'] }}
 									
 								</li>
 							@endforeach
 							</ul>
	    				@else
	    					No Jobs
	    				@endif
  					</div>
  				</div>
				
			</div>
			
			<div class="col-md-3">
				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Completed : jobs</h3>
					</div>
					<div class="panel-body" style="word-wrap: break-word; height: 500px; overflow-y:auto;">
    					@if($completed)
    						<ul class="list-group">
	    					@foreach ($completed as $key=> $complete)	
 								<li class="list-group-item" onclick="toggle('cjobs-{{ $key+1 }}')">
 									{{ $complete['job'] }}
 									
 								</li>
 							@endforeach
 							</ul>
	    				@else
	    					No Jobs
	    				@endif
  					</div>
  				</div>
					
			</div>
			<div class="col-md-3">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title">Failed : jobs</h3>
					</div>
  					<div class="panel-body" style="word-wrap: break-word; height: 500px; overflow-y:auto;">
    					@if($failed)
    						<ul class="list-group">
	    					@foreach ($failed as $key=> $fail)	
 								<li class="list-group-item" onclick="toggle('fjobs-{{ $key+1 }}')">
 									{{ $fail['job'] }}
 									
 								</li>
 							@endforeach
 							</ul>
	    				@else
	    					No Jobs
	    				@endif
  					</div>
  				</div>
			</div>
		</div>
	</div>
			
</body>
</html>