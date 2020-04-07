## Instalação através do [Docker](https://www.docker.com/get-started)

-   Obs.: Para essa instalação estamos considerando que o docker já esteja instalado e configurado em sua máquina.

1. Clonar o projeto
   git clone https://github.com/iagoronanvs/private-code-backend-challange.git

2. Acesse o diretório raiz
   cd private-code-backend-challange

3. Inicie o container do Docker
   docker-compose up -d

3.1 O comando acima é responsável por iniciar 3 serviços

-   Banco de dados Mysql
-   SGBD Adminer
-   Nginx web server

4. Acesse o container do projeto
   docker exec -it private-code bash

5. Instalar as dependências do Laravel
   composer install

6. Execute as migrations
   php aritsan migrate

7. Execute os Seeds
   php artisan db:seed

8. Acesse a aplicação (http://localhost:8181/)[http://localhost:8181/]

8.1 Credenciais
email: admin@admin.com
senha: 123456
