<html>
<head>
<title> IKwizU </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<link href="CSS/styles.css" rel="stylesheet">
<script src="JS/inpValidate.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
	<h2 style="margin-left: 10%;"><a href="index.php">IKwizU</a></h2>
</header>
<div id="main">
	<span class="mainHeading">Welcome to the TRIVIA Quiz by IKwizU.</span><br/><br/>
	Here you can select the category the questions that would be based on, and the level of difficulty and type of questions as well.<br/>
	Once submitting the inputs, you have an option to take the quiz to test yourself and see where are at in your fav genre.<br/><br/> 
	Once you take the quiz, you can challenge your friends by sharing the same quiz to them via Facebook, Twitter, and other platforms, and see if they can beat you.<br/><br/>
	<div class="formBox">
		<form action="quizshere.php" method="post" name="inputsForm" onSubmit="return quizInpValidation();">
			Select Category:<br/>
			<select id="inpCategory" name="inpCategory" class="inpDD">
				<option value="any">Any category</option>
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
			</select><br/><br/>

			<br/>
			<button class="formButton" name="proceed" type="submit"><i class="fa fa-angle-double-right"></i> &nbsp;Click here to proceed</button>
			<div class="error" id="errContainer"></div>
		</form>
	</div>
</div>
</body>
</html>
