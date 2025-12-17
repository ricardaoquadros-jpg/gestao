# 📋 Sistema de Gestão de Chamados

Sistema web simples de gestão de chamados/tarefas, inspirado no funcionamento básico do Asana. Desenvolvido com PHP, MySQL, HTML, CSS e JavaScript.

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat-square&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)

## 📖 Sobre o Projeto

Este é um sistema de gestão de chamados desenvolvido para fins de portfólio. O objetivo é demonstrar habilidades em desenvolvimento web full-stack utilizando tecnologias tradicionais sem frameworks complexos.

### Funcionalidades

- ✅ **Autenticação de Usuários** - Cadastro e login com senhas criptografadas
- ✅ **CRUD de Chamados** - Criar, visualizar, editar e excluir chamados
- ✅ **Gerenciamento de Status** - Aberto, Em Andamento, Concluído
- ✅ **Atribuição de Responsáveis** - Designar usuários para cada chamado
- ✅ **Dashboard** - Visão geral com estatísticas e filtros
- ✅ **Busca e Filtros** - Pesquisar chamados por título/descrição e status
- ✅ **Interface Responsiva** - Layout adaptável para diferentes dispositivos

## 🛠️ Tecnologias Utilizadas

| Tecnologia | Uso |
|------------|-----|
| **PHP 7.4+** | Backend e lógica de negócios |
| **MySQL 5.7+** | Banco de dados relacional |
| **HTML5** | Estrutura das páginas |
| **CSS3** | Estilização e responsividade |
| **JavaScript** | Interações e validações |
| **PDO** | Conexão segura com banco de dados |

## 📁 Estrutura do Projeto

```
/gestao/
├── index.php           # Página inicial (redirecionamento)
├── login.php           # Página de login
├── cadastro.php        # Página de cadastro
├── logout.php          # Encerramento de sessão
├── dashboard.php       # Painel principal
├── create_chamado.php  # Criar novo chamado
├── view_chamado.php    # Visualizar detalhes
├── edit_chamado.php    # Editar chamado
├── delete_chamado.php  # Excluir chamado
├── database.sql        # Script do banco de dados
├── README.md           # Documentação
├── db/
│   └── conexao.php     # Configuração do banco
├── css/
│   └── style.css       # Estilos
└── js/
    └── main.js         # Scripts JavaScript
```

## 🚀 Como Rodar Localmente

### Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx) ou XAMPP/Laragon/WAMP

### Passo a Passo

1. **Clone ou baixe o projeto**
   ```bash
   git clone https://github.com/seu-usuario/gestao-chamados.git
   ```

2. **Configure o ambiente**
   - Coloque a pasta `gestao` dentro da pasta `htdocs` (XAMPP) ou `www` (Laragon/WAMP)

3. **Crie o banco de dados**
   - Acesse o phpMyAdmin (http://localhost/phpmyadmin)
   - Importe o arquivo `database.sql`
   - Ou execute o seguinte comando MySQL:
     ```bash
     mysql -u root -p < database.sql
     ```

4. **Configure a conexão**
   - Abra o arquivo `db/conexao.php`
   - Ajuste as credenciais se necessário:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'gestao_chamados');
     define('DB_USER', 'root');
     define('DB_PASS', ''); // Sua senha do MySQL
     ```

5. **Acesse o sistema**
   - Abra o navegador em: http://localhost/gestao
   - Crie uma conta e comece a usar!

## 📸 Screenshots

### Tela de Login
Interface limpa e moderna para autenticação de usuários.

### Dashboard
Visão geral com estatísticas, filtros e lista de chamados.

### Formulário de Chamado
Formulário intuitivo para criar e editar chamados.

## 🔐 Segurança

- Senhas criptografadas com `password_hash()` (bcrypt)
- Queries preparadas com PDO para prevenir SQL Injection
- Validação de dados no frontend e backend
- Proteção de páginas por sessão
- Escape de HTML com `htmlspecialchars()` para prevenir XSS

## 📋 Status dos Chamados

| Status | Cor | Descrição |
|--------|-----|-----------|
| 🟡 **Aberto** | Amarelo | Chamado recém-criado, aguardando ação |
| 🔵 **Em Andamento** | Azul | Chamado em processo de resolução |
| 🟢 **Concluído** | Verde | Chamado finalizado com sucesso |

## 🤝 Contribuições

Contribuições são bem-vindas! Sinta-se à vontade para:

1. Fazer um fork do projeto
2. Criar uma branch para sua feature (`git checkout -b feature/NovaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona NovaFeature'`)
4. Push para a branch (`git push origin feature/NovaFeature`)
5. Abrir um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👤 Autor

**Ricardo Quadros**

- GitHub: [@seu-usuario](https://github.com/seu-usuario)
- LinkedIn: [Ricardo Quadros](https://linkedin.com/in/seu-perfil)

---

⭐ Se este projeto foi útil para você, considere dar uma estrela!
