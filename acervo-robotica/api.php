<?php
/**
 * API somente leitura (JSON) para interoperabilidade futura com os domínios
 * "Gestão do Evento" e "Gestão das Competições".
 *   /api.php          -> todas as edições publicadas
 *   /api.php?ano=2025 -> uma edição
 */
require __DIR__ . '/inc/dados.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');
header('X-Content-Type-Options: nosniff');

$flags = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
$ano = filter_input(INPUT_GET, 'ano', FILTER_VALIDATE_INT);

if (isset($_GET['ano'])) {
    $ed = $ano ? buscar_edicao($ano) : null;
    if (!$ed) {
        http_response_code(404);
        echo json_encode(['erro' => 'Edição não encontrada ou não publicada.'], $flags);
        exit;
    }
    echo json_encode(['versao_contrato' => '1.0', 'edicao' => $ed], $flags);
    exit;
}
echo json_encode(['versao_contrato' => '1.0', 'edicoes' => carregar_edicoes()], $flags);
