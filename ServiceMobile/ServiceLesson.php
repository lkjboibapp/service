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

	$app->post('/getLesson' , function($request , $response , $args){ //เงือ่ไข
		
		include 'conn.php';

	$json = $request->getBody(); //POST
	$jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
    $course_id = isset($jsonArr['course_id'])?$jsonArr['course_id']:"";
	
		if(!empty($course_id))
			{
				$sql = "SELECT * FROM tbl_lesson WHERE course_id = '$course_id' AND active = 'y'";
			}
				else 
					{
						$sql = "SELECT * FROM tbl_lesson WHERE active = 'y'" ;
					}
		$result = $conn->query($sql);
		$arr = array();
		
		if ($result->num_rows > 0) 
			{
				$arr["results"] = "successfully";
				while($row = $result->fetch_assoc())
					{
						$id = $row['id'];
						$course_id = $row['course_id'];
						$title = $row['title'];
						$description = $row['description'];
						$content = $row['content'];
						$cate_amount = $row['cate_amount'];
						$cate_percent = $row['cate_percent'];
						$header_id = $row['header_id'];
						$time_test = $row['time_test'];
						$image = $row['image'];
						$create_date = $row['create_date'];
						$create_by = $row['create_by'];
						$update_date = $row['update_date'];
						$update_by = $row['update_by'];
						$active = $row['active'];
						$view_all = $row['view_all'];
						$status = $row['status'];
						$lesson_no = $row['lesson_no'];

							$data = (object)array
								(
									'id' => $id,
									'course_id' => $course_id,
									'title' => $title,
									'description' => $description,
									'content' => $content,
									'cate_amount' => $cate_amount,
									'cate_percent' => $cate_percent,
									'header_id' => $header_id,
									'time_test' => $time_test,
									'image' => $image,
									'create_date' => $create_date,
									'create_by' => $create_by,
									'update_date' => $update_date,
									'update_by' => $update_by,
									'active' => $active,
									'view_all' => $view_all,
									'status' => $status,
									'lesson_no' => $lesson_no
							);
						$arr["data"][] = $data;
					}
			}

			else
	     		{
	    			$arr["results"] = "error";
	    			$arr["data"] = "failed";
	    		}

		echo json_encode($arr , JSON_UNESCAPED_UNICODE);

		$conn->close();
	});
	$app->run();
?>