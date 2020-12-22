<?php

namespace App\Models;

use App\Models\Textos;
use App\Models\Adjacencia;
use App\Models\Similaridade;
use App\Helpers\ObjectHelper;

/**
 * Cromossomos
 *
 * Array de matrizes de adjacência, que servem como população para categorização dos textos. Se um 
 * objeto do tipo Textos for submetido junto a uma quantidade, os cromossomos são gerados a partir
 * do modelo de textos na quantidade desejada. Também é possível controlar as gerações de 
 * cromossomos, versionando as gerações.
 *
 * @uses \App\Models\Textos::class
 * @uses \App\Models\Adjacencia::class
 * @uses \App\Models\Similaridade::class
 * @uses \App\Helpers\ObjectHelper::class
 */
class Cromossomos
{
    /**
     * @var array  Lista de cromossomos
     */
    private array $_data;

    /**
     * @var array  Lista de gerações de cromossomos
     */
    private array $_geracoes = [];

    /**
     * @var \App\Models\Similaridade  Matriz de similaridade para os cromossomos
     */
    public $similaridade = null;

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

    public function __get($name) {
        if (ObjectHelper::existsMethod($this, $name))
            return $this->$name();

        return null;
    }
    
    public function __set($name, $value) {
        if (ObjectHelper::existsMethod($this, $name))
            $this->$name($value);
    }

    public function set($data)
    {
        if (!is_array($data))
            $this->throw_objectNotAllowed();

        foreach ($data as $d)
            if (!($d instanceof Adjacencia))
                $this->throw_objectNotAllowed();

        $this->_data = $data;

        return $this;
    }

    public function get()
    {
        return $this->_data;
    }

    public function count()
    {
        return count($this->get());
    }

    public function show()
    {
        for ($i = 0; $i < count($this->get()); $i++) {
            echo "\nIndex {$i}:";

            if ($this->similaridade)
                echo " Adaptacao: {$this->get()[$i]->adaptacao($this->similaridade)}";

            $this->get()[$i]->show("", "\t");
        }
    }

    public function geracoes($value = null)
    {
        if (isset($value)) 
            throw new \Exception('Setter not allowed for property: ' . $value);
        else 
            return $this->_geracoes;
    }

    /**
     * Cria uma quantidade determinada de cromossomos a partir dos textos. Também guarda o grau de  
     * similaridade dos textos para treinamento dos cromossomos.
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

        $this->similaridade = $textos->similaridade;

        return $this;
    }

    /**
     * Ordena os cromossomos pelo grau de adaptação. Se um grau de similaridade não tiver sido 
     * setado previamente, exige a passagem de um como parâmetro.
     * 
     * @param  \App\Models\Similaridade $similaridade
     * @return \App\Models\Cromossomos
     */
    public function order_by_adaptacao(Similaridade $similaridade = null)
    {
        if (!$similaridade && !$this->similaridade)
            $this->throw_setSimilaridade();

        $similaridade = $similaridade ?? $this->similaridade;

        usort($this->_data, function($a, $b) use ($similaridade) {
            return ($a->adaptacao($similaridade) < $b->adaptacao($similaridade)) ?  1 : -1;
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

                array_push($this->_data, $filho);
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

                array_push($this->_data, $mutante);
            }
        }

        return $this;
    }

    /**
     * Efetua a poda da lista de cromossomos mantendo apenas o número desejado de cromossomos. Se 
     * o número de cromossomos a serem mantidos não for informado, serão mantidos 10 cromossomos.
     * Também pode-se solicitar manter apenas cromossomos distintos e válidos, de acordo com a 
     * regra de conexões.
     * 
     * @param  int $amount  Quantidade de cromossomos que serão mantidos
     * @return \App\Models\Cromossomos
     */
    public function podar(int $amount = 10, bool $distinct = true, $valid = true)
    {
        if ($distinct)
            $this->set($this->distinct());

        if ($valid)
            $this->set($this->valid());
            
        $this->set(array_slice($this->get(), 0, $amount));
        
        return $this;
    }

    /**
     * Seleciona apenas os cromossomos distintos da lista atual de cromossomos.
     * 
     * @return array
     */
    public function distinct()
    {
        // Cria objetos de teste e retorno
        $real_data = array();
        $json_data = array();

        // Analisa cada cromossomo
        foreach ($this->get() as $cromossomo)
        {
            // Transforma objeto em json
            $_needle = json_encode($cromossomo->get());

            // Se objeto ainda não foi lido, guarda objeto nas listas de teste e retorno
            if (!in_array($_needle, $json_data)) {
                array_push($real_data, $cromossomo);
                array_push($json_data, $_needle);
            }
        }

        return $real_data;
    }

    /**
     * Seleciona apenas os cromossomos válidos da lista atual de cromossomos.
     * 
     * @return array
     */
    public function valid()
    {
        // Lista dos cromossomos válidos
        $valid = array();

        foreach ($this->get() as $cromossomo)
        {
            // Se o cromossomo for válido, adiciona-o a lista
            if ($cromossomo->is_valid())
                array_push($valid, $cromossomo);
        }

        return $valid;
    }

    /**
     * Registra uma geração de cromossomos adicionando os atuais cromossomos a lista de gerações.
     * 
     * @return \App\Models\Cromossomos
     */
    public function registrar_geracao()
    {
        array_push($this->_geracoes, $this->_data);

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

    private function throw_setSimilaridade()
    {
        throw new \Exception("Set \$similaridade property or method param.");
    }
}