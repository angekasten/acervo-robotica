<?php
require __DIR__ . '/inc/dados.php';

$ano = filter_input(INPUT_GET, 'ano', FILTER_VALIDATE_INT);
$ed  = $ano ? buscar_edicao($ano) : null;

if (!$ed) {
    http_response_code(404);
    $titulo = 'Edição não encontrada | Campeonato Joinvilense de Robótica';
    include __DIR__ . '/inc/cabecalho.php';
    echo '<main id="conteudo" class="acervo-container"><section class="acervo-card"><h1>Edição não encontrada</h1>'
       . '<p>Esta edição não existe ou ainda não foi publicada.</p><p><a href="' . e(url_inicio()) . '">Voltar à linha do tempo</a></p></section></main>';
    include __DIR__ . '/inc/rodape.php';
    exit;
}

$todas = carregar_edicoes();
$pos   = array_search($ed['ano'], array_column($todas, 'ano'));
$ant   = $todas[$pos - 1] ?? null;
$prox  = $todas[$pos + 1] ?? null;

$titulo    = $ed['edicao'] . ' (' . $ed['ano'] . ') | Campeonato Joinvilense de Robótica';
$descricao = $ed['resumo'];
include __DIR__ . '/inc/cabecalho.php';
?>
<main id="conteudo" class="acervo-container">
    <nav class="acervo-migalhas" aria-label="Você está em"><a href="<?= e(url_inicio()) ?>">Linha do tempo</a> › <span><?= (int) $ed['ano'] ?></span></nav>

    <h1 class="acervo-titulo"><?= e($ed['edicao']) ?> — <?= (int) $ed['ano'] ?></h1>
    <p class="acervo-desc"><?= e($ed['resumo']) ?></p>
    <?php if (!empty($ed['ficticio'])): ?>
        <p class="acervo-aviso" role="note">Dados fictícios para demonstração.<?= !empty($ed['nota']) ? ' ' . e($ed['nota']) : '' ?></p>
    <?php endif; ?>

    <div class="acervo-stats">
        <div class="acervo-stat"><strong><?= (int) $ed['escolas'] ?></strong><span>escolas</span></div>
        <div class="acervo-stat"><strong><?= (int) $ed['equipes'] ?></strong><span>equipes</span></div>
        <div class="acervo-stat"><strong><?= (int) $ed['estudantes'] ?>+</strong><span>estudantes</span></div>
    </div>

    <section aria-labelledby="t-cat">
        <h2 id="t-cat" class="acervo-titulo">Categorias e campeãs</h2>
        <ol class="acervo-cats">
            <?php foreach ($ed['categorias'] ?? [] as $i => $c): ?>
            <li class="acervo-card"><span class="acervo-cats__n"><?= $i + 1 ?></span>
                <h3><?= e($c['nome']) ?></h3>
                <p><strong><?= e($c['campea']) ?></strong><br><?= e($c['escola']) ?></p></li>
            <?php endforeach; ?>
        </ol>
    </section>

    <?php if (!empty($ed['projetos'])): ?>
    <section class="acervo-card" aria-labelledby="t-proj">
        <h2 id="t-proj">Projetos participantes em destaque</h2>
        <ul>
            <?php foreach ($ed['projetos'] as $p): ?>
            <li><?= e($p['titulo']) ?> <em>— <?= e($p['equipe']) ?></em></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <section class="acervo-card acervo-fonte" aria-labelledby="t-fonte">
        <h2 id="t-fonte">Procedência das informações</h2>
        <dl>
            <dt>Situação</dt><dd>Publicado</dd>
            <dt>Fonte</dt><dd><?= e($ed['fonte']['origem'] ?? '—') ?></dd>
            <dt>Validado por</dt><dd><?= e($ed['fonte']['validado_por'] ?? '—') ?>, em <?= e(data_br($ed['fonte']['validado_em'] ?? null)) ?></dd>
        </dl>
        <?php if (!empty($ed['historico_correcoes'])): ?>
            <h3>Correções registradas</h3>
            <ul>
                <?php foreach ($ed['historico_correcoes'] as $c): ?>
                <li><?= e(data_br($c['data'] ?? null)) ?> — <?= e($c['descricao'] ?? '') ?> <em>(<?= e($c['por'] ?? '') ?>)</em></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <nav class="acervo-pager" aria-label="Outras edições">
        <?php if ($ant): ?><a href="<?= e(url_edicao((int) $ant['ano'])) ?>">← <?= (int) $ant['ano'] ?></a><?php else: ?><span></span><?php endif; ?>
        <a href="<?= e(url_inicio()) ?>">Linha do tempo</a>
        <?php if ($prox): ?><a href="<?= e(url_edicao((int) $prox['ano'])) ?>"><?= (int) $prox['ano'] ?> →</a><?php else: ?><span></span><?php endif; ?>
    </nav>
</main>
<?php include __DIR__ . '/inc/rodape.php'; ?>
