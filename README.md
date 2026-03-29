# 🛠️ ServiceHub — Teste Técnico KPMG

Sistema de gestão de ordens de serviço desenvolvido como parte do desafio técnico para a KPMG.

> Desenvolvido por Alan Silva como teste técnico para a KPMG.

---

## 📋 Sobre o Projeto

O **ServiceHub** é uma aplicação web para gestão de tickets de serviço, onde empresas e projetos são organizados hierarquicamente e usuários autenticados podem abrir, acompanhar e enriquecer ordens de serviço.

### Domínio
```
Company (1:N) ──► Project (1:N) ──► Ticket (1:1) ──► TicketDetail
User (1:1) ──► UserProfile
User (1:N) ──► Tickets
```

### Fluxo principal

1. Usuário autenticado cria um **Ticket** vinculado a um Project
2. Faz upload opcional de um anexo `.json` ou `.txt`
3. Um **Job assíncrono** processa o anexo em fila e enriquece o **TicketDetail**
4. Os dados enriquecidos ficam disponíveis na tela de detalhe do ticket

---

## 🧱 Stack

| Camada | Tecnologia |
|---|---|
| Backend | Laravel 11 + PHP 8.3 |
| Frontend | Vue 3 + Inertia.js |
| Banco de dados | MySQL 8 |
| Autenticação | Laravel Breeze |
| Fila | Laravel Queue (driver: database) |
| Testes | Pest |
| Servidor | Nginx |
| Containers | Docker + Docker Compose |

---

## 📁 Estrutura do Projeto
```
service-hub/
├── app/
│   ├── Http/Controllers/    # TicketController e outros
│   ├── Jobs/                # ProcessTicketAttachment
│   └── Models/              # Company, Project, Ticket, TicketDetail, UserProfile
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── resources/js/
│   └── Pages/Tickets/       # Index.vue, Create.vue, Show.vue
├── docker/
│   └── nginx/default.conf
├── tests/
│   ├── Feature/             # TicketTest.php
│   └── Unit/                # TicketJobTest.php
├── Dockerfile
└── docker-compose.yml
```

---

## 🐳 Como rodar com Docker

### Pré-requisitos

- [Docker](https://www.docker.com/) instalado
- [Docker Compose](https://docs.docker.com/compose/) instalado

### Passo a passo

**1. Clone o repositório**
```bash
git clone https://github.com/alandsilva01/service-hub.git
cd service-hub
```

**2. Configure o .env**
```bash
cp .env.example .env
```

Edite o `.env` e ajuste:
```env
DB_HOST=db
DB_PORT=3306
DB_DATABASE=service_hub
DB_USERNAME=sail
DB_PASSWORD=password
```

**3. Suba os containers**
```bash
docker compose up -d --build
```

**4. Configure a aplicação**
```bash
docker exec -it service_hub_app bash
php artisan key:generate
php artisan migrate --seed
exit
```

**5. Acesse no navegador**
```
http://localhost:8080
```

**Login demo:** `alan@servicehub.com` / `password`

---

## ✅ Como rodar os testes
```bash
docker exec -it service_hub_app bash
php artisan test
exit
```

São **30 testes** cobrindo:
- Relacionamentos entre models (Company, Project, Ticket, User)
- Criação de tickets autenticado e não autenticado
- Job de processamento de anexo JSON com `Storage::fake`
- Testes padrão do Laravel Breeze (auth, perfil, senha)

---

## ⚙️ Como rodar o Queue Worker

O worker processa os anexos enviados nos tickets em segundo plano:
```bash
docker exec -it service_hub_app bash
php artisan queue:work
exit
```

---

## 👤 Autor

**Alan Silva** — [github.com/alandsilva01](https://github.com/alandsilva01)

Desenvolvido para o processo seletivo KPMG — 2026.
