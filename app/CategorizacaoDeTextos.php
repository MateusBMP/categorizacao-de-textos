<?php

namespace App;

class CategorizacaoDeTextos
{
    private $textos;
    private $similaridade;
    private $cromossomos;

    public $n_cromossomos = 10;

    public function __construct($textos)
    {
        $this->textos = $textos;

        $this->calc_matriz_de_similaridade();
        $this->calc_matriz_de_adjacencia();
        $this->init_cromossomos();
    }

    public function calc_matriz_de_similaridade()
    {
        $matriz_de_similaridade = array(array());

        for ($i = 0; $i < count($this->textos); $i++)
            for ($j = 0; $j < count($this->textos); $j++) {
                // Constrói a matriz de similaridade
                if (isset($matriz_de_similaridade[$i][$j]) == false)
                    $matriz_de_similaridade[$i][$j] = 0;

                // Faço a comparação dos textos
                if ($i != $j) {
                    foreach ($this->textos[$i] as $letra_texto_1) {
                        foreach ($this->textos[$j] as $letra_texto_2) {
                            if ($letra_texto_1 == $letra_texto_2) {
                                $matriz_de_similaridade[$i][$j]++;
                            }
                        }
                    }
                }
            }

        $this->similaridade = $matriz_de_similaridade;
    }

    public function calc_matriz_de_adjacencia()
    {
        $matriz_de_adjacencia = array(array());
        $conexoes_por_linha = array();
        $conexoes_por_coluna = array();
        $conexoes_matriz = 0;

        for ($i = 0; $i < count($this->textos); $i++) {
            // Constrói o controlador de conexões por linha 
            if (isset($conexoes_por_linha[$i]) == false)
                $conexoes_por_linha[$i] = 0;

            for ($j = 0; $j < count($this->textos); $j++) {
                // Constrói o controlador de conexões por coluna 
                if (isset($conexoes_por_coluna[$j]) == false)
                    $conexoes_por_coluna[$j] = 0;

                // Constrói a matriz de adjacencia
                if (isset($matriz_de_adjacencia[$i][$j]) == false) 
                    $matriz_de_adjacencia[$i][$j] = 0;

                // Verifica se será gerada ou nao a ligacao
                $rand = rand(0, 100);
                if ($rand > 80) {
                    // limita a duas conexões por linha e uma por coluna e máximo de conexões da 
                    // matriz a número de textos - 1
                    if ($conexoes_por_linha[$i] < 2 && 
                        $conexoes_por_coluna[$j] < 1 && 
                        $conexoes_matriz < (count($this->textos) - 1))
                    {
                        $matriz_de_adjacencia[$i][$j] = 1;
                        $conexoes_por_linha[$i]++;
                        $conexoes_por_coluna[$j]++;
                        $conexoes_matriz++;
                    }
                }
            }
        }

        $this->adjacencia = $matriz_de_adjacencia;
    }

    public function exibir_texto()
    {
        $this->exibir_matriz($this->textos);
    }

    public function exibir_matriz_de_similaridade()
    {
        $this->exibir_matriz($this->similaridade);
    }

    public function exibir_matriz_de_adjacencia()
    {
        $this->exibir_matriz($this->adjacencia);
    }

    private function exibir_matriz($matriz)
    {
        foreach ($matriz as $linha) {
            echo "\n";
            foreach ($linha as $coluna) {
                echo "| $coluna |";
            }
        }
    }

    private function init_cromossomos()
    {
        for($i = 0; $i < $this->n_cromossomos; $i++)
        {
            $this->cromossomos[$i] = $this->calc_matriz_de_adjacencia();
        }
    }
}
