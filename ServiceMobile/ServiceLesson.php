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

$app->get('/getLesson/{course_id}' , function($request , $response , $args){ //เงือ่ไข

	$course_id = $args['course_id'];

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
    //  $course_id = isset($jsonArr['course_id'])?$jsonArr['course_id']:"";
    
	 	if($course_id == "")
		 	{
		        $sql = "SELECT * FROM tbl_lesson WHERE active = 'y'" ;
		    }
		    	else 
		    		{ //WHERE Condition SQL Start!
				        $sql = "SELECT * FROM tbl_lesson WHERE course_id = '$course_id' AND active = 'y'";
		    		}
    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
				$arr["results"] = "successfully";
				while($row = $result->fetch_assoc())
				{
					  	$course_id = $row['course_id'];
        				$id = $row['id'];
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
        				$view_all = $row['view_all'];
        				$status = $row['status'];
        				$lesson_no = $row['lesson_no'];
        				$active = $row['active'];

					$data = (object)array('course_id' => $course_id,
					                              'id' => $id,
					                              'title' => $title,
					                               'description' => $description,
					                               'content' => $content,
					                               'time_test' => $time_test,
					                               'cate_percent' => $cate_percent,
												   'cate_amount' => $cate_amount,
					                               'header_id' => $header_id,
					                               'image' => $image,
					                               'create_date' => $create_date,
                                                   'create_by' => $create_by,
                                                   'update_date' => $update_date,
                                                   'update_by' => $update_by,
                                                   'view_all' => $view_all,
                                                   'status' => $status,
                                                   'lesson_no' => $lesson_no,
                                                   'active' => $active,
                                                   
					                            );
					$arr["data"][] = $data;
				}
					// mysqli_close($conn);
				// return json_encode($arr);
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