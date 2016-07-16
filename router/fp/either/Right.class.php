<?php

  namespace fp\either;

  /**
   * Description of Right
   *
   * @author sirkleber
   */
  class Right extends Either{
    private $value;
    
    private function __construct($value) {
      $this->value = $value;
    }
    
    public static function right($value){
      return new Right($value);
    }

    public function value() {
      return $this->value;
    }

    public function isLeft() {
      return false;
    }

    public function isRight() {
      return true;
    }

    public function __toString() {
      return "Right($this->value)";
    }
  }
  