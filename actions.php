<?php
/**
 * Processamento de ações POST
 */

require_once 'constants.php';
require_once 'functions.php';

// Variáveis globais para mensagens
$mensagem = '';
$tipoMensagem = '';

// Verificar se é uma ação de upload de capa
if (isset($_POST['acao']) && $_POST['acao'] === 'upload_capa' && isset($_POST['arquivo']) && isset($_FILES['capa'])) {
    $arquivo = $_POST['arquivo'];
    $uploadFile = $_FILES['capa'];
    
    // Validação básica de segurança do arquivo
    if (validarNomeArquivo($arquivo)) {
        // Criar diretório capas se não existir
        criarDiretorioSeNaoExistir(CAPAS_DIR);
        
        // Validar tipo de arquivo
        $fileType = $uploadFile['type'];
        $fileExtension = strtolower(pathinfo($uploadFile['name'], PATHINFO_EXTENSION));
        
        if (in_array($fileType, ALLOWED_IMAGE_TYPES) && in_array($fileExtension, ALLOWED_IMAGE_EXTENSIONS)) {
            // Verificar se houve erro no upload
            if ($uploadFile['error'] === UPLOAD_ERR_OK) {
                // Verificar tamanho do arquivo
                if ($uploadFile['size'] <= MAX_FILE_SIZE) {
                    $nomeArquivoSemExtensao = pathinfo($arquivo, PATHINFO_FILENAME);
                    $nomeCapaDestino = CAPAS_DIR . "/{$nomeArquivoSemExtensao}.{$fileExtension}";
                    
                    // Remover capa anterior se existir
                    removerCapaExistente($arquivo, $fileExtension);
                    
                    // Mover arquivo uploaded
                    if (move_uploaded_file($uploadFile['tmp_name'], $nomeCapaDestino)) {
                        $ordem = isset($_POST['ordem']) ? $_POST['ordem'] : 'asc';
                        header("Location: index.php?ordem=" . urlencode($ordem) . "&capa_salva=" . urlencode($arquivo));
                        exit;
                    } else {
                        $mensagem = "Erro ao salvar a imagem.";
                        $tipoMensagem = "erro";
                    }
                } else {
                    $mensagem = "Arquivo muito grande. Tamanho máximo: " . (MAX_FILE_SIZE / 1024 / 1024) . "MB.";
                    $tipoMensagem = "erro";
                }
            } else {
                $mensagem = "Erro no upload: " . $uploadFile['error'];
                $tipoMensagem = "erro";
            }
        } else {
            $mensagem = "Tipo de arquivo não permitido. Use: " . implode(', ', array_map('strtoupper', ALLOWED_IMAGE_EXTENSIONS)) . ".";
            $tipoMensagem = "erro";
        }
    } else {
        $mensagem = "Nome de arquivo inválido.";
        $tipoMensagem = "erro";
    }
}

// Verificar se é uma ação de remover capa
if (isset($_POST['acao']) && $_POST['acao'] === 'remover_capa' && isset($_POST['arquivo'])) {
    $arquivo = $_POST['arquivo'];
    
    // Validação básica de segurança
    if (validarNomeArquivo($arquivo)) {
        $nomeArquivoSemExtensao = pathinfo($arquivo, PATHINFO_FILENAME);
        $capaRemovida = false;
        
        foreach (ALLOWED_IMAGE_EXTENSIONS as $ext) {
            $caminhoCapa = CAPAS_DIR . "/{$nomeArquivoSemExtensao}.{$ext}";
            if (file_exists($caminhoCapa)) {
                if (unlink($caminhoCapa)) {
                    $capaRemovida = true;
                }
                break;
            }
        }
        
        if ($capaRemovida) {
            $ordem = isset($_POST['ordem']) ? $_POST['ordem'] : 'asc';
            header("Location: index.php?ordem=" . urlencode($ordem) . "&capa_removida=" . urlencode($arquivo));
            exit;
        } else {
            $mensagem = "Erro ao remover a capa.";
            $tipoMensagem = "erro";
        }
    } else {
        $mensagem = "Nome de arquivo inválido.";
        $tipoMensagem = "erro";
    }
}

// Verificar se é uma ação de salvar
if (isset($_POST['acao']) && $_POST['acao'] === 'salvar' && isset($_POST['arquivo']) && isset($_POST['conteudo'])) {
    $arquivo = $_POST['arquivo'];
    
    // Validação básica de segurança
    if (validarNomeArquivo($arquivo)) {
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
if (isset($_POST['acao']) && $_POST['acao'] === 'criar' && isset($_POST['nome_arquivo'])) {
    $nomeArquivo = trim($_POST['nome_arquivo']);
    
    // Validação do nome do arquivo
    if (empty($nomeArquivo)) {
        $mensagem = "Nome do arquivo não pode estar vazio.";
        $tipoMensagem = "erro";
    } elseif (!validarNomeArquivo($nomeArquivo)) {
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
        if (file_put_contents($nomeArquivo, DEFAULT_FILE_CONTENT) !== false) {
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
?>
