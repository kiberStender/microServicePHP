<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  include './autoloader.php';
  
  use br\com\microServicePjDemo\dbreader\controller\Controller;
  
  /**
   * 
   * @return \fp\result\Result
   */
  function main(){
    return Controller::controller()->select(json_decode($_POST['data']));
  }
  
  try {
    echo json_encode(main());
  } catch (Exception $e){
    echo "{\"failed\":true,\"decription\":\"$e\"}";
  }