--
-- Use a specific schema and set it as default - thingy.
--

SET search_path TO public;

--
-- Drop any existing tables.
--
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;
DROP TABLE IF EXISTS projectt CASCADE;
DROP TABLE IF EXISTS isLeader CASCADE;
DROP TABLE IF EXISTS isMember CASCADE;

--
-- Create tables.
--
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR
);

CREATE TABLE cards (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  user_id INTEGER REFERENCES users NOT NULL
);

CREATE TABLE items (
  id SERIAL PRIMARY KEY,
  card_id INTEGER NOT NULL REFERENCES cards ON DELETE CASCADE,
  description VARCHAR NOT NULL,
  done BOOLEAN NOT NULL DEFAULT FALSE
);



CREATE TABLE project(
  id SERIAL PRIMARY KEY,
  title varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  theme varchar(255) NOT NULL,
  archived boolean NOT NULL,
  search TSVECTOR
);

CREATE TABLE isLeader(
 id_user Int not null,
  id_project int not null,
  primary key (id_user, id_project),
  foreign key(id_user) references users(id),
  foreign key(id_project) references project(id)
);


CREATE TABLE isMember(
   id_user Int not null,
  id_project int not null,
  primary key (id_user, id_project),
  foreign key(id_user) references users(id),
  foreign key(id_project) references project(id)
);



INSERT INTO users VALUES (
  DEFAULT,
  'David Ferreira',
  'davidsferreira02@gmail.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')


INSERT INTO cards VALUES (DEFAULT, 'Things to do', 1);
INSERT INTO items VALUES (DEFAULT, 1, 'Buy milk');
INSERT INTO items VALUES (DEFAULT, 1, 'Walk the dog', true);

INSERT INTO cards VALUES (DEFAULT, 'Things not to do', 1);
INSERT INTO items VALUES (DEFAULT, 2, 'Break a leg');
INSERT INTO items VALUES (DEFAULT, 2, 'Crash the car');



INSERT INTO project (title, description, theme, archived)
VALUES ('Research and Development', 'In-depth research and product development', 'Technology', false);

INSERT INTO isLeader (id_user, id_project)
VALUES (1, 1);


INSERT INTO isMember (id_user, id_project)
VALUES (1, 1);
