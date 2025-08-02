<?php
/**
 * Sistema de Gerenciamento de Arquivos
 * Arquivo principal - index.php
 */

// Incluir arquivos de apoio
require_once 'actions.php';  // Processamento de ações POST
require_once 'config.php';   // Configurações e variáveis
require_once 'render_files.php'; // Renderização de arquivos
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($tituloPagina); ?></title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php if (isset($mensagem) && !empty($mensagem)): ?>
    <div class="mensagem <?php echo $tipoMensagem; ?>">
      <?php echo htmlspecialchars($mensagem); ?>
    </div>
  <?php endif; ?>

  <?php if ($gerenciarCapa && !$arquivoEditar): ?>
    <?php include 'templates/capa_manager.php'; ?>
  <?php endif; ?>

  <?php if ($mostrarFormularioNovo && !$arquivoEditar && !$gerenciarCapa): ?>
    <?php include 'templates/form_novo.php'; ?>
  <?php endif; ?>

  <?php if ($arquivoEditar): ?>
    <?php include 'templates/editor.php'; ?>
  <?php endif; ?>

  <div class="cabecalho-secao">
    <h1><?php echo $arquivoEditar ? 'Outros Arquivos' : ($mostrarFormularioNovo ? 'Arquivos e Pastas' : 'Arquivos e Pastas'); ?></h1>
    
    <div class="controles-ordenacao">
      <?php if (!$arquivoEditar && !$mostrarFormularioNovo): ?>
        <span class="label-ordenacao">Ordenar:</span>
        <a href="<?php echo '?ordem=asc' . ($mostrarFormularioNovo ? '&novo' : ''); ?>" 
           class="botao botao-ordenacao <?php echo $ordem === 'asc' ? 'ativo' : ''; ?>">
          ↑ A-Z
        </a>
        <a href="<?php echo '?ordem=desc' . ($mostrarFormularioNovo ? '&novo' : ''); ?>" 
           class="botao botao-ordenacao <?php echo $ordem === 'desc' ? 'ativo' : ''; ?>">
          ↓ Z-A
        </a>
        <a href="?novo" class="botao botao-novo">+ Novo Arquivo</a>
      <?php elseif (!$arquivoEditar && $mostrarFormularioNovo): ?>
        <div class="controles-ordenacao">
          <span class="label-ordenacao">Ordenar:</span>
          <a href="<?php echo '?ordem=asc&novo'; ?>" 
             class="botao botao-ordenacao <?php echo $ordem === 'asc' ? 'ativo' : ''; ?>">
            ↑ A-Z
          </a>
          <a href="<?php echo '?ordem=desc&novo'; ?>" 
             class="botao botao-ordenacao <?php echo $ordem === 'desc' ? 'ativo' : ''; ?>">
            ↓ Z-A
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
  
  <?php renderizarListaArquivos($ordem); ?>
</body>
</html> Verificar se é uma ação de salvar
if ($_POST['acao'] === 'salvar' && isset($_POST['arquivo']) && isset($_POST['conteudo'])) {
    $arquivo = $_POST['arquivo'];
    
    // Validação básica de segurança
    if (strpos($arquivo, '..') === false && strpos($arquivo, '/') === false && strpos($arquivo, '\\') === false) {
        if (file_put_contents($arquivo, $_POST['conteudo']) !== false) {
            // Redirecionar para a página principal com mensagem de sucesso
            $ordem = isset($_POST['ordem']) ? $_POST['ordem'] : 'asc';
            header("Location: index.php?ordem=" . urlencode($ordem) . "&salvo=" . urlencode($arquivo));
            exit;
        } else {
            $mensagem = "Erro ao salvar o arquivo.";
            $tipoMensagem = "erro";
        }
    } else {
        $mensagem = "Nome de arquivo inválido.";
        $tipoMensagem = "erro";
    }
}

// Verificar se é uma ação de criar novo arquivo
if ($_POST['acao'] === 'criar' && isset($_POST['nome_arquivo'])) {
    $nomeArquivo = trim($_POST['nome_arquivo']);
    
    // Validação do nome do arquivo
    if (empty($nomeArquivo)) {
        $mensagem = "Nome do arquivo não pode estar vazio.";
        $tipoMensagem = "erro";
    } elseif (strpos($nomeArquivo, '..') !== false || strpos($nomeArquivo, '/') !== false || strpos($nomeArquivo, '\\') !== false) {
        $mensagem = "Nome de arquivo inválido.";
        $tipoMensagem = "erro";
    } elseif (file_exists($nomeArquivo)) {
        $mensagem = "Arquivo já existe.";
        $tipoMensagem = "erro";
    } else {
        // Adicionar extensão .txt se não tiver extensão
        if (pathinfo($nomeArquivo, PATHINFO_EXTENSION) === '') {
            $nomeArquivo .= '.txt';
        }
        
        // Criar arquivo com conteúdo inicial
        $conteudoInicial = "Título do Artigo\nDescrição breve do artigo\n\nConteúdo do artigo...";
        
        if (file_put_contents($nomeArquivo, $conteudoInicial) !== false) {
            $mensagem = "Arquivo '$nomeArquivo' criado com sucesso!";
            $tipoMensagem = "sucesso";
            // Redirecionar para edição do novo arquivo
            header("Location: ?editar=" . urlencode($nomeArquivo));
            exit;
        } else {
            $mensagem = "Erro ao criar o arquivo.";
            $tipoMensagem = "erro";
        }
    }
}

// Verificar ordenação
$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'asc';
$ordem = in_array($ordem, ['asc', 'desc']) ? $ordem : 'asc';

// Verificar se há mensagem de capa salva ou removida
if (isset($_GET['capa_salva'])) {
    $mensagem = "Capa do arquivo '" . htmlspecialchars($_GET['capa_salva']) . "' salva com sucesso!";
    $tipoMensagem = "sucesso";
} elseif (isset($_GET['capa_removida'])) {
    $mensagem = "Capa do arquivo '" . htmlspecialchars($_GET['capa_removida']) . "' removida com sucesso!";
    $tipoMensagem = "sucesso";
}

// Verificar se há mensagem de arquivo salvo
if (isset($_GET['salvo'])) {
    $mensagem = "Arquivo '" . htmlspecialchars($_GET['salvo']) . "' salvo com sucesso!";
    $tipoMensagem = "sucesso";
}

// Verificar se é para mostrar gerenciador de capa
$gerenciarCapa = null;
if (isset($_GET['capa']) && is_file($_GET['capa'])) {
    $gerenciarCapa = $_GET['capa'];
    
    // Validação de segurança
    if (strpos($gerenciarCapa, '..') !== false || strpos($gerenciarCapa, '/') !== false || strpos($gerenciarCapa, '\\') !== false) {
        $gerenciarCapa = null;
    }
}

// Verificar se é para mostrar o formulário de novo arquivo
$mostrarFormularioNovo = isset($_GET['novo']);

// Verificar se é para editar um arquivo
$arquivoEditar = null;
$conteudoEditar = '';
if (isset($_GET['editar']) && is_file($_GET['editar'])) {
    $arquivoEditar = $_GET['editar'];
    
    // Validação de segurança
    if (strpos($arquivoEditar, '..') === false && strpos($arquivoEditar, '/') === false && strpos($arquivoEditar, '\\') === false) {
        $conteudoEditar = file_get_contents($arquivoEditar);
    } else {
        $arquivoEditar = null;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $arquivoEditar ? 'Editando: ' . $arquivoEditar : ($gerenciarCapa ? 'Capa: ' . $gerenciarCapa : ($mostrarFormularioNovo ? 'Novo Arquivo' : 'Arquivos')); ?></title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php if (isset($mensagem)): ?>
    <div class="mensagem <?php echo $tipoMensagem; ?>">
      <?php echo htmlspecialchars($mensagem); ?>
    </div>
  <?php endif; ?>

  <?php if ($gerenciarCapa && !$arquivoEditar): ?>
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
        $extensoesImagem = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        foreach ($extensoesImagem as $ext) {
          $caminhoCapa = "capas/{$nomeArquivoSemExtensao}.{$ext}";
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
            <small style="color: #b0b0b0;">Formatos: JPG, PNG, GIF, WebP (máximo 5MB)</small>
          </div>
          <input type="file" name="capa" accept="image/*" required>
        </div>
        
        <div class="botoes">
          <button type="submit" class="botao botao-novo">Upload Capa</button>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <?php if ($mostrarFormularioNovo && !$arquivoEditar && !$gerenciarCapa): ?>
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
  <?php endif; ?>

  <?php if ($arquivoEditar): ?>
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
  <?php endif; ?>

  <div class="cabecalho-secao">
    <h1><?php echo $arquivoEditar ? 'Outros Arquivos' : ($mostrarFormularioNovo ? 'Arquivos e Pastas' : 'Arquivos e Pastas'); ?></h1>
    
    <div class="controles-ordenacao">
      <?php if (!$arquivoEditar && !$mostrarFormularioNovo): ?>
        <span class="label-ordenacao">Ordenar:</span>
        <a href="<?php echo '?ordem=asc' . ($mostrarFormularioNovo ? '&novo' : ''); ?>" 
           class="botao botao-ordenacao <?php echo $ordem === 'asc' ? 'ativo' : ''; ?>">
          ↑ A-Z
        </a>
        <a href="<?php echo '?ordem=desc' . ($mostrarFormularioNovo ? '&novo' : ''); ?>" 
           class="botao botao-ordenacao <?php echo $ordem === 'desc' ? 'ativo' : ''; ?>">
          ↓ Z-A
        </a>
        <a href="?novo" class="botao botao-novo">+ Novo Arquivo</a>
      <?php elseif (!$arquivoEditar && $mostrarFormularioNovo): ?>
        <div class="controles-ordenacao">
          <span class="label-ordenacao">Ordenar:</span>
          <a href="<?php echo '?ordem=asc&novo'; ?>" 
             class="botao botao-ordenacao <?php echo $ordem === 'asc' ? 'ativo' : ''; ?>">
            ↑ A-Z
          </a>
          <a href="<?php echo '?ordem=desc&novo'; ?>" 
             class="botao botao-ordenacao <?php echo $ordem === 'desc' ? 'ativo' : ''; ?>">
            ↓ Z-A
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <ul>
      <?php renderizarListaArquivos($ordem); ?>
</body>
</html>
