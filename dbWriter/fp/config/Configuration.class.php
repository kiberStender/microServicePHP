<?php
  
  namespace fp\config;
  
  use fp\collections\map\Map;
  
  use SplFileObject;

  /**
   * Description of Configuration
   *
   * @author sirkleber
   */
  class Configuration {
    private static $conf_ = null;
    /**
     *
     * @var Map
     */
    private $map;
    
    private function __construct() {
      $configs = parse_ini_file('./resources/conf.properties');
      
      if(sizeof($configs) > 0){
        $this->map = Map::map_();
        
        foreach ($configs as $key => $value){
          $this->map = $this->map->cons(array($key, $value));
        }
      } else {
        return Map::map_();
      }
      
    }
    
    private function startsWith($haystack, $needle) {
      // search backwards starting from haystack length characters from the end
      return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
    
    /**
     * 
     * @return \fp\config\Configuration
     */
    public static function config(){
      if(!isset(self::$conf_)){
        self::$conf_ = new Configuration;
      }
      return self::$conf_;
    }
    
    /**
     * 
     * @param string $property
     * @return \fp\maybe\Maybe
     */
    public function getString($property){
      return $this->map->get($property);
    }
    
    public function __toString() {
      return "{$this->map}";
    }
  }