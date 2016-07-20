<?php

  /**
   * Description of Map
   *
   * @author sirkleber
   */

  namespace fp\collections\map;

  use fp\collections\FTraversable;
  use fp\maybe\Just;
  use fp\maybe\Nothing;

  abstract class Map extends FTraversable {

    /**
     *  
     * @return Map
     */
    public static final function map_(...$args) {
      $construct = function(array $args){
        $map = EmptyMap::emptyMap();
        
        foreach($args as $item){
          $map = $map->cons($item);
        }
        
        return $map;
      };

      return $construct($args);
    }

    protected function empty_() {
      return EmptyMap::EmptyMap();
    }

    /**
     * 
     * @param type $item
     * @return \KVMapCons
     */
    protected function add($item) {
      return new KVMap($item, $this);
    }

    private function compareTo($_key1, $_key2) {
      if ($_key1 == $_key2) {
        return 0;
      } elseif ($_key1 < $_key2) {
        return -1;
      } else {
        return 1;
      }
    }

    public function cons($item) {
      if ($this->isEmpty()) {
        return $this->add($item);
      } else {
        $head_ = $this->head();
        switch ($this->compareTo($item[0], $head_[0])) {
          case 1: return $this->tail()->cons($item)->add($head_);
          case 2:
            if ($item[1] === $head_[1]) {
              return $this;
            } else {
              return $this->tail()->cons($item);
            }
          default : return $this->tail()->add($head_)->add($item);
        }
      }
    }

    public function concat(FTraversable $prefix) {
      $helper = function(Map $acc, Map $other) use ($helper) {
        if ($other->isEmpty()) {
          return $acc;
        } else {
          return $helper($acc->cons($other->head()), $other->tail());
        }
      };
      return $helper($this, $prefix);
    }

    /**
     * 
     * @param type $key
     * @return \Maybe
     */
    public function get($key) {
      $n = $this->length();

      switch ($n) {
        case 0: return Nothing::nothing();
        case 1:
          $x = $this->head();
          if ($x[0] === $key) {
            return Just::just($x[1]);
          } else {
            return Nothing::nothing();
          }
        default:
          $tp = $this->splitAt(round($n / 2));
          $yh = $tp[1]->head();

          if ($this->compareTo($yh[0], $key) > 0) {
            return $tp[0]->get($key);
          } else {
            return $tp[1]->get($key);
          }
      }
    }

    protected function prefix() {
      return "Map";
    }

    protected function toStringFrmt() {
      return function($acc, $item) {
        if ($acc === "") {
          return "($item[0] -> $item[1])";
        } else {
          return "$acc, ($item[0] -> $item[1])";
        }
      };
    }

    public function splitAt($n) {
      $splitR = function($n, Map $curL, Map $pre) use(&$splitR) {
        if ($curL->isEmpty()) {
          return array($pre, Map::map_());
        } else {
          if ($n == 0) {
            return array($pre, $curL);
          } else {
            return $splitR($n - 1, $curL->tail(), $pre->cons($curL->head()));
          }
        }
      };
      return $splitR($n, $this, $this->empty_());
    }

  }