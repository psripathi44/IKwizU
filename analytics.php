<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analytics by Token</title>
<link rel="icon" type="image/ico" href="Images/favicon.ico">
<link href="../CSS/styles.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
</head>
<body>
<header>
	<nav class="navBar">
	  <nav class="menuwrapper">
		<div class="logo"><a href="/IKwizU"> IKwizU </a></div>
			<input type="checkbox" id="menu-toggle" />
			<label for="menu-toggle" class="label-toggle"></label>
			<ul>
				<li><a href="#" >About</a></li>
				<li><a href="../standings/" >Check Standings </a></li>
				<li><a href="../feedback/">Feedback</a></li>
				<li class="current"><a href="#">Analytics</a></li>
			</ul>
	  </nav>
	</nav>
</header>
<div id = "main">
<left>
	<div style="font-family: 'Tajawal', sans-serif;">
		<br/><span class="cfTitle"> Analytics by token! </span><br/>
		<p>Put in the token in the text box below and check the stats related to a token .</p><br/>
		<form method="POST" action="" enctype="multipart/form-data" style="width: 100%;">
			<input type="number" name="inpToken" value="" placeholder="Enter token here.." class = "standingsFormTB" required/>
			<input type="submit" name ="displayStandings" value="Analyze" class="standingsFormButton"/>
		</form>
		<br/>
		<div id="results">
			
		</div>
	</div>
</left>
</div>
</body>
</html>