# 🚀 SuitPay Cursos — Ambiente de Testes (Laravel + Docker)

Este projeto utiliza **Docker** para rodar a aplicação Laravel localmente, sem necessidade de instalar PHP, MySQL ou Nginx diretamente na sua máquina.

---

## 📦 Requisitos

- Docker  
- Docker Compose  
- Git  

---

## 📥 Clonar o repositório e subir o ambiente

```bash
git clone https://github.com/higorch/suitpaycursos.git
cd suitpaycursos
docker compose up -d
```

⏳ Aguarde cerca de **15 segundos** para o MySQL inicializar.

---

## ⚙️ Configuração inicial do Laravel

Entre no container da aplicação:

```bash
docker exec -it app bash
```

Agora, **dentro do container**, execute:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

---

## 🛠️ Configurar o `.env`

Edite o arquivo `src/.env` e deixe assim:

```env
DB_CONNECTION=mysql
DB_HOST=mysql8
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

---

## 🗄️ Rodar as migrations

Ainda dentro do container:

```bash
php artisan migrate
```

Depois pode sair com:

```bash
exit
```

---

## 🌐 Acessar a aplicação

Abra no navegador:

👉 **http://localhost:8029**

---

## 🧠 Informações do ambiente

| Serviço | Função | Porta externa |
|--------|--------|---------------|
| Nginx | Servidor web | 8029 |
| Laravel (app) | Aplicação PHP | — |
| MySQL 8 | Banco de dados | 3329 |

> A porta **3329** é apenas para acesso externo (ex: MySQL Workbench).  
> Entre os containers, o Laravel usa a porta interna **3306**.
