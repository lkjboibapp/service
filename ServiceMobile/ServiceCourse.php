<?php
require 'vendor/autoload.php';

$app = new Slim\App();

$app->get('/getCategory' , function($request , $response , $args){ //เงือ่ไข

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
     $id = isset($jsonArr['id'])?$jsonArr['id']:"";
    
	 	if($id == "")
		 	{
		        $sql = "SELECT * FROM tbl_category_course WHERE active = '1'";
		    }
		    	else 
		    		{ //WHERE Condition SQL Start!
				        $sql = "SELECT * FROM tbl_category_course WHERE id = '$id' AND active = '1'";
		    		}
    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
				$arr["results"] = "successfully";
				while($row = $result->fetch_assoc())
				{
					  	$id = $row['id'];
        				$cate_id = $row['cate_id'];
        				$name = $row['name'];
        				$pic = $row['pic'];
        				$create_at = $row['create_at'];
        				$create_by = $row['create_by'];
        				$update_at = $row['update_at'];
        				$update_by = $row['update_by'];
        				$active = $row['active'];

					$data = (object)array('id' => $id,
					                              'cate_id' => $cate_id,
					                              'name' => $name,
					                               'pic' => $pic,
					                               'create_at' => $create_at,
					                               'update_by' => $update_by,
					                               'update_at' => $update_at,
					                               'create_by' => $create_by,
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