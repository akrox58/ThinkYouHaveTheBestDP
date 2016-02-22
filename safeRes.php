<?php

// added in v4.0.0
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
session_start();
$app_secret='76dac9bb26f6cc18a83605f503142660';
FacebookSession::setDefaultApplication( '1685628418393637', $app_secret);
FacebookSession::enableAppSecretProof(true);

$servername = "localhost";
$username = "root";
$password = "akroshram";
$dbname = "HappyBox";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$res = $_SESSION['res'];
$info= $_SESSION['array']; 
$r=$res->getGraphObject()->asArray();

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
if ($result->num_rows == 0)
	$conn->query($sql);
	
foreach($r['data'] as $key){
	
	$output="No Caption";
	if(!(is_null($key->name))){
		$file = './detect/word.txt';
		$current =$key->name;
		file_put_contents($file, $current);
		$command = escapeshellcmd('/var/www/html/HappyBox/detect/def2.py');
		$output = shell_exec($command);
	}
	
	$query = "SELECT ID from memory where ID= '".mysqli_real_escape_string($conn,$info['id'])."' and  pID= '".mysqli_real_escape_string($conn,$key->id)."'";
	$sql="insert into memory(toID,fromID,pID,source,caption,mood,likes,comments) values('"
		.mysqli_real_escape_string($conn,$info['id'] )
		."','"
		.mysqli_real_escape_string($conn,$key->from->id )
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
	foreach($key->tags as $donkey)
		foreach($donkey as $don)
			if(!is_null($don)){
				$sql="insert into memuser(ID,pID) values('"
					.mysqli_real_escape_string($conn,$don->id )
					."','"
					.mysqli_real_escape_string($conn,$key->id)
					."')";
				
					$conn->query($sql);
			}
	
}

/*while ($next_request = $res->getRequestForNextPage()){
	$res = $next_request->execute();
	$r=$res->getGraphObject()->asArray();
	foreach($r['data'] as $key){
/*		if(!(is_null($key->name))){
		echo $key->name . "<br>";
		$file = './detect/word.txt';
		$current =$key->name;
		file_put_contents($file, $current);
		$command = escapeshellcmd('/var/www/html/HappyBox/detect/def2.py');
		$output = shell_exec($command);
		echo $output;
		echo "<br>";
	}

	}  	
*/ 
 echo "done";
?>

