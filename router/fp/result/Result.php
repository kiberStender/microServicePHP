<?php

/**
 * Description of Result
 *
 * @author sirkleber
 */

set_include_path(dirname(__FILE__) . "/../");

require_once 'maybe/Maybe.php';

abstract class Result {
  protected $value;
  protected $type;
  protected $f;
  
  public abstract function isFailure();
  
  public function getValue(){
    return $this->value;
  }
  
  public function getType(){
    return $this->type;
  }
  
  /**
   * @param string $type
   * @return Result
   */
  public abstract function as_($type);
  
  /**
   * 
   * @param Fn1 $f
   * @return Result
   */
  public abstract function withParser(Fn1 $f);
  
  public function getParser(){
    return $this->f;
  }
}

class ResFailure extends Result{
  
  public static function build($value){
    return new ResFailure($value);
  }
  
  private function __construct($value, $type = "json", Maybe $f = null) {
    $this->value = $value;
    $this->type = $type;
    
    if(isset($f)){
      $this->f = $f;
    } else {
      $this->f = Nothing::Nothing();
    }
  }
  
  public function as_($type) {
    return new ResFailure($this->value, $type, $this->f);
  }
  
  public function withParser(Fn1 $f) {
    return new ResFailure($this->value, $this->type, new Just($f));
  }

  
  public function isFailure() {
    return true;
  }
}

class ResSuccess extends Result{
  
  public static function build($value){
    return new ResSuccess($value);
  }
  
  public function as_($type) {
    return new ResSuccess($this->value, $type, $this->f);
  }
  
  public function withParser(Fn1 $f) {
    return new ResSuccess($this->value, $this->type, new Just($f));
  }
  
  private function __construct($value, $type = "json", Maybe $f = null) {
    $this->value = $value;
    $this->type = $type;
    
    if(isset($f)){
      $this->f = $f;
    } else {
      $this->f = Nothing::Nothing();
    }
  }  
  
  public function isFailure() {
    return false;
  }
}