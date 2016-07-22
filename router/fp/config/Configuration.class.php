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
      $this->map = Map::map_();
      $file = new SplFileObject('./resources/conf.properties');
      
      while(!$file->eof()){
        list($key, $value) = explode('=', $file->fgets(), 2);
        
        $this->map = $this->map->cons(array($key, trim(preg_replace('/\s+/', '', $value))));
        
      }
      
      $file = null;
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