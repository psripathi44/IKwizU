<html>
<head>
<title> IKwizU </title>
</head>
<body>
<form action="" method="post">
	Select Category:
	<select name="inpCategory" class="form-control">
		<option value="9">General Knowledge</option>
		<option value="10">Entertainment: Books</option>
		<option value="11">Entertainment: Film</option>
		<option value="12">Entertainment: Music</option>
		<option value="13">Entertainment: Musicals & Theatres</option>
		<option value="14">Entertainment: Television</option>
		<option value="15">Entertainment: Video Games</option>
		<option value="16">Entertainment: Board Games</option>
		<option value="17">Science & Nature</option>
		<option value="18">Science: Computers</option>
		<option value="19">Science: Mathematics</option>
		<option value="20">Mythology</option>
		<option value="21">Sports</option>
		<option value="22">Geography</option>
		<option value="23">History</option>
		<option value="24">Politics</option>
		<option value="25">Art</option>
		<option value="26">Celebrities</option>
		<option value="27">Animals</option>
		<option value="28">Vehicles</option>
		<option value="29">Entertainment: Comics</option>
		<option value="30">Science: Gadgets</option>
		<option value="31">Entertainment: Japanese Anime &amp; Manga</option>
		<option value="32">Entertainment: Cartoon &amp; Animations</option>		
		<option value="any">Mix of all</option>
	</select><br/>

	Select Difficulty:
	<select name="inpDifficulty" class="form-control">
		<option value="easy">Easy</option>
		<option value="medium">Medium</option>
		<option value="hard">Hard</option>
		<option value="any">Mix of each</option>
	</select><br/>
	
	Select Type:
	<select name="inpQtype" class="form-control">>
		<option value="multiple">Multiple Choice</option>
		<option value="boolean">True / False</option>
		<option value="any">Mix of each</option>
	</select><br/>

	<br/>
	<button class="" name="proceed" type="submit">Proceed</button>
</form>
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
	
	$data = file_get_contents($url); // put the contents of the file into a variable
	$characters = json_decode($data, true); // decode the JSON feed
	$i = 0;
	$resArr = [];
	
	print "<html><head></head><body><form action='' method='POST'>";
	foreach ($characters as $character) {
		if(is_array($character) || is_object($character))
		foreach($character as $value){
			$i += 1;
			print $i.". ".$value['question']."<br/>";
			$arr = $value['incorrect_answers'];
			array_push($arr, $value['correct_answer']);
			
			array_push($resArr, $value['correct_answer']);
			foreach($arr as $key=>$choiceOptions){
				print "<input type='radio' name='Ques$i' id='$key' value='$choiceOptions' /> $choiceOptions<br/>";
			}
			print "<br/><br/>";
		}
	}
	foreach($resArr as $key)
		echo '<input type="hidden" name="key[]" value="'. $key. '">';
		
	print "<button class='' name='submit' type='submit'>Submit</button></form><body></html>";
}

if(isset($_POST["submit"])){
	$key = $_POST["key"];
	$answer = [];
	$retCnt = 0;
	for($i = 1 ; $i <= 20 ; $i++) { 
		$postarray = 'Ques'.$i; 
		array_push($answer, $_POST[$postarray]); 
	}
	print_r($answer);
	print "<br/><br/>";
	print_r($key);
	print "<br/><br/>";
	
	foreach($answer as $index=>$value){
		if($key[$index] == $value)
			$retCnt += 1;
	}
	print "Your score: $retCnt";
}
?>

</body>
</html>