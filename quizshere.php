<?PHP
	session_start();
?>
<html>
<head>
<title> IKwizU </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<link href="CSS/styles.css" rel="stylesheet">
<script src="JS/validation.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
	<nav class="navBar">
	  <nav class="menuwrapper">
		<div class="logo"><a href="index.php">IKwizU</a></div>
			<input type="checkbox" id="menu-toggle" />
			<label for="menu-toggle" class="label-toggle"></label>
			<ul>
				<li><a href="about.php" >About</a></li>
				<li><a href="standings.php" >Check Standings </a></li>
				<li><a href="bulkOptions.php" >For group at large?</a></li>
				<li><a href="feedback.php">Feedback</a></li>
			</ul>
	  </nav>
	</nav>
</header>
<div id="main">
<form action="results.php" class ="quizFormBox" method="POST" name="quizForm" onSubmit="return quizFormValidation();">
<?PHP
require "db.inc";

if(!isset($_SESSION['quizAttempted'])){
    $_SESSION['quizAttempted'] = true;

	$lToken = $_SESSION['currToken'];
	$i = 0;
	$quesArr = [];
	$corrAns = [];
	
	if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
		die("Could not connect to database"); 
	
	if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
		showerror($connection);
		
	$quesStmt = "select qno, question, quesType, optA, optB, optC, optD, optKey from quizbytoken where token = $lToken order by qNo ASC";
	$quesAnsQuery = @ mysqli_query ($connection, $quesStmt);
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
    header("Location: http://localhost/iKwizU/results.php");
	exit;
}
?>
</div>
<script>

</script>
</body>
</html>