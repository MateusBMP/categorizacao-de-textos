<?php

namespace App;

use App\Factories\TextosByCSV;
use App\Models\Textos;
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
     * @var \App\Models\Textos
     */
    private Textos $_textos;

    /**
     * @var \App\Models\Cromossomos  População de cromossomos 
     */
    private Cromossomos $_cromossomos;

    /**
     * @var int  Número de cromossomos gerados ao estabelecer um novo texto.
     */
    public int $n_cromossomos;

    public function __construct($textos, $n_cromossomos = 10)
    {
        $this->n_cromossomos = $n_cromossomos;
        $this->textos($textos);
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
     * @param  \App\Models\Textos $textos
     * @param  int $n_cromossomos
     * @return void
     */
    private function create_cromossomos(Textos $textos, int $n_cromossomos)
    {
        $this->_cromossomos = new Cromossomos($textos, $n_cromossomos);
    }

    public function textos($value = null)
    {
        if (isset($value)) {
            $this->_textos = (is_string($value)) ? 
                TextosByCSV::create($value) : 
                new Textos($value);
            $this->create_cromossomos($this->textos, $this->n_cromossomos);
        } else {
            return $this->_textos;
        }
    }

    public function cromossomos($value = null)
    {
        if (isset($value)) 
            throw new \Exception('Setter not allowed for property: ' . $value);
        else 
            return $this->_cromossomos;
    }

    /**
     * Registra uma evolução dos cromossomos. Para isso, deve-se estabelecer a taxa de cruzamento,
     * quantidade de cruzamentos, taxa de mutação, quantidade de mutações, quantos cromossomos 
     * serão mantidos após a evolução e se serão mantidos cromossomos iguais. Os cromossomos 
     * mantidos serão os mais bem adaptados, distintos ou não, de acordo com o grau de similaridade 
     * de cada cromossomo. Ao final da evolução, uma nova geração será registrada na lista de 
     * cromossomos.
     * 
     * @param  int  $q_cruzamento   Quantidade de cruzamentos
     * @param  int  $t_cruzamento   Taxa de cruzamento, entre 0 e 100 (por cento)
     * @param  int  $q_mutacao      Quantidade de mutações
     * @param  int  $t_mutacao      Taxa de mutação, entre 0 e 100 (por cento)
     * @param  int  $n_cromossomos  Número de cromossomos que serão mantidos
     * @param  bool $distintos      Se deseja manter apenas cromossomos distintos
     * @return \App\Categorizacao
     */
    public function evoluir(int $q_cruzamento = 10, int $t_cruzamento = 50, int $q_mutacao = 10, int $t_mutacao = 50, $n_cromossomos = 10, $distintos = true)
    {
        $this->cromossomos->handle_cruzamento($q_cruzamento, $t_cruzamento);
        $this->cromossomos->handle_mutacao($q_mutacao, $t_mutacao);
        $this->cromossomos->order_by_adaptacao();
        $this->cromossomos->podar($n_cromossomos, $distintos);
        $this->cromossomos->registrar_geracao();

        return $this;
    }
}
