Please run this commands to publish livewire assets:
php artisan livewire:publish --config 
php artisan livewire:publish --assets 

Please run this commands to config shield pluging roles:
php artisan shield:setup
php artisan shield:install admin
php artisan shield:generate

**SUPER IMPORANTE ESTE COMANDO PARA GENERAR PERMISOS PARA TODAS LAS ENTIDADES O MODELOS**
php artisan shield:generate --all

php artisan shield:super-admin
php artisan shield:seeder
php artisan shield:publish

php artisan link:storage para publicar logo de la empresa


## INGRESAR CON EL USUARIO SUPER ADMIN POR DEFECTO
Email: admin@tiburon.com
Password: admintiburon

## PASOS PARA CONSEGUIR TU TOKEN DE APIS PERU

1. Ir a esta pagina https://apisperu.com/servicios/dniruc y registrate
![alt text](image.png)

2. Te llegara a tu correo un token como este:
 eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9....
 copia y reemplaza el valor la variable por VITE_TOKEN_DNI_API= de tu archivo **.env**


## Instalar la extension ERD EDITOR 
Para que puedas ver tus tablas, campos y relaciones de tu base de datos:

![alt text](image-1.png)