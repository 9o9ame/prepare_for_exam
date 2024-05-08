<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prepare For Exams</title>
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<style>
	 
	@import url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css');

	page {
      padding: 10px
    }

    .logo {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 30px
    }

    .logo img {
      width: 10px;
    }

    .questions {
      display: flex;
      justify-content: start;
      align-items: center;
      flex-direction: column;
      gap: 10px;
    }

    .question-head {
      font-weight: bold;
      font-size: 20px;
      margin-right: 10px;
    }
	
	img {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
	</style>
  </head>
  <body>
  
    <div class="row">
	<div class="col-md-4">
	</div>
	<div class="col-md-4">
	 <center> <img src="https://studentquestionbank.weazy.in/static/media/main_logo.fe178b29.png" alt="LOGO" style="width:50px;"></center>
	</div>
	<div class="col-md-4">
	</div>
    
    </div>
    <div class="questions">
		@if(isset($data))
			<?php
		$x=1;
		foreach($data as $d)
		{
		?>
      <p>
        <span class="question-head">Question {{$x}}</span>{!!$d->question!!}
      </p>
	  <?php $x++;
	 }
	 ?>
	@endif
	
    </div>
  

    <!--End Invoice-->
  </body>
</html>
