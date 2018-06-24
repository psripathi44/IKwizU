<?PHP
	session_start();
	require "db.inc";
		if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
			die("Could not connect to database"); 

		if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
			showerror($connection);
?>
<html>
<head>
<title> Standings </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">
<link href="../CSS/styles.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
	<nav class="navBar">
	  <nav class="menuwrapper">
		<div class="logo"><a href="/IKwizU">IKwizU</a></div>
			<input type="checkbox" id="menu-toggle" />
			<label for="menu-toggle" class="label-toggle"></label>
			<ul>
				<li><a href="#" >About</a></li>
				<li class="current"><a href="#" >Check Standings </a></li>
				<li><a href="../feedback/">Feedback</a></li>
				<li><a href="../analytics/">Analytics</a></li>
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
		$lToken = $_POST['inpToken'];
		$i = 0;
		try{
			$standingsQueryStmt = "select name, email, score, inserted_date from scorebytoken where token = $lToken order by score DESC, inserted_date ASC";
			$standingsResults = @ mysqli_query ($connection, $standingsQueryStmt);
			$noOfRowsFound = @ mysqli_num_rows($standingsResults);
			if($noOfRowsFound == 0){
				print "<span class='standingsStyle'>Results: </span><br/>";
				print "There's no data available with input token - $lToken";
			} else {
				print "<span class='standingsStyle'>Results: </span><br/><br/>";
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
				print "</table>";
			}
		}
		catch(Exception $e){
			print "Error occured while processing your request - ". $e;
		}
	}
	?>
	</div>
</left>
<right class="standings">
<?PHP
print "Try these quizes if you havent't tried already. Sorted from Hardest to moderate.<br/><br/>";
	try{
		$i = 0;
		$tokenLinksQueryStmt = "select distinct token from scorebytoken order by score DESC LIMIT 10";
		$tokenLinksResults = @ mysqli_query ($connection, $tokenLinksQueryStmt);
		while ($record = @ mysqli_fetch_array($tokenLinksResults)){
			$i += 1;
			print "<i class='fa fa-angle-double-right'></i><a class='others' href = 'http://localhost/IKwizU/challenge/".$record["token"]."'> Attempt challenge ". $record["token"]."</a><br/><br/>";
		}
	}
	catch(Exception $e){
		print "Error occured while fetching the quizes that couldn't be cracked - ". $e;
	}
?>
</right>
</div>
</body>
</html>