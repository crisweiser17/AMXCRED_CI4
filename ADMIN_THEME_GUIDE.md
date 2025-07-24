# Guia do Admin Theme - AMX Cred

## Visão Geral

Este documento descreve a estrutura e uso do admin theme implementado com Preline.co e Tailwind CSS para o sistema AMX Cred.

## Estrutura de Arquivos

```
app/
├── Config/
│   ├── Navigation.php          # Configuração de módulos e navegação
│   └── Autoload.php            # Registro automático de helpers
├── Controllers/
│   ├── Home.php               # Controller do dashboard
│   └── ClientController.php   # Controller de clientes
├── Helpers/
│   └── navigation_helper.php  # Funções auxiliares de navegação
├── Views/
│   ├── layouts/
│   │   ├── main_layout.php    # Layout principal do admin
│   │   └── components/
│   │       ├── header.php     # Header com perfil do usuário
│   │       └── sidebar.php    # Menu lateral
│   ├── dashboard/
│   │   └── index.php          # Página do dashboard
│   └── clients/
│       └── index.php          # Página de listagem de clientes
```

## Como Usar

### 1. Estendendo o Layout Principal

Para usar o admin theme em qualquer página, estenda o layout principal:

```php
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
    <!-- Seu conteúdo aqui -->
<?= $this->endSection() ?>
```

### 2. Adicionando Novos Módulos

Para adicionar novos módulos ao menu lateral, edite o arquivo `app/Helpers/navigation_helper.php`:

```php
'novo_modulo' => [
    'name' => 'Nome do Módulo',
    'icon' => 'heroicons-icone-aqui',
    'items' => [
        'listar' => [
            'name' => 'Listar Itens',
            'url' => '/novo-modulo',
            'icon' => 'heroicons-list-bullet'
        ],
        'criar' => [
            'name' => 'Novo Item',
            'url' => '/novo-modulo/create',
            'icon' => 'heroicons-plus-circle'
        ]
    ]
]
```

### 3. Ícones Disponíveis

O sistema usa Heroicons. Alguns exemplos:
- `heroicons-home` - Dashboard
- `heroicons-user-group` - Clientes
- `heroicons-list-bullet` - Listar
- `heroicons-plus-circle` - Adicionar
- `heroicons-cog-6-tooth` - Configurações

### 4. Responsividade

O tema é totalmente responsivo:
- **Desktop**: Sidebar expandido (16rem)
- **Tablet**: Sidebar colapsível
- **Mobile**: Sidebar como overlay

### 5. Componentes Disponíveis

#### Cards
```html
<div class="bg-white shadow-sm rounded-lg p-6">
    <h3 class="text-lg font-medium text-gray-900">Título</h3>
    <p class="mt-1 text-sm text-gray-600">Descrição</p>
</div>
```

#### Tabelas
```html
<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <!-- cabeçalho -->
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        <!-- conteúdo -->
    </tbody>
</table>
```

#### Botões
```html
<button class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
    Botão Primário
</button>
```

## URLs Disponíveis

- `/dashboard` - Dashboard principal
- `/clients` - Listagem de clientes
- `/clients/create` - Formulário de novo cliente (em desenvolvimento)

## Personalização

### Cores
As cores podem ser personalizadas editando as classes Tailwind:
- Azul principal: `bg-blue-600`, `text-blue-600`
- Verde: `bg-green-600`, `text-green-600`
- Vermelho: `bg-red-600`, `text-red-600`

### Sidebar
Para colapsar/expandir o sidebar automaticamente em telas menores, use:
```javascript
// Adicionar ao main_layout.php
if (window.innerWidth < 768) {
    document.getElementById('sidebar').classList.add('sidebar-collapsed');
    document.getElementById('main-content').classList.add('content-collapsed');
}
```

## Próximos Passos

1. Implementar formulário de criação de clientes
2. Adicionar funcionalidade de edição de clientes
3. Implementar sistema de busca e filtros
4. Adicionar gráficos ao dashboard
5. Implementar sistema de permissões por usuário

## Suporte

Para dúvidas ou sugestões sobre o admin theme, consulte a documentação do [Preline.co](https://preline.co/) e [Tailwind CSS](https://tailwindcss.com/).