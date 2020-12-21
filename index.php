<?php

require_once("vendor/autoload.php");

use App\Helpers\ProgressBar;
use App\Categorizacao;
use App\Models\Adjacencia;

$textos = [
    ["a", "b", "c"],
    ["a"],
    ["a", "b", "d"],
    ["a", "d", "e"]
];
$n_evolucoes = 100;
$categorizacao = new Categorizacao($textos);

echo "Texto submetido:\n";
$categorizacao->textos->show();

echo "\n\n Matriz de similaridade:\n";
$categorizacao->textos->similaridade->show();

$max_adjacencia = new Adjacencia($categorizacao->textos->similaridade->get());
$max_adaptacao = $max_adjacencia->adaptacao($categorizacao->textos->similaridade);
echo "\n\n Adaptação máxima: {$max_adaptacao}";

echo "\n\n Efetuar evolução $n_evolucoes vezes...\n\n";
$categorizacao->cromossomos->order_by_adaptacao();
// $maior_adaptacao_atual = $categorizacao->cromossomos->get()[0]->adaptacao($categorizacao->textos->similaridade);
for ($i = 1; $i <= $n_evolucoes; $i++) {
    ProgressBar::show_status($i, $n_evolucoes);

    $categorizacao->evoluir();

    // $nova_adaptacao = $categorizacao->cromossomos->get()[0]->adaptacao($categorizacao->textos->similaridade);

    // if ($nova_adaptacao != $maior_adaptacao_atual)
    //     echo "\n";

    // $maior_adaptacao_atual = $nova_adaptacao;
}

echo "\n Cromossomos após evolução:\n";
$categorizacao->cromossomos->order_by_adaptacao();
$categorizacao->cromossomos->show();

echo "\n";
