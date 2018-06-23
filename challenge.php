<?PHP
	session_start();
	require "db.inc";
	$lToken = $_REQUEST['challengeToken'];
	
	if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
		die("Could not connect to database"); 
	
	if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
		showerror($connection);
		
	$tokenDtlsStmt = "select name, email_id, created_date from tokens where id = $lToken";
	$challengerDetails = mysqli_query ($connection, $tokenDtlsStmt);
	$challengerRec = mysqli_fetch_row($challengerDetails);
	
	if($challengerRec[0] == null){
		print "There exists no token with this challenge.";
		exit;
	}
?>
<html>
<head>
<title> IKwizU </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<link href="../CSS/styles.css" rel="stylesheet">
<script src="../JS/validation.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
	<nav class="navBar">
	  <nav class="menuwrapper">
		<div class="logo"><a href="/IKwizU">IKwizU</a></div>
			<input type="checkbox" id="menu-toggle" />
			<label for="menu-toggle" class="label-toggle"></label>
			<ul>
				<li><a href="#" >About</a></li>
				<li><a href="../standings/" >Check Standings </a></li>
				<li><a href="../feedback/">Feedback</a></li>
				<li><a href="../analytics/">Analytics</a></li>
			</ul>
	  </nav>
	</nav>
</header>
<div id="main">
<?PHP

if(isset($_POST["proceed"])){
	$_SESSION['challengeInpSubmitted'] = true;
	if(!isset($_SESSION['quizAttempted'])){
		$i = 0;
		$quesArr = [];
		$corrAns = [];
		
		if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
			die("Could not connect to database"); 
		
		if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
			showerror($connection);
			
		$quesStmt = "select qno, question, quesType, optA, optB, optC, optD, optKey from quizbytoken where token = $lToken order by qNo ASC";
		$quesAnsQuery = @ mysqli_query ($connection, $quesStmt);
		print "<form action='results.php' class ='quizFormBox' method='POST' name='quizForm' onSubmit='return quizFormValidation();'>";
		while ($record = @ mysqli_fetch_array($quesAnsQuery)){
			$i += 1;
			print $record['qno'].". ".$record['question']."<br/><br/>";
			if($record['quesType'] == "boolean")
				$choicesArr = array($record['optA'], $record['optB']);
			else
				$choicesArr = array($record['optA'], $record['optB'], $record['optC'], $record['optD']);
			
			foreach($choicesArr as $key=>$choiceOptions){
				print"<label class='container'>$choiceOptions
						<input type='radio' name='Ques$i' id='$key' value='$choiceOptions'>
						<span class='checkmark'></span>
					  </label><br/>";
			}
			print "<br/><br/>";
		}
		print "<button class='formButton' name='submit' type='submit'>Submit</button></form>";

	}else{
		header("Location: http://localhost/IKwizU/results.php");
		$_SESSION['challenge'] = true;
		$_SESSION['challToken'] = $_REQUEST['challengeToken'];
		exit;
	}
} else {
	if(isset($_SESSION['quizAttempted'])){
		//Add magic here, so the user is redirected, without being asked for inputs again.
	} else {
		print "<br/><b>Challenger details: </b><br/>";
		print "Name: $challengerRec[0]";
		print "&nbsp;&nbsp;&nbsp;&nbsp;Email: $challengerRec[1]"
		?>
		<form method = "POST">
			<br/><b>Name: </b><br/>
			<input name="inpName" type="text" value="" placeholder="Enter your name here.." class = "feedbackFormTB" style="background-color:#FFFFF" required/> <br/><br/>
			<b>Email: </b><br/>
			<input name="inpEmail" type="email" value="" placeholder="Enter your email here.." class ="feedbackFormTB" style="background-color:#FFFFF" required/><br/><br/>
			<input type="submit" name ="proceed" value="Proceed to challenge" class="challengeFormButton"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="reset" name ="reset" value="Reset Fields" class="challengeFormButton"/><br/><br/><br/>
			<label><span style="color: #B60000;">Note: </span>Please mind your email has to be unique, as you can attempt this challenge only once.</label>
		</form>
	<?PHP
	}
}
?>
</div>
<script>

</script>
</body>
</html>