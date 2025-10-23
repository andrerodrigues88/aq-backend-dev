# 📚 Instruções de Instalação e Configuração - Teste Backend AgronomiQ

## 📋 Pré-requisitos

- **PHP** >= 8.0.2
- **PostgreSQL** >= 12
- **Composer**
- **Extensão PostGIS** para PostgreSQL

## 🚀 Instalação Passo a Passo

### 1. Clone o Repositório

```bash
git clone https://github.com/SEU_USUARIO/aq-backend-dev.git
cd aq-backend-dev
```

### 2. Instale as Dependências

```bash
composer install
```

### 3. Configure o Ambiente

Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

### 4. Configure o Banco de Dados PostgreSQL

#### 4.1. Instalar PostGIS (se ainda não estiver instalado)

**No Ubuntu/Debian:**
```bash
sudo apt-get update
sudo apt-get install postgresql postgis postgresql-contrib
```

**No Windows:**
- Baixe e instale o PostgreSQL com PostGIS através do instalador oficial
- Durante a instalação, marque a opção para instalar o PostGIS

**No macOS:**
```bash
brew install postgresql postgis
```

#### 4.2. Criar o Banco de Dados

Acesse o PostgreSQL:

```bash
# Linux/macOS
sudo -u postgres psql

# Windows (PowerShell como Administrador)
psql -U postgres
```

Crie o banco de dados:

```sql
CREATE DATABASE agronomiq_teste;
\q
```

#### 4.3. Configurar o arquivo .env

Edite o arquivo `.env` e configure as credenciais do banco de dados:

```env
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=agronomiq_teste
DB_USERNAME=postgres
DB_PASSWORD=sua_senha_aqui
```

### 5. Gere a Chave da Aplicação

```bash
php artisan key:generate
```

### 6. Execute as Migrations

Este comando irá:
- Habilitar a extensão PostGIS
- Criar as tabelas `municipios_geometria`, `estados_geometria` e `pontos_usuario`

```bash
php artisan migrate
```

### 7. Popule o Banco de Dados com os GeoJSONs

Este comando irá:
- Baixar os GeoJSONs dos municípios de SP e MG
- Inserir os municípios na tabela `municipios_geometria`
- Criar os estados através do dissolve/união das geometrias dos municípios

```bash
php artisan db:seed
```

**⚠️ Atenção:** Este processo pode demorar alguns minutos, pois faz download e processa centenas de municípios.

### 8. Inicie o Servidor

```bash
php artisan serve
```

A aplicação estará disponível em: `http://localhost:8000`

---

## 🧪 Testando os Endpoints

### 1. Localizar Município por Coordenadas

**Endpoint:** `GET /api/localizar-municipio`

**Parâmetros:**
- `latitude` (obrigatório): Latitude em decimal
- `longitude` (obrigatório): Longitude em decimal

**Exemplo (São Paulo):**
```bash
curl "http://localhost:8000/api/localizar-municipio?latitude=-23.5505&longitude=-46.6333"
```

**Resposta de Sucesso:**
```json
{
  "success": true,
  "message": "Município localizado com sucesso",
  "data": {
    "id": 1,
    "nome_municipio": "São Paulo",
    "coordenadas": {
      "latitude": -23.5505,
      "longitude": -46.6333
    },
    "geometria": { ... }
  }
}
```

**Resposta de Erro (coordenadas fora de SP/MG):**
```json
{
  "success": false,
  "message": "Município não encontrado para as coordenadas fornecidas",
  "data": {
    "latitude": -10.0,
    "longitude": -50.0
  }
}
```

---

### 2. CRUD de Pontos de Usuário

#### 2.1. Listar Todos os Pontos

**Endpoint:** `GET /api/pontos`

```bash
curl http://localhost:8000/api/pontos
```

**Resposta:**
```json
{
  "success": true,
  "message": "Pontos recuperados com sucesso",
  "data": [
    {
      "id": 1,
      "latitude": "-23.5505",
      "longitude": "-46.6333",
      "municipio_id": 1,
      "geom": { "type": "Point", "coordinates": [-46.6333, -23.5505] },
      "created_at": "2024-01-01T10:00:00.000000Z",
      "updated_at": "2024-01-01T10:00:00.000000Z"
    }
  ]
}
```

#### 2.2. Criar um Novo Ponto

**Endpoint:** `POST /api/pontos`

**Body (JSON):**
```json
{
  "latitude": -23.5505,
  "longitude": -46.6333
}
```

**Exemplo:**
```bash
curl -X POST http://localhost:8000/api/pontos \
  -H "Content-Type: application/json" \
  -d '{"latitude": -23.5505, "longitude": -46.6333}'
```

**Resposta:**
```json
{
  "success": true,
  "message": "Ponto criado com sucesso",
  "data": {
    "id": 1,
    "latitude": "-23.5505",
    "longitude": "-46.6333",
    "municipio_id": 1,
    "geom": { "type": "Point", "coordinates": [-46.6333, -23.5505] },
    "created_at": "2024-01-01T10:00:00.000000Z",
    "updated_at": "2024-01-01T10:00:00.000000Z"
  }
}
```

#### 2.3. Exibir um Ponto Específico

**Endpoint:** `GET /api/pontos/{id}`

```bash
curl http://localhost:8000/api/pontos/1
```

#### 2.4. Atualizar um Ponto

**Endpoint:** `PUT /api/pontos/{id}`

**Body (JSON):**
```json
{
  "latitude": -23.5600,
  "longitude": -46.6400
}
```

**Exemplo:**
```bash
curl -X PUT http://localhost:8000/api/pontos/1 \
  -H "Content-Type: application/json" \
  -d '{"latitude": -23.5600, "longitude": -46.6400}'
```

#### 2.5. Deletar um Ponto

**Endpoint:** `DELETE /api/pontos/{id}`

```bash
curl -X DELETE http://localhost:8000/api/pontos/1
```

**Resposta:**
```json
{
  "success": true,
  "message": "Ponto removido com sucesso"
}
```

---

## 📊 Estrutura do Banco de Dados

### Tabela: `municipios_geometria`

| Campo           | Tipo      | Descrição                           |
|-----------------|-----------|-------------------------------------|
| id              | bigint    | Chave primária                      |
| nome_municipio  | varchar   | Nome do município                   |
| geom            | geometry  | Geometria do município (SRID 4326)  |
| created_at      | timestamp | Data de criação                     |
| updated_at      | timestamp | Data de atualização                 |

### Tabela: `estados_geometria`

| Campo        | Tipo      | Descrição                          |
|--------------|-----------|-------------------------------------|
| id           | bigint    | Chave primária                      |
| nome_estado  | varchar   | Nome do estado (SP ou MG)           |
| geom         | geometry  | Geometria do estado (SRID 4326)     |
| created_at   | timestamp | Data de criação                     |
| updated_at   | timestamp | Data de atualização                 |

### Tabela: `pontos_usuario`

| Campo        | Tipo      | Descrição                                |
|--------------|-----------|------------------------------------------|
| id           | bigint    | Chave primária                           |
| latitude     | decimal   | Latitude do ponto                        |
| longitude    | decimal   | Longitude do ponto                       |
| municipio_id | bigint    | FK para municipios_geometria (nullable)  |
| geom         | geometry  | Geometria do ponto (SRID 4326)           |
| created_at   | timestamp | Data de criação                          |
| updated_at   | timestamp | Data de atualização                      |

---

## 🛠️ Tecnologias Utilizadas

- **Laravel 9.x** - Framework PHP
- **PostgreSQL** - Banco de dados relacional
- **PostGIS** - Extensão geoespacial para PostgreSQL
- **grimzy/laravel-mysql-spatial** - Pacote Laravel para tipos espaciais

---

## 📝 Funcionalidades Implementadas

✅ Instalação e configuração do PostGIS via migrations  
✅ Tabela `municipios_geometria` com dados dos GeoJSONs de SP e MG  
✅ Tabela `estados_geometria` criada através do dissolve dos municípios  
✅ Tabela `pontos_usuario` com campos latitude, longitude, municipio_id e geom  
✅ Endpoint `/api/localizar-municipio` para consulta por coordenadas  
✅ CRUD completo `/api/pontos` (GET, POST, PUT, DELETE)  
✅ Todas as colunas de geometria com tipo `Geometry` e SRID 4326  
✅ Índices espaciais GIST para otimização de consultas  
✅ Validação de dados de entrada  
✅ Respostas JSON padronizadas  
✅ Código documentado e organizado  

---

## 🐛 Troubleshooting

### Erro: "could not find driver"

**Solução:** Habilite a extensão PDO PostgreSQL no PHP:

```bash
# Ubuntu/Debian
sudo apt-get install php-pgsql

# Reinicie o servidor
php artisan serve
```

### Erro: "extension postgis does not exist"

**Solução:** Instale o PostGIS:

```bash
# Ubuntu/Debian
sudo apt-get install postgis postgresql-contrib

# Depois conecte ao banco e execute:
psql -U postgres -d agronomiq_teste -c "CREATE EXTENSION postgis;"
```

### Erro ao executar seeders (timeout)

**Solução:** Aumente o timeout no seeder ou execute em partes menores. O processo pode demorar devido ao download e processamento de centenas de municípios.

---

## 📧 Contato

Para dúvidas sobre este teste, entre em contato com a equipe AgronomiQ.

---

**Desenvolvido para AgronomiQ - Teste Técnico Backend**
