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

$app->get('/usability', function($request , $response , $args)  {

	include 'conn.php';

	$json = $request->getBody(); //POST
	$jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
    $usa_id  = isset($jsonArr["usa_id"])?$jsonArr["usa_id"]:"";
    $usa_titleData = isset($jsonArr["usa_title"])?$jsonArr["usa_title"]:"";
    $seach_id ="";
    $seach_title ="";

	if(!empty($usa_id))
            {
               $seach_id = " AND usa_id = '$usa_id'";
            }
    if(!empty($usa_titleData))
            {
               $seach_title = " AND usa_title = '$usa_titleData'";
            }
                
    $sql = "SELECT * FROM tbl_usability WHERE active = 'y'".$seach_id.$seach_title;
                    
	$result = $conn->query($sql);


	$arr = array();
	if ($result->num_rows > 0) 
    {
        $arr["result"] = "successfully";

    // output data of each row
            while($row = $result->fetch_assoc()) 
            {

            	$usa_id = $row["usa_id"];
            	$usa_title = $row["usa_title"];
                $usa_detail = $row["usa_detail"];
                $create_data = $row["create_date"];
                $create_by = $row["create_by"];
                $update_date = $row["update_date"];
                $update_by = $row["update_by"];
                $active = $row["active"];

            $data = (object)array('usa_id'=> $usa_id, 
                                 'usa_title' => $usa_title,
                				'usa_detail' => $usa_detail,
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



$app->post('/getupdate' , function($request , $response , $args){

	include 'conn.php';

	$json = $request->getBody(); //POST
	$jsonArr = json_decode($json, true);
    $usa_idData = $jsonArr['usa_id'];
    $usa_detailData = isset($jsonArr["usa_detail"])?$jsonArr["usa_detail"]:""; 
    $usa_titleData = isset($jsonArr["usa_title"])?$jsonArr["usa_title"]:"";
    $update_byData= isset($jsonArr["update_by"])?$jsonArr["update_by"]:"";
    // $update_dateData = $jsonArr['update_date'];
    
    $update_dateData = date("Y-m-d H:i:s");

    if($usa_idData == "")
        {
            $arr["result"] =  "Error updating record: " . $conn->error;
        }
        else{ $sql = "UPDATE tbl_usability SET usa_title ='$usa_titleData'
                                        ,usa_detail ='$usa_detailData'
                                        ,update_date ='$update_dateData' 
                                        ,update_by = '$update_byData'
                                WHERE usa_id ='$usa_idData'";
                                if($conn->query($sql)== TRUE){
                                $arr["result"] = "Record updated successfully";

                                }
                                else
                                {
                                     $arr["result"] = "Error updating record: " .$conn -> error;
                                }
      }
    
   
    echo json_encode($arr , JSON_UNESCAPED_UNICODE);
	$conn->close();
});

$app->post('/getdelete' , function($request , $response , $args){

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true);
    $usa_idData = isset($jsonArr["usa_id"])?$jsonArr["usa_id"]:"";

        if($usa_idData == ""){$arr["results"] = "failed: "  . $conn->error;}
            else{ $sql = "UPDATE tbl_usability SET active ='n' WHERE usa_id ='$usa_idData'";

      if ($conn->query($sql) === TRUE) {
        $arr["results"] = "success";

        } else {
        $arr["results"] = "Error updating record: " . $conn->error;
            $arr["data"] = "failed";
}
}
     
   
    echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    $conn->close();
});

$app->post('/getinsert' , function($request , $response , $args){

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true);
     $usa_detailData = isset($jsonArr["usa_detail"])?$jsonArr["usa_detail"]:""; 
    $usa_titleData = isset($jsonArr["usa_title"])?$jsonArr["usa_title"]:"";
    $create_byData= isset($jsonArr["create_by"])?$jsonArr["create_by"]:"";
   
    $create_dateData = date("Y-m-d H:i:s");
    $update_dateData = date("Y-m-d H:i:s"); 
    
    if(!empty($usa_titleData) OR !empty($usa_detailData)){
    $sql = "INSERT INTO tbl_usability (usa_title,
                                        usa_detail,
                                        create_date,
                                        create_by)
            VALUES ('$usa_titleData',
                    '$usa_detailData',
                    '$create_dateData',
                    '$create_byData')";

      if ($conn->query($sql) === TRUE) {
      $arr["result"] = "success";
       $arr["data"] = "New  updated successfully";
        }
    }
     else {
        $arr["result"] = "failed" . $conn->error;
        $arr["data"] = "failed";
}
   
    echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    $conn->close();
});


$app->run();
?>
