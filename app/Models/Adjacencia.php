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
    
    /**
     * Efetua o cruzamento da matriz de adjacência com outra matriz de adjacência. Deve receber a
     * matriz que será cruzada, se o corte será em linha ou coluna e o ponto de corte. A função
     * retorna a nova matriz de adjacência cruzada.
     * 
     * @param  \App\Models\Adjacencia $matriz
     * @param  string $posicao
     * @param  int    $corte
     * @return \App\Models\Adjacencia
     */
    public function crossbreeding(Adjacencia $breed, string $posicao, int $corte)
    {
        $crossbreed = [];

        // Pega as partes dos dois cromossomos selecionados para gerar o novo cromossomo a
        // partir de um ponto de corte e posicao de corte, que deve ser 'linha' ou 'coluna'.
        for ($i = 0; $i < $this->count(); $i++) {
            for ($j = 0; $j < $this->count(); $j++) {
                if ($posicao == "linha") {
                    $crossbreed[$i][$j] = ($i < $corte) ? 
                        $this->get()[$i][$j] : 
                        $breed->get()[$i][$j];
                } else {
                    $crossbreed[$i][$j] = ($j < $corte) ? 
                        $this->get()[$i][$j] : 
                        $breed->get()[$i][$j];
                }
            }
        }

        return new Adjacencia($crossbreed);
    }

    /**
     * Cria um mutante da matriz de adjacência. Deve receber se a troca será em linha ou coluna e 
     * os dois pontos que serão trocados para gerar o mutante. A função retorna uma nova matriz de 
     * adjacência resultante da mutação.
     * 
     * @param  string $posicao
     * @param  int    $ponto_1
     * @param  int    $ponto_2
     * @return \App\Models\Adjacencia
     */
    public function mutate(string $posicao, int $ponto_1, int $ponto_2)
    {
        $mutate = [];

        // Percorre a nova matriz de adjacência onde, quando a posicao atual da linha ou coluna 
        // for a posição de troca, busca os dados da outra posição. Agora se o ponto não for o de 
        // troca, apenas mantem o mesmo valor naquele campo.
        for ($i = 0; $i < $this->count(); $i++) {
            for ($j = 0; $j < $this->count(); $j++) {
                if ($posicao == "linha") {
                    if ($i == $ponto_1) {
                        $mutate[$i][$j] = $this->get()[$ponto_2][$j];    
                    } elseif ($i == $ponto_2) {
                        $mutate[$i][$j] = $this->get()[$ponto_1][$j];
                    } else {
                        $mutate[$i][$j] = $this->get()[$i][$j];
                    }
                } else {
                    if ($j == $ponto_1) {
                        $mutate[$i][$j] = $this->get()[$i][$ponto_2];    
                    } elseif ($j == $ponto_2) {
                        $mutate[$i][$j] = $this->get()[$i][$ponto_1];
                    } else {
                        $mutate[$i][$j] = $this->get()[$i][$j];
                    }
                }
            }
        }

        return new Adjacencia($mutate);
    }
}