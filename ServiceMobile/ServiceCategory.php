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

$app->get('/getCategory' , function($request , $response , $args){ //เงือ่ไข

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
     $cate_id = isset($jsonArr['cate_id'])?$jsonArr['cate_id']:"";
    
	 	if($cate_id == "")
		 	{
		        $sql = "SELECT * FROM tbl_category WHERE active = 'y'";
		    }
		    	else 
		    		{ //WHERE Condition SQL Start!
				        $sql = "SELECT * FROM tbl_category WHERE cate_id = '$cate_id' AND active = 'y'";
		    		}
    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
				$arr["results"] = "successfully";
				while($row = $result->fetch_assoc())
				{
					  	$cate_id = $row['cate_id'];
        				$cate_title = $row['cate_title'];
        				$cate_short_detail = $row['cate_short_detail'];
        				$cate_detail = $row['cate_detail'];
        				$create_date = $row['create_date'];
        				$cate_image = $row['cate_image'];
        				$cate_show = $row['cate_show'];
						$create_by = $row['create_by'];
        				$update_by = $row['update_by'];
        				$update_date = $row['update_date'];
        				$special_category = $row['special_category'];
        				$active = $row['active'];

					$data = (object)array('cate_id' => $cate_id,
					                              'cate_title' => $cate_title,
					                              'cate_short_detail' => $cate_short_detail,
					                               'cate_detail' => $cate_detail,
					                               'create_date' => $create_date,
					                               'update_by' => $update_by,
					                               'cate_show' => $cate_show,
												   'cate_image' => $cate_image,
					                               'cate_show' => $cate_show,
					                               'create_by' => $create_by,
					                               'update_date' => $update_date,
					                               'special_category' => $special_category,
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