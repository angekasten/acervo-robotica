<?php
// Espera: $titulo (string), $descricao (string, opcional)
$base = base_path();
$embed = modo_embed();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?></title>
    <meta name="description" content="<?= e($descricao ?? 'Acervo público com a história do Campeonato Joinvilense de Robótica.') ?>">
    <link rel="stylesheet" href="<?= e($base) ?>assets/style.css">
</head>
<body class="acervo-body<?= $embed ? ' acervo-embed' : '' ?>">
<a class="acervo-skip" href="#conteudo">Ir para o conteúdo</a>
<?php if (!$embed): ?>
<!-- Cabeçalho de referência. No WordPress da prefeitura, use o cabeçalho do tema e mantenha apenas o <main>. -->
<header class="acervo-cabecalho">
    <div class="acervo-container acervo-cabecalho__conteudo">
        <div>
            <p class="acervo-cabecalho__marca">Prefeitura de Joinville</p>
            <span>Secretaria de Educação • Robótica Educacional</span>
        </div>
        <nav aria-label="Principal">
            <a href="<?= e(url_inicio()) ?>#sobre">Sobre</a>
            <a href="<?= e(url_inicio()) ?>#linha-do-tempo">Linha do tempo</a>
            <a href="<?= e(url_inicio()) ?>#indicadores">Indicadores</a>
        </nav>
    </div>
</header>
<?php endif; ?>
