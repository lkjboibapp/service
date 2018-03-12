<?php
require 'vendor/autoload.php';

$app = new Slim\App();

$app->get('/getabout', function($request , $response , $args)  {

	include 'conn.php';

	$json = $request->getBody(); //POST
	$jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
    $about_id  = isset($jsonArr["about_id"])?$jsonArr["about_id"]:"";
    $about_titleData = isset($jsonArr["about_title"])?$jsonArr["about_title"]:"";
    $about_detailData = isset($jsonArr["about_detail"])?$jsonArr["about_detail"]:""; 
    $create_byData= isset($jsonArr["create_by"])?$jsonArr["create_by"]:"";
    $update_byData= isset($jsonArr["update_by"])?$jsonArr["update_by"]:"";
    $create_dateData = date("Y-m-d H:i:s");
    $update_dateData = date("Y-m-d H:i:s"); 

     $sql = "SELECT * FROM tbl_about WHERE active = 'y'";
                    
	$result = $conn->query($sql);


	$arr = array();
	if ($result->num_rows > 0) 
    {
        $arr["result"] = "successfully";

    // output data of each row
            while($row = $result->fetch_assoc()) 
            {

        $about_id = $row["about_id"];
            	$about_title = $row["about_title"];
                $about_detail = $row["about_detail"];
                $create_data = $row["create_date"];
                $create_by = $row["create_by"];
                $update_date = $row["update_date"];
                $update_by = $row["update_by"];
                $active = $row["active"];

            $data = (object)array('about_id'=> $about_id, 
                                 'about_title' => $about_title,
                				'about_detail' => $about_detail,
        						'create_data' => $create_data,
        						'create_by' => $create_by,
        						'update_date' => $update_date,
        						'update_by' => $update_by,
        						'active' => $active
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
