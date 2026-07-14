<?php

declare(strict_types=1);

$path = $argv[1] ?? 'build/coverage.xml';
$minimum = (float) ($argv[2] ?? 50);

if (!is_file($path)) {
    fwrite(STDERR, "Relatório de cobertura não encontrado: {$path}\n");
    exit(2);
}

if (!function_exists('simplexml_load_file')) {
    fwrite(STDERR, "A extensão SimpleXML é necessária para ler cobertura Clover.\n");
    exit(2);
}

$xml = simplexml_load_file($path);
if ($xml === false || !isset($xml->project->metrics)) {
    fwrite(STDERR, "Relatório Clover inválido.\n");
    exit(2);
}

$metrics = $xml->project->metrics->attributes();
$statements = (int) ($metrics['statements'] ?? 0);
$covered = (int) ($metrics['coveredstatements'] ?? 0);
$coverage = $statements === 0 ? 0.0 : ($covered / $statements) * 100;

printf("Cobertura de instruções: %.2f%% (mínimo %.2f%%)\n", $coverage, $minimum);
exit($coverage + 0.0001 >= $minimum ? 0 : 1);
