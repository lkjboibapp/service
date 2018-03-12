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


$app->get('/getFeaturedLinks' , function($request , $response , $args){

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //

         	  	$sql = "SELECT * FROM tbl_featured_links WHERE active = '1'";
    	        $result = $conn->query($sql);

	           $arr = array();

	        if ($arr != "") {
	              if ($result->num_rows > 0) {
	        // output data of each row
	        while($row = $result->fetch_assoc()) {
				$link_id = $row['link_id'];
				$link_image = $row['link_image'];
				$link_name = $row['link_name'];
				$link_url = $row['link_url'];
				$active = $row['active'];
				$createby = $row['createby'];
				$createdate = $row['createdate'];
				$updateby = $row['updateby'];
				$updatedate = $row['updatedate'];
				

                $data[] = (object)array('link_id' => $link_id,
                'link_image' => $link_image,
                'link_name' => $link_name,
                'link_url' => $link_url,
                'active' => $active,
                'createby' => $createby,	                                    
                'createdate' => $createdate,                                    
                'updateby' => $updateby,                                   
                'updatedate' => $updatedate                                  
                                                	                                    
            );
	               }
		            $arr['result'] = 'success';
	          		$arr['data'] = $data;
	          		echo json_encode($arr , JSON_UNESCAPED_UNICODE);

    		     } else {
            		$arr['result'] = 'false';
          			$arr['data'] = "Unsuccessful";
          		echo json_encode($arr , JSON_UNESCAPED_UNICODE);

             	}
             	$conn->close();
             }       
    });

$app->run();
?>