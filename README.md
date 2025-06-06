# Teste Técnico da E-completo

## Instalação e Instruções para Rodar

### 1. Preparar o Ambiente

1. Renomeie o arquivo `env.example` para `.env`.

2. Edite o arquivo `.env`:
   - Descomente e altere a linha:
     ```ini
     CI_ENVIRONMENT = development
     ```
   - Descomente uma das linhas:
     ```ini
     app.baseURL = ''
     ```
     ou
     ```ini
     app_baseURL = ''
     ```

### 2. Subir os Contêineres com Docker

No terminal, execute:

```bash
docker compose up -d
```

Isso irá instalar o banco de dados e criar um perfil padrão.

### 3. Configuração do Banco de Dados

Ainda no arquivo `.env`, altere as seguintes linhas:

**De:**

```ini
database.default.hostname = localhost
database.default.database = ci4
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

**Para:**

```ini
database.default.hostname = localhost
database.default.database = ci4_app
database.default.username = ci4_user
database.default.password = ci4_pass
database.default.DBDriver = Postgre
database.default.port = 5432
database.default.charset = UTF8
```

> Lembre-se de descomentar todas as linhas acima.

### 4. Configuração no pgAdmin

- Acesse o pgAdmin.
- Para login, use:
  - **Email:** `admin@admin.com`
  - **Senha:** `admin123`
- Crie um novo *Server* com as seguintes configurações:
  - **Hostname:** `db`
  - **Database:** `ci4_app`
  - **Username:** `ci4_user`
  - **Password:** `ci4_pass`
  - **Port:** `5432`

### 5. Rodar Migrações e Seeders

Execute os seguintes comandos no terminal para criar as tabelas e popular o banco de dados:

```bash
php spark migrate
php spark db:seed Database
```

### 6. Iniciar o Servidor

Rode:

```bash
php spark serve
```

A aplicação provavelmente estará disponível em: [http://localhost:8082](http://localhost:8082)

---

## Testando a API

Você pode testar a API utilizando Insomnia, Postman ou qualquer outro cliente HTTP.

### Requisição

- **Método:** `POST`
- **URL:** `http://localhost:8082/exams/processTransaction`
- **Headers:**
  - `accessToken: TOKEN_FORNECIDO`
- **Body (JSON):**

```json
{
  "external_order_id": 932832,
  "amount": 21.40,
  "card_number": "4111111111111111",
  "card_cvv": "123",
  "card_expiration_date": "0922",
  "card_holder_name": "Morpheus Fishburne",
  "customer": {
    "external_id": "3311",
    "name": "Morpheus Fishburne",
    "type": "individual",
    "email": "mopheus@nabucodonozor.com",
    "documents": [
      {
        "type": "cpf",
        "number": "30621143049"
      }
    ],
    "birthday": "1965-01-01"
  }
}
```

---

## Observações

- Certifique-se de que todos os contêineres estejam em execução.
- Verifique se as migrações foram aplicadas corretamente antes de testar a API.