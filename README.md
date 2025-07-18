## Requerimientos
Tener instalado:
Laravel 12
Php +8.3
Node v. 22

# Setup con base de datos MySQL
configurar las credenciales de tu .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

# Setup con base de datos Sqlite
Puedes ejecutar en terminal despues de tener instalado php:
``` bash 
  php --ini
```
ingresar a tu php.ini, busca la extension "pdo_sqlite" y elimina el ;, guarda los cambios:
``` bash 
  extension=pdo_sqlite
```

1. Ejecutar estos comando para publicar archivos javscript de livewire: 
``` sh
  php artisan livewire:publish --config 
  php artisan livewire:publish --assets 
```

``` sh
  php artisan storage:link
```
2. Ejecutar el comando para publicar el storage link public:
``` sh
  php artisan storage:link
```

1. Ejecutar e ingresa la opcion 0:
``` sh
  php artisan migrate --seed
```

Seguidamente ingresar con cuenta de super_admin:
email: admin@tiburon.com
pass: admintiburon

Tambien se crean los usuarios de prueba:
-Con rol empleado:
email: empleado1@tiburon.com
pass: empleado1

-Con rol cliente:
email: rosmel@tiburon.com
pass: cliente1

## PASOS PARA CONSEGUIR TU TOKEN DE APIS PERU

1. Ir a esta pagina https://apisperu.com/servicios/dniruc y registrate
![alt text](image.png)

2. Te llegara a tu correo un token como este:
 eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9....
 copia y reemplaza el valor la variable por VITE_TOKEN_DNI_API= de tu archivo **.env**


## Instalar la extension ERD EDITOR 
Para que puedas ver tus tablas, campos y relaciones de tu base de datos, en el archivo db_tiburon.erd.json:

![alt text](image-1.png)
![alt text](image-2.png)