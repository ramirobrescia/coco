
# Pasos para la creación

## Laravel

Opción Brezze + Vue.

```console
$ laravel new coco
```

Quitamos del archivo `composer.json` el módulo `laravel/pail` ya que necesita el módulo de 
php `[pcntl]` que no está disponible en windows. Luego actualizamos.

```console
$ composer update
```

Procesamos el frontend.

```console
$ pnpm i
$ pnpm run build
```

## Base de datos

Agregar al `.env` los datos de conexión y ejecutar el migrate.

```console
$ php artisan migrate
```

## Componentes para Vue

Instalamos Vuetify según lo indica [Existing projects](https://vuetifyjs.com/en/getting-started/installation/#existing-projects)

## Ejecución de la aplicación

### Heard

Agregamos la app y la ejecutamos directamente desde ahi