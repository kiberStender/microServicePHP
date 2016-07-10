<?php

/**
 * Description of SQL
 *
 * @author sirkleber
 */
set_include_path(dirname(__FILE__) . "/../");

include_once 'collections/Seq.php';

class SQL implements Functor{
    private $query;
    private $pdo;
    private $st;
    
    /**
     * 
     * @param PDO $pdo
     * @param string $query
     * @return \SQL
     */
    public static function sql(PDO $pdo, $query){
        return new SQL($pdo, $query);
    }
    
    private function __construct(PDO $pdo, $query, PDOStatement $st = null) {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->st = $st;
    }
    
    /**
     * Funtion for insert, delete, updates and procedures sql statements
     * @param Map $m
     * @return SQL
     */
    public function on(Map $m){
      $call = new OnFpForeach($this->pdo->prepare($this->query));
      $m->fpForeach($call);
      return new SQL($this->pdo, $this->query, $call->getSt());
    }
    
    /**
     * Function for mapping the object Array that cames from select statement
     * into a Seq of A's
     * @param Fn1 $f
     * @return Seq
     */
    public function as_(Fn1 $f){
      $st = $this->pdo->prepare($this->query);
      $arr = Nil::Nil();
      
      foreach ($st as $value){
        $arr = $arr->cons($f->apply($value));
      }
      
      return $arr;
    }
    
    public function executeUpdate(){
      return $this->st->execute();
    }
    
    public function map(Fn1 $f) {
      $arr = Nil::Nil();
      
      foreach ($this->st as $value){
        $arr = $arr->cons($f->apply($value));
      }      
      return $arr;
    }
}

class OnFpForeach implements Fn1{
  private $st;
  
  function __construct(PDOStatement $st) {
    $this->st = $st;
  }
  
  /**
   * 
   * @return PDOStatement
   */
  function getSt() {
    return $this->st;
  }
  
  public function apply($m) {
    $this->st->bindValue(":$m[0]", $m[1]);
    return $m;
  }
}