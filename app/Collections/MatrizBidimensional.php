<?php

namespace App\Collections;

/**
 * Matriz Bidimensional
 * 
 * Objeto em formato de matriz bidimensional, útil para construção de diversos objetos.
 */
class MatrizBidimensional
{
    /**
     * @var array  Matriz bidimensional
     */
    private $matriz;

    public function __construct($matriz = null)
    {
        $this->set($matriz ?? self::structure());
    }

    public function set($matriz)
    {
        $this->matriz = $matriz;
    }

    public function get()
    {
        return $this->matriz;
    }

    public function json()
    {
        return json_encode($this->get());
    }

    /**
     * Retorna os valores da linha desejada de uma matriz bidimensional.
     * 
     * @param  int $pos 
     * @return array
     */
    public function get_line($pos)
    {
        return $this->get()[$pos];
    }

    /**
     * Retorna os valores da coluna desejada de uma matriz bidimensional.
     * 
     * @param  int $pos 
     * @return array
     */
    public function get_column($pos)
    {
        $array = [];

        foreach ($this->get() as $line)
            array_push($array, $line[$pos]);

        return $array;
    }

    public function count()
    {
        $matriz = is_array($this->get()) ? $this->get() : array();
        return count($matriz);
    }

    /**
     * Calcula a transposta da matriz bidimensional.
     * 
     * @return \App\Collections\MatrizBidimensional
     */
    public function transpose()
    {
        $self = clone $this;
        $transpose = [];

        for($i = 0; $i < $this->count(); $i++)
            for($j = 0; $j < $this->count(); $j++)
                $transpose[$j][$i] = $this->get()[$i][$j];

        $self->set($transpose);

        return $self;
    }

    /**
     * Exibe de forma organizada uma matriz bidimensional. Se desejado, pode-se informar um 
     * prefixo e sufixo para cada linha e coluna
     * 
     * @param  string $pref_l  Prefixo da linha
     * @param  string $pref_c  Prefixo da coluna
     * @return void
     */
    public function show($pref_l = "", $suf_l = "", $pref_c = "", $suf_c = "")
    {
        foreach ($this->matriz as $linha) {
            echo $pref_l . "\n" . $suf_l;
            foreach ($linha as $coluna) {
                echo $pref_c . "| $coluna |" . $suf_c;
            }
        }
    }

    /**
     * Cria a estrutura básica da collection.
     * 
     * @return array
     */
    public static function structure()
    {
        return array(array());
    }
}