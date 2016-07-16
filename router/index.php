<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  include './autoloader.php';
  
  use br\com\microservicePjDemo\router\controller\Controller;
  use fp\result\ResFailure;  
  
  function main(){
    switch($_GET['type']){
      case 'register': return Controller::controller()->register(json_decode($_POST['data']));
      case 'request': return Controller::controller()->request(json_decode($_POST['data']));
      default: return ResFailure::failure(array($_GET, $_POST));
    }
  }
  
  try {
  
    echo json_encode(main());
  } catch (Exception $e){
    echo "{\"failed\":true,\"decription\":\"$e\"}";
  }
