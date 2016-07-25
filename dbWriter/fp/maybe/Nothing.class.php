<?php

/**
 * Description of Nothing
 *
 * @author sirkleber
 */

namespace fp\maybe;

class Nothing extends Maybe{
    
    private function __construct() {}
    
    private static $not = null;
    
    /**
     * 
     * @return Maybe
     */
    public static final function nothing(){
        if(!isset(self::$not)){
            self::$not = new Nothing();
        }
        return self::$not;
    }
    
    public function getOrElse(callable $f) {
        return $f();
    }
    
    public function get() {
        throw new Exception("No such element");
    }
    
    public function __toString() {
        return "Nothing";
    }

    public function isDefined() {
      return false;
    }

  }