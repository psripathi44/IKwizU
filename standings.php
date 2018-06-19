<?PHP
	session_start();
?>
<html>
<head>
<title> IKwizU - Standings </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<link href="CSS/styles.css" rel="stylesheet">
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
				<li><a href="#" >About</a></li>
				<li class="current"><a href="standings.php" >Check Standings </a></li>
				<li><a href="bulkOptions.php" >For group at large?</a></li>
				<li><a href="feedback.php">Feedback</a></li>
			</ul>
	  </nav>
	</nav>
</header>
<div id="main">
<left class="standings">
	<br/><span class="cfTitle"> Check your standings here! </span><br/>
	<p>You can use this page to check your standings, basis the token.</p><br/>
	<form method="POST" action="" enctype="multipart/form-data" style="width: 100%;">
		<input type="number" name="inpToken" value="" placeholder="Enter token here.." class = "standingsFormTB" required/>
		<input type="submit" name ="displayStandings" value="Display Standings" class="standingsFormButton"/>
	</form>
	<br/>
	<div id="results">
	<?PHP
	if(isset($_POST["displayStandings"])){
		require "db.inc";
		if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
			die("Could not connect to database"); 

		if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
			showerror($connection);
			
		$lToken = $_POST['inpToken'];
		$i = 0;
		try{
			$standingsQueryStmt = "select name, email, score, inserted_date from scorebytoken where token = $lToken order by score DESC, inserted_date ASC";
			$standingsResults = @ mysqli_query ($connection, $standingsQueryStmt);
			print"<table class='standingsTable'>
					<thead>
						<th> Standing </th>
						<th> Name </th>
						<th> Email ID </th>
						<th> Score </th>
						<th> Test taken date </th>
					</thead>";
			while ($record = @ mysqli_fetch_array($standingsResults)){
				$i += 1;
				print "<tr>";
				print "<td>$i</td>";
				print "<td>".$record["name"]."</td>";
				print "<td>".$record["email"]."</td>";
				print "<td>".$record["score"]."</td>";
				print "<td>".$record["inserted_date"]."</td>";
				print "</tr>";
			}
		}
		catch(Exception $e){
			print "Error occured while processing your request - ". $e;
		}
	}
	?>
	</table>
	</div>
</left>
<right class="standings">
<?PHP
print "Popular quiz by tokens -";
?>
</right>
</div>
</body>
</html>