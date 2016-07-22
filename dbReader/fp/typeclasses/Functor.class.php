<?php
/**
 *
 * @author sirkleber
 */

namespace fp\typeclasses;

interface Functor {
    /**
     * Function to traverse the container and apply a function to transform it
     * f a -> (a -> b) -> f b
     * @param callable $f
     */
    public function map(callable $f);
}
