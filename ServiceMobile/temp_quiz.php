<?php
require 'vendor/autoload.php';

$app = new Slim\App();
    $app->post('/checkQuiz' , function($request , $response , $args){
        include 'conn.php';

        $json = $request->getBody(); //POST
        $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //
        $user_id = isset($jsonArr['user_id'])?$jsonArr['user_id']:"";
        if(!empty($user_id))
            {
                $sql = "SELECT * FROM temp_quiz WHERE user_id = '$user_id'" ;
            }
                else 
					{
                        $sql = "SELECT * FROM temp_quiz" ;                        
                    }

        $result = $conn->query($sql);
		$arr = array();
            if ($result->num_rows > 0) 
                {
                 $arr["results"] = "successfully";
                    while($row = $result->fetch_assoc())
                        {
                            $id = $row['id'];
                            $user_id = $row['user_id'];
                            $type = $row['type'];
                            $lesson = $row['lesson'];
                            $group_id = $row['group_id'];
                            $ques_id = $row['ques_id'];
                            $number = $row['number'];
                            $ans_id = $row['ans_id'];
                            $status = $row['status'];
                            $time_start = $row['time_start'];
                            $question = $row['question'];
                            $time_up = $row['time_up'];
                        $data = (object)array('id' => $id,  
                            'user_id' => $user_id,  
                            'type' => $type,  
                            'lesson' => $lesson,
        					'group_id' => $group_id,
        					'ques_id' => $ques_id,
        					'number' => $number,
                            'ans_id' => $ans_id,
                            'status' => $status,   
                            'time_start' => $time_start,  
                            'question' => $question,  
                            'time_up' => $time_up
                    	);
                $arr["data"][] = $data;
                            
                            
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