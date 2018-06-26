<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feed us back</title>
<link rel="icon" type="image/ico" href="Images/favicon.ico">
<link href="../CSS/styles.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
</head>
<body>
<header>
	<nav class="navBar">
	  <nav class="menuwrapper">
		<div class="logo"><a href="/IKwizU"> IKwizU </a></div>
			<input type="checkbox" id="menu-toggle" />
			<label for="menu-toggle" class="label-toggle"></label>
			<ul>
				<li><a href="../about/" >About</a></li>
				<li><a href="../standings/" >Check Standings </a></li>
				<li class="current"><a href="#">Feedback</a></li>
				<li><a href="../analytics/">Analytics</a></li>
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
			$name=$_POST['name'];
			$email=$_POST['email'];
			$message=$_POST['message'];
			if (($name=="")||($email=="")||($message==""))
				{
					print '<br/><b style="color:#B60000">Exception:</b> ';
					echo "<br/><b>All fields are required. Please fill all the fields.</b>";
				}
			else{		
				/*Email code BEGIN*/
				require 'PHPMailer/PHPMailerAutoload.php';
				
				$mail = new PHPMailer;
				
				$mail->isSMTP();									// Set mailer to use SMTP
				$mail->Host = 'host name';                 			// Specify main and backup SMTP servers
				$mail->SMTPAuth = true;								// Enable SMTP authentication
				$mail->Username = 'user@domain.com';				// SMTP username
				$mail->Password = 'passcode';						// SMTP password
				$mail->SMTPSecure = 'true';							// Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;									// TCP port to connect to
				
				$mail->setFrom('user@domain.com', $name);
				$mail->addReplyTo($email, $name);
				$mail->addAddress('prashu421@gmail.com');   		// Add a recipient
				
				$mail->isHTML(true);  // Set email format to HTML

				$bodyContent = "<html>\n"; 
				$bodyContent .= "<head>\n";
				$bodyContent .= "<link href='https://fonts.googleapis.com/css?family=Tajawal' rel='stylesheet'>\n";
				$bodyContent .= "</head>\n";  
				$bodyContent .= "<body style=\"font-family: 'Tajawal', sans-serif; font-size: 1em; font-style: normal; font-weight: 300; color: #4B4B4B;\">\n";
				$bodyContent .= "<br/> Hello Admin!<br/><br/> PFB feeback from the user - '$name'.<br/><br/>\n";
				$bodyContent .= "Email ID: $email <br/> Message: $message <br/>\n";
				$bodyContent .= "<br/> Regards, <br/> Team IKwizU.<br/><br/>\n";
				$bodyContent .= "</body>\n"; 
				$bodyContent .= "</html>\n"; 
				
				
				$mail->Subject = 'Feedback - IKwizU';
				$mail->Body    = $bodyContent;
				
				if(!$mail->send()) {
					echo "<br/><span style='color:#B60000;'>Error: </span> <br/>Email could not be sent.";
					echo '<br/>Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo "<br/><b>Your feedback has been recorded, thank you for helping us get better!</b>";
				}
				/*Email code END*/
			}
		}  
	?>
</left>
</div>
</body>
</html>