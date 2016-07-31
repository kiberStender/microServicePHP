<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  include './autoloader.php';

  use fp\result\ResFailure;
  use fp\result\ResSuccess;
  use fp\config\Configuration;

  function curlJson(string $url, string $data) {
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
        return ResFailure::failure("Error accessing: $url. $error");
      } else {
        $result = json_decode($res);
        
        if($result->failed){
          return ResFailure::failure($result->description);
        } else {
          return ResSuccess::success($result->result);
        }
      }
  }

  function main() {
    ob_clean();
    header("Access-Control-Allow-Origin: *");
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-type: application/json");

    return Configuration::config()->getString('router.url')->map(function($url) {
      $data = json_encode(array('endpoint' => 'dbReader', 'endpointUrl' => 'http://localhost/dbReader/'));

      return curlJson("http://$url/?type=register", $data);
    })->getOrElse(function() {
      return ResFailure::failure('No router url provided in config.properties file!!!');
    });
  }
  
  echo json_encode(main());