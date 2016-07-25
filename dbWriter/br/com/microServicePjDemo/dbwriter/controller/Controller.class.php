<?php

  namespace br\com\microServicePjDemo\dbwriter\controller;

  use stdClass;
  use SplFileObject;
  use fp\collections\seq\Seq;
  use fp\result\Result;
  use fp\result\ResFailure;
  use fp\result\ResSuccess;
  use fp\collections\map\Map;
  use fp\maybe\Just;
  use fp\db\FDB;
  use fp\db\SQL;
  use fp\db\Row;

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

    /**
     * Function that reads the property file related to the given table and returns 
     * a Map with it's queries or an empty map in case the file is not found
     * @param string $table
     * @return Map
     */
    private function readResources(string $table) {
      $map = Map::map_();
      $file = new SplFileObject("./resources/dao/$table.properties");
      
      if ($file->isFile()) {
        while (!$file->eof()) {
          list($key, $value) = explode('=', $file->fgets(), 2);

          $map = $map->cons(array($key, $value));
        }
      }

      $file = null;
      return $map;
    }

    /**
     * Function that reads a given file to gets a given property and exec the query 
     * assigned to this property in case it has been found
     * @param stdClass $data
     * @return Result Description
     */
    public function updateDb(stdClass $data) {
      list($dao, $queryName) = explode('.', $data->query);
      $params = $data->params;
      return $this->readResources($dao)->get($queryName)->map(function($line) use($params) {
        return FDB::db()->withConnection(
          SQL::sql($line . ';')->on(...$params)->executeUpdate()
        )->fold(
          function(string $error) {return ResFailure::failure($error);}, 
          function(int $rows) {
            return ResSuccess::success("$rows affected(s)");
          }
        );
      })->getOrElse(function() use($dao) {
        return ResFailure::failure("Resource Dao properties file not found: resources/dao/{$dao}.properties");
      });
    }

  }