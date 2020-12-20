<?php

namespace App\Models;

use App\Collections\MatrizBidimensional;

/**
 * Textos
 *
 * Matriz de textos submetidos a categorização.
 *
 * @uses \App\Collections\MatrizBidimensional::class
 */
class Textos extends MatrizBidimensional
{
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