<?php
/**
 * Configurações e inicialização de variáveis
 */

require_once 'functions.php';

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
    if (!validarNomeArquivo($gerenciarCapa)) {
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
    if (validarNomeArquivo($arquivoEditar)) {
        $conteudoEditar = file_get_contents($arquivoEditar);
    } else {
        $arquivoEditar = null;
    }
}

// Definir título da página
$tituloPagina = 'Arquivos';
if ($arquivoEditar) {
    $tituloPagina = 'Editando: ' . $arquivoEditar;
} elseif ($gerenciarCapa) {
    $tituloPagina = 'Capa: ' . $gerenciarCapa;
} elseif ($mostrarFormularioNovo) {
    $tituloPagina = 'Novo Arquivo';
}
?>
