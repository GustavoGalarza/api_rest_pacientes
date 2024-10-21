# Proyecto API RESTful en Laravel

Este proyecto es una API RESTful desarrollada en PHP utilizando el framework Laravel. La API permite realizar operaciones CRUD sobre un conjunto de recursos, incluyendo médicos y usuarios, y proporciona autenticación y validación de datos.
## Participantes:
univ. Galarza Arias Gustavo Rafael
univ. Mamani Mamani Juan Daniel

## Contenido

- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Ejecución](#ejecución)
- [Base de Datos](#base-de-datos)
- [Documentación de la API](#documentación-de-la-api)

## Requisitos

- PHP >= 7.3
- Composer
- MySQL
- Laravel >= 8.x
- XAMPP (opcional, si deseas utilizarlo como servidor local)
## Proyecto en RAR
Url: https://drive.google.com/drive/folders/1BRkcudYDTJ1tE4rqk6oBHwXAdgsW5YmV?usp=drive_link

## Instalación

1. **Clona el repositorio**(si desea descargar desde  github, si tienes el rar,zip ignora este paso):
   ```bash
   git clone https://github.com/GustavoGalarza/api_rest_pacientes.git
   cd nombre-del-repositorio

## Instala las dependencias:

composer install

## Configura el archivo de entorno:
Abre el archivo .env y configura los detalles de la base de datos:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
## Genera la clave de la aplicación:
php artisan key:generate

## Ejecuta las migraciones y los factories para poblar la base de datos:

php artisan migrate --seed
## Ejecución
Para iniciar el servidor local de Laravel, utiliza el siguiente comando:

php artisan serve
La API estará disponible en http://localhost:8000.

## Base de Datos
La estructura de la base de datos se encuentra en los archivos de migración dentro de la carpeta database/migrations. Los datos de prueba se generan utilizando los factories y Faker.

## Documentación de la API
La documentación de la API se ha generado utilizando Swagger y se encuentra en formato PDF. Puedes encontrar el archivo en la carpeta del proyecto.
Abre el archivo APIREST-Documentation-Sistema Pacientes.pdf que se encuentra en la raíz del proyecto.
Revisa los endpoints y ejemplos de uso de la API.
