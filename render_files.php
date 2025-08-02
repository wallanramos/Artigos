<?php
/**
 * Renderização da lista de arquivos e pastas
 */

function renderizarListaArquivos($ordem) {
    $itens = listarItens($ordem);
    $pastas = $itens['pastas'];
    $arquivos = $itens['arquivos'];
    
    echo "<ul>";
    
    // Mostrar pastas
    foreach ($pastas as $pasta) {
        echo "<li><a href=\"$pasta\" class=\"pasta-item\">📁 $pasta</a></li>";
    }

    // Mostrar arquivos (exceto index.php)
    foreach ($arquivos as $arquivo) {
        if ($arquivo !== 'index.php') {
            renderizarItemArquivo($arquivo, $ordem);
        }
    }
    
    echo "</ul>";
}

function renderizarItemArquivo($arquivo, $ordem) {
    $infoArquivo = obterTituloDescricao($arquivo);
    $editavel = ehArquivoEditavel($arquivo);
    $caminhoCapa = obterCaminhoCapaArquivo($arquivo);
    
    $classeEditavel = $editavel ? ' editavel' : '';
    echo "<li class=\"arquivo-item$classeEditavel\">";
    
    // Mostrar capa (se existir)
    if ($caminhoCapa && file_exists($caminhoCapa)) {
        echo "<img src=\"" . htmlspecialchars($caminhoCapa) . "?v=" . filemtime($caminhoCapa) . "\" alt=\"Capa\" class=\"arquivo-capa\" onerror=\"this.style.display='none'; this.nextElementSibling.style.display='flex';\">";
        echo "<div class=\"arquivo-sem-capa\" style=\"display: none;\">📷</div>";
    } else {
        echo "<div class=\"arquivo-sem-capa\">📷</div>";
    }
    
    echo "<div class=\"arquivo-conteudo\">";
    
    if ($editavel) {
        renderizarLinkEditavel($arquivo, $infoArquivo, $ordem);
    } else {
        renderizarLinkNaoEditavel($arquivo, $infoArquivo);
    }
    
    // Botão para gerenciar capa
    echo "<div style=\"margin-top: 0.5rem;\">";
    if ($caminhoCapa && file_exists($caminhoCapa)) {
        echo "<a href=\"?capa=" . urlencode($arquivo) . "&ordem=" . urlencode($ordem) . "\" class=\"botao botao-capa\">🖼️ Alterar Capa</a>";
    } else {
        echo "<a href=\"?capa=" . urlencode($arquivo) . "&ordem=" . urlencode($ordem) . "\" class=\"botao botao-capa\">🖼️ Adicionar Capa</a>";
    }
    echo "</div>";
    
    echo "</div>";
    echo "</li>";
}

function renderizarLinkEditavel($arquivo, $infoArquivo, $ordem) {
    $linkEditor = "?editar=" . urlencode($arquivo) . "&ordem=" . urlencode($ordem);
    
    if ($infoArquivo && !empty($infoArquivo['titulo'])) {
        // É um arquivo de texto com título
        echo "<a href=\"$linkEditor\">";
        echo "<div class=\"arquivo-titulo\">📄 " . htmlspecialchars($infoArquivo['titulo']) . "</div>";
        if (!empty($infoArquivo['descricao'])) {
            echo "<div class=\"arquivo-descricao\">" . htmlspecialchars($infoArquivo['descricao']) . "</div>";
        }
        echo "</a>";
    } else {
        // Arquivo comum editável
        echo "<a href=\"$linkEditor\">📄 $arquivo</a>";
    }
}

function renderizarLinkNaoEditavel($arquivo, $infoArquivo) {
    if ($infoArquivo && !empty($infoArquivo['titulo'])) {
        // É um arquivo de texto com título
        echo "<a href=\"$arquivo\">";
        echo "<div class=\"arquivo-titulo\">📄 " . htmlspecialchars($infoArquivo['titulo']) . "</div>";
        if (!empty($infoArquivo['descricao'])) {
            echo "<div class=\"arquivo-descricao\">" . htmlspecialchars($infoArquivo['descricao']) . "</div>";
        }
        echo "</a>";
    } else {
        // Arquivo comum não editável
        echo "<a href=\"$arquivo\">📄 $arquivo</a>";
    }
}
?>
