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
DROP TABLE IF EXISTS password_reset CASCADE;
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
  title varchar(255) NOT NULL UNIQUE,
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
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
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


CREATE TABLE password_reset ( 
    email TEXT NOT NULL, 
    token TEXT NOT NULL,
    PRIMARY KEY (email, token)
);





INSERT INTO users (name,username, password, email) VALUES
('david','davidsferreira02','$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W','davidsferreira02@gmail.com'),
  ('alice','alice02', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'alice@example.com'),
  ('bob', 'bob02' ,'$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'bob@example.com'),
  ('John Doe', 'john_doe', '$2y$10$Fak4VW1F2UahvwL9h1m2XucnT2A0.3vVjWJAJ8EgCfmEw5jEDdhqK', 'john@example.com'),
('Alice Smith', 'alice_smith', '$2y$10$XpJGu1sD7dOC9hS5jUjL8uAxeasex6RFjXPT5mIA3ukgPFOEeCvga', 'alice2@example.com'),
('Bob Johnson', 'bob_johnson', '$2y$10$obEeBwTMRQhHsOs39ObmYuyQ5KW7BfTnRjvwrc9KDTj4yrDXpMn8q', 'bob2@example.com'),
('Emma Davis', 'emma_davis', '$2y$10$LT3TgVW37.6Iajf2YXmDGOwGCSzOzGZW89QpYSvRm6NDQFm8F5PtW', 'emma@example.com'),
('Michael Wilson', 'michael_wilson', '$2y$10$LHnnoYrbNp7pVwPn8rYsZOFE9QIt9jUe7XW/09RuxF1Nn6P18CJpa', 'michael@example.com'),
('Emily Brown', 'emily_brown', '$2y$10$AYN.7.BDqOhQk.8HLPYcA.vk97UfLQd9wIIBrIA/6Zlh6LBoI9K9S', 'emily@example.com'),
('William Martinez', 'william_martinez', '$2y$10$wjSPyXc3qVEGxRF39NmmPOxRfRuf1ZabVrT6Ch.MtdViUu6Er/nKa', 'william@example.com'),
('Olivia Garcia', 'olivia_garcia', '$2y$10$z4uv/pwB27w.XdP6YhCx7.7b2yJlJmK93xB1q3ZImeKg/YVTohlKu', 'olivia@example.com'),
('James Miller', 'james_miller', '$2y$10$ojJ11KYR0HphNX.Q9rX5MuLJlsr3PmOD87Y2V/KhHjwEeSPoQQQ22', 'james@example.com'),
('Sophia Lopez', 'sophia_lopez', '$2y$10$ZKl3r4gfvF7upGp3CQPNb.qkPBSc6b/xOEWYrzUJxw/JTgH6ZWiLW', 'sophia@example.com'),
('Alexander Hernandez', 'alexander_hernandez', '$2y$10$D6EU.fVAyFZ2KFRsA3bDWuOP4vadYZXm5dRkYw2SOfT61u1f/Pb/K', 'alexander@example.com'),
('Mia Scott', 'mia_scott', '$2y$10$b/mJj7oeyGWJtK.QXe9qkuf8pttG7xkIyUEr1Aas7b2jqBe2rgQ9i', 'mia@example.com'),
('Benjamin Torres', 'benjamin_torres', '$2y$10$ohEKnELq9RYQ7OWMaBXb1OBUTD31z/45Ym5OeYU3aLJm8o1/oY6U6', 'benjamin@example.com'),
('Charlotte King', 'charlotte_king', '$2y$10$jnqm.LVNg8QCB0yGFFKnxOaMUh7H8XjHs8uCXESl6VPEcmZ2QZT9O', 'charlotte@example.com'),
('Daniel Perez', 'daniel_perez', '$2y$10$iojVATz3Z2nxgLpE2VfvNeAtxv/YqblCM.qcUoJwSgKz8BZzE12ju', 'daniel@example.com'),
('Amelia Young', 'amelia_young', '$2y$10$qvL8sAxnZolHxLTTNhcL/.S.dZ3fyv3j8PF/LmMs..YRldIdZ5ZYG', 'amelia@example.com'),
('Liam Flores', 'liam_flores', '$2y$10$Fv4TwsFWvVQg5v9w1IlMf.3weOXPJ5pT9Wn3SYb4UIZ6QESWSTyiS', 'liam2@example.com'),
('Ava Washington', 'ava_washington', '$2y$10$2GjBcLxfjx05sYwQsBp9gefXeA0u.NTVcQsVPUmjA5a6o3Rd/eZW.', 'ava@example.com'),
('Lucas Clark', 'lucas_clark', '$2y$10$fOyYNW8rD4xiL8GgrbCim.7JQ0EBjZz2SEvSVKktjMvEcs8V9Za.K', 'lucas@example.com'),
('Harper Adams', 'harper_adams', '$2y$10$F/00D/RNZMbmrWBrOCSmVewWVbDL1zlsKmGVsu79xTGrVKe9PS9yq', 'harper@example.com'),
('Elijah Ramirez', 'elijah_ramirez', '$2y$10$96AnEZJCGYiXUjyRmTLazek2s6Jg6ltO01T47/zbUBT.gBwS6EnVu', 'elijah@example.com'),
('Avery Hill', 'avery_hill', '$2y$10$QR.UhGKyB.KWKLX5kQsC6uR71G6sYIWYk3NHuLXK8T9dLe2JmFvam', 'avery@example.com'),
('Logan Ward', 'logan_ward', '$2y$10$eAKrHNXoRfYBVFb2Ae9rxeR0sJTuU8UKjotkE9w5e.nvQdeKSuQUK', 'logan@example.com'),
('Evelyn Baker', 'evelyn_baker', '$2y$10$Xa3PCWmbz/BEzG9LjOzgqeGyckifQtTxKDvQdeF.9gMxcjSOxQ7.2', 'evelyn@example.com'),
('Jayden Cook', 'jayden_cook', '$2y$10$pm8bFupY9SDWsmVC4pT13OZuq9ltc/KxP7yDkAH5MLquKdAFR3p/S', 'jayden@example.com'),
('Luna Gonzales', 'luna_gonzales', '$2y$10$gS2aH0hT4t.8Nu01dETJveJxt3aEY3NbJas9cQzRZFX/nxd2NSb.2', 'luna@example.com'),
('Liam Murphy', 'liam_murphy', '$2y$10$JNXO2zCfKFxenFm1MEnkLe9eAuziM9z7/3f.SqE5tSKQztvy9BqJq', 'liam@example.com'),
('Aria Hall', 'aria_hall', '$2y$10$fc2m8Q91iIX17AwhfRJz0.g88lYjE1JdKwYL/yA6ND1u2tPAnvW0C', 'aria@example.com'),
('Carter Ward', 'carter_ward', '$2y$10$Q4JYqShVywabOhvl.yJX/.gdw67cwS1KydTpMWKZJN8q1X/J0AC/e', 'carter@example.com'),
('Arianna Russell', 'arianna_russell', '$2y$10$QKYx1dPMZ2en9pVumqq09e/vTK0aInyJGup4B.izxDRhNTbbiqEZK', 'arianna@example.com'),
('Lincoln Turner', 'lincoln_turner', '$2y$10$Q7V0vKcc7sBqSVIjQGt2kOdqw7bGqYfZq7X1KjwEm8NlQ8yM7vDR2', 'lincoln@example.com'),
('Scarlett Ward', 'scarlett_ward', '$2y$10$owEI5.HMOuZ1vR62U6MlAeppxtWBfyLQl4/sqZJW2GrELO7F4qQJu', 'scarlett@example.com'),
('Grayson Mitchell', 'grayson_mitchell', '$2y$10$02x/EyZhJzfdjNx54A8FvOVlBEq5XsYzNXbz/A8BgeAPTr6XwNLUm', 'grayson@example.com'),
('Adeline Garcia', 'adeline_garcia', '$2y$10$wXYb/p4VYqQNZzE7i9HscePJY8v7DZz9m/.W/c5Mf60XEcq4FFIlK', 'adeline@example.com'),
('Ezra Ward', 'ezra_ward', '$2y$10$IDMLaxK/XVRcnCq5Wc0Yi.nTsoQ1UfUVHb.ljqNf8ZTtBBv5B1Lg2', 'ezra@example.com'),
('Nova Nelson', 'nova_nelson', '$2y$10$HWq0IqN9NlwHz9.CxUbgPey9HxZv2S0P5RtYkOYR6a/dGPE6tYrly', 'nov@example.com'),
('Sebastian Thompson', 'sebastian_thompson', '$2y$10$6FsUom4C8zvzgU/jfA/9meRiv7HrQw0Lyfk8Z9EYJh.yq97gSxU5C', 'sebastian@example.com'),
('Nova Rodriguez', 'nova_rodriguez', '$2y$10$FlgXSTY0wr1xflYJ49Tm8uH.0BZ0Pklx.vbfv.hYZ2I1bKQeVynMC', 'nova@example.com'),
('Levi Powell', 'levi_powell', '$2y$10$jAtuOgjJdMx0s2ALD1SIs.GcIVUlkU1u.eE2u6fxx50Rj3uujqV5G', 'levi@example.com'),
 ('Anônimo', 'anonimo', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'anonimo@example.com');




INSERT into isadmin(user_id) VALUES
(3),
(5),
(7),
(9),
(11);
 

INSERT INTO project (title, description, theme) VALUES
('Content Management System', 'Development of a CMS system to manage web content.', 'Technology'),
('E-commerce Platform', 'Building an online platform for sales.', 'E-commerce'),
('Health Application', 'Development of an app for health monitoring.', 'Health'),
('Environmental Sustainability Project', 'Initiative to promote environmental sustainability in the local community.', 'Sustainability'),
('Professional Social Network', 'Creating a social network to connect professionals from different fields.', 'Technology'),
('Online Education Platform', 'Development of a platform for online courses.', 'Education'),
('Financial Management App', 'App to assist in personal finance management.', 'Finance'),
('Urban Art Project', 'Initiative to promote urban art in public spaces.', 'Culture'),
('Music Streaming Platform', 'Development of a music streaming platform.', 'Entertainment'),
('Culinary Recipes App', 'App for sharing and discovering culinary recipes.', 'Culinary');




INSERT INTO is_member (id_user, id_project)
VALUES
-- Adicione membros para o projeto 1
(1,1),
(2, 1),
(4, 1), 
(6, 1),
(8, 1), 
(10, 1), 

(2, 2), 
(4, 2), 
(6, 2), 
(8, 2),  
(10, 2),
(6,3),
(8,3),
(10,3),
(8,4),
(10,4),
(12,4),
(10,5),
(12,5),
(14,5),
(12,6),
(14,6),
(16,6),
(14,7),
(16,7),
(18,7),
(16,8),
(18,8),
(20,8),
(18,9),
(20,9),
(2,9),
(20,10),
(2,10),
(4,10);

INSERT INTO is_leader (id_user, id_project)
VALUES

(2, 1), (8, 1),

(4, 2), (10, 2),

(6, 3), (8, 3),

(8, 4), (10, 4),

(10, 5), (12, 5),

(12, 6), (14, 6),

(14, 7), (16, 7),

(16, 8), (18, 8),

(18, 9), (2, 9),

(20, 10), (4, 10);



 

  
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
    UPDATE commentowner
    SET id_user = (SELECT id FROM users WHERE username = 'anonimo')
    WHERE id_user = OLD.id;

    UPDATE taskowner
SET id_user = (SELECT id FROM users WHERE username = 'anonimo')
WHERE id_user = OLD.id;

-- Agora você pode atualizar o usuário para anonimo

    
    RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION update_task_search_vector() RETURNS TRIGGER AS $$
BEGIN
    NEW.search = to_tsvector('english', coalesce(NEW.title, '') || ' ' ||
                                coalesce(NEW.content, ''));
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER task_search_vector_update_trigger BEFORE INSERT OR UPDATE
    ON task FOR EACH ROW EXECUTE FUNCTION update_task_search_vector();


CREATE TRIGGER after_delete_user_anonymize_comments
AFTER DELETE ON users
FOR EACH ROW
EXECUTE FUNCTION anonymize_user_comments();

UPDATE users SET search = to_tsvector('english', name);
UPDATE project SET search = to_tsvector('english', title);
CREATE INDEX idx_gin_search ON task USING GIN (search);



