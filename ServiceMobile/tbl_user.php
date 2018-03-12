<?php
require 'vendor/autoload.php';

		header("Access-Control-Allow-Origin: *");
		header("Content-Type:application/json; charset=UTF-8");


$app = new Slim\App();


$app->get('/getUserPassWord' , function($request , $response , $args){

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //

         	  	$sql = "SELECT * FROM tbl_users WHERE superuser = '1'";
    	        $result = $conn->query($sql);

	           $arr = array();

	        if ($arr != "") {
	              if ($result->num_rows > 0) {
	        // output data of each row
	        while($row = $result->fetch_assoc()) {
				$id = $row['id'];
				$username = $row['username'];
				$password = $row['password'];
				

	            $data[] = (object)array('id' => $id,'username' => $username,'password' => $password	                                    );
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


	$app->post('/InsertUser' , function($request , $response , $args){
		$postdata = file_get_contents("php://input");
				   include 'conn.php';
	   
		   if (isset($postdata)) {
			   $request = json_decode($postdata);

	   
			   $username = isset($jsonArr['username'])?$jsonArr['username']:"";
			   $password = isset($jsonArr['password'])?$jsonArr['password']:"";
			   $email = isset($jsonArr['email'])?$jsonArr['email']:"";
			   $pic_user = isset($jsonArr['pic_user'])?$jsonArr['pic_user']:"";
			   $orgchart_lv2 = isset($jsonArr['orgchart_lv2'])?$jsonArr['orgchart_lv2']:"";
			   $department_id = isset($jsonArr['department_id'])?$jsonArr['department_id']:"";
			   $activkey = isset($jsonArr['activkey'])?$jsonArr['activkey']:"";

			 
			   $superuser = isset($jsonArr['superuser'])?$jsonArr['superuser']:"";
			   $status = isset($jsonArr['status'])?$jsonArr['status']:"";
			   $online_status = isset($jsonArr['online_status'])?$jsonArr['online_status']:"";
			   $online_user = isset($jsonArr['online_user'])?$jsonArr['online_user']:"";
			   $last_ip = isset($jsonArr['last_ip'])?$jsonArr['last_ip']:"";
			  
			 
			   $lastactivity = isset($jsonArr['lastactivity'])?$jsonArr['lastactivity']:"";
			   $avatar = isset($jsonArr['avatar'])?$jsonArr['avatar']:"";
			   $company_id = isset($jsonArr['company_id'])?$jsonArr['company_id']:"";
			   $division_id = isset($jsonArr['division_id'])?$jsonArr['division_id']:"";
			   $position_id = isset($jsonArr['position_id'])?$jsonArr['position_id']:"";
			   $bookkeeper_id = isset($jsonArr['bookkeeper_id'])?$jsonArr['bookkeeper_id']:"";
			   $pic_cardid = isset($jsonArr['pic_cardid'])?$jsonArr['pic_cardid']:"";
			   $auditor_id = isset($jsonArr['auditor_id'])?$jsonArr['auditor_id']:"";
			   $pic_cardid2 = isset($jsonArr['pic_cardid2'])?$jsonArr['pic_cardid2']:"";
			   
				$create_at = date("Y-m-d H:i:s");
				$lastvisit_at = date("Y-m-d H:i:s");
			   
				$last_activity = date("Y-m-d H:i:s");
	   
			   if (isset($request->username) != "") {
				  $sql = "INSERT INTO tbl_users (username, password, email, pic_user,orgchart_lv2,department_id, activkey, superuser,status,online_status,online_user,last_ip,last_activity,lastactivity,avatar,company_id,division_id,position_id,bookkeeper_id,pic_cardid,auditor_id,pic_cardid2)
					
				   VALUES ('$username', '$password' , '$email','$pic_user','$contac_subject','$department_id','$activkey', '$superuser','$status','$online_status','$online_user','$last_ip','$last_activity','$lastactivity','$avatar','$company_id','$division_id','$position_id','$bookkeeper_id','$pic_cardid','$auditor_id','$pic_cardid2')";
	   
				  if (mysqli_query($conn, $sql)) 
				  {
					 $arr['result'] = 'success';
					 $arr['data'] = "Successfully";
					 echo json_encode($arr , JSON_UNESCAPED_UNICODE);
				   } 
					 else 
					   {
							 $arr['result'] = 'false';
							 $arr['data'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
							 echo json_encode($arr , JSON_UNESCAPED_UNICODE);
					   }
	   
				   echo "Server returns: " . $username,'+',$password;
			   }
			   else {
				   echo "Empty username parameter!";
			   }
		   }
		   else {
			   echo "Not called properly with username parameter!";
		   }
				$conn->close();
			 });

$app->run();
?>