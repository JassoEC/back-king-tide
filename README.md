# Prueba técnica Emanuel Campos Jasso

## Instrucciones

### 1. En la capeta de su eleccion descargar los repositorios listados a continuación ejecutando:

    - git clone https://github.com/JassoEC/settings-king-tide.git
    - git clone https://github.com/JassoEC/front-king-tide.git
    - git clone https://github.com/JassoEC/back-king-tide.git

### 2. Crear la carpeta database

    - mkdir settings-king-tide/database

### 3. Ejecutar los siguientes comandos

    - cd settings-king-tide
    - docker-compose up -d

## Generación de base de datos

### Ejecutar

    - cd settings-king-tide
    - docker-compose exec backend php /var/www/html/back-king-tide/artisan migrate --seed

# Prueba de la aplicación

## Front

    - https://ecjasso.netlify.app

## Back

    - https://king-tide.ecjasso.com

# Prueba local de la aplicación

## Front

    - http://localhost:3000

## Back

    - http://localhost:8000
