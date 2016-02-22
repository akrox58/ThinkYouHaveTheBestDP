<?php


session_start();

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "HappyBox";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$info= $_SESSION['array']; 

$query = "SELECT ID from users where ID= '".mysqli_real_escape_string($conn,$info['id'])."'";
$sql="insert into users(ID,email,name,friends) values('"
		.mysqli_real_escape_string($conn,$info['id'] )
		."','"
		.mysqli_real_escape_string($conn,$info['email'])
		."','"
		.mysqli_real_escape_string($conn,$info['name'])
		."','"
		.mysqli_real_escape_string($conn,$info['friends']->summary->total_count)
		."')";

$result = $conn->query($query);
if ($result->num_rows == 0)
	$conn->query($sql);



foreach($info['friends']->data as $house){


		$query = "SELECT ID from users where ID= '".mysqli_real_escape_string($conn,$house->id)."' and fID='" . mysqli_real_escape_string($conn,$info['id']) . "'";
		$result = $conn->query($query);
		if ($result->num_rows == 0){
			$sql="insert into friends(ID,fID) values('"
				.mysqli_real_escape_string($conn,$house->id)
				."','"
				.mysqli_real_escape_string($conn,$info['id'] )
				."')";
			$conn->query($sql);
		}
		
		$query = "SELECT ID from users where ID= '".mysqli_real_escape_string($conn,$house->id)."' and fID='" . mysqli_real_escape_string($conn,$info['id']) . "'";
		$result = $conn->query($query);
		if ($result->num_rows == 0){
			$sql="insert into friends(ID,fID) values('"
				.mysqli_real_escape_string($conn,$info['id'] )
				."','"
				.mysqli_real_escape_string($conn,$house->id)
				."')";
			$conn->query($sql);
		}
		
		$query = "SELECT ID from users where ID= '".mysqli_real_escape_string($conn,$info['id'])."' and fID='" . mysqli_real_escape_string($conn,$info['id']) . "'";
		$result = $conn->query($query);
		if ($result->num_rows == 0){
			$sql="insert into friends(ID,fID) values('"
				.mysqli_real_escape_string($conn,$info['id'] )
				."','"
				.mysqli_real_escape_string($conn,$info['id'])
				."')";
			$conn->query($sql);
		}

}


foreach($info['albums']->data as $h){
	if($h->name="Profile Pictures"){	
		foreach($h->photos as $house){
			foreach($house as $key){
			
				$query = "SELECT ID from memory where photoID= '".mysqli_real_escape_string($conn,$key->id)."'";
				$result = $conn->query($query);
				if ($result->num_rows == 0){
					$output="No Caption";
					if(!(is_null($key->name))){
						$file = './detect/word.txt';
						$current =$key->name;
						file_put_contents($file, $current);
						$command = escapeshellcmd('/var/www/html/HappyBox/detect/def2.py');
						$output = shell_exec($command);
					}
	
					if(!(is_null($key))){
						$sql="insert into memory(ID,photoID,source,caption,mood,likes,comments) values('"
							.mysqli_real_escape_string($conn,$info['id'] )
							."','"
							.mysqli_real_escape_string($conn,$key->id)
							."','"
							.mysqli_real_escape_string($conn,$key->images[0]->source)
							."','"
							.mysqli_real_escape_string($conn,$key->name)
							."','"
							.mysqli_real_escape_string($conn,$output)
							."','"
							.mysqli_real_escape_string($conn,$key->likes->summary->total_count)
							."','"
							.mysqli_real_escape_string($conn,$key->comments->summary->total_count)
							."')";
		
						$conn->query($sql);
					}
		
	
				}
			}
		}
	}
}
/*
$sql1 = "select users.ID,max(likes) as theid,(avg(likes)+avg(comments)/friends) as score from memory,users where memory.ID=users.ID and users.ID in (select ID from friends where fID='". $info['id']. "') group by users.ID order by score limit 3";

$result = $conn->query($sql1);
var_dump($result);

$row = mysql_fetch_row($result);

var_dump($row);
 echo "done<br><Br><br>";
select users.ID,source,max(likes) as theid,(avg(likes)+avg(comments)/friends) as score from memory,users where memory.ID=users.ID and users.ID in (select ID from friends where fID='10206707607910180') group by users.ID order by score limit 3;


SELECT source,caption,mood
FROM   memory s1
WHERE  ID in (SELECT ID
		  from memory,users 
              where memory.ID=users.ID
                         );
             
             
select users.ID,source,max(likes) as theid,(avg(likes)+avg(comments)/friends) as score from memory,users where memory.ID=users.ID and users.ID in (select ID from friends where fID='10206707607910180') group by users.ID order by score limit 3;


SELECT source,caption,mood
FROM   memory s1
WHERE  ID in (SELECT ID
		  from memory,users 
              where memory.ID=users.ID
                         );
             
             
             
SELECT source,caption,mood
FROM  memory s1
INNER JOIN
     (SELECT users.ID
              from memory,users 
              where memory.ID=users.ID and users.ID in (select ID from friends where fID='10206707607910180') group by users.ID limit 3
             ) as v2
  ON s1.ID = v2.ID;*/
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>One Page Wonder - Start Bootstrap Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/one-page-wonder.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="login.html">Sign Out</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#about">Third </a>
                    </li>
                    <li>
                        <a href="#services">Second </a>
                    </li>
                    <li>
                        <a href="#contact">First</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Full Width Image Header -->
    <header class="header-image">
        <div class="headline">
            <div class="container">
                <h1>Where Do You Stand</h1>
                <h2>Do You Think You Have The Best DP?</h2>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="container">

        <hr class="featurette-divider">

        <!-- First Featurette -->
        <div class="featurette" id="about">
            <img class="featurette-image img-circle img-responsive pull-right" src="http://placehold.it/500x500">
            <h2 class="featurette-heading">The Third Person
                <span class="text-muted">Will Catch Your Eye</span>
            </h2>
            <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
        </div>

        <hr class="featurette-divider">

        <!-- Second Featurette -->
        <div class="featurette" id="services">
            <img class="featurette-image img-circle img-responsive pull-left" src="http://placehold.it/500x500">
            <h2 class="featurette-heading">Second Person
                <span class="text-muted">Is Pretty Cool Too.</span>
            </h2>
            <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
        </div>

        <hr class="featurette-divider">

        <!-- Third Featurette -->
        <div class="featurette" id="contact">
            <img class="featurette-image img-circle img-responsive pull-right" src="http://placehold.it/500x500">
            <h2 class="featurette-heading">The First Picture
                <span class="text-muted">Will Seal the Deal.</span>
            </h2>
            <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
        </div>

        <hr class="featurette-divider">

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2016</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
