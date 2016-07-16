<?php

  namespace fp\result;

  use fp\maybe\Nothing;

  /**
   * Description of ResFailure
   *
   * @author sirkleber
   */
  class ResFailure extends Result {

    public static function failure($value) {
      return new ResFailure($value);
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

    public function as_($type) {
      return new ResFailure($this->value, $type, $this->f);
    }

    public function withParser(callable $f) {
      return new ResFailure($this->value, $this->type, new Just($f));
    }

    public function isFailure() {
      return true;
    }
  }
  