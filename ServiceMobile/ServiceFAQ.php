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

$app->get('/getfaq_type' , function($request , $response , $args){ //เงือ่ไขหน้าบ้าน

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
    $faq_type_id = $jsonArr['faq_type_id'];
  
	 	if($faq_type_id  == "")
		 	{
		        $sql = "SELECT * FROM cms_faq_type WHERE active = 'y'";
		    }
		    	else 
		    		{ //WHERE Condition SQL Start!
				        $sql = "SELECT * FROM cms_faq_type WHERE faq_type_id = '$faq_type_id'  AND active = 'y'";
		    		}

    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
				$arr["results"] = "successfully";

			    // output data of each row
			    while($row = $result->fetch_assoc())
			    	{
				        $faq_type_id = $row['faq_type_id'];
        				$faq_type_title_TH = $row['faq_type_title_TH'];
        				$create_date = $row['create_date'];
        				$create_by = $row['create_by'];
        				$update_date = $row['update_date'];
        				$update_by = $row['update_by'];
        				$active = $row['active'];


					        $data = (object)array('faq_type_id' => $faq_type_id,
					                              'faq_type_title_TH' => $faq_type_title_TH,
					                               'create_date' => $create_date,
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
//
$app->get('/getfaq' , function($request , $response , $args){ //เงือ่ไขหน้าบ้าน

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
    $faq_type_id = $jsonArr['faq_type_id'];
  
	 	if($faq_type_id  == "")
		 	{
		        $sql = "SELECT * FROM cms_faq WHERE active = 'y'";
		    }
		    	else 
		    		{ //WHERE Condition SQL Start!
				        $sql = "SELECT * FROM cms_faq WHERE faq_type_id = '$faq_type_id'  AND active = 'y'";
		    		}

    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
				$arr["results"] = "successfully";

			    // output data of each row
			    while($row = $result->fetch_assoc())
			    	{
				        $faq_type_id = $row['faq_type_id'];
        				$faq_THtopic = $row['faq_THtopic'];
        				$faq_THanswer = $row['faq_THanswer'];
        				$create_date = $row['create_date'];
        				$create_by = $row['create_by'];
        				$update_date = $row['update_date'];
        				$update_by = $row['update_by'];
        				$active = $row['active'];


					        $data = (object)array('faq_type_id' => $faq_type_id,
					                              'faq_THtopic' => $faq_THtopic,
					                               'faq_THanswer' => $faq_THanswer,
					                               'create_date' => $create_date,
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

////Delate   
$app->post('/deletefaq_type', function($request , $response , $args)  
	{
	    include 'conn.php';
	        $json = $request->getBody(); //POST
	        $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
			$faq_type_id = $jsonArr['faq_type_id'];
		if ($faq_type_id == "") {
			$arr["results"] = "failed: " . $conn->error;
	    				$arr["data"] = "failed";
		}
		else
		{
			$sql = " UPDATE cms_faq_type  SET active = 'n' WHERE faq_type_id = '$faq_type_id' " ;
			        if ($conn->query($sql) === TRUE)
			        	{
			        		$arr["results"] = "success";
			        	} 
			        		else 
			        			{
			        				$arr["results"] = "Error updating record: " . $conn->error;
								}
		}
		        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
		    $conn->close();
	});

$app->post('/insertfaq_type' , function($request , $response , $args)
	{
	        include 'conn.php';
		        $json = $request->getBody(); //POST
		        $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
		        $faq_type_title_TH = isset($jsonArr['faq_type_title_TH'])?$jsonArr['faq_type_title_TH']:"";
		        $create_by = isset($jsonArr['create_by'])?$jsonArr['create_by']:"";
		        $update_by = isset($jsonArr['update_by'])?$jsonArr['update_by']:"";
   				$create_date = date("Y-m-d H:i:s");
		        $update_date = date("Y-m-d H:i:s");
		        if (!empty($faq_type_title_TH)) 
		        {
		     

			        $sql = "INSERT INTO cms_faq_type (faq_type_title_TH,
			                                    	create_by,
			                                     	create_date,
			                                     	update_date )
			                VALUES ('$faq_type_title_TH',
			                                    	'$create_by',
			                                    	'$create_date',
			                                    	'$update_date')";
			        if (mysqli_query($conn, $sql)) 
			        	{
			            	$arr["success"] = "New record created successfully";
			        	}
		        }else 
			        			{
						            $arr["success"] = "Error";
						        }
			echo json_encode($arr , JSON_UNESCAPED_UNICODE);
	    $conn->close();
	});


$app->post('/updatefaq_type', function($request , $response , $args)  
	{
	    include 'conn.php';
			        $json = $request->getBody(); //POST
			        $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
			        $faq_type_id = isset($jsonArr['faq_type_id'])?$jsonArr['faq_type_id']:"";
			   	    $faq_type_title_TH = isset($jsonArr['faq_type_title_TH'])?$jsonArr['faq_type_title_TH']:"";
					$update_by = isset($jsonArr['update_by'])?$jsonArr['update_by']:"";
					$update_date = date("Y-m-d H:i:s");
			        
if ($faq_type_id == "") 
{
	$arr["result"]= "Error updating record: " . $conn->error;
}else{

		$sql = " UPDATE cms_faq_type SET
						faq_type_title_TH = '$faq_type_title_TH',
			        	update_date = '$update_date', 
						update_by = '$update_by'
				WHERE
						faq_type_id = '$faq_type_id' " ;
			        if ($conn->query($sql) === TRUE) 
			        	{
						    $arr["result"] = "Record updated successfully";
						} 
						else 
						    {
								$arr["result"] = "Error updating record: " . $conn->error;
							}
	}
		        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
		    $conn->close();
	});

$app->run();
?>