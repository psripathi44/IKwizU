<?PHP
	session_start();
	unset($_SESSION['quizAttempted']);
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
				<li><a href="../about/" >About</a></li>
				<li><a href="../standings/" >Check Standings </a></li>
				<li><a href="../feedback/">Feedback</a></li>
				<li><a href="../analytics/">Analytics</a></li>
			</ul>
	  </nav>
	</nav>
</header>
<div id="main">
<?PHP
require "db.inc";
if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
	die("Could not connect to database"); 

if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
	showerror($connection);
	
$_SESSION['challenge'] = true;
$_SESSION['challToken'] = $_REQUEST['challengeToken'];
$lToken = $_REQUEST['challengeToken'];
$invalidToken = 0;

if(!is_numeric($lToken))
	$invalidToken = 1;
else {
	$tokenDtlsStmt = "select name, email_id, created_date from tokens where id = $lToken";
	$challengerDetails = mysqli_query ($connection, $tokenDtlsStmt);
	$challengerRec = mysqli_fetch_row($challengerDetails);

	$challengerScoreStmt = "select score from scorebytoken where token = $lToken and email = \"$challengerRec[1]\"";
	$challengerScore = mysqli_query ($connection, $challengerScoreStmt);
	$challengerScoreRec = mysqli_fetch_row($challengerScore);
}

if($invalidToken == 1 || $challengerRec[0] == null){
	print "<br/><br/><left class='challenge'><span style='color: #B60000;'>Error: </span>Invalid URL. There exists no challenge with this token.</left>";
	
	print "<right class='challenge'>";
		print "Here's a few challenges you can try. Sorted from Hardest to moderate.<br/><br/>";
		try{
			$i = 0;
			$tokenLinksQueryStmt = "select distinct token from scorebytoken order by score DESC LIMIT 10";
			$tokenLinksResults = @ mysqli_query ($connection, $tokenLinksQueryStmt);
			while ($record = @ mysqli_fetch_array($tokenLinksResults)){
				$i += 1;
				print "<i class='fa fa-angle-double-right'></i><a class='others' href = 'http://localhost/IKwizU/challenge/".$record["token"]."'> Attempt challenge ". $record["token"]."</a><br/><br/>";
			}
		}
		catch(Exception $e){
			print "Error occured while fetching the quizes that couldn't be cracked - ". $e;
		}
	print"</right>";
	exit;
}

if(isset($_POST["proceed"])){
	if(!isset($_SESSION['quizAttempted'])){	
		$_SESSION['inpName'] = mysqli_real_escape_string($connection, $_POST["inpName"]);
		$_SESSION['inpEmail'] = mysqli_real_escape_string($connection, $_POST["inpEmail"]);
		
		$lEmail = $_POST["inpEmail"];
		
		$uniqueChallengerQuery = "select * from scorebytoken where token = $lToken and email = \"$lEmail\"";
		$uniqueChallengerCheck = mysqli_query($connection, $uniqueChallengerQuery);
		$uniqueChallengerRec = mysqli_fetch_row($uniqueChallengerCheck);
		
		if($uniqueChallengerRec[0] != null){
			print "<br/>Hey it looks like this challenge was already attempted with the given email id, hence cannot proceed.<br/><br/>";
			print "You will be redirected in 15 secs to the challenge page, try with different email address this time. "; 
			print "<a style='color:#B60000' href='http://localhost/IKwizU/challenge/$lToken'>Click here</a> to redirected manually.";
			header( "refresh:15; url=http://localhost/IKwizU/challenge/$lToken" );
			exit;
		} else {
			$i = 0;
			$quesArr = [];
			$corrAns = [];
			$quesStmt = "select qno, question, quesType, optA, optB, optC, optD, optKey from quizbytoken where token = $lToken order by qNo ASC";
			$quesAnsQuery = @ mysqli_query ($connection, $quesStmt);
			print "<form action='../results/' class ='quizFormBox' method='POST' name='quizForm' onSubmit='return quizFormValidation();'>";
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
		}
	} else{
		$_SESSION['challenge'] = true;
		$_SESSION['challToken'] = $_REQUEST['challengeToken'];
		header("Location: http://localhost/IKwizU/results/");
		exit;
	}
} else {
	?>
	<br/><br/>
	<left class="challenge">
	<form method = "POST">
		<b>Name: </b><br/>
		<input name="inpName" type="text" value="" placeholder="Enter your name here.." class = "standingsFormTB" style="background-color:#FFFFF" required/> <br/><br/>
		<b>Email: </b><br/>
		<input name="inpEmail" type="email" value="" placeholder="Enter your email here.." class ="standingsFormTB" style="background-color:#FFFFF" required/><br/><br/><br/>
		<input type="submit" name ="proceed" value="Proceed to challenge" class="challengeFormButton"/>&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="reset" name ="reset" value="Reset Fields" class="challengeFormButton"/><br/><br/><br/>
		<label><span style="color: #B60000;">Note: </span>Please mind your email has to be unique, as you can attempt this challenge only once.</label>
	</form>
	</left>
	<?PHP
		print "<right class='challenge'><b> Challenger details:</b><hr/>";
		print "<i class='fa fa-address-book'></i> Name: $challengerRec[0] <br/>
			   <i class='fa'>&#xf0c5;</i> Score: $challengerScoreRec[0]<br/>
			   Difficulty: ";
		
		if($challengerScoreRec[0] >= 10)
			print "Easy <br/><br/>";
		else if($challengerScoreRec[0] >=7)
			print "Medium <br/><br/>";
		else
			print "Hard <br/><br/>";
		
		print "<b>This token standings -</b><hr/>";
		$i=0;
		try{
			$standingsQueryStmt = "select name, email, score, inserted_date from scorebytoken where token = $lToken order by score DESC, inserted_date ASC";
			$standingsResults = @ mysqli_query ($connection, $standingsQueryStmt);
			$noOfRowsFound = @ mysqli_num_rows($standingsResults);

			print"<table class ='challengeStandings' style='min-width: 100px;'>
					<thead>
						<th> Standing </th>
						<th> Name </th>
						<th> Score </th>
					</thead>";
			while ($record = @ mysqli_fetch_array($standingsResults)){
				$i += 1;
				print "<tr>";
				print "<td>$i</td>";
				print "<td>".$record["name"]."</td>";
				print "<td>".$record["score"]."</td>";
				print "</tr>";
			}
			print "</table>";
		}
		catch(Exception $e){
			print "Error occured while processing your request - ". $e;
		}
		print "</right>";
	}
?>
</div>
<script>

</script>
</body>
</html>