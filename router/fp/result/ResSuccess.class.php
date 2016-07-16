<?php

  namespace fp\result;
  
  use JsonSerializable;

  /**
   * Description of ResSuccess
   *
   * @author sirkleber
   */
  class ResSuccess extends Result {
    /**
     *
     * @var JsonSerializable
     */
    private $value;

    public static function success($value) {
      return new ResSuccess($value);
    }

    private function __construct($value) {
      $this->value = $value;
    }

    public function jsonSerialize() {
      return [
          'failed' => false,
          'description' => '',
          'result' => $this->value
      ];
    }
  }
  