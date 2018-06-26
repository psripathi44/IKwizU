<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analytics by Token</title>
<link rel="icon" type="image/ico" href="Images/favicon.ico">
<link href="../CSS/styles.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Tajawal" rel="stylesheet">

<style>
#results {
	min-width: 310px;
	max-width: 800px;
	height: 400px;
	margin: 20px auto;
}
</style>
</head>
<body>
<header>
	<nav class="navBar">
	  <nav class="menuwrapper">
		<div class="logo"><a href="/IKwizU"> IKwizU </a></div>
			<input type="checkbox" id="menu-toggle" />
			<label for="menu-toggle" class="label-toggle"></label>
			<ul>
				<li><a href="../about/" >About</a></li>
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
	</form><br/>
	
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<div id="results"></div>
	
	<?PHP
	if(isset($_POST["displayStandings"])){
		require "db.inc";
		if (!($connection = @ mysqli_connect("localhost", $username, $password)))//Connecting to localhost
			die("Could not connect to database"); 

		if (!mysqli_select_db($connection, $databaseName)) //connecting to Database using "db.inc"
			showerror($connection);
			
		$lToken = $_POST['inpToken'];
		
		print "<script type='text/javascript'>	
			Highcharts.chart('results', {
			title: {
				text: 'Analytics for Token $lToken by month'
			},

			subtitle: {
				text: 'Source: IKwizU DB'
			},

			yAxis: {
				title: {
					text: 'Number of challengers attempted'
				}
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle'
			},

			plotOptions: {
				series: {
					label: {
						connectorAllowed: true
					},
					pointStart: 2010
				}
			},

			series: [{
				name: 'Token $lToken',
				data: [45,12,48,77,56,45,15,71,13,58]
			}]

		});
		</script>";
	}
	?>
</div>
</left>
</div>
</body>
</html>