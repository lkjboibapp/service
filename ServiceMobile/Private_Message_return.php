<?php
require 'vendor/autoload.php';

$app = new Slim\App();

$app->post('/privatemassage', function($request , $response , $args)  {

	include 'conn.php';

	$json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
    $pm_id = isset($jsonArr['pm_id'])?$jsonArr['pm_id']:"";

    if(!empty($pm_id))
			{
				$sql = "SELECT * FROM Private_Message_return WHERE pm_id = '$pm_id' AND active = 'y'";
			}
				else 
					{
						$sql = "SELECT * FROM Private_Message_return WHERE active = 'y'" ;
					}
		$result = $conn->query($sql);
		$arr = array();
   
        if ($result->num_rows > 0) 
        {
            $arr["results"] = "successfully";
            while($row = $result->fetch_assoc())
                {
                    $pmr_id = $row['pmr_id'];
                    $pm_id = $row['pm_id'];
                    $pmr_return = $row['pmr_return'];
                    $create_date = $row["create_date"];
                    $create_by = $row["create_by"];
                    $update_date = $row["update_date"];
                    $update_by = $row["update_by"];
                    $active = $row["active"];
                    $answer_by = $row["answer_by"];
                    $status_answer = $row["status_answer"];
                    $all_file_return_pm = $row["all_file_return_pm"];
                    
                    $data = (object)array
								(
                                'pmr_id' => $pmr_id,  
                                'pm_id' => $pm_id,  
                                'pmr_return' => $pmr_return,  
                                'create_date' => $create_date,
        						'create_by' => $create_by,
        						'update_date' => $update_date,
        						'update_by' => $update_by,
                                'active' => $active,
                                'answer_by' => $answer_by,   
                                'status_answer' => $status_answer,  
                                'all_file_return_pm' => $all_file_return_pm,  
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


$app->post('/insertMassage', function($request , $response , $args)  {

	include 'conn.php';

	$json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
    $pm_id = isset($jsonArr['pm_id'])?$jsonArr['pm_id']:"";
    $pmr_return = isset($jsonArr['pmr_return'])?$jsonArr['pmr_return']:"";
    $create_by = isset($jsonArr['create_by'])?$jsonArr['create_by']:"";
    $all_file_return_pm = isset($jsonArr['all_file_return_pm'])?$jsonArr['all_file_return_pm']:"";
    $create_date = date("Y-m-d H:i:s");
    $update_date = date("Y-m-d H:i:s");

    if(!empty($pm_id))
			{
				$sql = "INSERT INTO private_message_return (pm_id,pmr_return,create_date,create_by,update_date,all_file_return_pm)
                        VALUES ('$pm_id','$pmr_return','$create_date','$create_by','$update_date','$all_file_return_pm')";
        
                 if (mysqli_query($conn, $sql)) 
                    {
                         $arr['result'] = 'success';
                        $arr['data'] = "Successfully->";
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

echo json_encode($arr , JSON_UNESCAPED_UNICODE);

$conn->close();
});


$app->run();
?>

