# 🚀 SuitPay Cursos — Rodando Local com Docker

Projeto Laravel configurado para rodar 100% via **Docker**.

---

## ✅ Requisitos

- Docker  
- Docker Compose  
- Git  

---

## 📥 Clonar o projeto

```bash
git clone https://github.com/higorch/suitpaycursos.git
cd suitpaycursos
```

---

## 🐳 Subir os containers

```bash
docker compose up -d
```

Aguarde cerca de **15 segundos** para o MySQL iniciar completamente, mesmo depois de todos conteiners docker forem criados.

---

## ⚙️ Configuração inicial

Entre no container da aplicação:

```bash
docker exec -it app bash
```

Dentro do container rode:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

---

## 🛠️ Configurar o banco

Edite o arquivo `src/.env` e deixe assim:

```env
DB_CONNECTION=mysql
DB_HOST=mysqlsutipaycursos
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

---

## 🗄️ Criar banco e dados de teste

Ainda dentro do container:

```bash
php artisan migrate --seed
exit
```

Isso cria automaticamente:
- Usuários (Administradores, Criadores e Alunos)
- Cursos

---

## 🌐 Acessar o sistema

Abra no navegador:

**http://localhost:8029**

Você será redirecionado para a tela de login.

---

## 🔐 Logins de teste

### 👑 Administrador

| Email | Senha |
|------|------|
| suitpay@mail.com | password |

Acesso completo ao painel administrativo.

---

### 🎓 Criadores (Professores)

| Email | Senha |
|------|------|
| maria@mail.com | password |
| joao@mail.com | password |

Responsáveis pelos cursos na plataforma.

---

### 👨‍🎓 Alunos

| Email | Senha | Criador Vinculado |
|------|------|-------------------|
| gustavo@mail.com | password | Maria |
| danilo@mail.com | password | João |

Acessam o catálogo e os cursos matriculados.

---

## 🧠 Portas dos serviços

| Serviço | Porta |
|--------|------|
| Aplicação (Nginx) | **8029** |
| MySQL (acesso externo) | **3329** |

> Internamente o Laravel usa a porta **3306** para o banco.
