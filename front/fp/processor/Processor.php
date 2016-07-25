<?php

/**
 * Description of Processor
 *
 * @author sirkleber
 */

set_include_path(dirname(__FILE__) . "/../");

require_once 'collections/map/Map.php';
require_once 'collections/seq/Seq.php';
require_once 'processor/Parseable.php';

class Processor {
  
  /**
   * 
   * @param array $arr
   * @return Map
   */
  private static function arrayToMap(array $arr){
    $m = Map::build();
    
    foreach ($arr as $key => $val) {
      $m = $m->cons(array($key, $val));
    }
    
    return $m;
  }
  
  public static final function build(Fn1 $f){
    $res = $f->apply(self::arrayToMap($_POST));
    $prim = Seq::build("string", "array", "integer", "boolean", "float");
    
    if($res->getValue() instanceof FTraversable){
      
      if($prim->constains($res->getValue()->subType())){
        $forPrim = new ForeachPrim($res->getValue() instanceof Map);
        $res->getValue()->fpForeach($forPrim);
        
        self::io(new ArrayPrimParseable($forPrim->getArr(), $res->getType(), $res->getParser()));        
      } else {
        self::io($res->getParser()->get());
      }
    } else {
      if($prim->constains(self::subType($res->getValue()))){
        self::io(new PrimParseable($res->getValue(), $res->getType(), $res->getParser()));
      } else {
        self::io($res->getParser()->get());
      }
    }
  }
  
  private static function io(Parseable $p){
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-type: application/{$p->getContentType()}");
    echo $p->parse();    
  }
  
  private static function subType($val) {
      if(is_object($val)){
        return get_class($val);
      } else {
        return gettype($val);
      }
    }
}

class ForeachPrim implements Fn1 {
  private $arr;
  private $isMap;
  
  function __construct($isMap) {
    $this->arr = array();
    $this->isMap = $isMap;
  }

  public function apply($item) {
    if($this->isMap){
      $this->arr[$item[0]] = $item[1];
    } else {
      $this->arr[] = $item;
    }    
    return $item;
  }
  
  function getArr() {
    return $this->arr;
  }
}