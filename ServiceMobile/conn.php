<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_plm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
	mysqli_set_charset($conn,"utf8");
	date_default_timezone_set("Asia/Bangkok");
header('Access-Control-Allow-Origin: *');  
	
	// echo "Connection Successful!";
}


/* API key encryption */
function apiToken($session_uid)
{
	// var_dump("session_uid",$session_uid);
$key=md5(SITE_KEY.$session_uid);
// var_dump($key);
return hash('sha256', $key);
}
?>