<?php
require 'vendor/autoload.php';

$app = new Slim\App();

    $app->POST('/InsertPrivateMessage' , function($request , $response , $args){

        $postdata = file_get_contents("php://input");
        include 'conn.php';

        if (isset($postdata)) 
        {
            $request = json_decode($postdata);

            if(isset($request->pm_topic))
                {
                    $pm_topic = $request->pm_topic;
                }
                    else 
                        {
                            $error[] = "pm_topic is required.";
                        }
        
            if(isset($request->pm_quest))
                {
                    $pm_quest = $request->pm_quest;
                }
                    else 
                        {
                            $error[] = "pm_quest is required.";
                        }
        
            if(isset($request->pm_to))
                {
                    $pm_to = $request->pm_to;
                }
                    else 
                        {
                            $error[] = "pm_to is required.";
                        }

            if(isset($request->question_status))
                {
                    $question_status = $request->question_status;
                }
                    else 
                        {
                            $error[] = "question_status is required.";
                        }

            $all_file = isset($request->all_file)?$request->all_file:""; 
            $create_date = date("Y-m-d H:i:s");

                if (isset($request->pm_to) != "")
                    {
                        $sql = "INSERT INTO private_message (pm_topic, pm_quest, pm_to, question_status, all_file, create_date)
                                    VALUES ('$pm_topic','$pm_quest','$pm_to','$question_status','$all_file', '$create_date')";

                        if (mysqli_query($conn, $sql)) 
                            {
                                $arr['result'] = 'success';
                                $arr['data'] = "Successfully";
                            } 
                                else 
                                    {
                                        $arr['result'] = 'false';
                                        $arr['data'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
                                    }
                    }
                        else 
                            {
                                $arr['result'] = 'ส่งข้อความไม่สำเร็จ';
                                $arr['data'] = "Successfully";
                                
                            }
        }    
            else
                {
                    $arr['result'] = 'Not called properly with username parameter!';
                }

        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    $conn->close();
    });


$app->get('/getPrivateMessage' , function($request , $response , $args){
    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //

    if(isset($jsonArr['pm_id'])){
         	   $pm_id = $jsonArr['pm_id'];
         	   $sql = "SELECT * FROM private_message WHERE pm_id = '$pm_id' AND active = 'y'";
         }else if (isset($jsonArr['pm_id'])== "") {
         	  	$sql = "SELECT * FROM private_message WHERE active = 'y'";
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

	            $pm_id = $row['pm_id'];
	            $pm_topic = $row['pm_topic'];
	            $pm_quest = $row['pm_quest'];
	            $pm_to = $row['pm_to'];
	            $pm_alert = $row['pm_alert'];
	            $question_status = $row['question_status'];
              $all_file = $row['all_file'];
	            
              $create_date = $row['create_date'];
	            $create_by = $row['create_by'];
	            $update_date = $row['update_date'];
	            $update_by = $row['update_by'];
	            $active = $row['active'];

	            $data[] = (object)array('pm_id' => $pm_id,
	                                  'pm_topic' => $pm_topic,
	                                    'pm_quest'=> $pm_quest,
	                                    'pm_to' => $pm_to,
	                                    'pm_alert' => $pm_alert,
	                                    'question_status' => $question_status,
                                      'all_file' => $all_file,
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