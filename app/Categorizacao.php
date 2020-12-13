<?php

namespace App;

use App\Models\Textos;
use App\Models\Adjacencia;
use App\Models\Cromossomos;
use App\Models\Similaridade;
use App\Helpers\ObjectHelper;

/**
 * Categorização
 * 
 * Classe de categorização de textos. Efetua todas as operações para categorizar um texto 
 * utilizando o algoritmo genético de Holland.
 * 
 * @uses \App\Models\Textos::class;
 * @uses \App\Models\Adjacencia::class;
 * @uses \App\Models\Cromossomos::class;
 * @uses \App\Models\Similaridade::class;
 * @uses \App\Helpers\ObjectHelper::class;
 */
class Categorizacao
{
    /**
     * @var App\Models\Textos
     */
    private $_textos;

    /**
     * @var App\Models\Similaridade
     */
    private $_similaridade;

    /**
     * @var array  População de cromossomos 
     */
    private $_cromossomos;

    /**
     * @var int  Número de cromossomos gerados na população
     */
    public $n_cromossomos = 10;

    public function __construct($textos)
    {
        $this->textos($textos);
        $this->similaridade($this->textos);
        $this->create_cromossomos();
    }

    public function __get($name) {
        if (ObjectHelper::existsMethod($this, $name))
            return $this->$name();

        return null;
    }
    
    public function __set($name, $value) {
        if (ObjectHelper::existsMethod($this, $name))
            $this->$name($value);
    }

    /**
     * Inicializa matriz de cromossomos
     * 
     * @return void
     */
    private function create_cromossomos()
    {
        $this->_cromossomos = new Cromossomos($this->textos, $this->n_cromossomos);
    }

    public function textos($value = null)
    {
        if (isset($value)) 
            $this->_textos = new Textos($value);
        else 
            return $this->_textos;
    }

    public function similaridade($value = null)
    {
        if (isset($value)) 
            $this->_similaridade = new Similaridade($value);
        else 
            return $this->_similaridade;
    }

    public function cromossomos($value = null)
    {
        if (isset($value)) 
            throw new \Exception('Setter not allowed for property: ' . $name);
        else 
            return $this->_cromossomos;
    }
}
