# Teste Técnico da E-completo

## Instalação e instruções para rodar

### Ambiente

- Mude o nome do "env.example" para ".env"
- Descomente as linhas:
  "# CI_ENVIRONMENT = production" e mude de production para development
  e as linhas
  "# app.baseURL = ''" ou "# app_baseURL = ''"

- Após isso rode no terminal:
  docker compose up -d

isso irá rodar instalar todo o banco de dados e criar um perfil

- então altere os dados de:

# database.default.hostname = localhost

# database.default.database = ci4

# database.default.username = root

# database.default.password = root

# database.default.DBDriver = MySQLi

# database.default.DBPrefix =

# database.default.port = 3306

para:
database.default.hostname = localhost
database.default.database = ci4_app
database.default.username = ci4_user
database.default.password = ci4_pass
database.default.DBDriver = Postgre
database.default.port = 5432
database.default.charset = UTF8

(descomentados)

- então vá no pgAdmin e para login use:
  email: admin@admin.com
  senha: admin123

- após isso crie um server, com as mesmas informações que estão no env com a diferença é que o localhost no postgre se chama db, então:
  hostname = db
  database = ci4_app
  username = ci4_user
  password = ci4_pass
  port = 5432

-Após a conexão, deverá rodar as migrações, eu fiz uma migração que migra e utilizar das seeders para popular o banco, então no terminal:
php spark migrate para as migrações
e
php spark db:seed
Database

e assim o banco está pronto.

Após isso no terminal rode php spark serve
provavelmente irá rodar na localhost:8082

então para testar:

- no Insomnia ou Postman, ou qualquer outra forma de fazer a requisição POST

na url utilize: POST localhost:8082/exams/processTransaction

como parâmetro terá: "accessToken: TOKEN_FORNECIDO"

assim na requisição utilize o JSON e para isso ficará como o documento fornecido:
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
