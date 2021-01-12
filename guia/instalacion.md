# Instrucciones de instalación y despliegue

## En local

### Requisitos

- *PHP 7.4.0*
- *PostgreSQL 12.0 o superior*
- *Composer*
- *Cuenta de AWS*
- *Cuenta de gmail*

### Instalación

1. Ejecutamos los siguientes comandos
    ```sh
    $ git clone https://github.com/vmmarr/go.git
    $ cd go
    $ componser install
    ```

2. Rellenamos las variables de entorno, para ello cogemos el fichero `.env.example` y lo guardamos como `.env`. Una vez lo hayais guardado rellenar las variables.

3. Creamos la base de datos y le cargamos los datos, para ello:
    ```sh
    $ db/create.sh
    $ db/load.sh
    ```

4. Para iniciar la aplicación ejecutamos el comando:
    ```sh
    $ make serve
    ```

5. Acceder a la aplicación introducimos en el navegador `localhost:8080`.

## En la nube

### Requisitos

- Instalar Heroku CLI

### Despliegue

1. Ejecutamos el siguiente comando para clonar el repositorio: ` $ git clone https://github.com/vmmarr/go.git`

2. Nos registramos en heroku.

3. Creamos una aplicación en Heroku.

3. Añadimos el add-on **Heroku Postgres** para poder conectar nuestra base de datos de PostgreSQL.

4. En el mismo directorio que hemos clonado, debemos de ejecutar los siguiente comandos:
    1. `$ heroku login` para iniciar sesión con Heroku.
    2. `$ heroku git:remote -a nombre_app_heroku` para añadir el remoto.
    3. `$ heroku psql < db/go.sql -a nombre_aplicacion` para tener en Heroku nuestra base de datos.

5. Configuramos las variables de entorno:
    - `YII_ENV` en esta variable indicamos prod
    - `SMTP_PASS` contraseña de la cuenta de correo.
    - `AWS_ACCESS_KEY_ID` el id de la clave privada.
    - `AWS_SECRET_ACCESS_KEY` la clave privada.
    - `DATABASE_URL` la url de la base de datos.
    - `S3_BUCKET` el bucket AWS.
