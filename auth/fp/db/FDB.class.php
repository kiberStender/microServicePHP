<?php

  /**
   * Description of FDB
   *
   * @author sirkleber
   */

  namespace fp\db;

  use PDO;
  use fp\maybe\Nothing;
  use fp\config\Configuration;
  use fp\either\Right;
  use fp\either\Left;

  class FDB {

    private static $fdb_ = null;
    private $connUrl;

    /**
     *
     * @var \fp\maybe\Maybe
     */
    private $user;

    /**
     *
     * @var \fp\maybe\Maybe
     */
    private $pass;

    private function __construct($connUrl, $user = null, $pass = null) {
      $this->connUrl = $connUrl;
      $this->user = !isset($user) ? Nothing::nothing() : $user;
      $this->pass = !isset($pass) ? Nothing::nothing() : $pass;
    }

    /**
     * 
     * @return FDB
     */
    public static function db() {
      if (!isset(self::$fdb_)) {
        $conf = Configuration::config();
        self::$fdb_ = $conf->getString("db.urlconn")
                ->map(function($url) use($conf) {
                  return new FDB($url, $conf->getString("db.user"), $conf->getString("db.pass"));
                })->getOrElse(function() { return new FDB('');});
      }

      return self::$fdb_;
    }

    /**
     * Methodo para conexão com banco de dados
     * @version 4.0
     * @return \fp\either\Either [String, PDO]
     */
    private final function connect() {
      if ($this->connUrl == '') {
        return Left::left('No connection Url found');
      } else {
        $that = $this;

        return $this->user->flatMap(function($user) use($that) {
          return $that->pass->map(function($pass) use($that, $user) {
                return Right::right(new PDO($that->connUrl, $user, $pass));
              });
        })->getOrElse(function() use($that) {
          return Right::right(new PDO($that->connUrl));
        });
      }
    }

    /**
     * 
     * @param callable $fn
     * @version 4.0
     * @return \fp\either\Either [String, Any]
     */
    public function withConnection(callable $fn) {
      return $this->connect()->fold(
        function($error){
          return Left::left($error);
        },
        function(PDO $pdo) use($fn){
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $result = $fn($pdo);
          $pdo = null;
          
          return $result;
        }
      );
    }

  }