<?php

/**
 * Description of EmptyMap
 *
 * @author sirkleber
 */

class EmptyMap extends Map{
  private static $emtpym = null;
  
  /**
   * 
   * @return Map
   */
  public static final function EmptyMap(){
    if(!isset(self::$emtpym)){
      self::$emtpym = new EmptyMap();
    }
    return self::$emtpym;
  }
  
  private function __construct() {}
  
  public function isEmpty() {
    return true;
  }
  
  public function head() {
    throw new Exception("No such Element");
  }
  
  public function tail() {
    throw new Exception("No such Element");
  }
  
  public function init() {
    throw new Exception("No such Element");
  }
  
  public function last() {
    throw new Exception("No such Element");
  }
  
  public function maybeHead() {
    return Nothing::Nothing();
  }
  
  public function maybeLast() {
    return Nothing::Nothing();
  }
}