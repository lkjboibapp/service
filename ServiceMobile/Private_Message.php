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
            
              if(isset($request->create_by))
                {
                    $create_by = $request->create_by;
                }
                    else 
                        {
                            $error[] = "question_status is required.";
                        }

            $all_file = isset($request->all_file)?$request->all_file:""; 
            $create_date = date("Y-m-d H:i:s");
            $update_date = date("Y-m-d H:i:s");

                if (isset($request->pm_to) != "")
                    {
                        $sql = "INSERT INTO private_message (pm_topic, pm_quest, pm_to, question_status, all_file, create_date, update_date,create_by)
                                    VALUES ('$pm_topic','$pm_quest','$pm_to','$question_status','$all_file', '$create_date','$update_date','$create_by')";

                        if (mysqli_query($conn, $sql)) 
                            {
                                $pm_id = mysqli_insert_id($conn);

                                $sql = "INSERT INTO private_message_return (pm_id,pmr_return , all_file_return_pm, create_date, update_date,create_by)
                                VALUES ('$pm_id','$pm_quest','$all_file', '$create_date','$update_date','$create_by')";

                            if (mysqli_query($conn, $sql)) 
                                {
                                    $arr['result'] = 'success';
                                    $arr['data'] = "Successfully->".$pm_id;
                                }

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


$app->post('/getPrivateMessage' , function($request , $response , $args){
    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //

    if(isset($jsonArr['create_by'])){
         	   $create_by = $jsonArr['create_by'];
         	   $sql = "SELECT * FROM private_message WHERE create_by = '$create_by' AND active = 'y' ORDER BY pm_id DESC";
         }else if (isset($jsonArr['create_by'])== "") {
         	  	$sql = "SELECT * FROM private_message WHERE active = 'y' ORDER BY pm_id DESC";
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