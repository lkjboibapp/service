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


$app->post('/InsertContectUs' , function($request , $response , $args){
 $postdata = file_get_contents("php://input");
            include 'conn.php';

    if (isset($postdata)) {
        $request = json_decode($postdata);

          if(isset($request->contac_by_name)){
             $contac_by_name = $request->contac_by_name;
          // $contac_by_name = $jsonArr['contac_by_name'];
         }else {
          $error[] = "contac_by_name is required.";
         }
       
          if(isset($request->contac_by_surname)){
        $contac_by_surname = $request->contac_by_surname;
          // $contac_by_surname = $jsonArr['contac_by_surname'];
         }else {
          $error[] = "contac_by_surname is required.";
         }

          if(isset($request->contac_by_tel)){
        $contac_by_tel = $request->contac_by_tel;
          // $contac_by_tel = $jsonArr['contac_by_tel'];
         }else {
          $error[] = "contac_by_tel is required.";
         }

          if(isset($request->contac_by_email)){
          $contac_by_email = $request->contac_by_email;
          // $contac_by_email = $jsonArr['contac_by_email'];
         }else {
          $error[] = "contac_by_email is required.";
         }

          if(isset($request->contac_subject)){
        $contac_subject = $request->contac_subject;
          // $contac_subject = $jsonArr['contac_subject'];
         }else {
          $error[] = "contac_subject is required.";
         }

          if(isset($request->contac_type)){
        $contac_type = $request->contac_type;

          // $contac_type = $jsonArr['contac_type'];
         }else {
          $error[] = "contac_type is required.";
         }

          if(isset($request->contac_detail)){
        $contac_detail = $request->contac_detail;

          // $contac_detail = $jsonArr['contac_detail'];
         }else {
          $error[] = "contac_detail is required.";
         }

         $create_date = date("Y-m-d H:i:s");

        if (isset($request->contac_by_name) != "") {
           $sql = "INSERT INTO tbl_contactus (contac_by_name, contac_by_surname, contac_by_tel, contac_by_email,contac_subject,contac_type, contac_detail, create_date)
             
            VALUES ('$contac_by_name', '$contac_by_surname' , '$contac_by_tel','$contac_by_email','$contac_subject','$contac_type','$contac_detail', '$create_date')";

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

            echo "Server returns: " . $contac_by_name,'+',$contac_by_surname;
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