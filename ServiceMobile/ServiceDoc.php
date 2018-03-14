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

$app->get('/getdocs' , function($request , $response , $args){ //เงื่อนไข

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
	 $dow_id = $jsonArr['dty_id'];
	// $dow_address = $jsonArr['dow_address'];
	 
    
	 	if($dow_id == "")
		 	{
		        $sql = "SELECT cms_download_type.dty_id,cms_download_type.dty_name, cms_download.dow_name, cms_download.dow_detail, cms_download.dow_address FROM cms_download_type 
    INNER JOIN cms_download ON cms_download_type.dty_id = cms_download.dty_id WHERE cms_download_type.active = '1'";
		    }
		    	else 
		    		{ //WHERE Condition SQL Start!
				       $sql = "SELECT  cms_download_type.dty_id,cms_download_type.dty_name, cms_download.dow_name, cms_download.dow_detail, cms_download.dow_address FROM cms_download_type 
    INNER JOIN cms_download ON cms_download_type.dty_id = cms_download.dty_id WHERE cms_download_type.active = '1' AND cms_download_type.dty_id = '$dow_id'";
		    		}
		    		
    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
			    // output data of each row
			    while($row = $result->fetch_assoc())
			    	{
        				$cms_download_type = $row['dty_id'];
        				$dty_name = $row['dty_name'];
        				// $dow_name = $row['dow_name'];
        				$dow_detail = $row['dow_detail'];
				        $dow_address = $row['dow_address'];
        				
					        $data[] = (object)array('dty_id' => $cms_download_type,
					        	'dty_name' => $dty_name,
					        	// 'dow_name' => $dow_name,
								'dow_detail' => $dow_detail,
								'dow_address' => $dow_address

					                            
					                            );
					        // $arr[] = $data;
			    	}
					$arr['result'] = 'success';
					$arr['data'] = $data;
					echo json_encode($arr , JSON_UNESCAPED_UNICODE);

	     	}
	     		else
	     			{
	    				echo "0 results";
	    			}
    $conn->close();
});

$app->run();
?>
