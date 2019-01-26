------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuario CASCADE;

CREATE TABLE usuarios
(
      id               BIGSERIAL    PRIMARY KEY
    , nombre           VARCHAR(100)
    , primer_apellido  VARCHAR(100)
    , segundo_apellido VARCHAR(100)
    , login            VARCHAR(50)  NOT NULL UNIQUE
                                    CONSTRAINT ck_login_sin_espacios
                                    CHECK (login NOT LIKE '% %')
    , password         VARCHAR(60)  NOT NULL
    , email            VARCHAR(255)  -- ¿Le ponemos restricción con un patrón para email? --
);

DROP TABLE IF EXISTS noticia CASCADE;

CREATE TABLE noticias
(
      id          BIGSERIAL    PRIMARY KEY
    , titulo      VARCHAR(255) NOT NULL
    , descripcion TEXT         NOT NULL
    , url         VARCHAR(255) NOT NULL -- ¿Le ponemos UNIQUE? ¿Le ponemos tambien un patrón? ¿Lo llamamos URL o URI? --
);
