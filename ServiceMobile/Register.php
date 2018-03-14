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
 
//Insert ทำแล้ว

$app = new Slim\App();


$app->post('/InsertRegister' , function($request , $response , $args){
 $postdata = file_get_contents("php://input");
            include 'conn.php';

    if (isset($postdata)) {
        $request = json_decode($postdata);

          if(isset($request->identification)){
             $identification = $request->identification;
          // $contac_by_name = $jsonArr['contac_by_name'];
         }else {
          $error[] = "contac_by_name is required.";
         }
       
          if(isset($request->email)){
        $email = $request->email;
          // $contac_by_surname = $jsonArr['contac_by_surname'];
         }else {
          $error[] = "contac_by_surname is required.";
         }

          if(isset($request->title)){
        $title = $request->title;
          // $contac_by_tel = $jsonArr['contac_by_tel'];
         }else {
          $error[] = "contac_by_tel is required.";
         }

          if(isset($request->title_id)){
          $title_id = $request->title_id;
          // $contac_by_email = $jsonArr['contac_by_email'];
         }else {
          $error[] = "contac_by_email is required.";
         }

          if(isset($request->firstname)){
        $firstname = $request->firstname;
          // $contac_subject = $jsonArr['contac_subject'];
         }else {
          $error[] = "contac_subject is required.";
         }

          if(isset($request->lastname)){
        $lastname = $request->lastname;

          // $contac_type = $jsonArr['contac_type'];
         }else {
          $error[] = "contac_type is required.";
         }

          if(isset($request->department)){
        $department = $request->department;

          // $contac_detail = $jsonArr['contac_detail'];
         }else {
          $error[] = "contac_detail is required.";
         }

        if(isset($request->job)){
        $job = $request->job;

          // $contac_detail = $jsonArr['contac_detail'];
         }else {
          $error[] = "contac_detail is required.";
         }

        if (isset($request->identification) != "") {
           $sqluser = "INSERT INTO tbl_users (username, password, email, orgchart_lv2)
             
            VALUES ('$email', '$identification' , '$email','$title')";

            

           if (mysqli_query($conn,$sqluser)) 
           {
            $sqlprofile = "INSERT INTO tbl_contactus (contac_by_name, contac_by_surname, contac_by_email, contac_by_tel, contac_subject, contac_detail)
             
            VALUES ('$identification', '$title_id' , '$firstname','$lastname','$department','$job')";
              if (mysqli_query($conn, $sqlprofile)) 
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
          } 

            echo "Server returns: " . $firstname,'+',$lastname;
        }//
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