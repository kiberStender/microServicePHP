<?php
  
  namespace fp\either;

  /**
   * Description of Either
   *
   * @author sirkleber
   */
  abstract class Either {
    public abstract function value();
    public abstract function isRight();
    public abstract function isLeft();
    public abstract function __toString();

    public function fold(callable $l, callable $r){
      if($this->isRight()){
        return $r($this->value());
      } else {
        return $l($this->value());
      }
    }
  }
  