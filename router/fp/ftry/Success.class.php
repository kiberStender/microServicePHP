<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  namespace fp\ftry;
  
  use Exception;

  /**
   * Description of Success
   *
   * @author sirkleber
   */
  class Success extends FTry {

    private $value;

    public function __construct($value) {
      $this->value = $value;
    }

    public function map(callable $f) {
      $that = $this;
      return FTry::ftry(function() use($that, $f){return $f($that->value);});
    }

    public function flatMap(callable $f) {
      try {
        return $f($this->value);
      } catch (Exception $ex) {
        return new Failure($ex);
      }
    }

    public function getOrElse(callable $f) {
      return $this->value;
    }

    public function isFailure() {
      return false;
    }

    public function isSuccess() {
      return true;
    }

    public function __toString() {
      return "Success($this->value)";
    }

  }
  