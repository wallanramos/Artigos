<?php
/**
 * Template do Editor de Arquivos
 */
?>
<!-- Modo de edição -->
<div class="editor-container">
  <div class="editor-header">
    <div class="editor-titulo">Editando: <?php echo htmlspecialchars($arquivoEditar); ?></div>
    <div class="botoes">
      <button type="submit" form="form-editor" class="botao-salvar">Salvar</button>
      <a href="<?php echo 'index.php?ordem=' . urlencode($ordem); ?>" class="botao botao-cancelar">Cancelar</a>
    </div>
  </div>
  
  <form id="form-editor" method="POST">
    <input type="hidden" name="acao" value="salvar">
    <input type="hidden" name="arquivo" value="<?php echo htmlspecialchars($arquivoEditar); ?>">
    <input type="hidden" name="ordem" value="<?php echo htmlspecialchars($ordem); ?>">
    <textarea name="conteudo" placeholder="Digite o conteúdo do arquivo..."><?php echo htmlspecialchars($conteudoEditar); ?></textarea>
  </form>
</div>
