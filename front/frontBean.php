<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  include './autoloader.php';
  
  use br\com\microServicePjDemo\front\controller\Controller;
  
  use fp\result\ResFailure;

  function main(array $get, array $post) {
    switch($get['type']){
      case 'login': return Controller::controller()->login($post['user'], $post['pass']);
      default : return ResFailure::failure('Invalid value!!!');
    }
  }

  try {
    ob_flush();
    header("Access-Control-Allow-Origin: *");
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-type: application/json");
    echo json_encode(main($_GET, $_POST));
  } catch (Exception $e) {
    echo "{\"failed\":true,\"decription\":\"$e\"}";
  }

