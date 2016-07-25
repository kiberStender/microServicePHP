<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  include './autoloader.php';
  
  use br\com\microServicePjDemo\front\controller\Controller;
  
  use fp\result\ResFailure;

  function main(array $args) {
    switch($args['type']){
      case 'login': return Controller::controller()->login($_POST['user'], $_POST['pass']);
      default : return ResFailure::failure('Invalid value!!!');
    }
  }

  try {
    header("Access-Control-Allow-Origin: *");
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-type: application/json");
    echo json_encode(main($_GET));
  } catch (Exception $e) {
    echo "{\"failed\":true,\"decription\":\"$e\"}";
  }

