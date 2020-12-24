<?php

namespace App\Helpers;

class File
{
    /**
     * @var array  Arquivo de configuração
     */
    public static $config = [
        'private' =>  __DIR__ . "/../../storage/",
        'public' => __DIR__."/../../dist/files/"
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
        foreach (self::$config as $file_type => $file_dir)
            self::fileWrite($file_type, $file, $data);
    }

    /**
     * Efetua o processo de escrita de dados em um arquivo a partir do nome de diretório de
     * configuração fornecido.
     * 
     * @param  string $dir
     * @param  string $file
     * @param  string $data
     * @return void
     */
    private static function fileWrite(string $dir, string $file, string $data)
    {
        $fp = fopen(self::$config[$dir].$file, 'w');
        fwrite($fp, $data);
        fclose($fp);
    }
}