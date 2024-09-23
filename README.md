# Aplicação Vendas (Sem Front)

## Resume
Aplicação consiste em um sistema de vendas basico podendo ter alteração da da informações da venda, existindo um rebalanceador de valores nos metodos de pagamento.

O sistema já vem com um admin pré-registrado:
> User: master <br/>
> Email: admin@email.com.br <br/>
> Password: adm!n@123 <br/>

### Necessário
 - [PHP 8.0](https://www.php.net/)
 - [Composer](https://getcomposer.org/)

### Importante
Antes de ativar o projeto, você deve primeiro configurar o arquivo **.env**. Este arquivo é extremamente importante para o projeto porque contém as principais configurações do sistema. O arquivo [.env.example](./.env.example) servirá como base para o nosso sistema. As variáveis ​​a serem configuradas neste arquivo são
 
<details>
<summary>Configurações .env</summary>

### Database
`DB_HOST`-> host de banco de dados<br>
`DB_DATABASE`->O banco de dados principal<br>
`DB_PORT`->Porta usada no sistema de banco de dados<br>
`DB_USERNAME`->usuário do banco de dados<br>
`DB_PASSWORD`->senha do banco de dados<br>

</details>
<br>

Após fazer as configurações apropriadas no arquivo **.env**, execute alguns comandos de terminal dentro do repositório:

1. Instale todas as dependências do projeto com o composer:
```bash
composer install
```
2. Gerar chave de criptografia do aplicativo:
```bash
php artisan key:generate
```
3. Crie bancos de dados e segmentos iniciais
```bash
php artisan migrate --seed
```
4. Iniciar um servidor local
```bash
php artisan serve
```

## Finished
Se você quiser usá-lo em um servidor independente, você deve redirecionar para [/public/index.php](public/index.php) para que o aplicativo funcione corretamente.

Se desejar, execute os testes para analisar se as rotas na aplicação estão em ordem:
```bash
php artisan test
```

Se você quiser usar um produto para testes, use o comando:
```bash
php artisan db:seed
```