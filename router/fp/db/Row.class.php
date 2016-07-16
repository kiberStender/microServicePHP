<?php

  namespace fp\db;

  use fp\collections\map\Map;

  /**
   * Description of Row
   *
   * @author sirkleber
   */
  class Row {

    /**
     *
     * @var Map
     */
    private $columns;

    public function __construct(Map $columns = null) {
      if (isset($columns)) {
        $this->columns = $columns;
      } else {
        $this->columns = Map::map_();
      }
    }

    /**
     * Method to construct a Row instance
     * @param array $column
     * @return \fp\db\Row
     */
    public final function withColumn(array $column) {
      return new Row($this->columns->cons($column));
    }
    
    /**
     * 
     * @param string $column
     * @return \fp\maybe\Maybe
     */
    public final function getColumn($column) {
      return $this->columns->get($column);
    }
    
    public function __toString() {
      return "{$this->columns}";
    }

  }
  