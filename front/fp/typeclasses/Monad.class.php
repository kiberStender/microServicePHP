<?php

/**
 * Description of Monad
 *
 * @author sirkleber
 */

namespace fp\typeclasses;

abstract class Monad implements Functor{
    
    /**
     * Haskell >>= (bind) function
     * flatMap:: m a -> (a -> m b) -> m b
     * @param $f
     */
    public abstract function flatMap(callable $f);
    
    /**
     * Haskell >> function
     * @param Fn1 $f
     */
    public function fpForeach(callable $f){
        $this->map($f);
    }
}
