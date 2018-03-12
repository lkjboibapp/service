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

    $app = new Slim\App();

    $app->get('/getImgslide' , function($request , $response , $args){

        include 'conn.php';
    
        $json = $request->getBody(); 
        $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); 
    
        if(isset($jsonArr['imgslide_id'])){
                    $imgslide_id = $jsonArr['imgslide_id'];
                    $sql = "SELECT * FROM tbl_imgslide WHERE imgslide_id = '$imgslide_id' AND active = 'y'";
             }else if (isset($jsonArr['imgslide_id'])== "") {
                       $sql = "SELECT * FROM tbl_imgslide WHERE active = 'y'";
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
    
                    $imgslide_id = $row['imgslide_id'];
                    $imgslide_link = $row['imgslide_link'];
                    $imgslide_picture = $row['imgslide_picture'];
                    $create_date = $row['create_date'];
                    $create_by = $row['create_by'];
                    $update_date = $row['update_date'];
                    $update_by = $row['update_by'];
                    $active = $row['active'];
    
                    $data[] = (object)array('imgslide_id' => $imgslide_id,
                                          'imgslide_link' => $imgslide_link,
                                            'imgslide_picture'=> $imgslide_picture,
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

        $app->post('/DelectImgslide', function($request , $response , $args)  {

            include 'conn.php';
    
                $json = $request->getBody(); //POST
                $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); //POST     
    
             if(isset($jsonArr['imgslide_id'])){
                    $imgslide_id = $jsonArr['imgslide_id'];
             }else {
                 $error[] = "false";
             }
    
              if(isset($error)){
                  $arr['result'] = 'false';
                  $arr['data'] = $error;
    
                  echo json_encode($arr , JSON_UNESCAPED_UNICODE);
              }else {
               
                $sql = " UPDATE tbl_imgslide  SET active = 'n' WHERE imgslide_id = '$imgslide_id' " ;
    
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

    $app->post('/InsertImgslide' , function($request , $response , $args){
        $postdata = file_get_contents("php://input");
                   include 'conn.php';
       
           if (isset($postdata)) {
               $request = json_decode($postdata);
       
                 if(isset($request->imgslide_link)){
                    $imgslide_link = $request->imgslide_link;
                }else {
                 $error[] = "imgslide_link is required.";
                }
              
                 if(isset($request->imgslide_picture)){
               $imgslide_picture = $request->imgslide_picture;
                }else {
                 $error[] = "imgslide_picture is required.";
                }

                 if(isset($request->create_by)){
                 $create_by = $request->create_by;
                }else {
                 $error[] = "create_by is required.";
                }
              
                 if(isset($request->update_by)){
               $update_by = $request->update_by;
                }else {
                 $error[] = "update_by is required.";
                }
       
                $update_date = date("Y-m-d H:i:s");
                $create_date = date("Y-m-d H:i:s");
       
               if (isset($request->imgslide_link) != "") {
                  $sql = "INSERT INTO tbl_contactus (imgslide_link, imgslide_picture, create_date, create_by,update_date,update_by, contac_detail, create_date)
                    
                   VALUES ('$imgslide_link', '$imgslide_picture' , '$create_date','$create_by','$update_date','$update_by','$contac_detail', '$create_date')";
       
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
       
                   echo "Server returns: " . $imgslide_link,'+',$imgslide_picture;
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