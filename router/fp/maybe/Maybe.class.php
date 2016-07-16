<?php

  /**
   * Description of Maybe
   *
   * @author sirkleber
   */

  namespace fp\maybe;

  use fp\typeclasses\Monad;

  abstract class Maybe extends Monad {

    public function map(callable $f) {
      if (!$this->isDefined()) {
        return $this;
      } else {
        return Just::just($f($this->get()));
      }
    }

    public function flatMap(callable $f) {
      if (!$this->isDefined()) {
        return $this;
      } else {
        return $f($this->get());
      }
    }

    /**
     * @return boolean
     */
    public abstract function isDefined();

    public abstract function getOrElse(callable $f);

    public abstract function get();

    public abstract function __toString();
  }
  