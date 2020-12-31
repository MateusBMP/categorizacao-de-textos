<?php

namespace App\Factories;

use App\Helpers\File;
use App\Models\Textos;

/**
 * Factory Textos
 * 
 * Constrói o objeto Textos a partir de um arquivo csv.
 * 
 * @uses \App\Models\Textos
 */
class TextosByCSV
{
    /**
     * Cria o objeto do tipo Textos. Deve receber o nome do arquivo.
     * 
     * @param  string $filename
     * @return \App\Models\Textos
     */
    public static function create($filename)
    {
        // Abre o arquivo
        $fp = File::open("texts/{$filename}");

        // Transforma o arquivo em um array de objetos
        $data = self::csv_to_array($fp);

        // Formata o array de objetos para o formato aceito pelo objeto Textos
        $f_data = self::format_data($data);

        // temp... limitando isso aqui pq não aguentou a memória ram
        $f_data = array_slice($f_data, 0, 500);

        // Retorna o objeto do tipo Textos
        return new Textos($f_data);
    }

    /**
     * Modified...
     * 
     * Convert a comma separated file into an associated array.
     * The first row should contain the array keys.
     * 
     * Example:
     * 
     * @param string $filename Path to the CSV file
     * @param string $delimiter The separator used in the file
     * @return array
     * @link http://gist.github.com/385876
     * @author Jay Williams <http://myd3.com/>
     * @copyright Copyright (c) 2010, Jay Williams
     * @license http://www.opensource.org/licenses/mit-license.php MIT License
     */
    private static function csv_to_array($file, $delimiter=',')
    {
        if(!$file)
            return FALSE;
        
        $header = NULL;
        $data = array();

        while (($row = fgetcsv($file, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($file);

        return $data;
    }

    /**
     * Formata os títulos do csv e retorna apenas as palavras em formato de array bidimensional. 
     * Esse é o formato reconhecido pelo objeto que será analisado.
     * 
     * @param  array $data
     * @return array
     */
    private static function format_data($data)
    {
        return array_map(function($value) {
            return explode(" ", $value['title']);
        }, $data);
    }
}