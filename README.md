# 📋 Sistema de Gestão de Chamados

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" alt="HTML5">
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
</p>

## 📖 Descrição do Projeto

Sistema web de gestão de chamados e tarefas inspirado no Asana. Permite criar, organizar e acompanhar chamados/tarefas de forma simples e eficiente.

**Problema que resolve:** Organização e acompanhamento de tarefas em equipes, permitindo atribuir responsáveis, definir status e manter histórico de chamados.

---

## ✨ Funcionalidades

| Funcionalidade | Descrição |
|----------------|-----------|
| 🔐 **Autenticação** | Cadastro e login de usuários com senhas criptografadas (bcrypt) |
| ➕ **Criar Chamados** | Formulário para criar novos chamados com título, descrição e responsável |
| ✏️ **Editar Chamados** | Alterar informações e status de chamados existentes |
| 🗑️ **Excluir Chamados** | Remover chamados com confirmação |
| 📋 **Listar Chamados** | Dashboard com todos os chamados e estatísticas |
| 🔍 **Busca e Filtros** | Pesquisar por título/descrição e filtrar por status |
| 📊 **Dashboard** | Visão geral com contadores por status |
| 👤 **Atribuição** | Designar responsáveis para cada chamado |

### Status dos Chamados

| Status | Descrição |
|--------|-----------|
| 🟡 Aberto | Chamado recém-criado, aguardando ação |
| 🔵 Em Andamento | Chamado em processo de resolução |
| 🟢 Concluído | Chamado finalizado |

---

## 🛠️ Tecnologias Utilizadas

- **PHP 7.4+** — Backend e lógica de negócios
- **MySQL 5.7+** — Banco de dados relacional
- **HTML5** — Estrutura das páginas
- **CSS3** — Estilização responsiva
- **JavaScript** — Interações e validações
- **PDO** — Conexão segura com banco de dados

---

## 🚀 Como Rodar Localmente

### Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (XAMPP, Laragon, WAMP ou similar)

### Instalação

1. **Clone o repositório**
   ```bash
   git clone https://github.com/ricardaoquadros-jpg/gestao.git
   ```

2. **Mova para a pasta do servidor**
   - **Laragon:** `C:\laragon\www\`
   - **XAMPP:** `C:\xampp\htdocs\`

3. **Configure o banco de dados**
   
   Acesse `http://localhost/gestao/setup.php` para criar o banco automaticamente.
   
   **Ou manualmente:** Importe o arquivo `database.sql` no phpMyAdmin.

4. **Configure a conexão** (se necessário)
   
   Edite `db/conexao.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'gestao_chamados');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // sua senha
   ```

5. **Acesse o sistema**
   ```
   http://localhost/gestao
   ```

---

## 📁 Estrutura do Projeto

```
gestao/
├── index.php           # Página inicial (redirect)
├── login.php           # Autenticação
├── cadastro.php        # Registro de usuários
├── logout.php          # Encerrar sessão
├── dashboard.php       # Painel principal
├── create_chamado.php  # Criar chamado
├── view_chamado.php    # Visualizar detalhes
├── edit_chamado.php    # Editar chamado
├── delete_chamado.php  # Excluir chamado
├── database.sql        # Script do banco
├── db/
│   └── conexao.php     # Configuração PDO
├── css/
│   └── style.css       # Estilos
└── js/
    └── main.js         # JavaScript
```

---

## 🔐 Segurança

- ✅ Senhas criptografadas com `password_hash()` (bcrypt)
- ✅ Queries preparadas (PDO) contra SQL Injection
- ✅ Escape de HTML com `htmlspecialchars()` contra XSS
- ✅ Proteção de rotas por sessão

---

## 🚧 Possíveis Evoluções

- [ ] Comentários em chamados
- [ ] Anexar arquivos
- [ ] Notificações por email
- [ ] Prioridade de chamados (Alta, Média, Baixa)
- [ ] Relatórios e gráficos
- [ ] API REST para integração
- [ ] Categorias/Tags para chamados
- [ ] Histórico de alterações

---

## 👤 Autor

**Ricardo Quadros**

[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/ricardaoquadros-jpg)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://linkedin.com/in/seu-perfil)

---

## 📄 Licença

Este projeto está sob a licença MIT.

---

⭐ Se este projeto foi útil, considere dar uma estrela!
