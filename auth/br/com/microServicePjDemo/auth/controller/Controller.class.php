<?php

  namespace br\com\microServicePjDemo\auth\controller;

  /**
   * Description of Controller
   *
   * @author sirkleber
   */
  class Controller {

    private static $controller_ = null;

    private function __construct() {}

    /**
     * 
     * @return Controller
     */
    public static function controller() {
      if (!isset(self::$controller_)) {
        self::$controller_ = new Controller();
      }
      return self::$controller_;
    }

  }
  