<?php
/**
 * Description of Try
 *
 * @author sirkleber
 */
require_once "fn/Fn.php";
require_once 'typeclasses/Monad.php';

abstract class FTry extends Monad {
    public static final function build(Fn $f){
        try {
            return new Success($f->apply());
        } catch (Exception $ex) {
            return new Failure($ex);
        }
    }
    
    public abstract function getOrElse(Fn $f);

    public abstract function isSuccess();

    public abstract function isFailure();
}

class Success extends FTry {
    private $value;
    
    function __construct($value) {
        $this->value = $value;
    }
    
    public function map(Fn1 $f) {
        return FTry::build(new MapFn($f->apply($this->value)));
    }
    
    public function flatMap(Fn1 $f) {
        try {
            return $f->apply($this->value);
        } catch (Exception $ex) {
            return new Failure($ex);
        }
    }
    
    public function getOrElse(Fn $f) {
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

class Failure extends FTry {
    private $value;
    
    function __construct(Exception $value) {
        $this->value = $value;
    }
    
    public function map(Fn1 $f) {
        return $this;
    }
    
    public function flatMap(Fn1 $f) {
        return $this;
    }
    
    public function getOrElse(Fn $f) {
        return $f->apply();
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

class MapFn implements Fn{
    private $val;
    function __construct($val) {
        $this->val = $val;
    }

    public function apply() {
        return $this->val;
    }
}
