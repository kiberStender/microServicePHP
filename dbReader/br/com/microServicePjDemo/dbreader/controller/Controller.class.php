<?php

  namespace br\com\microServicePjDemo\dbreader\controller;

  use stdClass;
  use fp\result\Result;
  use fp\result\ResFailure;
  use fp\result\ResSuccess;
  use fp\collections\map\Map;
  use fp\db\FDB;
  use fp\db\SQL;
  use SplFileObject;

  /**
   * Description of Controller
   *
   * @author sirkleber
   */
  class Controller {

    private static $controller_ = null;

    private function __construct() {
      
    }

    public static function controller() {
      if (isset(self::$controller_)) {
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
          list($key, $value) = explode('=', $file->fgets());

          $map = $map->cons(array($key, $value));
        }
      }

      $file = null;
      return $map;
    }

    /**
     * Function that reads a given file to gets a given property and exec the query 
     * assigned to this property in case it is found
     * @param stdClass $data
     * @return Result Description
     */
    public function select(stdClass $data) {
      $params = $data->params;

      return $this->readResources($data->table)->get($data->query)->map(function($query) use($params) {
        return FDB::db()->withConnection(
          SQL::sql($query)->on($params)->as_($f)
        )->fold(
          ResFailure::failure, 
          function(Result $res) {}
        );
      })->getOrlElse(function(){
        return ResFailure::failure('Resource Dao properties file not found!');
      });
    }

  }
  