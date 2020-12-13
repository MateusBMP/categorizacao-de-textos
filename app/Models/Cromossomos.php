<?php

namespace App\Models;

use App\Models\Textos;
use App\Models\Adjacencia;

/**
 * Cromossomos
 *
 * Array de matrizes de adjacência, que servem como população para categorização dos textos. Se um 
 * objeto do tipo Textos for submetido junto a uma quantidade
 *
 * @uses \App\Models\Textos::class
 * @uses \App\Models\Adjacencia::class
 */
class Cromossomos
{
    /**
     * @var array  Array unidirecional
     */
    private $data;

    public function __construct($data = array(), $amount = null)
    {
        if ($data instanceof Textos) {
            if (!isset($amount))
                $this->throw_setAmount();

            $this->set_by_textos($data, $amount);
        } else {
            $this->set($data);
        }
    }

    public function set($data)
    {
        if (!is_array($data))
            $this->throw_objectNotAllowed();

        foreach ($data as $d)
            if (!($d instanceof Adjacencia))
                $this->throw_objectNotAllowed();

        $this->data = $data;

        return $this;
    }

    public function get()
    {
        return $this->data;
    }

    public function show()
    {
        for ($i = 0; $i < count($this->data); $i++) {
            echo "\n{$i}:";
            $this->data[$i]->show("", "\t");
        }
    }

    /**
     * Cria uma quantidade determinada de cromossomos a partir dos textos.
     * 
     * @param  \App\Models\Textos $textos  Textos para geracao da adjacência
     * @param  int $amount  Quantidade de cromossomos
     * @return \App\Models\Cromossomos::class
     */
    public function set_by_textos(Textos $textos, int $amount)
    {
        $cromossomos = array();

        for($i = 0; $i < $amount; $i++)
            $cromossomos[] = new Adjacencia($textos);

        $this->set($cromossomos);

        return $this;
    }

    private function throw_objectNotAllowed()
    {
        throw new \Exception("Object not allowed. Object need to be a array of Adjacencia objects.");
    }

    private function throw_setAmount()
    {
        throw new \Exception("Set \$amount param if you pass \$data param in constructor.");
    }
}