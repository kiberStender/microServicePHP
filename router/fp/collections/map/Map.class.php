<?php

/**
 * Description of Map
 *
 * @author sirkleber
 */

abstract class Map extends FTraversable{
  /**
   *  
   * @param array $args
   * @return Map
   */
  private static final function construct(array $args){
    if(sizeof($args) === 0){
      return EmptyMap::EmptyMap();
    } else {
      return self::construct(array_slice($args, 1))->cons($args[0]);
    }
  }
  
  /**
   *  
   * @return Map
   */
  public static final function build(){
    return self::construct(func_get_args());
  }
  
  protected function empty_() {
    return EmptyMap::EmptyMap();
  }
  
  /**
   * 
   * @param type $item
   * @return \KVMapCons
   */
  protected function add($item){
    return new KVMap($item, $this);
  }
  
  private function compareTo($_key1, $_key2){
    if($_key1 == $_key2) {
      return 0;
    } elseif($_key1 < $_key2){
      return -1;
    } else {
      return 1;
    }
  }
  
  public function cons($item) {
    if($this->isEmpty()){
      return $this->add($item);
    } else {
      $head_ = $this->head();
      switch ($this->compareTo($item[0], $head_[0])){
        case 1: return $this->tail()->cons($item)->add($head_);
        case 2: 
          if($item[1] === $head_[1]){
            return $this;
          } else {
            return $this->tail()->cons($item);
          }
        default : return $this->tail()->add($head_)->add($item);
      }
    }
  }
  
  private function helper(Map $acc, Map $other){
    if($other->isEmpty()){
      return $acc;
    } else {
      return $this->helper($acc->cons($other->head()), $other->tail());
    }
  }
  
  public function concat(FTraversable $prefix) {
    return $this->helper($this, $prefix);
  }
  
  /**
   * 
   * @param type $key
   * @return \Maybe
   */
  public function get($key){
    $n = $this->length();
    
    switch($n){
      case 0: return Nothing::Nothing();
      case 1: 
        $x = $this->head();
        if($x[0] === $key){
          return new Just($x[1]);
        }else {
          return Nothing::Nothing();
        }
      default:
        $tp = $this->splitAt(round($n / 2));
        $yh = $tp[1]->head();
        
        if($this->compareTo($yh[0], $key) > 0){
          return $tp[0]->get($key);
        } else {
          return $tp[1]->get($key);
        }
    }
  }
  
  protected function prefix() {
    return "Map";
  }
  
  protected function toStringFrmt() {
    return new MapFrmToString();
  }
  
  private function splitR($n, Map $curL, Map $pre){
    
    if($curL->isEmpty()){
      return array($pre, $this->empty_());
    } else {
      if($n == 0){
        return array($pre, $curL);
      } else {
        return $this->splitR($n - 1, $curL->tail(), $pre->cons($curL->head()));
      }
    }
  }
  
  public function splitAt($n) {
    return $this->splitR($n, $this, $this->empty_());
  }
}

class MapFrmToString implements Fn2{
  public function apply($acc, $item) {
    if($acc === ""){
      return "($item[0] -> $item[1])";
    } else {
      return "$acc, ($item[0] -> $item[1])";
    }
  }
}

