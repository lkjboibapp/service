<?php
require 'vendor/autoload.php';

$app = new Slim\App();

$app->POST('/manage', function($request , $response , $args)  {

    include 'conn.php';
    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); 
    $manage_id = isset($jsonArr['manage_id'])?$jsonArr['manage_id']:"";
    //$seach_id ="";
  
    //if(!empty($manage_id))
            //{
              // $seach_id = " AND usa_id = '$manage_id'";
            //}
   
    $sql = "SELECT * FROM tbl_manage WHERE active = 'y'";

    $result = $conn->query($sql);

    $arr = array();
	if ($result->num_rows > 0) 
    {
        $arr["result"] = "successfully";

    // output data of each row
            while($row = $result->fetch_assoc()) 
            {
                $manage_id =$row["manage_id"];
                $id =$row["id"];
                $group_id =$row["group_id"];
                $type =$row["type"];
                $manage_row =$row["manage_row"];
                $create_date = $row["create_date"];
                $create_by = $row["create_by"];
                $update_date = $row["update_date"];
                $update_by = $row["update_by"];
                $active = $row["active"];

                $data = (object)array('manage_id'=> $manage_id, 
                            'id' => $id,
                            'group_id' => $group_id,
                            'type' => $type,
                            'manage_row' => $manage_row,
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

$app->run();
?>