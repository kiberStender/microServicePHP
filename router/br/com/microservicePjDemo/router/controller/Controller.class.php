<?php
  namespace br\com\microservicePjDemo\router\controller;
    
  use stdClass;
  use fp\result\{ResSuccess, ResFailure, Result};
  use fp\collections\{map\Map, seq\Seq};
  use fp\db\{FDB, SQL, Row};
    
  class Controller {
    private static $_instance = null;
      
    private function __construct(){}
      
    public static function controller(){
      if(self::$_instance == null) {
        self::$_instance = new Controller();
      }        
      return self::$_instance;
    }
    
    public function register(stdClass $obj): Result {
      return FDB::db()->withConnection(
        SQL::sql('Insert into service(endpoint, endpointUrl) values(:endpoint, :endpointUrl);')
          ->on(array(':endpoint', $obj->endpoint), array(':endpointUrl', $obj->endpointUrl))
          ->executeUpdate()
      )->fold(
        function(string $error){
          return ResFailure::failure($error);
        },
        function(int $rows){
          if($rows > 0){
            return ResSuccess::success('Endpoint inserted');
          } else {
            return ResFailure::failure('Failure ant inserting endpoint');
          }
        }
      );
    }
      
    private function curlJson($url, $data): Result{
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
        return ResFailure::failure($error);
      } else {
        $result = json_decode($res);
        
        if($result->failed){
          return ResFailure::failure($result->description);
        } else {
          return ResSuccess::success($result->result);
        }
      }
    }
    
    public function request(stdClass $obj): Result{
      $that = $this;
      return FDB::db()->withConnection(
        SQL::sql('Select endpointUrl from service where endpoint = :endpoint')
          ->on(array(':endpoint', $obj->endpoint))
          ->as_(function(Row $row){return $row->getColumn('endpointUrl');})
      )->fold(
        function($error){
          return ResFailure::failure($error);
        }, 
        function(Seq $urls)use($obj, $that){
          if($urls->length() >= 1){
            return $that->curlJson($urls->head(), $obj->data);
          } else {
            return ResFailure::failure('No endpoint');
          }
        }
      );
    }
  }
