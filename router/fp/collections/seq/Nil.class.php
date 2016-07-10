<?php
/**
 * Description of Nil
 *
 * @author sirkleber
 */

class Nil extends Seq{
    private static $nil = null;
    
    /**
     * 
     * @return Seq
     */
    public static function Nil(){
        if(!isset(self::$nil)){
            self::$nil = new Nil();
        }
        return self::$nil;
    }
    
    private function __construct() {}
    
    public function isEmpty() {
        return true;
    }
    
    public function head() {
        throw new Exception("No such Element");
    }
    
    public function tail() {
        throw new Exception("No such Element");
    }
    
    public function init() {
        throw new Exception("No such Element");
    }
    
    public function last() {
        throw new Exception("No such Element");
    }
    
    public function maybeHead() {
        return Nothing::Nothing();
    }
    
    public function maybeLast() {
        return Nothing::Nothing();
    }
}