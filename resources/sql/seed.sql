-- Use a specific schema and set it as default - thingy.
--
DROP SCHEMA IF EXISTS lbaw2373 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2373;
SET search_path TO lbaw2373;

--
-- Drop any existing tables.
--
DROP TABLE IF EXISTS users CASCADE;
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
DROP TABLE IF EXISTS is_member CASCADE;
DROP TABLE IF EXISTS is_leader CASCADE;
DROP TABLE IF EXISTS taskowner CASCADE;
DROP TABLE IF EXISTS assigned CASCADE;
DROP TABLE IF EXISTS commentowner CASCADE;
CREATE TYPE accept_st as ENUM ('Pendent', 'Accepted', 'Rejected');

--
-- Create tables.
--
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  username Varchar NOT NULL UNIQUE,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR,
  isblocked Boolean NOT NULL DEFAULT false,
  photo TEXT NOT NULL DEFAULT 'profile/default.jpg',
  search TSVECTOR
);





CREATE TABLE project(
  id SERIAL PRIMARY KEY,
  title varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  theme varchar(255) NOT NULL,
  archived boolean  DEFAULT FALSE,
  search TSVECTOR
);

CREATE TABLE task (
  id SERIAL PRIMARY KEY,
  priority TEXT CHECK(priority IN ('Low', 'Medium', 'High')) NOT NULL,
  content TEXT, 
  isCompleted BOOLEAN Not NULL DEFAULT false, 
    dateCreation DATE DEFAULT CURRENT_TIMESTAMP, 
    deadLine DATE,
  title varchar(255) NOT null,
  id_project int not null,
  foreign key (id_project) references project(id) ON DELETE CASCADE,
  CONSTRAINT unique_task_title_per_project UNIQUE (title, id_project),
  search TSVECTOR
);

CREATE TABLE comment (
  id SERIAL PRIMARY KEY,
  content VARCHAR(255) NOT NULL,
  id_task INT NOT NULL,
  FOREIGN KEY (id_task) REFERENCES task(id) ON DELETE CASCADE,
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE likes(
  id SERIAL PRIMARY KEY,
  comment_id INT NOT NULL,
  user_id INT NOT NULL,
  FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE isadmin(
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE favorite(
  id SERIAL primary key,
  project_id INT NOT NULL,
  users_id INT NOT NULL,
  FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE,
  FOREIGN KEY (users_id) REFERENCES users (id)
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
  foreign key(id_project) references project(id) on DELETE CASCADE
);

CREATE TABLE is_member(
   id_user Int not null,
  id_project int not null,
  primary key (id_user, id_project),
  foreign key(id_user) references users(id),
  foreign key(id_project) references project(id) on DELETE CASCADE
);


CREATE TABLE taskowner(
   id_user Int not null,
  id_task int not null,
  primary key (id_user, id_task),
  foreign key(id_user) references users(id),
  foreign key(id_task) references task(id) ON DELETE CASCADE
);

CREATE TABLE assigned(
  id_user Int not null,
  id_task INT not null,
  primary key (id_user, id_task),
  foreign key(id_user) references users(id),
  foreign key(id_task) references task(id) ON DELETE CASCADE
);

CREATE TABLE commentowner(
   id_user Int not null,
  id_comment int not null,
  primary key (id_user, id_comment),
  foreign key(id_user) references users(id),
  foreign key(id_comment) references comment(id) ON DELETE CASCADE
);

CREATE TABLE inviteproject (
    id_user INT NOT NULL, 
    id_project INT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    acceptance_status accept_st NOT NULL DEFAULT 'Pendent',
    PRIMARY KEY (id_user, id_project),
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_project) REFERENCES project(id) ON DELETE CASCADE
);






INSERT INTO users (name,username, password, email) VALUES
('david','davidsferreira02','$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W','david@lbaw.com'),
  ('alice','alice02', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'alice@example.com'),
  ('bob', 'bob02' ,'$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'bob@example.com');




INSERT into isadmin(user_id) VALUES
(3);
 
  
  
  DROP INDEX IF EXISTS searchGenericUserName;
DROP INDEX IF EXISTS searchProjectTitle;
DROP INDEX IF EXISTS searchProjectTheme;
DROP INDEX IF EXISTS searchTaskDeadline;
DROP INDEX IF EXISTS searchTaskPriority;
DROP INDEX IF EXISTS searchMember;
DROP INDEX IF EXISTS searchLeader;
DROP INDEX IF EXISTS searchTaskOwner;
DROP INDEX IF EXISTS searchAssigned;
DROP INDEX IF EXISTS searchCommentOwner;
DROP INDEX IF EXISTS searchUser;
DROP INDEX IF EXISTS searchProject;

-- IDX01
CREATE INDEX searchGenericUserName ON users USING HASH (name);

-- IDX02
CREATE INDEX searchProjectTitle ON project USING HASH (title);

-- IDX03
CREATE INDEX searchProjectTheme ON project USING HASH (theme);

-- IDX04
CREATE INDEX searchTaskDeadline ON task USING BTREE (deadLine);

-- IDX05
CREATE INDEX searchTaskPriority ON task USING BTREE (priority);

-- IDX06
CREATE INDEX searchMember ON is_member USING BTREE (id_project);

-- IDX07
CREATE INDEX searchLeader ON is_leader USING HASH (id_project);

-- IDX08
CREATE INDEX searchTaskOwner ON taskOwner USING HASH (id_task);

-- IDX09
CREATE INDEX searchAssigned ON assigned USING BTREE (id_task);

-- IDX10
CREATE INDEX searchCommentOwner ON commentOwner USING HASH (id_comment);

-- IDX11
CREATE INDEX searchUser ON users USING GIN (search);
CREATE OR REPLACE FUNCTION user_search_update() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.search = (SELECT setweight(to_tsvector(users.name), 'A') FROM users WHERE NEW.id = users.id);
    ELSIF TG_OP = 'UPDATE' AND (NEW.name <> OLD.name) THEN
        NEW.search = (SELECT setweight(to_tsvector(users.name), 'A') FROM users WHERE NEW.id = users.id);
    END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql';


DROP TRIGGER IF EXISTS update_user_search ON users;

CREATE TRIGGER update_user_search
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
EXECUTE PROCEDURE user_search_update();


-- IDX12
CREATE INDEX searchProject ON project USING GIN (search);
CREATE OR REPLACE FUNCTION project_search_update() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.search = (SELECT setweight(to_tsvector(NEW.title), 'A') || setweight(to_tsvector(NEW.description), 'B'));
    ELSIF TG_OP = 'UPDATE' AND (NEW.title <> OLD.title OR NEW.description <> OLD.description) THEN
        NEW.search = (SELECT setweight(to_tsvector(NEW.title), 'A') || setweight(to_tsvector(NEW.description), 'B'));
END IF;
RETURN NEW;
END;
$BODY$
    LANGUAGE 'plpgsql';

DROP TRIGGER IF EXISTS update_project_search ON project;

CREATE TRIGGER update_project_search
    BEFORE INSERT OR UPDATE ON project
    FOR EACH ROW
EXECUTE PROCEDURE project_search_update();




DROP TRIGGER IF EXISTS update_project_search ON project;

CREATE TRIGGER update_project_search
    BEFORE INSERT OR UPDATE ON project
    FOR EACH ROW
EXECUTE PROCEDURE project_search_update();


-- TRIGGER01


-- TRIGGER02
CREATE OR REPLACE FUNCTION add_new_member_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
INSERT INTO project_notification (projectType, notification_id, user_id, project_id)
VALUES ('newMember', 4, NEW.user_id, NEW.id);
RETURN NEW;
END;
$BODY$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_new_member_notification ON project_notification;

CREATE TRIGGER add_new_member_notification
    AFTER INSERT ON project_notification
    FOR EACH ROW
EXECUTE PROCEDURE add_new_member_notification();


-- TRIGGER03
CREATE OR REPLACE FUNCTION add_deleted_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
INSERT INTO project_notification (projectType, notification_id, user_id, project_id)
VALUES ('deleted', 3, NEW.user_id, NEW.id);
RETURN NEW;
END;
$BODY$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_deleted_notification ON project_notification;

CREATE TRIGGER add_deleted_notification
    AFTER INSERT ON project_notification
    FOR EACH ROW
EXECUTE PROCEDURE add_deleted_notification();


-- TRIGGER04
CREATE OR REPLACE FUNCTION add_expelled_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
INSERT INTO project_notification (projectType, notification_id, user_id, project_id)
VALUES ('expelled', 2, NEW.user_id, NEW.id);
RETURN NEW;
END;
$BODY$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_expelled_notification ON project_notification;

CREATE TRIGGER add_expelled_notification
    AFTER INSERT ON project_notification
    FOR EACH ROW
EXECUTE PROCEDURE add_expelled_notification();


-- TRIGGER05
CREATE OR REPLACE FUNCTION add_project_leader_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
INSERT INTO project_notification (projectType, notification_id, user_id, project_id)
VALUES ('newLeader', 1, NEW.user_id, NEW.id);
RETURN NEW;
END;
$BODY$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_project_leader_notification ON project_notification;

CREATE TRIGGER add_project_leader_notification
    AFTER INSERT ON project_notification
    FOR EACH ROW
EXECUTE PROCEDURE add_project_leader_notification();


-- TRIGGER06
CREATE OR REPLACE FUNCTION add_task_completed_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
INSERT INTO task_notification (taskType, notification_id, user_id, task_id)
VALUES ('completed', 6, NEW.user_id, NEW.id_task);
RETURN NEW;
END;
$BODY$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_task_completed_notification ON task_notification;

CREATE TRIGGER add_task_completed_notification
    AFTER INSERT ON task_notification
    FOR EACH ROW
EXECUTE PROCEDURE add_task_completed_notification();


--TRIGGER07
CREATE OR REPLACE FUNCTION add_task_assigned_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
INSERT INTO task_notification (taskType, notification_id, user_id, task_id)
VALUES ('assigned', 5, NEW.user_id, NEW.id_task);
RETURN NEW;
END;
$BODY$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_task_assigned_notification ON task_notification;

CREATE TRIGGER add_task_assigned_notification
    AFTER INSERT ON task_notification
    FOR EACH ROW
EXECUTE PROCEDURE add_task_assigned_notification();


-- TRIGGER08
CREATE OR REPLACE FUNCTION add_comment_response_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
INSERT INTO comment_notification (comment, notification_id, user_id, comment_id)
VALUES ('response', 8, NEW.user_id, NEW.id_comment);
RETURN NEW;
END;
$BODY$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_comment_response_notification ON comment_notification;

CREATE TRIGGER add_comment_response_notification
    AFTER INSERT ON comment_notification
    FOR EACH ROW
EXECUTE PROCEDURE add_comment_response_notification();


-- TRIGGER09
CREATE OR REPLACE FUNCTION add_comment_like_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
INSERT INTO comment_notification (comment, notification_id, user_id, comment_id)
VALUES ('like', 7, NEW.user_id, NEW.id_comment);
RETURN NEW;
END;
$BODY$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_comment_like_notification ON comment_notification;

CREATE TRIGGER add_comment_like_notification
    AFTER INSERT ON comment_notification
    FOR EACH ROW
EXECUTE PROCEDURE add_comment_like_notification();


CREATE OR REPLACE FUNCTION anonymize_user_comments()
RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE comment
    SET content = REPLACE(content, OLD.name, 'Anônimo' || NEW.id)
    WHERE id_user = OLD.id;
    
    RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER after_delete_user_anonymize_comments
AFTER DELETE ON users
FOR EACH ROW
EXECUTE FUNCTION anonymize_user_comments();

UPDATE users SET search = to_tsvector('english', name);
UPDATE project SET search = to_tsvector('english', title);
CREATE INDEX idx_gin_search ON task USING GIN (search);



