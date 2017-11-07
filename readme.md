#Localidades y Provincias de Argentina para Laravel >= 5.4

Este paquete crea las migraciones y tablas de paises, provincias y localidades.

También genera un comando para popular estás bases.

#Insctrucciones

```
composer require cardumen/argentina-provincias-localidades:1.0.5
```


Agregar el provider en config/app 

```
Cardumen\ArgentinaProvinciasLocalidades\ArgentinaProvinciasLocalidades::class,
```

Correr las migraciones

```
php artisan migrate
```

Cargar los datos

```
php artisan provincias-localidades:cargar
```



