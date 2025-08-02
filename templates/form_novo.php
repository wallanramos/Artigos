<?php
/**
 * Template do Formulário para Criar Novo Arquivo
 */
?>
<!-- Formulário para criar novo arquivo -->
<div class="form-novo">
  <div class="editor-header">
    <div class="editor-titulo">Criar Novo Arquivo</div>
    <div class="botoes">
      <a href="<?php echo 'index.php?ordem=' . urlencode($ordem); ?>" class="botao botao-cancelar">Cancelar</a>
    </div>
  </div>
  
  <form method="POST">
    <input type="hidden" name="acao" value="criar">
    <div class="form-grupo">
      <label for="nome_arquivo">Nome do Arquivo:</label>
      <input type="text" id="nome_arquivo" name="nome_arquivo" placeholder="exemplo: meu-artigo.txt" required>
      <div class="form-ajuda">Se não especificar a extensão, será adicionado .txt automaticamente</div>
    </div>
    <div class="botoes">
      <button type="submit" class="botao botao-novo">Criar Arquivo</button>
    </div>
  </form>
</div>
