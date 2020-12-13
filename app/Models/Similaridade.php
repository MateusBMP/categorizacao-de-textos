<?php

namespace App\Models;

use App\Models\Textos;
use App\Collections\MatrizBidimensional;

/**
 * Similaridade
 *
 * Matriz de similaridade entre textos submetidos a categorização.
 *
 * @uses \App\Models\Textos::class
 * @uses \App\Collections\MatrizBidimensional::class
 */
class Similaridade extends MatrizBidimensional
{
    public function __construct($data = null)
    {
        if ($data instanceof Textos) {
            $this->set_by_textos($data);
        } else {
            parent::__construct($data);
        }
    }

    /**
     * Instancia a partir do objeto Texto,
     * 
     * @param  \App\Models\Texto $texto  Texto para categorizacao
     * @return \App\Models\Similaridade::class
     */
    public function set_by_textos(Textos $textos)
    {
        $matriz_de_similaridade = array(array());

        for ($i = 0; $i < $textos->count(); $i++)
            for ($j = 0; $j < $textos->count(); $j++) {
                // Constrói a matriz de similaridade
                if (isset($matriz_de_similaridade[$i][$j]) == false)
                    $matriz_de_similaridade[$i][$j] = 0;

                // Faço a comparação dos textos
                if ($i != $j) {
                    foreach ($textos->get()[$i] as $letra_texto_1) {
                        foreach ($textos->get()[$j] as $letra_texto_2) {
                            if ($letra_texto_1 == $letra_texto_2) {
                                $matriz_de_similaridade[$i][$j]++;
                            }
                        }
                    }
                }
            }

        $this->set($matriz_de_similaridade);

        return $this;
    }
}