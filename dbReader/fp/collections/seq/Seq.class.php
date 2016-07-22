<?php

  namespace fp\collections\seq;

  use fp\collections\FTraversable;

  /**
   * Description of Seq
   *
   * @author sirkleber
   */
  abstract class Seq extends FTraversable {

    /**
     * 
     * @return Seq
     */
    public static function seq(...$args) {
      
      $construct = function (array $args){
        $seq = Nil::nil();
        
        foreach ($args as $value){
          $seq = $seq->cons($value);
        }
        return $seq;
      };
      
      return $construct($args);
    }

    protected function empty_() {
      return Nil::nil();
    }

    public function cons($item) {
      return Cons::cons_($item, $this);
    }

    public function concat(FTraversable $prefix) {

      $helper = function(Seq $acc, Seq $other) use(&$helper) {
        if ($other->isEmpty()) {
          return $acc;
        } else {
          return $helper($acc->cons($other->head()), $other->tail());
        }
      };

      return $helper($this, $prefix->reverse());
    }

    protected function prefix() {
      return "Seq";
    }

    protected function toStringFrmt() {
      return function ($acc, $item) {
        if ($acc === "") {
          return $item;
        } else {
          return "$acc, $item";
        }
      };
    }

    /**
     * Function that inverts the order of the items
     * @return Seq
     */
    public function reverse() {
      return $this->foldLeft($this->empty_(), function ($acc, $x) {
            return $acc->cons($x);
          });
    }

    public function splitAt($n) {

      function splitR($n, Seq $curL, Seq $pre) {
        if ($curL->isEmpty()) {
          return array($pre->reverse(), $this->empty_());
        } else {
          if ($n == 0) {
            return array($pre->reverse(), $curL);
          } else {
            return splitR($n - 1, $curL->tail(), $pre->cons($curL->head()));
          }
        }
      }

      return splitR($n, $this, $this->empty_());
    }

  }
  