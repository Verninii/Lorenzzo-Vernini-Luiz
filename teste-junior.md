# Sistema de Pedidos – Teste Prático PHP/Laravel

Este projeto implementa um sistema completo de cadastro de **Clientes, Produtos e Pedidos**, incluindo autenticação, paginação configurável, deleção em massa e API REST.  
Todo o ambiente roda em **Docker**.

---

# 🚀 Como rodar

## 1. Subir o ambiente Docker

```bash
docker-compose up -d --build
```

## 2. Instalar dependências

docker-compose exec app composer install

## 3. Configurar aplicação

cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
Acesse: http://localhost:8000

## 4. Autenticação

**O sistema possui:**
Tela de login
Tela de registro
Controle de acesso para todos os CRUDs

Rotas:
/login
/register

## 5. Funcionalidades

✔ Clientes
Filtro por nome, email e documento
Ordenação por qualquer coluna
Paginação configurável (10/20/50/100)
Deleção individual e deleção em massa

✔ Produtos
Filtro por nome/descrição
Ordenação
Paginação configurável
Deleção individual e em massa

✔ Pedidos
Filtro por status, cliente ou ID
Cadastro com múltiplos itens
Recalculo do total com desconto
Detalhamento completo
Paginação configurável
API REST JSON

**Rotas disponíveis:**

GET /api/clients
POST /api/clients
GET /api/clients/{id}
PUT /api/clients/{id}
DELETE /api/clients/{id}
GET /api/products ...
GET /api/orders ...
Controllers da API ficam em:
app/Http/Controllers/Api/

🧹 Deleção em Massa

Implementada nos 3 módulos.
Checkbox por item
“Selecionar todos”
Botão Excluir Selecionados
Rotas dedicatedas: clients.bulk-destroy, etc.

📄 Paginação configurável
Cada listagem possui seletor:
10 | 20 | 50 | 100 por página

Persistência via querystring + paginate com appends().

## 6. Estrutura do Projeto

app/Http/Controllers
├── ClientController.php
├── ProductController.php
├── OrderController.php
└── Api/
├── ClientController.php
├── ProductController.php
└── OrderController.php
routes/
├── web.php
└── api.php
resources/views/
├── clients/
├── products/
├── orders/
└── auth/

## 7. Bônus Implementados

**Requisito Extra Status**
Autenticação ✅
Itens por página configuráveis ✅
Deleção em massa ✅
API JSON completa ✅
Bootstrap responsivo ✅
