<?php if (!modo_embed()): ?>
<footer class="acervo-rodape">
    <div class="acervo-container">
        <p>&copy; <?= date('Y') ?> Prefeitura Municipal de Joinville | Secretaria de Educação</p>
        <small>MVP desenvolvido no Hackathon ADS — UNISENAI Joinville. Dados fictícios para demonstração.</small>
    </div>
</footer>
<?php endif; ?>
<script src="<?= e(base_path()) ?>assets/script.js" defer></script>
</body>
</html>
