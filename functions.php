<?php
/**
 * Funções auxiliares para o sistema de arquivos
 */

require_once 'constants.php';

/**
 * Obter título e descrição de um arquivo de texto
 */
function obterTituloDescricao($nomeArquivo) {
    $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
    
    if (in_array($extensao, ALLOWED_TEXT_EXTENSIONS) && is_readable($nomeArquivo)) {
        $linhas = file($nomeArquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $titulo = '';
        $descricao = '';
        
        if (count($linhas) >= 1) {
            $titulo = trim($linhas[0]);
            // Remove possíveis marcações markdown do título
            $titulo = preg_replace('/^#+\s*/', '', $titulo);
        }
        
        if (count($linhas) >= 2) {
            $descricao = trim($linhas[1]);
            // Remove possíveis marcações markdown da descrição
            $descricao = preg_replace('/^\*+\s*/', '', $descricao);
        }
        
        return ['titulo' => $titulo, 'descricao' => $descricao];
    }
    
    return null;
}

/**
 * Verificar se um arquivo é editável
 */
function ehArquivoEditavel($nomeArquivo) {
    $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
    return in_array($extensao, ALLOWED_EDITABLE_EXTENSIONS);
}

/**
 * Obter caminho da capa de um arquivo
 */
function obterCaminhoCapaArquivo($nomeArquivo) {
    $nomeArquivoSemExtensao = pathinfo($nomeArquivo, PATHINFO_FILENAME);
    
    foreach (ALLOWED_IMAGE_EXTENSIONS as $ext) {
        $caminhoCapa = CAPAS_DIR . "/{$nomeArquivoSemExtensao}.{$ext}";
        if (file_exists($caminhoCapa)) {
            return $caminhoCapa;
        }
    }
    
    return null;
}

/**
 * Validar nome de arquivo para segurança
 */
function validarNomeArquivo($nomeArquivo) {
    return strpos($nomeArquivo, '..') === false && 
           strpos($nomeArquivo, '/') === false && 
           strpos($nomeArquivo, '\\') === false;
}

/**
 * Listar arquivos e pastas organizados
 */
function listarItens($ordem = 'asc') {
    $diretorio = '.';
    $itens = array_diff(scandir($diretorio), array('.', '..'));

    $pastas = [];
    $arquivos = [];

    foreach ($itens as $item) {
        if (is_dir($item)) {
            $pastas[] = $item;
        } else {
            $arquivos[] = $item;
        }
    }

    // Ordenar os dois grupos separadamente
    if ($ordem === 'asc') {
        natcasesort($pastas);
        natcasesort($arquivos);
    } else {
        natcasesort($pastas);
        natcasesort($arquivos);
        $pastas = array_reverse($pastas, true);
        $arquivos = array_reverse($arquivos, true);
    }

    return ['pastas' => $pastas, 'arquivos' => $arquivos];
}

/**
 * Remover capa existente de um arquivo
 */
function removerCapaExistente($nomeArquivo, $excluirExtensao = null) {
    $nomeArquivoSemExtensao = pathinfo($nomeArquivo, PATHINFO_FILENAME);
    
    foreach (ALLOWED_IMAGE_EXTENSIONS as $ext) {
        if ($excluirExtensao && $ext === $excluirExtensao) {
            continue;
        }
        
        $capaAntiga = CAPAS_DIR . "/{$nomeArquivoSemExtensao}.{$ext}";
        if (file_exists($capaAntiga)) {
            unlink($capaAntiga);
        }
    }
}

/**
 * Criar diretório se não existir
 */
function criarDiretorioSeNaoExistir($diretorio) {
    if (!is_dir($diretorio)) {
        return mkdir($diretorio, 0755, true);
    }
    return true;
}
?>$extensoesImagem = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    foreach ($extensoesImagem as $ext) {
        $caminhoCapa = "capas/{$nomeArquivoSemExtensao}.{$ext}";
        if (file_exists($caminhoCapa)) {
            return $caminhoCapa;
        }
    }
    
    return null;
}

/**
 * Validar nome de arquivo para segurança
 */
function validarNomeArquivo($nomeArquivo) {
    return strpos($nomeArquivo, '..') === false && 
           strpos($nomeArquivo, '/') === false && 
           strpos($nomeArquivo, '\\') === false;
}

/**
 * Listar arquivos e pastas organizados
 */
function listarItens($ordem = 'asc') {
    $diretorio = '.';
    $itens = array_diff(scandir($diretorio), array('.', '..'));

    $pastas = [];
    $arquivos = [];

    foreach ($itens as $item) {
        if (is_dir($item)) {
            $pastas[] = $item;
        } else {
            $arquivos[] = $item;
        }
    }

    // Ordenar os dois grupos separadamente
    if ($ordem === 'asc') {
        natcasesort($pastas);
        natcasesort($arquivos);
    } else {
        natcasesort($pastas);
        natcasesort($arquivos);
        $pastas = array_reverse($pastas, true);
        $arquivos = array_reverse($arquivos, true);
    }

    return ['pastas' => $pastas, 'arquivos' => $arquivos];
}

/**
 * Remover capa existente de um arquivo
 */
function removerCapaExistente($nomeArquivo, $excluirExtensao = null) {
    $nomeArquivoSemExtensao = pathinfo($nomeArquivo, PATHINFO_FILENAME);
    $extensoesImagem = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    foreach ($extensoesImagem as $ext) {
        if ($excluirExtensao && $ext === $excluirExtensao) {
            continue;
        }
        
        $capaAntiga = "capas/{$nomeArquivoSemExtensao}.{$ext}";
        if (file_exists($capaAntiga)) {
            unlink($capaAntiga);
        }
    }
}
?>
