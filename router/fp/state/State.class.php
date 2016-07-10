<?php

/**
 * Description of State
 *
 * @author sirkleber
 */
class State extends Monad {

  /**
   *
   * @var Fn1
   */
  private $run;

  function __construct(Fn1 $run) {
    $this->run = $run;
  }
  
  /**
   * 
   * @return Fn1
   */
  public function getRun(){
    return $this->run;
  }

  public function map(Fn1 $f) {
    return new State(new MapFn1($this->run, $f));
  }

  public function flatMap(Fn1 $f) {
    return new State(new FlatMapFn1($this->run, $f));
  }

  public function evaluate($s) {
    $t = $this->run->apply($s);
    return $t[1];
  }

  public static final function insert($a) {
    return new State(new InsertFn1($a));
  }

  public static final function get(Fn1 $f) {
    return new State(new GetFn1($f));
  }

  public static final function mod(Fn1 $f) {
    return new State(new ModFn1($f));
  }

}

class MapFn1 implements Fn1 {

  public $run;
  private $f;

  function __construct(Fn1 $run, Fn1 $f) {
    $this->run = $run;
    $this->f = $f;
  }

  public function apply($s) {
    $t = $this->run->apply($s);
    return array($t[1], $this->f->apply($t[0]));
  }

}

class FlatMapFn1 implements Fn1 {

  private $run;
  private $f;

  function __construct(Fn1 $run, Fn1 $f) {
    $this->run = $run;
    $this->f = $f;
  }

  public function apply($s) {
    $t = $this->run->apply($s);
    return $this->f->apply($t[1])->getRun()->apply($t[0]);
  }

}

class InsertFn1 implements Fn1 {

  private $a;

  function __construct($a) {
    $this->a = $a;
  }

  public function apply($s) {
    return array($s, $this->a);
  }

}

class GetFn1 implements Fn1 {

  private $fn;

  function __construct(Fn1 $fn) {
    $this->fn = $fn;
  }

  public function apply($s) {
    return array($s, $this->fn->apply($s));
  }

}

class ModFn1 implements Fn1 {

  private $fn;

  function __construct(Fn1 $fn) {
    $this->fn = $fn;
  }

  public function apply($s) {
    return array($this->fn->apply($s), array());
  }

}
