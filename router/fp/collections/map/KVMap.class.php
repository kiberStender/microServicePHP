<?php
/**
 * Description of KVMap
 *
 * @author sirkleber
 */

class KVMap extends Map {
  private $head_;
  private $tail_;
  
  function __construct($head_, Map $tail_) {
    $this->head_ = $head_;
    $this->tail_ = $tail_;
  }
  
  public function isEmpty() {
    return false;
  }
  
  public function head() {
    return $this->head_;
  }
  
  public function tail() {
    return $this->tail_;
  }
  
  public function init() {
    if($this->tail_->isEmpty()){
      return $this->empty_();
    } else {
      return $this->tail_->init()->cons($this->head_);
    }
  }
  
  public function last() {
    if($this->tail_->isEmpty()){
      return $this->head_;
    } else {
      return $this->tail_->last();
    }
  }
  
  public function maybeHead() {
    return new Just($this->head_);
  }
  
  public function maybeLast() {
    return new Just($this->last());
  }
}