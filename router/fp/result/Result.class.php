<?php

  /**
   * Description of Result
   *
   * @author sirkleber
   */

  namespace fp\result;
  
  use JsonSerializable;

  abstract class Result implements JsonSerializable{    
    public final function toJson(){
      return json_encode($this);
    }

        public function __toString() {
      return "Result({$this->value})";
    }
  }
  