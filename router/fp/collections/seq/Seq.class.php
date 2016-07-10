<?php

/**
 * Description of Seq
 *
 * @author sirkleber
 */

abstract class Seq extends FTraversable{
    
    /**
     * 
     * @param array $args
     * @return Seq
     */
    private static final function construct(array $args){
        if(sizeof($args) === 0){
            return Nil::Nil();
        } else {
            return self::construct(array_slice($args, 1))->cons($args[0]);
        }
    }
    
    /**
     * 
     * @return Seq
     */
    public static final function build(){
        return self::construct(func_get_args());
    }
    
    protected function empty_() {
        return Nil::Nil();
    }
    
    public function cons($item) {
        return new Cons($item, $this);
    }
    
    private function helper(Seq $acc, Seq $other){
        if($other->isEmpty()){
            return $acc;
        } else {
            return $this->helper($acc->cons($other->head()), $other->tail());
        }
    }
    
    public function concat(FTraversable $prefix) {
        return $this->helper($this, $prefix->reverse());
    }
    
    protected function prefix() {
        return "Seq";
    }
    
    protected function toStringFrmt() {
        return new SeqFrmToString();
    }
    
    /**
     * 
     * @return Seq
     */
    public function reverse(){
        return $this->foldLeft($this->empty_(), new SeqReverse());
    }
    
    private function splitR($n, Seq $curL, Seq $pre){
        if($curL->isEmpty()){
            return array($pre->reverse(), $this->empty_());
        } else {
            if($n == 0){
                return array($pre->reverse(), $curL);
            } else {
                return $this->splitR($n - 1, $curL->tail(), $pre->cons($curL->head()));
            }
        }
    }
    
    public function splitAt($n) {
        return $this->splitR($n, $this, $this->empty_());
    }
    
}

class SeqFrmToString implements Fn2{
    public function apply($acc, $item) {
        if($acc === ""){
            return $item;
        } else {
            return "$acc, $item";
        }
    }
}

class SeqReverse implements Fn2{
    public function apply($acc, $item) {
        return $acc->cons($item);
    }
}