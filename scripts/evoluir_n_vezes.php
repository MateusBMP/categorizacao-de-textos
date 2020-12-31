<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use App\Categorizacao;
use App\Helpers\File;
use App\Helpers\ProgressBar;

// Arquivos gerados
$textos_file = "textos.json";
$ultima_geracao_file = "ultima-geracao.json";
$geracoes_file = "geracoes.json";
$melhor_adaptacao_file = "melhor-adaptacao.json";

// Base de dados
// $textos_csv = "acm-computing-classification-system.csv";
$textos_csv = "example.csv";

// Número de evoluções
$n_evolucoes = 35;

echo "Evoluindo {$n_evolucoes} vezes...\n";

// Efetua a evolução e registra o processo com seu tempo restante
$categorizacao = new Categorizacao($textos_csv);
for ($i = 1; $i <= $n_evolucoes; $i++)
{
    ProgressBar::show_status($i, $n_evolucoes);
    $categorizacao->evoluir();
}

// Calcula a melhor adaptacao por geracao
$melhor_adaptacao_por_geracao = array_map(function($cromossomos) use ($categorizacao) {
    return $cromossomos->get()[0]->adaptacao($categorizacao->textos->similaridade);
}, $categorizacao->cromossomos->geracoes->get());

// Escreve os arquivos
echo "Base de dados salva em \"{$textos_file}\".\n";
File::write("outputs/{$textos_file}", $categorizacao->textos->json());

echo "Ultima geração salva em \"{$ultima_geracao_file}\".\n";
File::write("outputs/{$ultima_geracao_file}", $categorizacao->cromossomos->json());

echo "Gerações salvas em \"{$geracoes_file}\".\n";
File::write("outputs/{$geracoes_file}", $categorizacao->cromossomos->geracoes->json());

echo "Melhor grau de adaptacao de cada geracao salvo em \"{$melhor_adaptacao_file}\".\n";
File::write("outputs/{$melhor_adaptacao_file}", json_encode($melhor_adaptacao_por_geracao));