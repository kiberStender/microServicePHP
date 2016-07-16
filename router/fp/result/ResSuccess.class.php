<?php

  namespace fp\result;
  
  use fp\maybe\Nothing;

  /**
   * Description of ResSuccess
   *
   * @author sirkleber
   */
  class ResSuccess extends Result {

    public static function success($value) {
      return new ResSuccess($value);
    }

    public function as_($type) {
      return new ResSuccess($this->value, $type, $this->f);
    }

    public function withParser(callable $f) {
      return new ResSuccess($this->value, $this->type, new Just($f));
    }

    private function __construct($value, $type = "json", Maybe $f = null) {
      $this->value = $value;
      $this->type = $type;

      if (isset($f)) {
        $this->f = $f;
      } else {
        $this->f = Nothing::nothing();
      }
    }

    public function isFailure() {
      return false;
    }
  }
  