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
	<h2 style="margin-left: 10%;"><a href="index.php">IKwizU</a></h2>
</header>
<div id="main">
<?PHP
if(isset($_POST["proceed"])){
	require "db.inc";
					
	$inpCategory = $_POST["inpCategory"];
	$inpDifficulty = $_POST["inpDifficulty"];
	$inpQtype = $_POST["inpQtype"];
	$inpName = $_POST["inpName"];
	$inpEmail = $_POST["inpEmail"];
	
	$url = 'https://opentdb.com/api.php?amount=20';
	
	if($inpCategory == 30)
		$url = 'https://opentdb.com/api.php?amount=18';	
	else{
		if($inpCategory != "any")
			$url.= '&category='.$inpCategory;
		
		if($inpDifficulty != "any")
			$url.= '&difficulty='.$inpDifficulty;
		
		if($inpQtype != "any")
			$url.= '&type='.$inpQtype;
	}
	
	$data = file_get_contents($url, true); // put the contents of the file into a variable
	$characters = json_decode($data, true); // decode the JSON feed
	$i = 0;
	$quesArr = [];
	$corrAns = [];
	
	if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
		die("Could not connect to database"); 
	
	if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
		showerror($connection);
		
	$nextRecId_row = mysqli_query($connection, "select max(id)+1 from tokens");
	$nextRecId = mysql_fetch_row($l_nextWineID_row); //Fetching the next wine_id, this acts as sequence
	$current_date = date("Y-m-d H:i:s");
		
	$insToken = "insert into table tokens(id, created_date, name, email_id) values({$nextRecId}, '{$current_date}', '{$inpName}', '{$inpEmail}')";
	if(!@mysqli_query ($connection, $insToken)){
		print '<br><b style="color:red">Exception:</b> '; //Exception raised if the token insertion fails.
		throw new Exception(showerror());
	} else {
		print "<form action='' class ='quizFormBox' method='POST' name='quizForm' enctype='multipart/form-data'  onSubmit='return quizFormValidation();'>";
		foreach ($characters as $character) {
			if(is_array($character) || is_object($character))
			foreach($character as $value){
				$i += 1;
				print $i.". ".$value['question']."<br/><br/>";
				$arr = $value['incorrect_answers'];
				array_push($arr, $value['correct_answer']);
				
				shuffle($arr); //This shuffles the choices every time the user takes the test.
				
				$insQues = "INSERT INTO `quizbytoken`(token, question, optA, optB, optC, optD, optKey) 
				VALUES ({$nextRecId}, {$value['question']}, {$arr[0]}, {$arr[1]}, {$arr[2]}, {$arr[3]}, {$value['correct_answer']})";
				
				if(!@mysqli_query ($connection, $insQues)){
					print '<br><b style="color:red">Exception:</b> '; //Exception raised if the token insertion fails.
					throw new Exception(showerror());
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
		/* We can remove these statements from here, and in the next page, the answers for the questions can be fetched and displayed from DB
		foreach($corrAns as $key)
			echo '<input type="hidden" name="key[]" value="'. $key. '">';
		foreach($quesArr as $ques)
			echo '<input type="hidden" name="ques[]" value="'. $ques. '">';*/
		print "<button class='formButton' name='submit' type='submit'>Submit</button></form>";
	}
}

if(isset($_POST["submit"])){
	$key = $_POST["key"];
	$ques = $_POST["ques"];
	$answer = [];
	$retCnt = 0;
	for($i = 1 ; $i <= 20 ; $i++) { 
		$postarray = 'Ques'.$i; 
		array_push($answer, $_POST[$postarray]); 
	}
	
	foreach($answer as $index=>$value){
		if($key[$index] == $value)
			$retCnt += 1;
	}
	
	print "Your score: $retCnt<br/><br/>";
	
	for($i=0; $i<20; $i++){
		print ($i+1).".".$ques[$i]." <br/>" ;
		print "Your answer: ".$answer[$i];
		print "&nbsp; &nbsp; Correct answer: ".$key[$i]."<br/><br/>";
	}
	
}
?>
</div>
</body>
</html>