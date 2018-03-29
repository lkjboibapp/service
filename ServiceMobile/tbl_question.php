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

$app->post('/get' , function($request , $response , $args){ //เงือ่ไข

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
	 $ques_type = isset($jsonArr['ques_type'])?$jsonArr['ques_type']:"";
     $group_id = isset($jsonArr['group_id'])?$jsonArr['group_id']:"";
	 
    
	 	if(empty($group_id))
		 	{
		        $sql = "SELECT * FROM tbl_question WHERE active = 'y'" ;
		    }
		    	else 
					{
						if (!empty($ques_type)) 
							{
								$sql = "SELECT * FROM tbl_question WHERE group_id = '$group_id' AND active = 'y' AND ques_type = '$ques_type'";		
							}
				        $sql = "SELECT * FROM tbl_question WHERE group_id = '$group_id' AND active = 'y'";
		    		}
    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
				$arr["results"] = "successfully";
				while($row = $result->fetch_assoc())
				{
					  	$ques_id = $row['ques_id'];
        				$group_id = $row['group_id'];
        				$ques_type = $row['ques_type'];
        				$test_type = $row['test_type'];
        				$difficult = $row['difficult'];
        				$ques_title = $row['ques_title'];
        				$ques_explain = $row['ques_explain'];
        				$create_date = $row['create_date'];
        				$create_by = $row['create_by'];
                        $update_date = $row['update_date'];
        				$update_by = $row['update_by'];
        				$active = $row['active'];
                        
					$data = (object)array('ques_id' => $ques_id,
					                              'group_id' => $group_id,
					                              'ques_type' => $ques_type,
					                               'test_type' => $test_type,
					                               'difficult' => $difficult,
					                               'ques_title' => $ques_title,
                                                   'ques_explain' => $ques_explain,
                                                   'create_date' => $create_date,
                                                   'create_by' => $create_by,
                                                   'update_date' => $update_date,
                                                   'update_by' => $update_by,
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