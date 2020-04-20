------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id         bigserial    PRIMARY KEY
  , nombre       varchar(255)  NOT NULL
  , username     varchar(60) NOT NULL UNIQUE
  , biografia varchar(255)
  , email   varchar(255)  NOT NULL
  , password  varchar(255) NOT NULL
  , authKey varchar(255) NOT NULL
  , token varchar(255)
);

DROP TABLE IF EXISTS publicaciones CASCADE;

CREATE TABLE publicaciones
(
    id         bigserial    PRIMARY KEY
  , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
  , descripcion varchar(255)
  , created_at timestamp NOT NULL DEFAULT current_timestamp
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
    id         bigserial    PRIMARY KEY
  , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
  , publicacion_id bigint   NOT NULL REFERENCES publicaciones (id)
  , comentario varchar(255)
  , created_at timestamp(0) NOT NULL DEFAULT current_timestamp
);

DROP TABLE IF EXISTS likes CASCADE;

CREATE TABLE likes
(
    id         bigserial    PRIMARY KEY
  , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
  , publicacion_id bigint   NOT NULL REFERENCES publicaciones (id)
);

DROP TABLE IF EXISTS seguidores CASCADE;

CREATE TABLE seguidores
(
    id         bigserial    PRIMARY KEY
  , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
  , seguidor_id bigint       NOT NULL REFERENCES usuarios (id)
  , aceptacion boolean
);