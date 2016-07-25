<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  namespace fp\ftry;
  
  use Exception;

  /**
   * Description of Failure
   *
   * @author sirkleber
   */
  class Failure extends FTry {

    private $value;

    function __construct(Exception $value) {
      $this->value = $value;
    }

    public function map(callable $f) {
      return $this;
    }

    public function flatMap(callable $f) {
      return $this;
    }

    public function getOrElse(callable $f) {
      return $f();
    }

    public function isFailure() {
      return true;
    }

    public function isSuccess() {
      return false;
    }

    public function __toString() {
      return "Failure($this->value)";
    }
  }
  