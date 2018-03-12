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

    $app->get('/getProfile' , function($request , $response , $args){

        include 'conn.php';
    
        $json = $request->getBody(); 
        $jsonArr = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE); 
    
        if(isset($jsonArr['user_id'])){
                    $user_id = $jsonArr['user_id'];
                    $sql = "SELECT * FROM tbl_profiles WHERE user_id = '$user_id'";
             }else if (isset($jsonArr['user_id'])== "") {
                       $sql = "SELECT * FROM tbl_profiles";
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
    
                    $user_id = $row['user_id'];
                    $title_id = $row['title_id'];
                    $firstname = $row['firstname'];
                    $lastname = $row['lastname'];
                    $type_user = $row['type_user'];
                    $sex = $row['sex'];
                    $birthday = $row['birthday'];
                    $lastname_en = $row['lastname_en'];
                    $identification = $row['identification'];
                    $firstname_en = $row['firstname_en'];
                    $age = $row['age'];
                    $education = $row['education'];
                    $occupation = $row['occupation'];
                    $bussiness_model_id = $row['bussiness_model_id'];
                    $bussiness_type_id = $row['bussiness_type_id'];
                    $company = $row['company'];
                    $juristic = $row['juristic'];
                    $position = $row['position'];
                    $website = $row['website'];
                    $address = $row['address'];
                    $province = $row['province'];
                    $tel = $row['tel'];
                    $phone = $row['phone'];
                    $fax = $row['fax'];
                    $contactfrom = $row['contactfrom'];
                    $advisor_email1 = $row['advisor_email1'];
                    $advisor_email2 = $row['advisor_email2'];
                    $file = $row['file'];
                    $generation = $row['generation'];
    
                    $data[] = (object)array('user_id' => $user_id,
                                          'title_id' => $title_id,
                                            'firstname'=> $firstname,
                                            'lastname' => $lastname,
                                            'type_user' => $type_user,
                                            'sex' => $sex,
                                            'birthday' => $birthday,
                                            'lastname_en' => $lastname_en,
                                            'identification' => $identification,
                                            'firstname_en' => $firstname_en,
                                            'age' => $age,
                                            'education' => $education,
                                            'occupation' => $occupation,
                                            'bussiness_model_id' => $bussiness_model_id,
                                            'bussiness_type_id' => $bussiness_type_id,
                                            'company' => $company,
                                            'juristic' => $juristic,
                                            'position' => $position,
                                            'website' => $website,
                                            'address' => $address,
                                            'province' => $province,
                                            'tel' => $tel,
                                            'phone' => $phone,
                                            'fax' => $fax,
                                            'contactfrom' => $contactfrom,
                                            'advisor_email1' => $advisor_email1,
                                            'advisor_email2' => $advisor_email2,
                                            'file' => $file,
                                            'generation' => $generation
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

        // $app->post('/InsertProfile' , function($request , $response , $args){
        //     $postdata = file_get_contents("php://input");
        //                include 'conn.php';
           
        //        if (isset($postdata)) {
        //            $request = json_decode($postdata);
           
        //              if(isset($request->firstname)){
        //                 $firstname = $request->firstname;
        //              // $firstname = $jsonArr['firstname'];
        //             }else {
        //              $error[] = "firstname is required.";
        //             }
                  
        //              if(isset($request->lastname)){
        //            $lastname = $request->lastname;
        //              // $lastname = $jsonArr['lastname'];
        //             }else {
        //              $error[] = "lastname is required.";
        //             }
           
        //              if(isset($request->type_user)){
        //            $type_user = $request->type_user;
        //              // $type_user = $jsonArr['type_user'];
        //             }else {
        //              $error[] = "type_user is required.";
        //             }
           
        //              if(isset($request->sex)){
        //              $sex = $request->sex;
        //              // $sex = $jsonArr['sex'];
        //             }else {
        //              $error[] = "sex is required.";
        //             }
           
        //              if(isset($request->birthday)){
        //            $birthday = $request->birthday;
        //              // $birthday = $jsonArr['birthday'];
        //             }else {
        //              $error[] = "birthday is required.";
        //             }
           
        //              if(isset($request->lastname_en)){
        //            $lastname_en = $request->lastname_en;
           
        //              // $lastname_en = $jsonArr['lastname_en'];
        //             }else {
        //              $error[] = "lastname_en is required.";
        //             }
           
        //              if(isset($request->identification)){
        //            $identification = $request->identification;
        //             }else {
        //              $error[] = "identification is required.";
        //             }

        //             if(isset($request->firstname_en)){
        //             $firstname_en = $request->firstname_en;
        //             }else {
        //             $error[] = "firstname_en is required.";
        //             }

        //             if(isset($request->age)){
        //             $age = $request->age;
        //             }else {
        //             $error[] = "age is required.";
        //             }

        //             if(isset($request->education)){
        //             $education = $request->education;
        //             }else {
        //             $error[] = "education is required.";
        //             }


        //                          if(isset($request->occupation)){
        //                             $occupation = $request->occupation;
        //                              }else {
        //                               $error[] = "occupation is required.";
        //                              }

        //                              if(isset($request->bussiness_model_id)){
        //                                 $bussiness_model_id = $request->bussiness_model_id;
        //                                  }else {
        //                                   $error[] = "bussiness_model_id is required.";
        //                                  }

        //                                  if(isset($request->bussiness_type_id)){
        //                                     $bussiness_type_id = $request->bussiness_type_id;
        //                                      }else {
        //                                       $error[] = "bussiness_type_id is required.";
        //                                      }

        //                                      if(isset($request->company)){
        //                                         $company = $request->company;
        //                                          }else {
        //                                           $error[] = "company is required.";
        //                                          }

        //                                          if(isset($request->juristic)){
        //                                             $juristic = $request->juristic;
        //                                              }else {
        //                                               $error[] = "juristic is required.";
        //                                              }

        //                                              if(isset($request->position)){
        //                                                 $position = $request->position;
        //                                                  }else {
        //                                                   $error[] = "position is required.";
        //                                                  }

        //                                                  if(isset($request->address)){
        //                                                     $address = $request->address;
        //                                                      }else {
        //                                                       $error[] = "address is required.";
        //                                                      }

        //                                                      if(isset($request->province)){
        //                                                         $province = $request->province;
        //                                                          }else {
        //                                                           $error[] = "province is required.";
        //                                                          }

        //                                                          if(isset($request->tel)){
        //                                                             $tel = $request->tel;
        //                                                              }else {
        //                                                               $error[] = "tel is required.";
        //                                                              }

        //                                                              if(isset($request->phone)){
        //                                                                 $phone = $request->phone;
        //                                                                  }else {
        //                                                                   $error[] = "phone is required.";
        //                                                                  }

        //                                                                  if(isset($request->fax)){
        //                                                                     $fax = $request->fax;
        //                                                                      }else {
        //                                                                       $error[] = "fax is required.";
        //                                                                      }
           
        //             $create_date = date("Y-m-d H:i:s");
           
        //            if (isset($request->firstname) != "") {
        //               $sql = "INSERT INTO tbl_contactus (firstname, lastname, type_user, sex,birthday,lastname_en, contac_detail, create_date)
                        
        //                VALUES ('$firstname', '$lastname' , '$type_user','$sex','$birthday','$lastname_en','$contac_detail', '$create_date')";
           
        //               if (mysqli_query($conn, $sql)) 
        //               {
        //                  $arr['result'] = 'success';
        //                  $arr['data'] = "Successfully";
        //                  echo json_encode($arr , JSON_UNESCAPED_UNICODE);
        //                } 
        //                  else 
        //                    {
        //                          $arr['result'] = 'false';
        //                          $arr['data'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
        //                          echo json_encode($arr , JSON_UNESCAPED_UNICODE);
        //                    }
           
        //                echo "Server returns: " . $firstname,'+',$lastname;
        //            }
        //            else {
        //                echo "Empty username parameter!";
        //            }
        //        }
        //        else {
        //            echo "Not called properly with username parameter!";
        //        }
        //             $conn->close();
        //          });
    $app->run();
    ?>