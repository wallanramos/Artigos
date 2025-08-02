<?php
/**
 * Template do Gerenciador de Capa
 */
?>
<!-- Gerenciador de capa -->
<div class="capa-container">
  <div class="editor-header">
    <div class="editor-titulo">Gerenciar Capa: <?php echo htmlspecialchars($gerenciarCapa); ?></div>
    <div class="botoes">
      <a href="<?php echo 'index.php?ordem=' . urlencode($ordem); ?>" class="botao botao-cancelar">Voltar</a>
    </div>
  </div>
  
  <?php
    // Verificar se já existe capa
    $nomeArquivoSemExtensao = pathinfo($gerenciarCapa, PATHINFO_FILENAME);
    $capaExistente = null;
    
    foreach (ALLOWED_IMAGE_EXTENSIONS as $ext) {
      $caminhoCapa = CAPAS_DIR . "/{$nomeArquivoSemExtensao}.{$ext}";
      if (file_exists($caminhoCapa)) {
        $capaExistente = $caminhoCapa;
        break;
      }
    }
  ?>
  
  <?php if ($capaExistente): ?>
    <!-- Mostrar capa existente -->
    <div style="text-align: center; margin-bottom: 1.5rem;">
      <img src="<?php echo htmlspecialchars($capaExistente) . '?v=' . filemtime($capaExistente); ?>" alt="Capa atual" class="capa-preview" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
      <div class="capa-placeholder" style="display: none;">❌ Erro ao carregar imagem</div>
      
      <form method="POST" style="margin-top: 1rem;">
        <input type="hidden" name="acao" value="remover_capa">
        <input type="hidden" name="arquivo" value="<?php echo htmlspecialchars($gerenciarCapa); ?>">
        <input type="hidden" name="ordem" value="<?php echo htmlspecialchars($ordem); ?>">
        <button type="submit" class="botao botao-remover-capa" onclick="return confirm('Tem certeza que deseja remover a capa?')">Remover Capa</button>
      </form>
    </div>
    
    <h3 style="color: #ffffff; margin-bottom: 1rem;">Substituir Capa:</h3>
  <?php else: ?>
    <!-- Placeholder para nova capa -->
    <div style="text-align: center; margin-bottom: 1.5rem;">
      <div class="capa-placeholder">
        📷 Sem capa
      </div>
    </div>
    
    <h3 style="color: #ffffff; margin-bottom: 1rem;">Adicionar Capa:</h3>
  <?php endif; ?>
  
  <!-- Formulário de upload -->
  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="acao" value="upload_capa">
    <input type="hidden" name="arquivo" value="<?php echo htmlspecialchars($gerenciarCapa); ?>">
    <input type="hidden" name="ordem" value="<?php echo htmlspecialchars($ordem); ?>">
    
    <div class="upload-area">
      <div style="margin-bottom: 1rem;">
        <strong>Selecione uma imagem</strong><br>
        <small style="color: #b0b0b0;">Formatos: <?php echo implode(', ', array_map('strtoupper', ALLOWED_IMAGE_EXTENSIONS)); ?> (máximo <?php echo (MAX_FILE_SIZE / 1024 / 1024); ?>MB)</small>
      </div>
      <input type="file" name="capa" accept="image/*" required>
    </div>
    
    <div class="botoes">
      <button type="submit" class="botao botao-novo">Upload Capa</button>
    </div>
  </form>
</div>
