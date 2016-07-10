<?php

/**
 * Description of Maybe
 *
 * @author sirkleber
 */

namespace fp\maybe;

abstract class Maybe extends Monad{
  public function map(callable $f) {
    if($this instanceof Nothing){
      return $this;
    } else {
      return Just::just($f->apply($this->get()));
    }
  }
  
  public function flatMap(callable $f) {
    if($this instanceof Nothing){
      return $this;
      } else {
        return $f->apply($this->get());
      }
  }
  
  public abstract function getOrElse(callable $f);
  
  public abstract function get();
  
  public abstract function __toString();
}