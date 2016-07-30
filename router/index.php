<?php
  //ini_set('display_errors', 1);
  //ini_set('display_startup_errors', 1);
  //error_reporting(E_ALL);

  include './autoloader.php';
  
  use br\com\microservicePjDemo\router\controller\Controller;
  use fp\result\ResFailure;  
  
  function main(array $get, array $post){
    switch($get['type']){
      case 'register': return Controller::controller()->register(json_decode($post['data']));
      case 'request': return Controller::controller()->request(json_decode($post['data']));
      default: return ResFailure::failure(array($get, $post));
    }
  }
  
  try {
    ob_flush();
    header("Access-Control-Allow-Origin: *");
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-type: application/json");
    echo json_encode(main($_GET, $_POST));
  } catch (Exception $e){
    echo "{\"failed\":true,\"decription\":\"$e\"}";
  }
