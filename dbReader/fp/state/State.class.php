<?php

  /**
   * Description of State
   *
   * @author sirkleber
   */

  namespace fp\state;

  use fp\typeclasses\Monad;
  
  use fp\utils\unit\Unit;

  class State extends Monad {

    private $run;

    public function __construct(callable $run) {
      $this->run = $run;
    }

    public function map(callable $f) {
      $that = $this;
      return new State(function($s) use($f, $that) {
        $t = $that->run($s);
        return array($t[1], $f($t[0]));
      });
    }

    public function flatMap(callable $f) {
      $that = $this;
      return new State(function($s) use($f, $that) {
        $t = $that->run($s);
        return $f($t[1])->run($t[0]);
      });
    }

    public function evaluate($s) {
      return $this->run($s)[1];
    }

    public static final function insert($a) {
      return new State(function($s) use($a) {
        return array($s, $a);
      });
    }

    public static final function get(callable $f) {
      return new State(function($s) use($f) {
        return array($s, $f($s));
      });
    }

    public static final function mod(callable $f) {
      return new State(function($s) use($f) {
        return array($f($s), Unit::unit());
      });
    }

  }
  