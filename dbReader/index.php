<?php
  //ini_set('display_errors', 1);
  //ini_set('display_startup_errors', 1);
  //error_reporting(E_ALL);
  
  include './autoloader.php';
  
  use br\com\microServicePjDemo\dbreader\controller\Controller;
  
  /**
   * 
   * @return \fp\result\Result
   */
  function main(array $get, $post){
    return Controller::controller()->select(json_decode($post['data']));
  }
  
  try {
    ob_clean();
    header("Access-Control-Allow-Origin: *");
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-type: application/json");
    echo json_encode(main($_GET, $_POST));
  } catch (Exception $e){
    echo "{\"failed\":true,\"decription\":\"$e\"}";
  }