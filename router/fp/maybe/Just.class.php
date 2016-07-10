<?php

/**
 * Description of Maybe
 *
 * @author sirkleber
 */

namespace fp\maybe;

class Just extends Maybe {
    private static $just_ = null;
    private $value;
    
    private function __construct($value) {
        $this->value = $value;
    }
    
    public static function just($value){
        return new Just($value);
    }

    public function getOrElse(callable $f) {
        return $this->value;
    }
    
    public function get() {
        return $this->value;
    }
    
    public function __toString() {
        return "Just($this->value)";
    }
}