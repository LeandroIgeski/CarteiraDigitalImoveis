# 🏠 Administração de Imóveis - Sistema de Gestão Imobiliária

Um sistema web em PHP para cadastro, análise e gerenciamento de imóveis para investimento, com autenticação de usuários, análise automática via IA, e geolocalização de endereços.

## 🎯 Funcionalidades

✅ **Autenticação de Usuários**
- Login e registro com senha
- Sessões persistentes e seguras
- Regeneração de ID de sessão após login

✅ **Cadastro de Imóveis**
- Formulário simplificado: valor do imóvel, valor do aluguel, localização
- Autocompletar de endereços com geolocalização (API Geoapify)
- Análise automática com IA (Hugging Face)

✅ **Análise Financeira Automática**
- Cálculo de IPTU (1.2% ao ano)
- Custos mensais estimados
- Lucro mensal e anual
- Payback do investimento
- Parecer de recomendação via IA

✅ **Listagem de Imóveis**
- Visualização dos imóveis cadastrados pelo usuário
- Vinculação automática ao usuário logado

✅ **Interface Moderna**
- Design responsivo com Bootstrap 5.3.8
- Gradiente cyan-yellow
- Menu lateral intuitivo

## 🛠️ Tecnologias

- **Backend:** PHP 8.0+ (XAMPP)
- **Banco de Dados:** MySQL (tabelas: `usuarios`, `imoveis`)
- **Frontend:** Bootstrap 5.3.8, JavaScript vanilla
- **APIs Externas:**
  - [Geoapify](https://www.geoapify.com/) - Autocomplete de endereços
  - [Hugging Face](https://huggingface.co/) - IA para análise de imóveis (facebook/blenderbot-3B)
- **Servidor Web:** Apache 2.4.58

## 📋 Pré-requisitos

- XAMPP (Apache + PHP 8.0+ + MySQL)
- Git
- Chaves de API:
  - **Geoapify** (gratuita): [https://www.geoapify.com/](https://www.geoapify.com/)
  - **Hugging Face** (gratuita): [https://huggingface.co/](https://huggingface.co/)

## 🚀 Instalação Local

### 1. Clone o repositório

```bash
cd c:\xampp\htdocs
git clone https://github.com/seu-usuario/administracao-imoveis.git Adm
cd Adm
```

### 2. Configure o banco de dados

Crie um banco de dados MySQL chamado `administracao`:

```sql
CREATE DATABASE administracao;
USE administracao;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(100) NOT NULL,
    nome VARCHAR(150),
    email VARCHAR(150),
    cpf VARCHAR(20)
);

-- Tabela de imóveis
CREATE TABLE imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    valor_imovel DECIMAL(12, 2),
    valor_aluguel DECIMAL(12, 2),
    localizacao VARCHAR(255),
    iptu DECIMAL(10, 2),
    custos_mensais DECIMAL(10, 2),
    lucro_mensal DECIMAL(10, 2),
    lucro_anual DECIMAL(10, 2),
    payback DECIMAL(10, 2),
    analise LONGTEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
```

### 3. Adicione suas chaves de API

Edite `config/conexao.php` se necessário (ou adicione um arquivo `.env`):

```php
// Já está em view/imoveis/cadastrarImoveis.php (função gerarAnaliseIA)
$api_key_hugging_face = 'sua_chave_aqui';
$api_key_geoapify = 'sua_chave_aqui';
```

### 4. Inicie o XAMPP

- Abra o painel de controle do XAMPP
- Inicie Apache e MySQL
- Acesse: `http://localhost/Adm`

### 5. Crie um usuário de teste

Clique em "Não possui conta? Cadastre-se agora" e registre um novo usuário.

## 📁 Estrutura do Projeto

```
Adm/
├── config/
│   └── conexao.php           # Configuração de BD + sessão
├── controller/
│   ├── ImovelController.php
│   └── UsuarioController.php
├── model/
│   ├── imoveis.php
│   └── usuarios.php
├── view/
│   ├── home.php              # Página inicial após login
│   ├── login.php             # Tela de login
│   ├── imoveis/
│   │   ├── cadastrarImoveis.php
│   │   └── verImoveis.php
│   └── usuarios/
│       └── cadastrarUsuario.php
├── sessions/                 # Pasta de sessões do PHP
├── index.php                 # Router principal
├── .gitignore
└── README.md
```

## 🔑 Variáveis de Ambiente (Opcional)

Se quiser usar `.env`, instale via Composer:

```bash
composer require vlucas/phpdotenv
```

Depois crie `.env`:

```
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=administracao

GEOAPIFY_KEY=sua_chave
HUGGINGFACE_KEY=sua_chave
```

## 🐛 Troubleshooting

**Erro de sessão vazia (usuario_id = NULL)?**
- Verifique se `session.save_path` está configurado em `config/conexao.php`
- A pasta `/sessions` deve existir e ser gravável

**IA não retorna análise?**
- Verifique a chave da API do Hugging Face em `view/imoveis/cadastrarImoveis.php`
- Confirme que você tem saldo/quota na API

**Autocomplete de endereço não funciona?**
- Valide a chave da API Geoapify
- Verifique o console do navegador (F12) para erros

## 📝 Licença

Projeto desenvolvido como sistema de gestão imobiliária. Use livremente.

## 👤 Autor

Desenvolvido em Novembro de 2025.

## 📞 Suporte

Para dúvidas ou bugs, abra uma issue no GitHub.

---

**Próximos passos:** Adicione CI/CD, testes unitários e deploy em produção!
