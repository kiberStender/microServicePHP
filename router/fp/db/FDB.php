<?php

/**
 * Description of FDB
 *
 * @author sirkleber
 */
set_include_path(dirname(__FILE__) . "/../");

require_once 'maybe/Maybe.php';

class FDB {
  
  /**
   * Static function for getting the database configuration
   * @version 2.0
   * @return Maybe
   */
  private final static function getDBConfig() {
    $filename = Utils::getServer() . "config/dbconfig.json";
    $file = file_get_contents($filename);
    
    if($file == NULL){
      return Nothing::Nothing();
    } else {
      return new Just(json_decode($file));
    }
  }
  
  /**
   * Methodo para conexão com banco de dados
   * @version 3.5
   * @return Maybe
   */
  private final static function connect(){
    return self::getDBConfig()->flatMap(new GetDbConfFlatMap());
  }
  
  private final static function disconnect(array $arr){
    $pdo = $arr[0];
    $data = $arr[1];
    
    $pdo = null;
    return $data;
  }
  
  /**
   * 
   * @param Fn1 $fn
   * @version 3.5
   * @return Maybe
   */
  public static function withConnection(Fn1 $fn){
    return self::disconnect(self::connect()->flatMap(new ConnectFlatMap($fn)));
  }
  
}

class GetDbConfFlatMap implements Fn1{
  public function apply($json) {
    $dsn = "";
    
    if ($json->db == ""){
      $dsn = "{$json->dbms}:host={$json->host};port={$json->port};";
    } else{
      $dsn = "{$json->dbms}:host={$json->host};dbname={$json->db};port={$json->port};";
    }
    
    try {
      $pdo = new PDO($dsn, $json->username, $json->passwd);
      
      return new Just(array($pdo, null));
    } catch (PDOException $ex) {
      return new Just(array(null, $ex->getMessage()));
    }
  }
}

class ConnectFlatMap implements Fn1{
  private $fn;
  
  function __construct(Fn1 $fn) {
    $this->fn = $fn;
  }

  public function apply($tuple) {
    $pdo = $tuple[0];
    $ex = $tuple[1];
    
    if(isset($pdo) && $pdo instanceof PDO){
      return array($pdo, $this->fn->apply($pdo));
    } else {
      return array($pdo, $ex);
    }
  }
}