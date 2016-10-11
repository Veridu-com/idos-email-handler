# Idos E-mail Service Handler
This service handler is responsible fo sending e-mails.

## API
Set up a server:

`php -S localhost:8000 -t public/`

Access `/` route to list all registered e-mail endpoints.


## Command Line Interface

List all commands:

`php cli.php` 


Start worker daemon:

`php cli.php daemon:email`