<html>
<head>
<title> IKwizU </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<link href="CSS/styles.css" rel="stylesheet">
<!--script src="JS/validation.js"></script>-->
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
if(isset($_POST["proceed"])){
					
	$inpCategory = $_POST["inpCategory"];
	$inpDifficulty = $_POST["inpDifficulty"];
	$inpQtype = $_POST["inpQtype"];
	$inpName = $_POST["inpName"];
	$inpEmail = $_POST["inpEmail"];
	
	$url = 'https://opentdb.com/api.php?amount=2';
	
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
	$nextRecId = mysqli_fetch_row($nextRecId_row); //Fetching the next wine_id, this acts as sequence
	
	$insToken = "insert into tokens(id, name, email_id) values({$nextRecId[0]}, '{$inpName}', '{$inpEmail}');";
	
	if(!@mysqli_query ($connection, $insToken)){
		print '<br><b style="color:red">Exception:</b> '; //Exception raised if the token insertion fails.
		throw new Exception(showerror($connection));
	} else {		
		foreach ($apiQuizDetails as $quizDetails) {
			if(is_array($quizDetails) || is_object($quizDetails))
			foreach($quizDetails as $quesDetails){
				$i += 1;

				
				print $i.". ".$quesDetails['question']."<br/><br/>";
				$arr = $quesDetails['incorrect_answers'];
				array_push($arr, $quesDetails['correct_answer']);
				
				shuffle($arr); //This shuffles the choices every time the user takes the test.
				
				if($quesDetails['type'] == "multiple")
					$insQues = "INSERT INTO quizbytoken (token, qNo, question, quesType, optA, optB, optC, optD, optKey) VALUES ({$nextRecId[0]}, $i, '{$quesDetails['question']}', '{$quesDetails['type']}' ,'{$arr[0]}', '{$arr[1]}', '{$arr[2]}', '{$arr[3]}', '{$quesDetails['correct_answer']}')";
				else
					$insQues = "INSERT INTO quizbytoken (token, qNo, question, quesType, optA, optB, optKey) VALUES ({$nextRecId[0]}, $i, '{$quesDetails['question']}', '{$quesDetails['type']}', '{$arr[0]}', '{$arr[1]}', '{$quesDetails['correct_answer']}')";
				
				if(!@mysqli_query ($connection, $insQues)){
					print '<br><b style="color:red">Exception:</b> '; //Exception raised if the token insertion fails.
					throw new Exception(showerror($connection));
				} else {
					foreach($arr as $key=>$choiceOptions){
						print"<label class='container'>$choiceOptions
								<input type='radio' name='Ques$i' id='$key' value='$choiceOptions'>
								<span class='checkmark'></span>
							  </label><br/>";
					}
					print "<br/><br/>";
				}
			}
		}
		print "<input type='hidden' name='token' value='$nextRecId[0]'>
		<input type='hidden' name='inpName' value='$inpName'>
		<input type='hidden' name='inpEmail' value='$inpEmail'>";
		
		print "<button class='formButton' name='submit' type='submit'>Submit</button></form>";
	}
}
?>
</div>
<script>
function quizFormValidation(){
	for(var i = 1 ; i <= 2 ; i++){
	  var choiceObjs = document.getElementsByName('Ques'+i);
	  var selected = true;
	  for (var j = 0, len = choiceObjs.length; j < len; j++){
		 if (choiceObjs[j].selected){
		  selected = true;
		  break;
		 }
	  }
	  if(!selected){
		 alert('Please answer Question No.'+i);
		 return false;
	  }
	}
	return true;
}
</script>
</body>
</html>