<?php

  namespace fp\result;

  /**
   * Description of ResFailure
   *
   * @author sirkleber
   */
  class ResFailure extends Result {
    /**
     *
     * @var string
     */
    private $description;

    public static function failure(string $description) {
      return new ResFailure($description);
    }

    private function __construct(string $description) {
      $this->description = $description;
    }

    public function jsonSerialize() {
      return [
          'failed' => true,
          'description' => $this->description,
          'result' => ''
      ];
    }

    public function __toString()  {
      return "Failure({$this->description})";
    }

  }
  