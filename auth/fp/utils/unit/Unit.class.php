<?php

  namespace fp\utils\unit;
  /**
   * Description of Unit
   *
   * @author sirkleber
   */
  class Unit {
    private static $_unit = null;
    private function __construct(){}
    
    public static function unit(){
      if(!isset(self::$_unit)){
        self::$_unit = new Unit();
      }
      
      return self::$_unit;
    }
    
    public function __toString() {
      return "Unit()";
    }
  }
  