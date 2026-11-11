# Acervo Digital — Campeonato Joinvilense de Robótica (Desafio 03)

MVP de um espaço público que preserva e organiza a história do campeonato, com linha do tempo interativa.
Todos os dados são **fictícios** (exceto os totais de 2026 do briefing oficial: 69 equipes, 35 escolas, 250+ estudantes).

## Como executar
- **XAMPP:** copie a pasta para `htdocs/acervo-robotica` e abra `http://localhost/acervo-robotica/`.
- **Sem Apache:** `php -S localhost:8000` dentro da pasta (PHP 7.4+).
- **URLs por edição (`/2025`):** com Apache e `mod_rewrite`, defina `ACERVO_URL_AMIGAVEL = true` em `inc/dados.php`.

## Estrutura
| Arquivo | Papel |
|---|---|
| `index.php` | Linha do tempo, indicadores e fluxo editorial |
| `edicao.php` | Página de uma edição: 7 categorias, projetos, procedência e correções |
| `api.php` | JSON somente leitura (`/api.php`, `/api.php?ano=2025`) para integração futura |
| `inc/dados.php` | Camada de dados. Só retorna edições com `status = publicado` |
| `inc/cabecalho.php`, `inc/rodape.php` | Layout de referência no estilo da prefeitura |
| `data/edicoes.json` | Dados de teste (substituível por banco ou WordPress) |
| `assets/style.css`, `assets/script.js` | Estilo (prefixo `.acervo-`) e interação do pop-up |

## Fluxo editorial
`rascunho → validado → publicado`. Para publicar, a Comunicação da Secretaria altera o `status` da edição em `data/edicoes.json`.
Correções são registradas em `historico_correcoes`, preservando a procedência. No exemplo, 2021 (rascunho) e 2022 (validado) não aparecem no site.

## Integração com o site da prefeitura (WordPress)
1. **Iframe:** `index.php?embed=1` remove cabeçalho e rodapé.
2. **Copiar o `<main>`** para uma página do tema e incluir `style.css` e `script.js`. As classes são prefixadas, sem reset global.
3. **Evolução:** migrar `edicoes.json` para um tipo de conteúdo personalizado do WordPress. Só `carregar_edicoes()` muda.

## Interoperabilidade
- **Produz:** edições publicadas, números de participação, procedência (`api.php`).
- **Consome (futuro):** resultados do domínio de Competições e dados de edição do domínio de Gestão do Evento, ligados pelo campo `id` (`joinville-robotica-AAAA`).
- **Fonte da verdade:** acervo interno da Secretaria de Educação.

## Limitações do MVP
Sem painel administrativo nem login (edição manual do JSON), sem busca, sem imagens, nomes das 7 categorias a confirmar, dados fictícios.

## Backlog
Painel de validação com perfis e log, busca por escola/equipe/projeto, galeria com imagens autorizadas, QR code para divulgação em escolas e universidades, termo de autorização/licença de uso, exportação CSV.

## Uso de IA
Código e textos gerados com apoio do Claude (Anthropic) e revisados pela equipe.
