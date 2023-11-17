-- Use a specific schema and set it as default - thingy.
--
DROP SCHEMA IF EXISTS lbaw2373 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2373;
SET search_path TO lbaw2373;

--
-- Drop any existing tables.
--
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS generic_user CASCADE;
DROP TABLE IF EXISTS project CASCADE;
DROP TABLE IF EXISTS task CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS likes CASCADE;
DROP TABLE IF EXISTS isadmin CASCADE;
DROP TABLE IF EXISTS favorite CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS project_notification CASCADE;
DROP TABLE IF EXISTS task_notification CASCADE;
DROP TABLE IF EXISTS comment_notification CASCADE;
DROP TABLE IF EXISTS ismember CASCADE;
DROP TABLE IF EXISTS isleader CASCADE;
DROP TABLE IF EXISTS taskowner CASCADE;
DROP TABLE IF EXISTS assigned CASCADE;
DROP TABLE IF EXISTS commentowner CASCADE;
CREATE TYPE accept_st as ENUM ('Pendent', 'Accepted', 'Rejected');

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



CREATE TABLE generic_user(
  id SERIAL PRIMARY KEY,
  name varchar(255) NOT NULL,
  birthdate DATE NOT NULL,
  profilePic TEXT NOT NULL,
  isBanned boolean NOT NULL,
  search TSVECTOR
);

CREATE TABLE project(
  id SERIAL PRIMARY KEY,
  title varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  theme varchar(255) NOT NULL,
  archived boolean NOT NULL,
  search TSVECTOR
);

CREATE TABLE task (
  id SERIAL PRIMARY KEY,
  priority TEXT CHECK(priority IN ('Low', 'Medium', 'High')) NOT NULL,
  content TEXT, 
  isCompleted BOOLEAN, 
    dateCreation DATE DEFAULT CURRENT_TIMESTAMP, 
    deadLine DATE,
  title varchar(255) NOT null,
  id_project int not null,
  foreign key (id_project) references project(id)
);

CREATE TABLE comment(
  id SERIAL PRIMARY KEY,
  content varchar(255) NOT NULL,
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE likes(
  id SERIAL PRIMARY KEY,
  comment_id INT NOT NULL,
  generic_user_id INT NOT NULL,
  FOREIGN KEY (comment_id) REFERENCES comment (id),
  FOREIGN KEY (generic_user_id) REFERENCES generic_user (id)
);

CREATE TABLE isadmin(
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE favorite(
  id SERIAL primary key,
  project_id INT NOT NULL,
  generic_user_id INT NOT NULL,
  FOREIGN KEY (project_id) REFERENCES project (id),
  FOREIGN KEY (generic_user_id) REFERENCES generic_user (id)
);

Create TABLE notification(
id SERIAL PRIMARY KEY,
description TEXT NOT NULL 
);

CREATE TABLE project_notification(
id SERIAL PRIMARY KEY,
project_id INT NOT NULL,
notification_id INT,
user_id INT NOT NULL,
projectType TEXT CHECK(projectType IN ('newLeader', 'expelled','deleted','newMember')) NOT NULL,
FOREIGN KEY (project_id) REFERENCES project (id),
FOREIGN KEY (notification_id) REFERENCES notification (id),
FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE task_notification(
id SERIAL PRIMARY KEY,
task_id INT NOT NULL,
notification_id INT NOT NULL,
user_id INT NOT NULL,
taskType TEXT CHECK(taskType IN ('assigned', 'completed')) NOT NULL,
FOREIGN KEY (task_id) REFERENCES task (id),
FOREIGN KEY (notification_id) REFERENCES notification (id),
FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE comment_notification(
id SERIAL PRIMARY KEY,
comment_id INT NOT NULL,
notification_id INT NOT NULL,
user_id INT NOT NULL,
 comment TEXT CHECK(comment IN ('like', 'response')) NOT NULL,
FOREIGN KEY (comment_id) REFERENCES comment (id),
FOREIGN KEY (notification_id) REFERENCES notification (id),
FOREIGN KEY (user_id) REFERENCES users (id)
);


CREATE TABLE is_leader(
   id_user Int not null,
  id_project int not null,
  primary key (id_user, id_project),
  foreign key(id_user) references users(id),
  foreign key(id_project) references project(id)
);

CREATE TABLE is_member(
   id_user Int not null,
  id_project int not null,
  primary key (id_user, id_project),
  foreign key(id_user) references users(id),
  foreign key(id_project) references project(id)
);


CREATE TABLE taskowner(
   id_user Int not null,
  id_task int not null,
  primary key (id_user, id_task),
  foreign key(id_user) references users(id),
  foreign key(id_task) references task(id)
);

CREATE TABLE assigned(
  id_user Int not null,
  id_task INT not null,
  primary key (id_user, id_task),
  foreign key(id_user) references users(id),
  foreign key(id_task) references task(id)
);

CREATE TABLE commentowner(
   id_user Int not null,
  id_comment int not null,
  primary key (id_user, id_comment),
  foreign key(id_user) references users(id),
  foreign key(id_comment) references comment(id)
);

Create Table inviteproject(
	id_user Int not null,
	id_project Int not null,
	 "date" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    acceptance_status accept_st NOT NULL DEFAULT 'Pendent',
	PRIMARY KEY (id_user, id_project),
   foreign key(id_user) references users(id),
  foreign key(id_project) references project(id)
);


-- Insert value.
--

INSERT INTO users VALUES (
  DEFAULT,
  'David Ferreira',
  'davidsferreira02@gmail.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

