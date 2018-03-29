<?php
require 'vendor/autoload.php';


	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
	}

	// Access-Control headers are received during OPTIONS requests
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

		}

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
			header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

		} 
		exit(0);
	}

	$app = new Slim\App();
$app->post('/course' , function($request , $response , $args){ //เงือ่ไข
		
		include 'conn.php';

	$json = $request->getBody(); //POST
	$jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
	$score_id = isset($jsonArr['score_id'])?$jsonArr['score_id']:"";
    $lesson_id = isset($jsonArr['lesson_id'])?$jsonArr['lesson_id']:"";
	
    $arr = array();
    	if(!empty($score_id))
			{
				if (!empty($lesson_id)) 
					{
						$sql_lesson = "SELECT * FROM tbl_score WHERE active = 'y' AND lesson_id = '$lesson_id' AND score_id = '$score_id'";
					}
						else
							{
								$sql_lesson = "SELECT * FROM tbl_score WHERE active = 'y' AND score_id = '$score_id' ";
							}
			  	// $sql_lesson = "SELECT * FROM tbl_score WHERE active = 'y'";		
			}
		$lesson = $conn->query($sql_lesson);
		// var_dump($lesson);
        $lessons = $lesson->fetch_assoc();

                $arr["results"] = "successfully";
                $arr["data"] = $lessons;
		
    	echo json_encode($arr , JSON_UNESCAPED_UNICODE);

		$conn->close();
	});
	$app->run();
?>