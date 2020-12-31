<?php

namespace App\Categorizacao;

use App\Categorizacao\Cromossomos;

/**
 * Gerações dos cromossomos do algoritmo de Holland para categorização.
 */
class Geracoes
{
    /**
     * @var array  Lista de conjuntos de cromossomos
     */
    private array $_data = [];

    public function get()
    {
        return $this->_data;
    }

    public function json()
    {
        return json_encode(array_map(function($g) {
            return array_map(function($c) {
                return $c->get();
            }, $g->get());
        }, $this->_data));
    }

    public function count()
    {
        return count($this->_data);
    }

    /**
     * Registra uma nova geração a partir dos cromossomos passados.
     * 
     * @param  \App\Categorizacao\Cromossomos $cromossomos
     * @return \App\Categorizacao\Geracoes
     */
    public function registrar(Cromossomos $cromossomos)
    {
        array_push($this->_data, $cromossomos);

        return $this;
    }
}