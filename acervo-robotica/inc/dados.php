<?php
/**
 * Camada de dados do acervo (MVP).
 * Fonte: data/edicoes.json. Somente edições com status "publicado" aparecem no site.
 * Fluxo editorial: rascunho -> validado -> publicado.
 * Para migrar para banco de dados ou WordPress, troque apenas carregar_edicoes().
 * Requer PHP 7.4+.
 */
const ACERVO_ARQUIVO = __DIR__ . '/../data/edicoes.json';
const ACERVO_URL_AMIGAVEL = false; // true quando o .htaccess (mod_rewrite) estiver ativo: /2025

function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function carregar_edicoes(bool $so_publicadas = true): array
{
    $json = is_readable(ACERVO_ARQUIVO) ? file_get_contents(ACERVO_ARQUIVO) : false;
    $dados = $json ? json_decode($json, true) : null;
    if (!is_array($dados) || !isset($dados['edicoes']) || !is_array($dados['edicoes'])) {
        return [];
    }
    $lista = $dados['edicoes'];
    if ($so_publicadas) {
        $lista = array_values(array_filter($lista, fn($x) => ($x['status'] ?? '') === 'publicado'));
    }
    usort($lista, fn($a, $b) => $a['ano'] <=> $b['ano']);
    return $lista;
}

function buscar_edicao(int $ano): ?array
{
    foreach (carregar_edicoes() as $ed) {
        if ((int) $ed['ano'] === $ano) {
            return $ed;
        }
    }
    return null;
}

function modo_embed(): bool
{
    return isset($_GET['embed']); // ?embed=1 => sem cabeçalho/rodapé (para iframe no site da prefeitura)
}

function base_path(): string
{
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    return $dir . '/';
}

function url_edicao(int $ano): string
{
    $q = modo_embed() ? 'embed=1' : '';
    if (ACERVO_URL_AMIGAVEL) {
        return base_path() . $ano . ($q ? '?' . $q : '');
    }
    return base_path() . 'edicao.php?ano=' . $ano . ($q ? '&' . $q : '');
}

function url_inicio(): string
{
    return base_path() . (modo_embed() ? '?embed=1' : '');
}

function data_br(?string $iso): string
{
    $t = $iso ? strtotime($iso) : false;
    return $t ? date('d/m/Y', $t) : '—';
}
