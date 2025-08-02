<?php
/**
 * Constantes e configurações do sistema
 */

// Configurações gerais
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('CAPAS_DIR', 'capas');
define('TEMPLATES_DIR', 'templates');

// Extensões permitidas
define('ALLOWED_TEXT_EXTENSIONS', ['txt', 'md', 'text', 'article']);
define('ALLOWED_EDITABLE_EXTENSIONS', ['txt', 'md', 'text', 'article', 'html', 'css', 'js', 'php', 'json', 'xml']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Conteúdo padrão para novos arquivos
define('DEFAULT_FILE_CONTENT', "Título do Artigo\nDescrição breve do artigo\n\nConteúdo do artigo...");

// Configurações de erro
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
?>
