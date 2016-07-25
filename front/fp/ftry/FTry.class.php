<?php

  /**
   * Description of Try
   *
   * @author sirkleber
   */

  namespace fp\ftry;

  use fp\typeclasses\Monad;
  use Exception;

  abstract class FTry extends Monad {

    /**
     * Builder dsl to construct a FTry object
     * @param callable $f
     * @return FTry
     */
    public static final function ftry(callable $f) {
      try {
        return new Success($f());
      } catch (Exception $ex) {
        return new Failure($ex);
      }
    }

    public abstract function getOrElse(callable $f);

    public abstract function isSuccess();

    public abstract function isFailure();
  }