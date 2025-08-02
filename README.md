# Sistema de Gerenciamento de Arquivos

Um sistema web simples e eficiente para gerenciar arquivos de texto com suporte a capas de imagem.

## 📁 Estrutura do Projeto

```
projeto/
├── index.php                      # Arquivo principal - interface do usuário
├── styles.css                      # Estilos CSS do sistema
├── constants.php               # Constantes e configurações
├── functions.php                # Funções auxiliares
├── actions.php                   # Processamento de ações POST x
├── config.php                     # Configuração de variáveis da página
├── render_files.php            # Renderização da lista de arquivos
├── templates/                     # Templates HTML
│   ├── capa_manager.php   # Gerenciador de capas
│   ├── form_novo.php         # Formulário novo arquivo
│   └── editor.php                # Editor de arquivos
├── capas/                           # Diretório para imagens de capa (criado automaticamente)
└── README.md                # Esta documentação
```

## 🚀 Funcionalidades

- **Edição de Arquivos**: Editor web para arquivos de texto
- **Gerenciamento de Capas**: Upload e gerenciamento de imagens de capa
- **Criação de Arquivos**: Interface para criar novos arquivos
- **Navegação**: Listagem organizada de arquivos e pastas
- **Responsivo**: Interface adaptada para desktop e mobile

## 📋 Requisitos

- PHP 7.4 ou superior
- Servidor web (Apache, Nginx, ou similar)
- Extensões PHP habilitadas:
  - `fileinfo` (para validação de tipos de arquivo)
  - `gd` ou `imagick` (recomendado para manipulação de imagens)

## ⚙️ Configuração

### Configurações Principais (constants.php)

```php
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // Tamanho máximo: 5MB
define('CAPAS_DIR', 'capas');              // Diretório das capas
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_EDITABLE_EXTENSIONS', ['txt', 'md', 'text', 'article', 'html', 'css', 'js', 'php', 'json', 'xml']);
```

### Permissões Necessárias

```bash
# Dar permissão de escrita ao diretório
chmod 755 /caminho/para/projeto/

# O diretório capas/ será criado automaticamente com as permissões corretas
```

## 🛠️ Instalação

1. **Baixar os arquivos**: Coloque todos os arquivos PHP no diretório do seu servidor web

2. **Configurar permissões**: Certifique-se de que o PHP tenha permissão para criar arquivos e diretórios

3. **Acessar**: Abra `http://seudominio.com/index.php` no navegador

4. **Primeiro uso**: O sistema criará automaticamente o diretório `capas/` quando necessário

## 📖 Como Usar

### Criar Novo Arquivo
1. Clique em "+ Novo Arquivo"
2. Digite o nome do arquivo (extensão .txt será adicionada automaticamente se não especificada)
3. Clique em "Criar Arquivo"
4. Você será redirecionado para o editor

### Editar Arquivo
1. Clique em qualquer arquivo editável na lista
2. Faça suas alterações no editor
3. Clique em "Salvar"

### Gerenciar Capas
1. Clique em "🖼️ Adicionar Capa" ou "🖼️ Alterar Capa"
2. Selecione uma imagem (JPG, PNG, GIF, WebP)
3. Clique em "Upload Capa"

### Navegação
- **Ordenação**: Use os botões "↑ A-Z" e "↓ Z-A" para ordenar
- **Pastas**: Clique em pastas para navegar
- **Arquivos**: Arquivos editáveis mostram um ícone de lápis

## 🔧 Arquitetura do Sistema

### Separação de Responsabilidades

- **index.php**: Interface principal e coordenação
- **actions.php**: Lógica de processamento de formulários
- **functions.php**: Funções utilitárias reutilizáveis
- **config.php**: Configuração de variáveis da sessão
- **render_files.php**: Lógica de renderização
- **templates/**: Views HTML modularizadas
- **constants.php**: Configurações centralizadas

### Fluxo de Execução

1. **Inicialização**: `constants.php` → `actions.php` → `config.php`
2. **Processamento**: Ações POST são processadas primeiro
3. **Renderização**: Templates são incluídos conforme necessário
4. **Listagem**: `render_files.php` gera a lista final

## 🔒 Segurança

### Validações Implementadas

- **Path Traversal**: Bloqueio de `../`, `/`, `\` em nomes de arquivos
- **Tipos de Arquivo**: Validação de MIME type e extensão
- **Tamanho**: Limite de 5MB para uploads
- **Sanitização**: Uso de `htmlspecialchars()` em todas as saídas

### Recomendações Adicionais

```php
// Em ambiente de produção, adicione ao .htaccess:
# Bloquear acesso direto aos arquivos PHP auxiliares
<Files "constants.php">
    Deny from all
</Files>
<Files "functions.php">
    Deny from all
</Files>
<Files "actions.php">
    Deny from all
</Files>
<Files "config.php">
    Deny from all
</Files>
<Files "render_files.php">
    Deny from all
</Files>
```

## 🎨 Personalização

### Modificar Estilos
Edite `styles.css` para personalizar a aparência.

### Adicionar Tipos de Arquivo
Modifique as constantes em `constants.php`:

```php
define('ALLOWED_EDITABLE_EXTENSIONS', ['txt', 'md', 'seu_tipo']);
```

### Customizar Templates
Os templates em `templates/` podem ser modificados independentemente.

## 🐛 Solução de Problemas

### Erro de Permissões
```bash
# Verificar permissões
ls -la

# Corrigir se necessário
chmod 755 .
chmod 644 *.php *.css
```

### Upload Não Funciona
1. Verifique `upload_max_filesize` no PHP
2. Confirme se o diretório `capas/` tem permissão de escrita
3. Verifique logs de erro do servidor

### Arquivos Não Aparecem
1. Confirme se os arquivos têm extensões suportadas
2. Verifique permissões de leitura
3. Examine se há caracteres especiais nos nomes

## 📝 Changelog

### Versão Atual
- ✅ Separação completa do PHP em módulos
- ✅ Sistema de templates
- ✅ Constantes centralizadas
- ✅ Melhor organização do código
- ✅ Documentação completa

## 🤝 Contribuição

Para contribuir com o projeto:

1. Mantenha a separação de responsabilidades
2. Use as constantes definidas em `constants.php`
3. Siga o padrão de validação de segurança
4. Documente mudanças significativas

## 📄 Licença

Este projeto é de código aberto. Use conforme necessário para seus projetos pessoais ou comerciais.
