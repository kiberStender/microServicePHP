<?php

  /**
   * Description of SQL
   *
   * @author sirkleber
   */

  namespace fp\db;

  use fp\collections\seq\Seq;
  use fp\collections\map\Map;
  use fp\maybe\Maybe;
  use fp\utils\unit\Unit;
  use fp\either\Left;
  use fp\either\Right;
  use PDO;

  class SQL {
    private $query;
    private $m;

    /**
     * 
     * @param PDO $pdo
     * @param string $query
     * @return \SQL
     */
    public static function sql($query) {
      return new SQL($query, Map::map_());
    }

    private function __construct($query, Map $m) {
      $this->query = $query;
      $this->m = $m;
    }
    
    /**
     * 
     * @param callable $f PDOStatement -> Any
     * @return \fp\either\Either
     */
    private function perform(callable $f){
      $that = $this;
      return function(PDO $pdo) use($that, $f){
        $st = $pdo->prepare($this->query);
        
        $that->m->map(function($tp)use(&$st) {
          list($k, $v) = $tp;
          $st->bindValue($k, $v);
          return Unit::unit();
        });
        
        $st->execute();
        $error = $st->errorInfo();
        
        if(isset($error[1])){
          return Left::left($error);
        } else {
          return Right::right($f($st));
        }
      };
    }

    /**
     * Funtion for insert, delete, updates and procedures sql statements
     * @param Map $m
     * @return SQL
     */
    public function on(Map $m) {
      return new SQL($this->query, $m);
    }

    /**
     * Function for mapping the object Array that cames from select statement
     * into a Seq of A's
     * @param callable $f
     * @return A
     */
    public function as_(callable $f) {
      return $this->perform(function($st) use($f){
        $arr = Seq::seq();
        
        foreach ($st as $value) {
          $row = new Row();
          
          foreach ($value as $k => $v){
            if(!is_numeric($k)){
              $row = $row->withColumn(array($k, $v));
            }
          }
          $arr = $arr->cons($row);
        }
        
        return $arr->map($f)
            ->filter(function(Maybe $mb){return $mb->isDefined();})
            ->map(function(Maybe $mb){return $mb->get();});
      });
    }

    /**
     * Function to execute updates, inserts and deletes
     * @return int
     */
    public function executeUpdate() {
      return $this->perform(function($st){
        return $st->rowCount();
      });      
    }

  }
  