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
		$sql="insert into friends(ID,fID) values('"
			.mysqli_real_escape_string($conn,$info['id'] )
			."','"
			.mysqli_real_escape_string($conn,$house->id)
			."')";
		$conn->query($sql);

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

 echo "done";
?>

