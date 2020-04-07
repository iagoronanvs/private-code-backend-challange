## Instalação através do [Docker](https://www.docker.com/get-started)

-   Obs.: Para essa instalação estamos considerando que o docker já esteja instalado e configurado em sua máquina.

1. Clonar o projeto<br/>
   git clone https://github.com/iagoronanvs/private-code-backend-challange.git

2. Acesse o diretório raiz<br/>
   cd private-code-backend-challange

3. Inicie o container do Docker<br/>
   docker-compose up -d

O comando acima é responsável por iniciar 3 serviços<br/>

-   Banco de dados Mysql
-   SGBD Adminer
-   Nginx web server

4. Acesse o container do projeto<br/>
   docker exec -it private-code bash

5. Instalar as dependências do Laravel<br/>
   composer install

6. Execute as migrations<br/>
   php aritsan migrate

7. Execute os Seeds<br/>
   php artisan db:seed

8. Acesse a aplicação (http://localhost:8181/)[http://localhost:8181/]

9. Credenciais<br/>
   email: admin@admin.com<br/>
   senha: 123456<br/>
