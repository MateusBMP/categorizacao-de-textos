<?php

namespace App\Models;

use App\Models\Textos;
use App\Models\Adjacencia;
use App\Models\Similaridade;

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

    public function count()
    {
        return count($this->get());
    }

    public function show()
    {
        for ($i = 0; $i < count($this->get()); $i++) {
            echo "\n{$i}:";
            $this->get()[$i]->show("", "\t");
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

    /**
     * Ordena os cromossomos pela similaridade
     * 
     * @param  \App\Models\Similaridade $similaridade
     * @return \App\Models\Cromossomos
     */
    public function order_by_similaridade(Similaridade $similaridade)
    {
        usort($this->data, function($a, $b) use ($similaridade) {
            return ($a->adaptacao($similaridade) < $b->adaptacao($similaridade)) ?  -1 : 1;
        });

        return $this;
    }

    /**
     * Efetua o cruzamento entre os cromossomos. Deve receber uma quantidade de cruzamentos que
     * tentará efetuar onde, caso não seja informado, terá como valor inicial 10. Recebe também 
     * uma taxa de cruzamento, entre 0 e 100, onde, caso não informada, será 50 (cinquenta por 
     * cento).
     * 
     * @param  int $quantidade
     * @param  int $taxa
     * @return \App\Models\Cromossomos
     */
    public function handle_cruzamento(int $quantidade = 10, int $taxa = 50)
    {
        for ($i = 0; $i < $quantidade; $i++) {
            if (rand(0, 100) <= $taxa) {
                // Seleciona linha ou coluna aleatoriamente
                $posicao = (rand(0,1) == 0) ? 'linha' : 'coluna';

                // Seleciona o ponto de corte aleatoriamente
                $corte = rand(0, $this->get()[0]->count() - 1);

                // Seleciona dois cromossomos aleatórios
                $pos_c1 = rand(0, $this->count() - 1);
                $c1 = $this->get()[$pos_c1];
                $pos_c2 = rand(0, $this->count() - 1);
                $c2 = $this->get()[$pos_c2];

                // Efetua o cruzamento
                $filho = $c1->crossbreeding($c2, $posicao, $corte);

                array_push($this->data, $filho);
            }
        }

        return $this;
    }

    /**
     * Efetua a mutação de um cromossomo. Pode recebe um número de cromossomos que serão usados
     * para gerar um novo mutante onde, se não informado, será igual a 10. Também pode receber uma 
     * taxa de mutação que, quando não for informada, será igual a 50 (cinquenta por cento).
     * 
     * @param  int $quantidade
     * @param  int $taxa
     * @return \App\Models\Cromossomos
     */
    public function handle_mutacao(int $quantidade = 10, int $taxa = 50)
    {
        for ($i = 0; $i < $quantidade; $i++) {
            if (rand(0, 100) <= $taxa) {
                // Seleciona linha ou coluna aleatoriamente
                $posicao = (rand(0,1) == 0) ? 'linha' : 'coluna';

                // Seleciona os ponto de troca aleatoriamente
                $troca_1 = rand(0, $this->get()[0]->count() - 1);
                $troca_2 = rand(0, $this->get()[0]->count() - 1);

                // Seleciona um cromossomo aleatório
                $pos_c = rand(0, $this->count() - 1);
                $c1 = $this->get()[$pos_c];

                $mutante = $c1->mutate($posicao, $troca_1, $troca_2);

                array_push($this->data, $mutante);
            }
        }

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