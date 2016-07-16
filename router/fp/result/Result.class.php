<?php

  /**
   * Description of Result
   *
   * @author sirkleber
   */

  namespace fp\result;

  abstract class Result {

    protected $value;
    protected $type;
    protected $f;

    public abstract function isFailure();

    public function getValue() {
      return $this->value;
    }

    public function getType() {
      return $this->type;
    }

    /**
     * @param string $type
     * @return Result
     */
    public abstract function as_($type);

    /**
     * 
     * @param callable $f
     * @return Result
     */
    public abstract function withParser(callable $f);

    public function getParser() {
      return $this->f;
    }
    
    public function __toString() {
      return "Result({$this->value})";
    }
  }
  