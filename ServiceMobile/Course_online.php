<?php
require 'vendor/autoload.php';

$app = new Slim\App();

$app->get('/get' , function($request , $response , $args){ //เงือ่ไข

    include 'conn.php';

    $json = $request->getBody(); //POST
    $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST
     $course_id = isset($jsonArr['course_id'])?$jsonArr['course_id']:"";
    
	 	if($course_id == "")
		 	{
		        $sql = "SELECT * FROM tbl_course_online WHERE active = 'y'";
		    }
		    	else 
		    		{ //WHERE Condition SQL Start!
				        $sql = "SELECT * FROM tbl_category_course WHERE course_id = '$course_id' AND active = 'y'";
		    		}
    $result = $conn->query($sql);
    $arr = array();
	    if ($result->num_rows > 0)
	    	{
				$arr["results"] = "successfully";
				while($row = $result->fetch_assoc())
				{
					  	$course_id = $row['course_id'];
        				$cate_id = $row['cate_id'];
        				$course_type = $row['course_type'];
        				$course_number = $row['course_number'];
        				$course_title = $row['course_title'];
        				$course_lecturer = $row['course_lecturer'];
        				$course_short_title = $row['course_short_title'];
        				$course_detail = $row['course_detail'];
        				$course_price = $row['course_price'];
        				$course_picture = $row['course_picture'];
        				$course_book_number = $row['course_book_number'];
        				$course_book_date = $row['course_book_date'];
        				$course_rector_date = $row['course_rector_date'];
        				$course_hour = $row['course_hour'];
        				$course_other = $row['course_other'];
        				$create_date = $row['create_date'];
        				$course_status = $row['course_status'];
        				$course_tax = $row['course_tax'];
        				$course_refer = $row['course_refer'];
        				$course_note = $row['course_note'];
        				$create_by = $row['create_by'];
        				$update_date = $row['update_date'];
        				$update_by = $row['update_by'];
        				$sortOrder = $row['sortOrder'];
        				$recommend = $row['recommend'];
        				$special_category = $row['special_category'];
        				$status = $row['status'];
        				$cate_course = $row['cate_course'];
        				$time_test = $row['time_test'];
        				
        				$active = $row['active'];

					$data = (object)array('course_id' => $course_id,
					                              'cate_id' => $cate_id,
					                              'course_type' => $course_type,
					                               'course_number' => $course_number,
					                               'course_title' => $course_title,
					                               'course_detail' => $course_detail,
					                               'course_short_title' => $course_short_title,
					                               'course_lecturer' => $course_lecturer,
					                               'course_price' => $course_price,
					                               'course_picture' => $course_picture,
					                               'course_book_number' => $course_book_number,
					                               'course_book_date' => $course_book_date,
					                               'course_rector_date' => $course_rector_date,
					                               'course_hour' => $course_hour,
					                               'course_other' => $course_other,
					                               'create_date' => $create_date,
					                               'course_status' => $course_status,
					                               'course_tax' => $course_tax,
					                               'course_refer' => $course_refer,
					                               'course_note' => $course_note,
					                               'create_by' => $create_by,
					                               'update_date' => $update_date,
					                               'update_by' => $update_by,
					                               'sortOrder' => $sortOrder,
					                               'recommend' => $recommend,
					                               'special_category' => $special_category,
					                               'status' => $status,
					                               'cate_course' => $cate_course,
					                               'time_test' => $time_test,
					                               
					                               'active' => $active
					                            );
					$arr["data"][] = $data;
				}
					// mysqli_close($conn);
				// return json_encode($arr);
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