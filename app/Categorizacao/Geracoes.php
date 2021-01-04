<?php

namespace App\Categorizacao;

use App\Categorizacao\Cromossomos;
use App\Helpers\File;

/**
 * Gerações dos cromossomos do algoritmo de Holland para categorização. A gerações são guardadas 
 * em arquivos, para poupar memória. Quando requisitada a geração, o arquivo é aberto e a 
 * serialização é desfeita.
 * 
 * @see https://www.php.net/manual/pt_BR/function.serialize
 */
class Geracoes
{
    /**
     * @var array  Lista de conjuntos de cromossomos
     */
    private array $_data = [];

    /**
     * Retorna a lista de gerações ou apenas uma geração.
     * 
     * @param  int $pos  Geracao desejada
     * @return array|\App\Categorizacao\Cromossomos
     */
    public function get($pos = null)
    {
        $data = [];

        if ($pos === null)
            foreach ($this->_data as $file)
                array_push($data, unserialize(File::toString("private", $file)));
        else 
            $data = unserialize(File::toString("private", $this->filename($pos)));

        return $data;
    }

    public function json()
    {
        return json_encode(array_map(function($file) {
            $g = unserialize(File::toString("private", $file));
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
     * Registra uma nova geração a partir dos cromossomos passados. A geração é guardada em um 
     * arquivo temporário, que é aberto quando solicitado.
     * 
     * @param  \App\Categorizacao\Cromossomos $cromossomos
     * @return \App\Categorizacao\Geracoes
     */
    public function registrar(Cromossomos $cromossomos)
    {
        $filename = $this->filename($this->count());

        File::fileWrite("private", $filename, serialize($cromossomos));

        array_push($this->_data, $filename);

        return $this;
    }

    /**
     * Retorna o nome do arquivo buscado com seu correto endereço.
     * 
     * @param  int $pos Posicao da geracao
     * @return string
     */
    private function filename($pos) 
    {
        return "temp/g{$pos}.serialize";
    }
}