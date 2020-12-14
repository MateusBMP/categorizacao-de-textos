<?php

namespace App\Models;

use App\Models\Textos;
use App\Models\Similaridade;
use App\Collections\MatrizBidimensional;

/**
 * Adjacencia
 *
 * Matriz de adjacencia entre textos submetidos a categorização, que deve ser comparada com a 
 * similaridade para efetuar seu cálculo de adaptacao.
 *
 * @uses \App\Models\Textos::class
 * @uses \App\Collections\MatrizBidimensional::class
 */
class Adjacencia extends MatrizBidimensional
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
     * @param  App\Models\Texto $texto  Texto para categorizacao
     * @return App\Models\Similaridade
     */
    public function set_by_textos(Textos $textos)
    {
        $matriz_de_adjacencia = MatrizBidimensional::structure();
        $conexoes_por_linha = array();
        $conexoes_por_coluna = array();
        $conexoes_matriz = 0;

        for ($i = 0; $i < $textos->count(); $i++) {
            // Constrói o controlador de conexões por linha 
            if (!isset($conexoes_por_linha[$i]))
                $conexoes_por_linha[$i] = 0;

            for ($j = 0; $j < $textos->count(); $j++) {
                // Constrói o controlador de conexões por coluna 
                if (!isset($conexoes_por_coluna[$j]))
                    $conexoes_por_coluna[$j] = 0;

                // Constrói a matriz de adjacencia
                if (!isset($matriz_de_adjacencia[$i][$j])) 
                    $matriz_de_adjacencia[$i][$j] = 0;

                // Verifica se será gerada ou nao a ligacao
                $rand = rand(0, 100);
                if ($rand > 80) {
                    // limita a duas conexões por linha e uma por coluna e máximo de conexões da 
                    // matriz a número de textos - 1
                    if ($conexoes_por_linha[$i] < 2 && 
                        $conexoes_por_coluna[$j] < 1 && 
                        $conexoes_matriz < ($textos->count() - 1))
                    {
                        $matriz_de_adjacencia[$i][$j] = 1;
                        $conexoes_por_linha[$i]++;
                        $conexoes_por_coluna[$j]++;
                        $conexoes_matriz++;
                    }
                }
            }
        }

        $this->set($matriz_de_adjacencia);

        return $this;
    }

    /**
     * Calcula o valor de adaptação da matriz de adjacência, baseada na matriz de similaridade. O
     * cálculo da adaptação equivale à soma dos produtos dos valores da matriz A(i,j) com a matriz
     * S(i,j), sendo A a matriz de adjacência e S a matriz de similaridade.
     * 
     * @param  \App\Models\Similaridade $similaridade  Matriz de similaridade
     * @return int
     */
    public function adaptacao(Similaridade $similaridade)
    {
        $adaptacao = 0;

        for ($i = 0; $i < $similaridade->count(); $i++)
            for ($j = 0; $j < $similaridade->count(); $j++) {
                $adaptacao += $similaridade->get()[$i][$j] * $this->get()[$i][$j]; 
            }

        return $adaptacao;
    }
}