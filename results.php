<?PHP
	session_start();
?>
<html>
<head>
<title> IKwizU </title>

<meta property="og:url"			content="http://localhost/IKwizU/challenge/" />
<meta property="og:type"		content="website" />
<meta property="og:title"		content="Hey IKwizU this challenge" />
<meta property="og:description"	content="Do you think you can beat my score?" />
<meta property="og:image"		content="image here" />

<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<link href="../CSS/styles.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="//platform-api.sharethis.com/js/sharethis.js#property=5b302f795a9f7800116e2d72&product=inline-share-buttons"></script>
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
				<li><a href="../analytics/">Analyze</a></li>
				<li><a href="../feedback/">Feedback</a></li>
			</ul>
	  </nav>
	</nav>
</header>
<div id="main">
<?PHP
//Checking and setting the token whether a new User token or a challenge token	
if(isset($_SESSION['challenge']))
	$lToken = $_SESSION['challToken'];
else
	$lToken = $_SESSION['currToken'];

if($lToken == null){
	header("Location: http://localhost/IKwizU/");
	exit;
}
else{
	
	require "db.inc";
	if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
		die("Could not connect to database"); 

	if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
		showerror($connection);

	$inpName = mysqli_real_escape_string($connection, $_SESSION['inpName']);
	$inpEmail = mysqli_real_escape_string($connection, $_SESSION['inpEmail']);

	$quizAttemptCheckQ = mysqli_query($connection, "select * from scorebytoken where token = $lToken and email = \"$inpEmail\"");
	$quizAttemptCheck = mysqli_fetch_row($quizAttemptCheckQ);
	
	if($quizAttemptCheck[0] == 0){ //Checking if record exists, if yes,$quizAttemptCheck[0] returns the id of the user score inserted
		if(isset($_POST["submit"])){
			$quesArr = [];
			$ansArr = [];
			$userSelections = [];
			$lScore = 0;
			
			/*The below for loop is the 15 $_POST[] statements which would fetch the answers selected for the quiz.*/
			for($i = 1 ; $i <= 15 ; $i++) {
				$optName = 'Ques'.$i;
				array_push($userSelections, $_POST["$optName"]);
			}
			
			try{
				$quesAnsQueryStmt = "select question, optKey from quizbytoken where token = $lToken order by qNo ASC";
				//print $quesAnsQueryStmt."<br/><br/>";
				
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
				$userAnsKeyForToken = mysqli_real_escape_string($connection, implode(";", $userSelections)); //Storing the answers selected by user as ";" seperated string
				$insScoreQueryStmt = "INSERT INTO scorebytoken (token, name, email, score, thisUserAnsKeyForToken) 
									  VALUES 
									  ($lToken, '{$inpName}', '{$inpEmail}', '{$lScore}', '{$userAnsKeyForToken}')";
									  
				//print $insScoreQueryStmt."<br/>";
				
				if(!@ mysqli_query ($connection, $insScoreQueryStmt)){
					print '<br><b style="color:red">Exception:</b> '; //Exception raised if the token insertion fails.
					throw new Exception(showerror($connection));
				}
			}
			catch(Exception $e){
				print "Error occured while processing your request - ". $e;
			}	
			
			print "<br/><span style='font-size: 18px'>Hey $inpName, here's your result summary - </span><br/>";
			print "<br/><table class ='quizStatsTable'>
						<tr>
							<td>Your score: $lScore </td> 
							<td>Challenge your friends, share by clicking on the buttons below - </td>   
						</tr>";
			print "<tr>
				<td>Token for your reference: $lToken</td>
				<td>
				<div class='a2a_kit a2a_kit_size_32 a2a_default_style' data-a2a-url='https://IKwizU.logngo.com/IKwizU/challenge/$lToken' data-a2a-title='IKwizU' data-a2a-description='This is a test'>
				<a class='a2a_button_copy_link'></a>
				<a class='a2a_button_twitter'></a>
				<a class='a2a_button_facebook'></a>
				<a class='a2a_button_facebook_messenger'></a>
				<a class='a2a_button_google_plus'></a>
				<a class='a2a_button_reddit'></a>
				<a class='a2a_button_linkedin'></a>
				<a class='a2a_button_whatsapp'></a>
				<a class='a2a_button_pinterest'></a>
				</div>
			<script async src='https://static.addtoany.com/menu/page.js'></script></td></tr></table>";
			
			print "<br/><br/><br/><table class='resultsTable'>";
			for($i=0; $i<15; $i++){
				if($userSelections[$i] == $ansArr[$i]){
					print "<tr><td><i class='fa fa-check-circle-o' style='font-size:24px;color:#63ce4f'></i></td> <td>". ($i+1).".&nbsp;&nbsp;".$quesArr[$i]." </td></tr>" ;
					print "<tr><td></td><td><span style='color:#63ce4f;'>Your answer: </span>".$userSelections[$i]."<br/><hr/><br/></td></tr>";
				}
				else{
					print "<tr><td><i class='fa fa-times-circle' style='font-size:24px;color:#B60000'></i></td> <td>". ($i+1).".&nbsp;&nbsp;".$quesArr[$i]." </td></tr>" ;
					print "<tr><td></td> <td><span style='color:#B60000'>Your answer: </span>".$userSelections[$i];
					print "&nbsp; &nbsp; <span style='color:#63ce4f'>Correct answer: </span>".$ansArr[$i]."<br/><hr/><br/></td></tr>";
				}
			}
			print "</table><br/><br/><br/>";
		}
	} else {
		$quesArr = [];
		$ansArr = [];
		$ansKeyArr = [];
		
		try{
			$quesAnsQueryStmt = "select question, optKey from quizbytoken where token = $lToken  order by qNo ASC";
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
			$userAnsQueryStmt = "select score, thisUserAnsKeyForToken from scorebytoken where token = $lToken and email = \"$inpEmail\"";
			$userAnsQuery = mysqli_query ($connection, $userAnsQueryStmt);
			$userAnsRecord = mysqli_fetch_row($userAnsQuery);

			$lScore = $userAnsRecord[0];
			$ansKeyArr = explode(";" , $userAnsRecord[1]);
			
		}
		catch(Exception $e){
			print "Error occured while processing your request - ". $e;
		}
		
		print "<br/><span style='font-size: 18px'>Hey $inpName, here's your result summary - </span><br/>";
		print "<br/><table class ='quizStatsTable'>
					<tr>
						<td>Your score: $lScore </td> 
						<td>Challenge your friends, share by clicking on the buttons below - </td>   
					</tr>";
		print "<tr>
			<td>Token for your reference: $lToken</td>
			<td>
			<div class='a2a_kit a2a_kit_size_32 a2a_default_style' data-a2a-url='https://IKwizU.logngo.com/IKwizU/challenge/$lToken' data-a2a-title='IKwizU' data-a2a-description='This is a test'>
			<a class='a2a_button_copy_link'></a>
			<a class='a2a_button_twitter'></a>
			<a class='a2a_button_facebook'></a>
			<a class='a2a_button_facebook_messenger'></a>
			<a class='a2a_button_google_plus'></a>
			<a class='a2a_button_reddit'></a>
			<a class='a2a_button_linkedin'></a>
			<a class='a2a_button_whatsapp'></a>
			<a class='a2a_button_pinterest'></a>
			</div>
		<script async src='https://static.addtoany.com/menu/page.js'></script></td></tr></table>";
		
		print "<br/><br/><br/><table class='resultsTable'>";
		for($i=0; $i<15; $i++){
			if($ansKeyArr[$i] == $ansArr[$i]){
				print "<tr><td><i class='fa fa-check-circle-o' style='font-size:24px;color:#63ce4f'></i></td> <td>". ($i+1).".&nbsp;&nbsp;".$quesArr[$i]." </td></tr>" ;
				print "<tr><td></td><td><span style='color:#63ce4f;'>Your answer: </span>".$ansKeyArr[$i]."<br/><hr/><br/></td></tr>";
			}
			else{
				print "<tr><td><i class='fa fa-times-circle' style='font-size:24px;color:#B60000'></i></td> <td>". ($i+1).".&nbsp;&nbsp;".$quesArr[$i]." </td></tr>" ;
				print "<tr><td></td> <td><span style='color:#B60000'>Your answer: </span>".$ansKeyArr[$i];
				print "&nbsp; &nbsp; <span style='color:#63ce4f'>Correct answer: </span>".$ansArr[$i]."<br/><hr/><br/></td></tr>";
			}
		}
		print "</table><br/><br/><br/>";
	}
}

?>
</div>
</body>
</html>