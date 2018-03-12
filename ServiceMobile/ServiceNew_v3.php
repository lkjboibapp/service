<?php
require 'vendor/autoload.php';

		header("Access-Control-Allow-Origin: *");
		header("Content-Type:application/json; charset=UTF-8");


$app = new Slim\App();


$app->post('/InsertNews' , function($request , $response , $args){

            include 'conn.php';
            $json = $request->getBody(); //POST
            $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
 


			
           // $cms_title = $jsonArr['cms_title'];
           //  $cms_short_title =$jsonArr['cms_short_title'];
           //  $cms_detail = $jsonArr['cms_detail']; 
           //  $cms_picture = $jsonArr['cms_picture'];   
            $create_date = date("Y-m-d H:i:s");
//            $create_by = $jsonArr['create_by'];
         
         if(isset($jsonArr['cms_title'])){
         	$cms_title = $jsonArr['cms_title'];
         }else {
         	$error[] = "cms_title is required.";
         }

         if(isset($jsonArr['cms_short_title'])){
         	$cms_short_title = $jsonArr['cms_short_title'];
         }else {
         	$error[] = "cms_short_title is required.";
         }

         
         	$cms_detail = isset($jsonArr['cms_detail'])?$jsonArr['cms_detail']:"";
         	$cms_picture = isset($jsonArr['cms_picture'])?$jsonArr['cms_picture']:"";
	 		$cms_link = isset($jsonArr['cms_link'])?$jsonArr['cms_link']:"";
          
         if(isset($jsonArr['create_by'])){
         	$create_by = $jsonArr['create_by'];
         }else {
         	$error[] = "create_by is required.";
         }

          if(isset($error)){
          	$arr['result'] = 'false';
          	$arr['data'] = $error;

          	echo json_encode($arr , JSON_UNESCAPED_UNICODE);
          }else {
          	 $sql = "INSERT INTO tbl_news (cms_title, cms_short_title, cms_link, cms_detail,cms_picture, create_date,create_by)
             VALUES ('$cms_title', '$cms_short_title' , '$cms_link','$cms_detail','$cms_picture', '$create_date','$create_by')";

             if (mysqli_query($conn, $sql)) {
             	$arr['result'] = 'success';
          		$arr['data'] = "New record created successfully";


          	echo json_encode($arr , JSON_UNESCAPED_UNICODE);

             } else {
				$arr['result'] = 'false';
          		$arr['data'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
          		echo json_encode($arr , JSON_UNESCAPED_UNICODE);
             }

         $conn->close();
          }

    });


  $app->post('/DelectNews', function($request , $response , $args)  {

        include 'conn.php';

            $json = $request->getBody(); //POST
            $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST     

		 if(isset($jsonArr['cms_id'])){
         	   $cms_id = $jsonArr['cms_id'];
         }else {
         	$error[] = "false";
         }

          if(isset($error)){
          	$arr['result'] = 'false';
          	$arr['data'] = $error;

          	echo json_encode($arr , JSON_UNESCAPED_UNICODE);
          }else {
           
            $sql = " UPDATE tbl_news  SET active = 'n' WHERE cms_id = '$cms_id' " ;

            if ($conn->query($sql) === TRUE) {

				$arr['result'] = 'success';
          		$arr['data'] = "Record delete successfully";
          		echo json_encode($arr , JSON_UNESCAPED_UNICODE);

            } else {
				$arr['result'] = 'false';
          		$arr['data'] = "Error updating record: " . $conn->error;
          		echo json_encode($arr , JSON_UNESCAPED_UNICODE);
        }
    }
        $conn->close();

});


   $app->post('/UpdatedNews', function($request , $response , $args)  {

        include 'conn.php';

            $json = $request->getBody(); //POST
            $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
       /*     $cms_id = $jsonArr['cms_id'];*/
            

            if(isset($jsonArr['cms_id'])){
         	   $cms_id = $jsonArr['cms_id'];
         }else {
         	$error[] = " Can not Update ";
         }

          if(isset($error)){
          	$arr['result'] = 'false';
          	$arr['data'] = $error;

          	echo json_encode($arr , JSON_UNESCAPED_UNICODE);
          }else {
            $cms_title = isset($jsonArr['cms_title'])?$jsonArr['cms_title']:"";
            $cms_short_title = isset($jsonArr['cms_short_title'])?$jsonArr['cms_short_title']:"";
           	$cms_detail = isset($jsonArr['cms_detail'])?$jsonArr['cms_detail']:"";
         	$cms_picture = isset($jsonArr['cms_picture'])?$jsonArr['cms_picture']:"";
            $update_by = isset($jsonArr['update_by'])?$jsonArr['update_by']:"";
            
            $update_date = date("Y-m-d H:i:s");

            $cms_link = isset($jsonArr['cms_link'])?$jsonArr['cms_link']:"";
          
            
                $sql = " UPDATE tbl_news  SET cms_title = '$cms_title', cms_short_title = '$cms_short_title',
                cms_detail = '$cms_detail',
                cms_link = '$cms_link', 
                cms_picture = '$cms_picture', 
                update_date = '$update_date', 
                update_by = '$update_by'
                 
                WHERE cms_id = '$cms_id' " ;

            if ($conn->query($sql) === TRUE) {
            	$arr['result'] = 'success';
          		$arr['data'] = "Record updated successfully";
          		echo json_encode($arr , JSON_UNESCAPED_UNICODE);
            } else {
	           	$arr['result'] = 'false';
          		$arr['data'] = "Error updating record: " . $conn->error;
          		echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    }
        $conn->close();
            }
    });




$app->get('/getNews' , function($request , $response , $args){

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //

    if(isset($jsonArr['cms_id'])){
         	   $cms_id = $jsonArr['cms_id'];
         	   $sql = "SELECT * FROM tbl_news WHERE cms_id = '$cms_id' AND active = 'y'";
         }else if (isset($jsonArr['cms_id'])== "") {
         	  	$sql = "SELECT * FROM tbl_news WHERE active = 'y'";
         }else {
         	$error[] = "Unsuccessful";
         }

          if(isset($error)){
          	$arr['result'] = 'false';
          	$arr['data'] = $error;
          	echo json_encode($arr , JSON_UNESCAPED_UNICODE);
          }else {

	        $result = $conn->query($sql);

	        $arr = array();

	        if ($arr != "") {
	              if ($result->num_rows > 0) {
	        // output data of each row
	        while($row = $result->fetch_assoc()) {

	            $cms_id = $row['cms_id'];
	            $cms_title = $row['cms_title'];
	            $cms_short_title = $row['cms_short_title'];
	            $cms_detail = $row['cms_detail'];
	            $cms_link = $row['cms_link'];
	            $cms_picture = $row['cms_picture'];
	            $create_date = $row['create_date'];
	            $create_by = $row['create_by'];
	            $update_date = $row['update_date'];
	            $update_by = $row['update_by'];
	            $active = $row['active'];

	            $data[] = (object)array('cms_id' => $cms_id,
	                                  'cms_title' => $cms_title,
	                                    'cms_short_title'=> $cms_short_title,
	                                    'cms_detail' => $cms_detail,
	                                    'cms_link' => $cms_link,
	                                    'cms_picture' => $cms_picture,
	                                    'create_date' => $create_date,
	                                    'create_by' => $create_by,
	                                    'update_date' => $update_date,
	                                    'update_by' => $update_by,
	                                    'active' => $active
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
  
	     }
	   
       
    });


$app->run();
?>