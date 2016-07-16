<?php
  namespace br\com\microservicePjDemo\router\controller;
    
  use stdClass;
  use PDO;
  use fp\result\ResSuccess;
  use fp\result\ResFailure;
  use fp\collections\map\Map;
  use fp\collections\seq\Seq;
  use fp\db\FDB;
  use fp\db\SQL;
  use fp\db\Row;
  use fp\utils\unit\Unit;
    
  class Controller {
    private static $_instance = null;
      
    private function __construct(){}
      
    public static function controller(){
      if(self::$_instance == null) {
        self::$_instance = new Controller();
      }
        
      return self::$_instance;
    }
    
    public function register(stdClass $obj){
      return FDB::db()->withConnection(
        SQL::sql('Insert into service(endpoint, endpointUrl) values(:endpoint, :endpointUrl);')
          ->on(Map::map_(array(':endpoint', $obj->endpoint), array(':endpointUrl', $obj->endpointUrl)))
          ->executeUpdate()
      )->fold(
        function($error){
          echo ResFailure::failure($error);
          return Unit::unit();
        },
        function($rows){
          if($rows > 0){
            echo ResSuccess::success('Endpoint inserted');
          } else {
            echo ResFailure::failure('Failure ant inserting endpoint');
          }
          return Unit::unit();
        }
      );
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
      $that = $this;
      return FDB::db()->withConnection(
        SQL::sql('Select endpointUrl from service where endpoint = :endpoint')
          ->on(Map::map_(array(':endpoint', $obj->endpoint)))
          ->as_(function(Row $row){return $row->getColumn('endpointUrl');})
      )->fold(
        function($error){
          echo ResFailure::failure($error);
          return Unit::unit();
        }, 
        function(Seq $urls)use($obj, $that){
          if($urls->length() >= 1){
            $that->curlJson($urls->head(), $obj->data);
          } else {
            echo ResFailure::failure('No endpoint');
          }                  
          return Unit::unit();
        }
      );
    }
  }
