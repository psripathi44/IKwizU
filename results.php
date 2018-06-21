<?PHP
	session_start();
?>
<html>
<head>
<title> IKwizU </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<link href="CSS/styles.css" rel="stylesheet">
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
require "db.inc";
if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
	die("Could not connect to database"); 

if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
	showerror($connection);
	
$lToken = $_SESSION['currToken'];
$inpName = $_SESSION['inpName'];
$inpEmail = $_SESSION['inpEmail'];

$quizAttemptCheckQ = mysqli_query($connection, "select count(1) from scorebytoken where token = $lToken");
$quizAttemptCheck = mysqli_fetch_row($quizAttemptCheckQ);

if($quizAttemptCheck[0] == 0){
	if(isset($_POST["submit"])){
		
		$quesArr = [];
		$ansArr = [];
		$userSelections = [];
		$lScore = 0;
		
		/*The below for loop is the 20 $_POST[] statements which would fetch the answers selected for the quiz.*/
		for($i = 1 ; $i <= 15 ; $i++) {
			$optName = 'Ques'.$i;
			array_push($userSelections, $_POST["$optName"]);
		}
		
		try{
			$quesAnsQueryStmt = "select question, optKey from quizbytoken where token = $lToken order by qNo ASC";
			$quesAnsQuery = @ mysqli_query ($connection, $quesAnsQueryStmt);
			while ($record = @ mysqli_fetch_array($quesAnsQuery)){
				array_push($quesArr, $record["question"]);
				array_push($ansArr, $record["optKey"]);
			}
		}
		catch(Exception $e){
			print "Error occured while processing your request - ". $e;
		}
		
		/*Calculating the score*/
		foreach($userSelections as $index=>$value){
			if($ansArr[$index] == $value)
				$lScore += 1;
		}

		/*Storing scores of the user for dashboard*/
		try{
			$userAnsKeyForToken = implode(";", $userSelections); //Storing the answers selected by user as ";" seperated string
			$insScoreQueryStmt = "INSERT INTO scorebytoken (token, name, email, score, thisUserAnsKeyForToken) VALUES ($lToken, '{$inpName}', '{$inpEmail}', '{$lScore}', '{$userAnsKeyForToken}')";
			if(!@ mysqli_query ($connection, $insScoreQueryStmt)){
				print '<br><b style="color:red">Exception:</b> '; //Exception raised if the token insertion fails.
				throw new Exception(showerror($connection));
			}
		}
		catch(Exception $e){
			print "Error occured while processing your request - ". $e;
		}	
		
		print "Your score: $lScore<br/><br/>";
		
		for($i=0; $i<15; $i++){
			print ($i+1).".".$quesArr[$i]." <br/>" ;
			print "Your answer: ".$userSelections[$i];
			print "&nbsp; &nbsp; Correct answer: ".$ansArr[$i]."<br/><br/>";
		}
	}
} else {
	$quesArr = [];
	$ansArr = [];
	$ansKeyArr = [];
	
	try{
		$quesAnsQueryStmt = "select question, optKey from quizbytoken where token = $lToken order by qNo ASC";
		$quesAnsQuery = @ mysqli_query ($connection, $quesAnsQueryStmt);
		while ($record = @ mysqli_fetch_array($quesAnsQuery)){
			array_push($quesArr, $record["question"]);
			array_push($ansArr, $record["optKey"]);
		}
	}
	catch(Exception $e){
		print "Error occured while processing your request - ". $e;
	}
	
	try{
		$userAnsQueryStmt = "select score, thisUserAnsKeyForToken from scorebytoken where token = $lToken";
		$userAnsQuery = mysqli_query ($connection, $userAnsQueryStmt);
		$userAnsRecord = mysqli_fetch_row($userAnsQuery);

		$lScore = $userAnsRecord[0];
		$ansKeyArr = explode(";" , $userAnsRecord[1]);
		
	}
	catch(Exception $e){
		print "Error occured while processing your request - ". $e;
	}
	
	print "Your score: $lScore<br/><br/>";
		
	for($i=0; $i<15; $i++){
		print ($i+1).".".$quesArr[$i]." <br/>" ;
		print "Your answer: ".$ansKeyArr[$i];
		print "&nbsp; &nbsp; Correct answer: ".$ansArr[$i]."<br/><br/>";
	}
}
?>
</div>
</body>
</html>