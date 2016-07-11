<?php
  namespace br\com\microservicePjDemo\router\controller;
    
  use stdClass;
  use PDO;
  use fp\result\ResSuccess;
  use fp\result\ResFailure;
    
  class Controller {
    private static $_instance = null;
    private $dbPath;
      
    private function __construct(){
      $this->dbPath = "sqlite:./resources/services.sq3";
    }
      
    public static function controller(){
      if(self::$_instance == null) {
        self::$_instance = new Controller();
      }
        
      return self::$_instance;
    }
      
    private function getStringEndpoints($arr){
      $arr_ = array();
        
      foreach($arr as $end){
        array_push($arr_, $end['endpointUrl']);
      }
        
      return $arr_;
    }
      
    private function withConnection($fn){
      $pdo = new PDO($this->dbPath);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
      $fn($pdo);
      $pdo = null;
    }
      
    public function register(stdClass $obj){
      $this->withConnection(function($pdo) use ($obj){
        $st = $pdo->prepare("Insert into service(endpoint, endpointUrl) values(:endpoint, :endpointUrl);");
        
        $st->bindValue(":endpoint", $obj->endpoint);
        $st->bindValue(":endpointUrl", $obj->endpointUrl);
        
        $st->execute();
        
        if($st->rowCount() > 0){
          echo ResSuccess::success('Endpoint inserted');
        } else {
          echo ResFailure::failure('Failure ant insertind endpoint');
        }
      });
    }
      
    private function curlJson($url, $data){
      $ch = curl_init($url . '/');
              
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/x-www-form-urlencoded'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
              
      //execute post              
      $res = curl_exec($ch);
              
      if(!$res){
        echo ResFailure::failure($res);
      } else {
        echo ResSuccess::success($res);
      }
            
      curl_close($ch);
    }
      
    public function request(stdClass $obj){
      $this->withConnection(function($pdo) use($obj){
          
        $st = $pdo->prepare("Select endpointUrl from service where endpoint = :endpoint");
        
        $st->bindValue(":endpoint", $obj->endpoint);
        
        $st->execute();
          
        $endpoints = $this->getStringEndpoints($st);
          
        if(sizeof($endpoints) >= 1){
          foreach($endpoints as $url){
            $this->curlJson($url, json_encode($obj->data));
          }
        } else {
          echo ResFailure::failure("No endpoint");
        }
      });
    }
  }
