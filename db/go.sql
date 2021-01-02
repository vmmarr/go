------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id                 bigserial     PRIMARY KEY
  , nombre             varchar(255)  NOT NULL
  , username           varchar(60)   NOT NULL UNIQUE
  , biografia          varchar(255)
  , email              varchar(255)  NOT NULL UNIQUE
  , password           varchar(255)  NOT NULL
  , authKey            varchar(255)  
  , verification_code  varchar(255)  NOT NULL DEFAULT ''
  , token              varchar(255)
  , extension          varchar(255)
);

insert into usuarios (nombre, username, email, password) values ('admin', 'admin', 'confirmvmmarr@gmail.com', crypt('admin', gen_salt('bf', 10)));

DROP TABLE IF EXISTS direcciones CASCADE;

CREATE TABLE direcciones
(
    id          bigserial    PRIMARY KEY
  , latitud     varchar(20) not NULL
  , longitud    varchar(20) NOT NULL
  , nombre      varchar(255) NOT NULL
);
insert into direcciones (latitud, longitud, nombre) values ('36.7828319', '-6.3500501', 'Calle Bolsa');
insert into direcciones (latitud, longitud, nombre) values ('37.0427332', '-6.4366354', 'Coto Doñana');
insert into direcciones (latitud, longitud, nombre) values ('36.790171', '-6.3267443', 'Martin Miguel');
insert into direcciones (latitud, longitud, nombre) values ('36.7729788', '-6.3906439', 'Sanlucar');

DROP TABLE IF EXISTS publicaciones CASCADE;

CREATE TABLE publicaciones
(
    id            bigserial    PRIMARY KEY
  , usuario_id    bigint       NOT NULL REFERENCES usuarios (id) on update CASCADE on delete CASCADE
  , direccion_id  bigint       REFERENCES direcciones (id) on update CASCADE on delete CASCADE
  , descripcion   varchar(255)
  , created_at    timestamp    NOT NULL DEFAULT current_timestamp
  , extension     varchar(255)
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
    id              bigserial    PRIMARY KEY
  , usuario_id      bigint       NOT NULL REFERENCES usuarios (id) on update CASCADE on delete CASCADE
  , publicacion_id  bigint       NOT NULL REFERENCES publicaciones (id) on update CASCADE on delete CASCADE
  , comentario      varchar(255)
  , created_at      timestamp(0) NOT NULL DEFAULT current_timestamp
);

DROP TABLE IF EXISTS likes CASCADE;

CREATE TABLE likes
(
    id             bigserial    PRIMARY KEY
  , usuario_id     bigint       NOT NULL REFERENCES usuarios (id) on update CASCADE on delete CASCADE
  , publicacion_id bigint       NOT NULL REFERENCES publicaciones (id) on update CASCADE on delete CASCADE
);

DROP TABLE IF EXISTS seguidores CASCADE;

CREATE TABLE seguidores
(
    id          bigserial    PRIMARY KEY
  , usuario_id  bigint       NOT NULL REFERENCES usuarios (id) on update CASCADE on delete CASCADE
  , seguidor_id bigint       NOT NULL REFERENCES usuarios (id) on update CASCADE on delete CASCADE
);

DROP TABLE IF EXISTS bloqueados CASCADE;

CREATE TABLE bloqueados
(
    id            bigserial    PRIMARY KEY
  , usuario_id    bigint       NOT NULL REFERENCES usuarios (id) on update CASCADE on delete CASCADE
  , bloqueado_id  bigint       NOT NULL REFERENCES usuarios (id) on update CASCADE on delete CASCADE
);