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

$app->post('/getFile' , function($request , $response , $args){ //เงือ่ไข

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
     $lesson_id = isset($jsonArr['lesson_id'])?$jsonArr['lesson_id']:"";
    
	 	if($lesson_id == "")
		 	{
		        $sql = "SELECT * FROM tbl_file WHERE active = 'y'";
		    }
		    	else 
		    		{ //WHERE Condition SQL Start!
				        $sql = "SELECT * FROM tbl_file WHERE lesson_id = '$lesson_id' AND active = 'y'";
		    		}
    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
				$arr["results"] = "successfully";
				while($row = $result->fetch_assoc())
				{
					  	$lesson_id = $row['lesson_id'];
        				$id = $row['id'];
        				$file_name = $row['file_name'];
        				$filename = $row['filename'];
        				$create_date = $row['create_date'];
        				$length = $row['length'];
        				$file_position = $row['file_position'];
                        $create_by = $row['create_by'];
        				$update_by = $row['update_by'];
        				$update_date = $row['update_date'];
        				$active = $row['active'];

					$data = (object)array('lesson_id' => $lesson_id,
					                              'id' => $id,
					                              'file_name' => $file_name,
					                               'filename' => $filename,
					                               'create_date' => $create_date,
					                               'update_by' => $update_by,
					                               'file_position' => $file_position,
												   'length' => $length,
					                               'create_by' => $create_by,
					                               'update_date' => $update_date,
					                               'active' => $active
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