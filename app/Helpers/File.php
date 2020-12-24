<?php

namespace App\Helpers;

class File
{
    /**
     * @var array  Arquivo de configuração
     */
    public static $config = [
        'dir' =>  __DIR__ . "/../../storage/"
    ];

    /**
     * Escreve no arquivo. Deve receber qual o nome do arquivo e o que será guardado nele.
     * 
     * @param  string $file
     * @param  string $data
     * @return void
     */
    public static function write(string $file, string $data)
    {
        $fp = fopen(self::$config['dir'].$file, 'w');
        fwrite($fp, $data);
        fclose($fp);
    }
}