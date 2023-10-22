SET search_path TO public;

DROP TABLE IF EXISTS userss CASCADE;
DROP TABLE IF EXISTS generic_user CASCADE;
DROP TABLE IF EXISTS projectt CASCADE;
DROP TABLE IF EXISTS task CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS likes CASCADE;
DROP TABLE IF EXISTS isAdmin CASCADE;
DROP TABLE IF EXISTS favorite CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS project_notification CASCADE;
DROP TABLE IF EXISTS task_notification CASCADE;
DROP TABLE IF EXISTS comment_notification CASCADE;
DROP TABLE IF EXISTS isMember CASCADE;
DROP TABLE IF EXISTS isLeader CASCADE;
DROP TABLE IF EXISTS taskOwner CASCADE;
DROP TABLE IF EXISTS assigned CASCADE;
DROP TABLE IF EXISTS commentOwner CASCADE;

--Tables

CREATE TABLE userss (
  id SERIAL PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL
);

CREATE TABLE generic_user(
  id SERIAL PRIMARY KEY,
  name varchar(255) NOT NULL,
  birthdate DATE NOT NULL,
  profilePic TEXT NOT NULL,
  isBanned boolean NOT NULL,
  search TSVECTOR
);

CREATE TABLE projectt(
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
    dateCreation DATE, 
    deadLine DATE,
  title varchar(255) NOT null,
  id_project int not null,
  foreign key (id_project) references projectt(id)
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

CREATE TABLE isAdmin(
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES userss (id)
);

CREATE TABLE favorite(
  id SERIAL primary key,
  project_id INT NOT NULL,
  generic_user_id INT NOT NULL,
  FOREIGN KEY (project_id) REFERENCES projectt (id),
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
FOREIGN KEY (project_id) REFERENCES projectt (id),
FOREIGN KEY (notification_id) REFERENCES notification (id),
FOREIGN KEY (user_id) REFERENCES userss (id)
);

CREATE TABLE task_notification(
id SERIAL PRIMARY KEY,
task_id INT NOT NULL,
notification_id INT NOT NULL,
user_id INT NOT NULL,
taskType TEXT CHECK(taskType IN ('assigned', 'completed')) NOT NULL,
FOREIGN KEY (task_id) REFERENCES task (id),
FOREIGN KEY (notification_id) REFERENCES notification (id),
FOREIGN KEY (user_id) REFERENCES userss (id)
);

CREATE TABLE comment_notification(
id SERIAL PRIMARY KEY,
comment_id INT NOT NULL,
notification_id INT NOT NULL,
user_id INT NOT NULL,
 comment TEXT CHECK(comment IN ('like', 'response')) NOT NULL,
FOREIGN KEY (comment_id) REFERENCES comment (id),
FOREIGN KEY (notification_id) REFERENCES notification (id),
FOREIGN KEY (user_id) REFERENCES userss (id)
);

CREATE TABLE isMember(
   id_user Int not null,
  id_project int not null,
  primary key (id_user, id_project),
  foreign key(id_user) references userss(id),
  foreign key(id_project) references projectt(id)
);

CREATE TABLE isLeader(
   id_user Int not null,
  id_project int not null,
  primary key (id_user, id_project),
  foreign key(id_user) references userss(id),
  foreign key(id_project) references projectt(id)
);

CREATE TABLE taskOwner(
   id_user Int not null,
  id_task int not null,
  primary key (id_user, id_task),
  foreign key(id_user) references userss(id),
  foreign key(id_task) references task(id)
);

CREATE TABLE assigned(
  id_user Int not null,
  id_task INT not null,
  primary key (id_user, id_task),
  foreign key(id_user) references userss(id),
  foreign key(id_task) references task(id)
);

CREATE TABLE commentOwner(
   id_user Int not null,
  id_comment int not null,
  primary key (id_user, id_comment),
  foreign key(id_user) references userss(id),
  foreign key(id_comment) references comment(id)
);



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
CREATE INDEX searchGenericUserName ON generic_user USING HASH (name);

-- IDX02
CREATE INDEX searchProjectTitle ON projectt USING HASH (title);

-- IDX03
CREATE INDEX searchProjectTheme ON projectt USING HASH (theme);

-- IDX04
CREATE INDEX searchTaskDeadline ON task USING BTREE (deadLine);

-- IDX05
CREATE INDEX searchTaskPriority ON task USING BTREE (priority);

-- IDX06
CREATE INDEX searchMember ON isMember USING BTREE (id_project);

-- IDX07
CREATE INDEX searchLeader ON isLeader USING HASH (id_project);

-- IDX08
CREATE INDEX searchTaskOwner ON taskOwner USING HASH (id_task);

-- IDX09
CREATE INDEX searchAssigned ON assigned USING BTREE (id_task);

-- IDX10
CREATE INDEX searchCommentOwner ON commentOwner USING HASH (id_comment);


-- IDX11
CREATE INDEX searchUser ON generic_user USING GIN (search);
CREATE OR REPLACE FUNCTION user_search_update() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.search = (SELECT setweight(to_tsvector(generic_user.name), 'A') FROM generic_user WHERE NEW.id=generic_user.id);
    ELSIF TG_OP = 'UPDATE' AND (NEW.username <> OLD.username) THEN
        NEW.search = (SELECT setweight(to_tsvector(generic_user.name), 'A') FROM generic_user WHERE NEW.id=generic_user.id);
END IF;
RETURN NEW;
END;
$BODY$
    LANGUAGE 'plpgsql';

DROP TRIGGER IF EXISTS update_user_search ON generic_user;

CREATE TRIGGER update_user_search
    BEFORE INSERT OR UPDATE ON generic_user
    FOR EACH ROW
EXECUTE PROCEDURE user_search_update();


-- IDX12
CREATE INDEX searchProject ON projectt USING GIN (search);
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

DROP TRIGGER IF EXISTS update_project_search ON projectt;

CREATE TRIGGER update_project_search
    BEFORE INSERT OR UPDATE ON projectt
    FOR EACH ROW
EXECUTE PROCEDURE project_search_update();


-- TRIGGER01
DROP TRIGGER IF EXISTS add_favorite ON favorite;

CREATE TRIGGER add_favorite
    BEFORE INSERT OR UPDATE ON favorite
    FOR EACH ROW
EXECUTE PROCEDURE add_favorite();

CREATE OR REPLACE FUNCTION add_favorite() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((SELECT COUNT(*)
        FROM favorite
        WHERE NEW.generic_user_id = generic_user_id)>=5)
        THEN
            RAISE EXCEPTION 'A user cant have more than 5 favorite projects';
    END IF;
        RETURN NEW;
    END;
$BODY$
LANGUAGE plpgsql;


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

