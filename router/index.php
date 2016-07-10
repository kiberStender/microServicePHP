<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  include './autoloader.php';
  
  use br\com\microservicePjDemo\router\controller\Controller;
  
  try {
  
    switch($_GET['type']){
      case 'register': 
        Controller::controller()->register(json_decode($_POST['data']));
        break;
      case 'request':
        Controller::controller()->request(json_decode($_POST['data']));
        break;
      default:
        echo var_dump($_POST['data']);
        break;
    }
  } catch (Exception $e){
    echo "{\"failed\":true,\"decription\":\"$e\"}";
  }
