<?php
  
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  include './autoloader.php';

  use fp\result\ResFailure;
  use fp\result\ResSuccess;
  
  function curlJson(string $url, string $data){
      $ch = curl_init($url);
              
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/x-www-form-urlencoded'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
              
      //execute post
      $res = curl_exec($ch);
      $error = curl_error($ch);
      curl_close($ch);
              
      if($error){
        return ResFailure::failure($error);
      } else {
        return ResSuccess::success($res);
      }
    }
    
    $data = json_encode(array('endpoint'=>'dbReader','endpointUrl'=>'http://localhost/microServicePHP/dbReader/'));
    
    echo curlJson('http://localhost/microServicePHP/router/?type=register', $data);
  