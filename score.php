<?php
session_start();
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
$sql1 = "select users.ID,max(likes) as theid,(avg(likes)+avg(comments)/friends) as score from memory,users where memory.ID=users.ID and users.ID in (select ID from friends where fID='". $info['id']. "') group by users.ID order by score";


$sql2 = "select users.ID,max(likes) as theid,(avg(likes)+avg(comments)/friends) as score from memory,users where memory.ID=users.ID and users.ID in (select ID from friends where fID='". $info['id']. "') group by users.ID order by score";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["ID"]. " - : " . "score:".$row["score"] . "<br>";
    }
} else {
    echo "0 results";
}
?>
