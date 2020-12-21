<?php

namespace App\Models;

use App\Helpers\ObjectHelper;
use App\Collections\MatrizBidimensional;

/**
 * Textos
 *
 * Matriz de textos submetidos a categorização.
 *
 * @uses \App\Helpers\ObjectHelper::class
 * @uses \App\Collections\MatrizBidimensional::class
 */
class Textos extends MatrizBidimensional
{
    public function __get($name) {
        if (ObjectHelper::existsMethod($this, $name))
            return $this->$name();

        return null;
    }

    /** 
     * Matriz de similaridade do texto.
     * 
     * @return \App\Models\Similaridade
     */
    public function similaridade()
    {
        return new Similaridade($this);
    }
}