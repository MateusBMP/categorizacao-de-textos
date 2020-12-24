<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use App\Categorizacao;
use App\Helpers\File;
use App\Helpers\ProgressBar;

$textos = [
    ["d"],
    ["c", "d"],
    ["a", "d"],
    ["a", "b", "c", "d"]
];
$n_evolucoes = 10;
$file = "dez-evolucoes.json";
$categorizacao = new Categorizacao($textos);

echo "Evoluindo {$n_evolucoes} vezes...\n\n";

for ($i = 1; $i <= $n_evolucoes; $i++)
{
    ProgressBar::show_status($i, $n_evolucoes);
    $categorizacao->evoluir();
}

echo "Gerações salvas em \"{$file}\".\n";

File::write($file, $categorizacao->cromossomos->geracoes_to_json());
