<?php

  namespace br\com\microServicePjDemo\front\controller;

  use fp\result\ResFailure;
  use fp\result\ResSuccess;
  use fp\config\Configuration;

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

    private function curlJson($url, $data) {
      $ch = curl_init($url);

      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/x-www-form-urlencoded'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

      //execute post
      $res = curl_exec($ch);
      $error = curl_error($ch);
      curl_close($ch);

      if ($error) {
        return ResFailure::failure("Error accessing: $url. $error");
      } else {
        $result = json_decode($res);

        if ($result->failed) {
          return ResFailure::failure($result->description);
        } else {
          return ResSuccess::success($result->result);
        }
      }
    }

    public function login(string $user, string $password) {
      return Configuration::config()->getString('router.url')->map(function($routerUrl) use($user, $password){
            $data = array(
                'endpoint' => 'auth',
                'data' => json_encode(array('username' => $user, 'password' => $password))
            );
            return $this->curlJson($routerUrl, json_encode($data));
          })->getOrElse(function() {
            
          });
    }

  }
  