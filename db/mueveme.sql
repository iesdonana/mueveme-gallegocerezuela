------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
      id        BIGSERIAL    PRIMARY KEY
    , nombre    VARCHAR(32) NOT NULL UNIQUE
                  CONSTRAINT ck_login_sin_espacios
                  CHECK (nombre NOT LIKE '% %')
    , password  VARCHAR(60) NOT NULL
    , email     VARCHAR(255) NOT NULL UNIQUE
    , confirmado BOOLEAN NOT NULL DEFAULT false
    , token     VARCHAR(32) NOT NULL
    , created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);


DROP TABLE IF EXISTS categorias CASCADE;

CREATE TABLE categorias
(
      id BIGSERIAL PRIMARY KEY
    , categoria VARCHAR(255) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS noticias CASCADE;

CREATE TABLE noticias
(
      id           BIGSERIAL    PRIMARY KEY
    , titulo       VARCHAR(255) NOT NULL
    , descripcion  TEXT         NOT NULL
    , url          VARCHAR(255) NOT NULL -- ¿Le ponemos UNIQUE? ¿Le ponemos tambien un patrón? ¿Lo llamamos URL o URI? --
    , usuario_id   BIGINT       NOT NULL REFERENCES usuarios(id)
                                ON DELETE CASCADE
                                ON UPDATE CASCADE
    , categoria_id BIGINT       REFERENCES categorias(id)
    , created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
      id            BIGSERIAL PRIMARY KEY
    , texto         TEXT      NOT NULL
    , usuario_id    BIGINT    NOT NULL REFERENCES usuarios(id)
                              ON DELETE CASCADE
                              ON UPDATE CASCADE
    , noticia_id    BIGINT    NOT NULL REFERENCES noticias(id)
    , comentario_id BIGINT    REFERENCES comentarios(id)
    , created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS votos CASCADE;

CREATE TABLE votos
(
      id            BIGSERIAL PRIMARY KEY
    , usuario_id    BIGINT    NOT NULL
                              REFERENCES usuarios(id)
                              ON DELETE CASCADE
                              ON UPDATE CASCADE
    , comentario_id BIGINT    NOT NULL
                              REFERENCES comentarios(id)
                              ON DELETE CASCADE
                              ON UPDATE CASCADE
    , votacion      SMALLINT  NOT NULL
                              CONSTRAINT ck_votacion_valida
                              CHECK votacion IN (1, -1)
    , UNIQUE(usuario_id, comentario_id)
);

DROP TABLE IF EXISTS movimientos CASCADE;

CREATE TABLE movimientos
(
      usuario_id BIGINT REFERENCES usuarios(id)
                        ON DELETE CASCADE
                        ON UPDATE CASCADE
    , noticia_id BIGINT REFERENCES noticias(id)
                        ON DELETE CASCADE
                        ON UPDATE CASCADE
    , PRIMARY KEY(usuario_id, noticia_id)
);

---------------------
-- Datos de prueba --
---------------------

INSERT INTO usuarios (nombre,password,email,confirmado,token)
     VALUES ('joni_182', crypt('joni', gen_salt('bf', 10)), 'joni@hotmail.com',true,'4-C_2yJQBe7L_X1zdmcHmKUaaY_utF4z')
          , ('admin', crypt('admin', gen_salt('bf', 10)), 'admin@hotmail.com',true,'nkKtEnLf_k7KaL6yP4awzfT58JtnVhjV')
          , ('juan', crypt('juan', gen_salt('bf', 10)), 'juan@hotmail.com',true,'7avF3wck6PrTJnRz-xKK1wHzQ7fFXBJb')
          , ('maria', crypt('maria', gen_salt('bf', 10)), 'maria@hotmail.com',true,'dZNc6pkS2dmLU5DX0L61fFgevE0lJbq_')
          , ('jose', crypt('jose', gen_salt('bf', 10)), 'jose@hotmail.com',true,'--ZLhXcaEnmKisl80GDlUGqtdBthQ5B4')
          , ('pepe', crypt('pepe', gen_salt('bf', 10)), 'pepe@hotmail.com',true,'82gobDGVynEKeO-VqauHdXsc6Higwiqd');



INSERT INTO categorias (categoria)
     VALUES ('Sucesos')
          , ('Política')
          , ('Ciencia')
          , ('Deportes')
          , ('Medio Ambiente')
          , ('Sanidad')
          , ('Actualidad')
          , ('Cultura')
          , ('Tecnologia');

INSERT INTO noticias (titulo, descripcion, url, usuario_id, categoria_id)
     VALUES ('Nueva técnica de soldadura permitirá usar aleación de aluminio en la fabricación de coches [EN]', 'Una aleación de aluminio desarrollada en la década de 1940, aluminio 7075 o Zicral, ha sido durante mucho tiempo una promesa para su uso en la fabricación de automóviles, a excepción de un obstáculo clave. Aunque es casi tan fuerte como el acero y solo un tercio del peso, es casi imposible de soldar juntos usando la técnica comúnmente utilizada para ensamblar paneles de carrocería piezas de motores.', 'https://phys.org/news/2019-01-nanotechnology-enables-weld-previously-un-weldable.html', 1, 3)
          , ('La nueva "Ley europea" sobre conciliación familiar explicada para trabajadores', 'Nuevos derechos laborales sobre paternidad, cuidadores, fuerza mayor por motivos familiares, horario flexible, protección contra el despido y sanciones disuasorias a las empresas por incumplimiento.', 'http://laboro-spain.blogspot.com/2019/01/directiva-europea-conciliacion-familiar.html', 2, 2)
          , (' Sanidad retira otros dos lotes de medicamentos para la hipertensión con irbesartán', 'El Ministerio de Sanidad a través de la web de la Aemps, ha informado de la orden de retirar del mercado dos lotes de medicamentos que se unen a los siete lotes ya retirados desde el día 16 de enero. Seis meses antes ya se había ordenado la retirada masiva de lotes de valsartán. Se trata de Irbesartan Sandoz 150 miligramos en comprimidos e Irbesartan Sandoz 300 miligramos en comprimidos del titular Sandoz Farmacéutica, S.A. y con fecha de caducidad en julio y septiembre de 2019 respectivamente.', 'https://www.20minutos.es/noticia/3545520/0/sanidad-retira-nuevos-lotes-hipertension-irbesartan/', 3, 6)
          , ('Fotos místicas de Wistman’s Wood, un encantador bosque en Inglaterra', 'En las profundidades de Dartmoor, Inglaterra, se encuentra Wistman’s Wood, un antiguo bosque que parece haber salido de un cuento de hadas –o de una historia de terror. Con piedras cubiertas de musgo y una compleja red de árboles torcidos, no es una sorpresa que esta misteriosa zona sea asociada con relatos sobrenaturales del folklore local. Muchos escritores incluso han descrito esta área como “el lugar más embrujado de Dartmoor”. Sin embargo, esta espeluznante reputación no espantó al fotógrafo Neil Burnell, quien se aventuró este siniestro.', 'https://mymodernmet.com/es/wistmans-wood-bosque-encantado-neil-burnell/', 4, 5);

INSERT INTO comentarios (texto, usuario_id, noticia_id, comentario_id)
     VALUES ('Mola lo de la soldadura', 2, 1, null)
          , ('Pues si que mola', 3, 1, 1)
          , ('Pues a mi no me gusra', 3, 1, 1)
          , ('Lo de la hipertensión es una mierda', 1, 3, null);

/* INSERT INTO votos (usuario_id, comentario_id, votacion)
     VALUES (1, 2, true)
          , (1, 3, true)
          , (2, 3, false)
          , (2, 1, true)
          , (3, 1, false)
          , (4, 1, false)
          , (4, 2, true)
          , (4, 3, false)
          , (1, 4, true); */

INSERT INTO movimientos (usuario_id, noticia_id)
     VALUES (1, 2)
          , (1, 3)
          , (2, 3)
          , (2, 1)
          , (3, 1)
          , (4, 1)
          , (4, 2)
          , (4, 3)
          , (1, 4);
