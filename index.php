<?PHP
	session_start();
	if(isset($_SESSION['disableQuizPage']))
		unset($_SESSION['disableQuizPage']); 
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
		<div class="logo">IKwizU</div>
			<input type="checkbox" id="menu-toggle" />
			<label for="menu-toggle" class="label-toggle"></label>
			<ul>
				<li><a href="#" >About</a></li>
				<li><a href="standings/" >Check Standings </a></li>
				<li><a href="feedback/">Feedback</a></li>
				<li><a href="analytics/">Analytics</a></li>
			</ul>
	  </nav>
	</nav>
</header>
<div id="main">
	<br/><br/>
	<span class="mainHeading">Welcome to the TRIVIA Quiz by IKwizU.</span><br/><br/>
	Here you can select the category the questions that would be based on, and the level of difficulty and type of questions as well.<br/>
	Once submitting the inputs, you have an option to take the quiz to test yourself and see where are at in your fav genre.<br/><br/> 
	Once you take the quiz, you can challenge your friends by sharing the same quiz to them via Facebook, Twitter, and other platforms, and see if they can beat you.<br/><br/>
	<div class="formBox">
		<form action="" method="post" name="inputsForm" onSubmit="return quizInpValidation();">
			Name:<br/>
			<input name="inpName" type="text" value="" placeholder="Enter your name here.." class = "inpText" style="background-color:#FFFFF" required/> <br/><br/>
			Email:<br/>
			<input name="inpEmail" type="email" value="" placeholder="Enter your email here.." class ="inpText" style="background-color:#FFFFF" required/><br/><br/>
			Select Category:<br/>
			<select id="inpCategory" name="inpCategory" class="inpDD">
				<option value="any">Any category</option>
			<?PHP
				$catFetch = file_get_contents("https://opentdb.com/api_category.php", true);
				$categoriesJson = json_decode($catFetch, true); // decode the JSON feed
				foreach ($categoriesJson as $categories) {
					if(is_array($categories) || is_object($categories)){
						foreach($categories as $category){
							print "<option value='".$category['id']."'>".$category['name']."</option>";
						}
					}
				}
			?>
			</select><br/><br/>

			Select Difficulty:<br/>
			<select id="inpDifficulty" name="inpDifficulty" class="inpDD">
				<option value="any">Any Difficulty</option>
				<option value="easy">Easy</option>
				<option value="medium">Medium</option>
				<option value="hard">Hard</option>
			</select><br/><br/>
			
			Select Type:<br/>
			<select id="inpQtype" name="inpQtype" class="inpDD">
				<option value="any">Any Type</option>
				<option value="multiple">Multiple Choice</option>
				<option value="boolean">True / False</option>
			</select><br/><br/><br/>
			
			<button class="formButton" name="proceed" type="submit"><i class="fa fa-angle-double-right"></i> &nbsp;Click here to proceed</button>
			<div class="error" id="errContainer"></div>
		</form>
	</div>
</div>
<?PHP
require "db.inc";

if(isset($_POST["proceed"])){
	$inpCategory = $_POST["inpCategory"];
	$inpDifficulty = $_POST["inpDifficulty"];
	$inpQtype = $_POST["inpQtype"];
	$inpName = $_POST["inpName"];
	$inpEmail = $_POST["inpEmail"];
	
	$url = 'https://opentdb.com/api.php?amount=15'; //Set it to 2 for testing flexibility, Change this back to 15
	
	if($inpCategory == 30)
		$url = 'https://opentdb.com/api.php?amount=15';	
	else{
		if($inpCategory != "any")
			$url.= '&category='.$inpCategory;
		
		if($inpDifficulty != "any")
			$url.= '&difficulty='.$inpDifficulty;
		
		if($inpQtype != "any")
			$url.= '&type='.$inpQtype;
	}
	
	$data = file_get_contents($url, true); // put the contents of the file into a variable
	$apiQuizDetails = json_decode($data, true); // decode the JSON feed
	$i = 0;
	$quesArr = [];
	$corrAns = [];
	
	if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
		die("Could not connect to database"); 
	
	if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
		showerror($connection);
		
	$nextRecId_row = mysqli_query($connection, "select max(id)+1 from tokens");
	$nextRecId = mysqli_fetch_row($nextRecId_row); //Fetching the next token value, this acts as a sequence
	
	$insToken = "insert into tokens(id, name, email_id) values({$nextRecId[0]}, '{$inpName}', '{$inpEmail}');";
	$_SESSION['currToken'] = $nextRecId[0];
	$_SESSION['inpName'] = $inpName;
	$_SESSION['inpEmail'] = $inpEmail;
	
	
	if(!@mysqli_query ($connection, $insToken)){
		print '<br><b style="color:red">Exception:</b> '; //Exception raised if the token insertion fails.
		throw new Exception(showerror($connection));
	} else {		
		foreach ($apiQuizDetails as $quizDetails) {
			if(is_array($quizDetails) || is_object($quizDetails)){
				foreach($quizDetails as $quesDetails){
					$i += 1;
					
					$choicesArr = $quesDetails['incorrect_answers'];
					array_push($choicesArr, $quesDetails['correct_answer']);
					
					shuffle($choicesArr); //This shuffles the choices. Same will be used when other users try to take test using this token
					
					if($quesDetails['type'] == "multiple")
						$insQues = "INSERT INTO quizbytoken (token, qNo, question, quesType, optA, optB, optC, optD, optKey) VALUES ({$nextRecId[0]}, $i, '{$quesDetails['question']}', '{$quesDetails['type']}' ,'{$choicesArr[0]}', '{$choicesArr[1]}', '{$choicesArr[2]}', '{$choicesArr[3]}', '{$quesDetails['correct_answer']}')";
					else
						$insQues = "INSERT INTO quizbytoken (token, qNo, question, quesType, optA, optB, optKey) VALUES ({$nextRecId[0]}, $i, '{$quesDetails['question']}', '{$quesDetails['type']}', '{$choicesArr[0]}', '{$choicesArr[1]}', '{$quesDetails['correct_answer']}')";
					
					if(!@mysqli_query ($connection, $insQues)){
						print '<br><b style="color:red">Exception:</b> '; //Exception raised if the token insertion fails.
						throw new Exception(showerror($connection));
					}
				}
			}
		}
	}
	header("Location: http://localhost/IKwizU/quizshere.php");
	exit;
}
?>
</body>
</html>
