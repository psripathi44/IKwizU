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
	<h2 style="margin-left: 10%;"><a href="index.php">IKwizU</a></h2>
</header>
<div id="main">
<?PHP
if(isset($_POST["proceed"])){
	$inpCategory = $_POST["inpCategory"];
	$inpDifficulty = $_POST["inpDifficulty"];
	$inpQtype = $_POST["inpQtype"];
	$url = 'https://opentdb.com/api.php?amount=20';
	
	if($inpCategory != "any")
		$url.= '&category='.$inpCategory;
	
	if($inpDifficulty != "any")
		$url.= '&difficulty='.$inpDifficulty;
	
	if($inpQtype != "any")
		$url.= '&type='.$inpQtype;	
	
	$data = file_get_contents($url, true); // put the contents of the file into a variable
	$characters = json_decode($data, true); // decode the JSON feed
	$i = 0;
	$quesArr = [];
	$corrAns = [];
	
	print "<form action='' method='POST'>";
	foreach ($characters as $character) {
		if(is_array($character) || is_object($character))
		foreach($character as $value){
			$i += 1;
			print $i.". ".$value['question']."<br/><br/>";
			$arr = $value['incorrect_answers'];
			array_push($arr, $value['correct_answer']);
			
			shuffle($arr); //This shuffles the choices every time the user takes the test.
			
			array_push($corrAns, $value['correct_answer']);
			array_push($quesArr, $value['question']);
			
			foreach($arr as $key=>$choiceOptions){
				print"<label class='container'>$choiceOptions
						<input type='radio' name='Ques$i' id='$key' value='$choiceOptions'>
						<span class='checkmark'></span>
						</label><br/>";
			}
			print "<br/><br/>";
		}
	}
	foreach($corrAns as $key)
		echo '<input type="hidden" name="key[]" value="'. $key. '">';
	foreach($quesArr as $ques)
		echo '<input type="hidden" name="ques[]" value="'. $ques. '">';
	print "<button class='formButton' name='submit' type='submit'>Submit</button></form>";
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