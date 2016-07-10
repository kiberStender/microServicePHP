<?php

/**
 * Description of FTraversable
 *
 * @author sirkleber
 */

namespace fp\collections;

use fp\typeclasses\Monad;
use fp\maybe\Just;
use fp\maybe\Nothing;
use callable;

abstract class FTraversable extends Monad {
  /**
   * @return Boolean Description
   */
  public abstract function isEmpty();
  
  /**
   * @return A The subtype of the Traversable
   */
  public function subType() {
    if(is_object($this->head())){
      return get_class($this->head());
    } else {
      return gettype($this->head());
    }
  }
  
  /**
   * @return A The item itself
   */
  public abstract function head();
  
  /**
   * @return FTraversable The array continuation
   */
  public abstract function tail();
  
  /**
   * @return FTraversable The array continuation
   */
  public abstract function init();
  
  /**
   * @return A The last irem of the array
   */
  public abstract function last();
  
  /**
   * @return Maybe If there is a head, it return a Just with it
   */
  public abstract function maybeHead();
  
  /**
   * @return Maybe If there is a last, it return a Just with it
   */
  public abstract function maybeLast();
  
  /**
   * @return FTraversable 
   */
  protected abstract function empty_();
  
  /**
   * Scala :: and Haskell : functions
   * @param item the item to be appended to the collection
   * @return FTraversable A new collection
   */
  public abstract function cons($item);
  
  /**
   * Scala and Haskell ++ function
   * @param prefix new collection to be concat in the end of this collection
   * @return FTraversable A new collection
   */
  public abstract function concat(FTraversable $prefix);
  
  public final function __toString(){
      return "{$this->prefix()}({$this->foldLeft("", $this->toStringFrmt)})";
  }
  
  protected abstract function prefix();
  
  protected abstract function toStringFrmt($acc, $x);
  
  /**
   * The traversable length
   * @return int
   */
  public function length(){
    return $this->foldLeft(0, function($acc, $_){
        return $acc + 1;
    });
  }
  
  /**
   * A function to traverse the container and matches a given value
   * @param callable $p
   * @return FTraversable
   */
  public function filter(callable $p){
    return $this->foldRight($this->empty_(), function ($x, $acc) use($p){
      if ($p($x)) {
        return $acc->cons($x);
      } else {
        return $acc;
      }
    });
  }
  
  /**
   * A function to traverse the container and return all items that does not 
   * matches the predicate
   * @param callable $p
   * @return FTraversable
   */
  public function filterNot(callable $p){
    return $this->filter(function($x) use($p){return !$p($x);});
  }
  
  /**
   * A function that split the container in two sides. One that matches
   * the given predicate and other that does not.
   * @param callable $p
   * @return FTraversable
   */
  public final function partition(callable $p){
    return array($this->filter($p), $this->filterNot($p));
  }
  
  /**
   * Function to find a given element
   * @param callable $p
   * @return Maybe
   */
  public function find(callable $p){
    if($this->isEmpty()){
      return Nothing::nothing();
    } else {
      if($p($this->head())){
        return Just::just($this->head());
      } else {
        return $this->tail()->find($p);
      }
    }
  }
  
  /**
   * @return Boolean
   */
  public function contains($item){
    return $this->find(function($x) use($item){return $item == $x;}) instanceof Just;
  }
  
  /**
   * A function to split the container in two based on the divisor element
   * @param int $n The divisor element
   * @return array A size 2 array that contains the splited container
   */
  public abstract function splitAt($n);
  
  public function foldLeft($acc, callable $f){
    if($this->isEmpty()){
      return $acc;
    } else {
      return $this->tail()->foldLeft($f($acc, $this->head()), $f);
    }
  }
  
  public function foldRight($acc, callable $f){
    if($this->isEmpty()){
      return $acc;
    } else {
      return $f($this->head(), $this->tail()->foldRight($acc, $f));
    }
  }
  
  public function map(Fn1 $f) {
    if($this->isEmpty()){
      return $this->empty_();
    } else {
      return $this->tail()->map($f)->cons($f->apply($this->head()));
    }
  }
  
  public function flatMap(Fn1 $f) {
    if($this->isEmpty()){
      return $this->empty_();
    } else {
      return $this->tail()->flatMap($f)->concat($f->apply($this->head()));
    }
  }
}

class ToStringFrm implements Fn2{
  private $frmt;
  
  function __construct(Fn2 $frmt) {
    $this->frmt = $frmt;
  }
  
  public function apply($acc, $item) {
    return $this->frmt->apply($acc, $item);
  }
}

class SumFoldLeft implements Fn2{
  public function apply($acc, $item) {
    return $acc + $item;
  }
}

class LengthFoldLeft implements Fn2{
  public function apply($a, $b) {
    return $a + 1;
  }
}

class FilterFoldRight implements Fn2 {
  private $p;
  
  public function __construct(Fn1 $p) {
    $this->p = $p;
  }
  
  public function apply($item, $acc) {
    if($this->p->apply($item)){
      return $acc->cons($item);
    } else {
      return $acc;
    }
  }
}

class FilterNot implements Fn1{
  private $p;
  
  public function __construct(Fn1 $p) {
    $this->p = $p;
  }
  
  public function apply($item) {
    return !$this->p->apply($item);
  }
}

class FindContains implements Fn1{
  private $item;
  
  function __construct($item) {
    $this->item = $item;
  }
  
  public function apply($a) {
    return $this->item == $a;
  }
}
