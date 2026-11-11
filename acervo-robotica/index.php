<?php
require __DIR__ . '/inc/dados.php';

$edicoes = carregar_edicoes();     
$ultima  = $edicoes ? end($edicoes) : null;
$maxEq   = $edicoes ? max(array_column($edicoes, 'equipes')) : 1;

$popup = array_map(function ($ed) {
    $cats = $ed['categorias'] ?? [];
    return [
        'ano' => $ed['ano'], 'edicao' => $ed['edicao'], 'resumo' => $ed['resumo'],
        'escolas' => $ed['escolas'], 'equipes' => $ed['equipes'], 'estudantes' => $ed['estudantes'],
        'destaques' => array_map(fn($c) => $c['nome'] . ': ' . $c['campea'], array_slice($cats, 0, 3)),
        'mais' => max(0, count($cats) - 3),
        'url' => url_edicao((int) $ed['ano']),
    ];
}, $edicoes);

$titulo = 'Histórico Público | Campeonato Joinvilense de Robótica';
include __DIR__ . '/inc/cabecalho.php';
?>
<?php if (!modo_embed()): ?>
<section class="acervo-hero">
    <div class="acervo-container">
        <h1>Campeonato Joinvilense de Robótica</h1>
        <p>A memória e o impacto educacional do campeonato, edição por edição.</p>
    </div>
</section>
<?php endif; ?>

<main id="conteudo" class="acervo-container">
    <p class="acervo-aviso" role="note">Demonstração com dados fictícios ou anonimizados. Nomes reais de estudantes e escolas só entram após autorização institucional.</p>

    <section id="sobre" class="acervo-card">
        <h2>Sobre o acervo</h2>
        <p>Reunimos em um só lugar as edições do campeonato, com resultados, projetos e números de participação. Cada conteúdo só é publicado depois de conferido com a fonte oficial da Secretaria de Educação.</p>
    </section>

    <section id="linha-do-tempo" aria-labelledby="t-linha">
        <h2 id="t-linha" class="acervo-titulo">Linha do tempo das edições</h2>
        <p class="acervo-desc">Passe o mouse, toque ou use o teclado em cada ano para ver o resumo. Clique em “Ver edição completa” para abrir a página da edição.</p>

        <?php if (!$edicoes): ?>
            <p class="acervo-card">Nenhuma edição publicada até o momento.</p>
        <?php else: ?>
        <div class="acervo-linha" tabindex="0" aria-label="Linha do tempo, role para o lado para ver mais edições">
            <ol>
                <?php foreach ($edicoes as $i => $ed): ?>
                <li class="acervo-no <?= $i % 2 ? 'abaixo' : 'acima' ?>">
                    <button type="button" class="acervo-no__botao" data-ano="<?= (int) $ed['ano'] ?>"
                            aria-haspopup="dialog" aria-expanded="false"
                            aria-label="<?= e($ed['edicao']) ?>, <?= (int) $ed['ano'] ?>: ver resumo"><?= (int) $ed['ano'] ?></button>
                    <span class="acervo-no__rotulo"><?= e($ed['edicao']) ?><small><?= (int) $ed['equipes'] ?> equipes</small></span>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>

        <p class="acervo-acesso">Acesso direto:
            <?php foreach (array_reverse($edicoes) as $ed): ?>
                <a href="<?= e(url_edicao((int) $ed['ano'])) ?>"><?= (int) $ed['ano'] ?></a>
            <?php endforeach; ?>
        </p>
        <?php endif; ?>

        <div id="acervo-popup" class="acervo-popup" role="dialog" aria-label="Resumo da edição" hidden></div>
        <script type="application/json" id="acervo-dados"><?= json_encode($popup, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
    </section>

    <?php if ($ultima): ?>
    <section id="indicadores" aria-labelledby="t-ind">
        <h2 id="t-ind" class="acervo-titulo">Indicadores</h2>
        <div class="acervo-stats">
            <div class="acervo-stat"><strong><?= count($edicoes) ?></strong><span>edições publicadas</span></div>
            <div class="acervo-stat"><strong><?= (int) $ultima['equipes'] ?></strong><span>equipes em <?= (int) $ultima['ano'] ?></span></div>
            <div class="acervo-stat"><strong><?= (int) $ultima['escolas'] ?></strong><span>escolas em <?= (int) $ultima['ano'] ?></span></div>
            <div class="acervo-stat"><strong><?= (int) $ultima['estudantes'] ?>+</strong><span>estudantes em <?= (int) $ultima['ano'] ?></span></div>
        </div>
        <div class="acervo-card">
            <h3>Crescimento de equipes por edição</h3>
            <ul class="acervo-barras">
                <?php foreach ($edicoes as $ed): ?>
                <li><span><?= (int) $ed['ano'] ?></span>
                    <span class="trilho"><i style="--v:<?= round($ed['equipes'] / $maxEq * 100) ?>%"></i></span>
                    <b><?= (int) $ed['equipes'] ?></b></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <?php endif; ?>

    <section id="criterios" class="acervo-card" aria-labelledby="t-crit">
        <h2 id="t-crit">Como o conteúdo é publicado</h2>
        <ol class="acervo-fluxo">
            <li><strong>Dado bruto</strong><span>Vem do acervo interno da Secretaria e ainda não aparece no site.</span></li>
            <li><strong>Validado</strong><span>Conferido com a fonte oficial e anonimizado, quando necessário.</span></li>
            <li><strong>Publicado</strong><span>Autorizado pela Secretaria. Correções ficam registradas na página da edição.</span></li>
        </ol>
    </section>
</main>
<?php include __DIR__ . '/inc/rodape.php'; ?>
