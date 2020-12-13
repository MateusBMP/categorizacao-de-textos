<?php

require_once("vendor/autoload.php");

use App\Models\Textos;
use App\Categorizacao;

$textos = [
    ["a", "b", "c"],
    ["a"],
    ["a", "b", "d"],
    ["a", "d", "e"]
];

$categorizacao = new Categorizacao($textos);

echo "Texto submetido:";
$categorizacao->textos->show();
echo "\n\n Matriz de similaridade:";
$categorizacao->similaridade->show();
echo "\n\n Cromossomos:";
$categorizacao->cromossomos->show();
echo "\n";