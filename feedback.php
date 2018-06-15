<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feed us back</title>
<link rel="icon" type="image/ico" href="Images/favicon.ico">
<link href="CSS/styles.css" rel="stylesheet" type="text/css">
<link href="CSS/menuStyles.css" rel="stylesheet" type="text/css">
<link href="CSS/styles_mediaQueries.css" rel="stylesheet" type="text/css">

<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<style>

</style>
</head>
<body>
<header>
	<nav class="navBar">
	  <nav class="menuwrapper">
		<div class="logo"><a href="index.php"> IKwizU </a></div>
			<input type="checkbox" id="menu-toggle" />
			<label for="menu-toggle" class="label-toggle"></label>
			<ul>
				<li><a href="about.php" >About</a></li>
				<li><a href="standings.php" >Check Standings </a></li>
				<li><a href="bulkOptions.php" >For group at large?</a></li>
				<li class="current"><a href="#">Feedback</a></li>
			</ul>
	  </nav>
	</nav>
</header>
<div id = "main">
<left>
	<div style="font-family: 'Tajawal', sans-serif;">
		<p class="cfTitle"> Hey! Help us improve! </p>
		<p class="cfText">Please fill out the form below to send us a message or feedback or suggestions too.</p><br/>

		<form method="POST" action="" enctype="multipart/form-data" style="width: 125%;">
			<input type="hidden" name="action" value="submit">
			<input name="name" type="text" value="" placeholder="Name" class = "feedbackFormTB"/> 
			<input name="email" type="text" value="" placeholder="Email" class ="feedbackFormTB"/><br/><br/>
			<textarea name="message" rows="6" placeholder="Message" class = "feedbackFormTA"></textarea><br/><br/>
			<input type="submit" name ="submit" value="Send Feedback" class="feedbackFormButton"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="reset" name ="reset" value="Reset Fields" class="feedbackFormButton"/>
		</form>
	</div>
	
	<?php
	if(isset($_POST["submit"]))
	{
		$name=$_REQUEST['name'];
		$message=$_REQUEST['message'];
		if (($name=="")||($message==""))
			{
				print '<br/><b style="color:#B60000">Exception:</b> ';
				echo "<br/><b>All fields are required. Please fill all the fields.</b>";
			}
		else{		
			/*Email code BEGIN*/
			require 'PHPMailer/PHPMailerAutoload.php';
			
			$mail = new PHPMailer;
			
			$mail->isSMTP();                                   // Set mailer to use SMTP
			$mail->Host = 'mx1.hostinger.com';                 // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                            // Enable SMTP authentication
			$mail->Username = 'admin@bugradanalytics.com';     // SMTP username
			$mail->Password = 'fO8eTlVa0PUf4m'; 		       // SMTP password
			$mail->SMTPSecure = 'true';                        // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                 // TCP port to connect to
			
			$mail->setFrom('admin@bugradanalytics.com', 'Bradley Graduate school Analytics');
			$mail->addReplyTo('admin@bugradanalytics.com', 'Bradley Graduate school Analytics');
			$mail->addAddress('prashu421@gmail.com');   // Add a recipient
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');
			
			$mail->isHTML(true);  // Set email format to HTML

			$bodyContent = "<html>\n"; 
			$bodyContent .= "<head>\n";
			$bodyContent .= "<link href='https://fonts.googleapis.com/css?family=Tajawal' rel='stylesheet'>\n";
			$bodyContent .= "</head>\n";  
			$bodyContent .= "<body style=\"font-family: 'Tajawal', sans-serif; font-size: 1em; font-style: normal; font-weight: 300; color: #4B4B4B;\">\n";
			$bodyContent .= "<br/> Hello Supervisor!<br/><br/> PFB feeback from the user - '$name'.<br/><br/>\n";
			$bodyContent .= "Email ID: $user_check <br/> Message: $message <br/>\n";
			$bodyContent .= "<br/> Regards, <br/> Team BU Analytics.<br/><br/> <b>Note: This is an automated email. Do not reply to it.</b>\n"; 
			$bodyContent .= "</body>\n"; 
			$bodyContent .= "</html>\n"; 
			
			
			$mail->Subject = 'Feedback - Bradley Grad School Analytics';
			$mail->Body    = $bodyContent;
			
			if(!$mail->send()) {
				echo "Email couldn't be send could not be sent.";
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				echo "<br/><b>Your feedback has been recorded, thanks!</b>";
			}

		 /*Email code END*/
			}
		}  
	?>
</left>
</div>
</body>
</html>