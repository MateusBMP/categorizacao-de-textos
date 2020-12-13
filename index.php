<?php

require_once("vendor/autoload.php");

use App\CategorizacaoDeTextos;

$textos = [
    ["a", "b", "c"],
    ["a"],
    ["a", "b", "d"],
    ["a", "d", "e"]
];

$categorizacao_de_textos = new CategorizacaoDeTextos($textos);

echo "Texto submetido:";
$categorizacao_de_textos->exibir_texto();
echo "\n\n Matriz de similaridade:";
$categorizacao_de_textos->exibir_matriz_de_similaridade();
echo "\n\n Matriz de adjacência:";
$categorizacao_de_textos->exibir_matriz_de_adjacencia();
echo "\n";