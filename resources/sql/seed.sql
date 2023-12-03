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
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR,
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
  user_id INT NOT NULL,
  FOREIGN KEY (comment_id) REFERENCES comment (id),
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
  FOREIGN KEY (project_id) REFERENCES project (id),
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
  foreign key(id_comment) references comment(id)
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






INSERT INTO users (name, password, email) VALUES
('david','$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W','david@lbaw.com'),
  ('alice', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'alice@example.com'),
  ('bob', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'bob@example.com'),
  ('charlie', 'Str0ngP@ssw0rd', 'charlie@example.com'),
  ('diana', 'Pa$$w0rd123', 'diana@example.com'),
  ('edward', 'MyP@ssw0rd', 'edward@example.com'),
  ('frank', 'Passw0rd!', 'frank@example.com'),
  ('grace', 'P@ss123', 'grace@example.com'),
  ('henry', 'P@ssw0rd12', 'henry@example.com'),
  ('isabel', 'S3cureP@ss', 'isabel@example.com'),
  ('jack', 'P@ssw0rd!', 'jack@example.com'),
  ('kate', 'Str0ngP@ssword', 'kate@example.com'),
  ('liam', 'P@ssw0rd456', 'liam@example.com'),
  ('mary', 'Secure123!', 'mary@example.com'),
  ('nathan', 'P@ssw0rd321', 'nathan@example.com'),
  ('olivia', 'P@ssw0rd789', 'olivia@example.com'),
  ('peter', 'P@ssw0rd567', 'peter@example.com'),
  ('quinn', 'P@ssw0rd!!', 'quinn@example.com'),
  ('ryan', '12345P@ss', 'ryan@example.com'),
  ('sophia', 'P@ssw0rd111', 'sophia@example.com'),
  ('thomas', 'S3cureP@ssword', 'thomas@example.com'),
  ('violet', 'P@ssw0rd@123', 'violet@example.com'),
  ('william', 'P@ssw0rd444', 'william@example.com'),
  ('xander', 'Secure1234!', 'xander@example.com'),
  ('yasmine', 'P@ssw0rd!!123', 'yasmine@example.com'),
  ('zane', 'P@ssw0rd7890', 'zane@example.com'),
  ('aurora', 'P@ssw0rd5678', 'aurora@example.com'),
  ('beckett', 'P@ssw0rd#123', 'beckett@example.com'),
  ('clara', 'Secure@1234', 'clara@example.com'),
  ('dexter', 'P@ssw0rd_456', 'dexter@example.com'),
  ('ella', 'P@ssw0rd123_', 'ella@example.com'),
  ('finn', 'P@ssw0rd#567', 'finn@example.com'),
  ('gabriella', 'P@ssw0rd$123', 'gabriella@example.com'),
  ('hudson', 'Secure5678!', 'hudson@example.com'),
  ('isla', 'P@ssw0rd789#', 'isla@example.com'),
  ('jaxon', 'P@ssw0rd_7890', 'jaxon@example.com'),
  ('kayla', 'P@ssw0rd123!', 'kayla@example.com'),
  ('luca', 'P@ssw0rd$5678', 'luca@example.com'),
  ('mia', 'Secure#1234', 'mia@example.com'),
  ('noah', 'P@ssw0rd!1234', 'noah@example.com'),
  ('oliver', 'P@ssw0rd5678#', 'oliver@example.com'),
  ('penelope', 'P@ssw0rd$1234', 'penelope@example.com'),
  ('quinton', 'Secure12345!', 'quinton@example.com'),
  ('rebecca', 'P@ssw0rd!5678', 'rebecca@example.com'),
  ('samuel', 'P@ssw0rd_12345', 'samuel@example.com'),
  ('tabitha', 'P@ssw0rd123!$', 'tabitha@example.com'),
  ('ulysses', 'Secure5678$!', 'ulysses@example.com'),
  ('vivienne', 'P@ssw0rd$12345', 'vivienne@example.com'),
  ('wesley', 'P@ssw0rd_5678$', 'wesley@example.com'),
  ('xavier', 'P@ssw0rd!12345', 'xavier@example.com'),
  ('yara', 'Secure12345!$', 'yara@example.com');





INSERT INTO project (title, description, theme, archived)
VALUES
  ('Research and Development', 'In-depth research and product development', 'Technology', false),
  ('Community Outreach Initiative', 'Engage with local communities and create positive impact', 'Community', false),
  ('Environmental Sustainability', 'Promoting eco-friendly practices and sustainability', 'Environment', false),
  ('Artistic Expression Showcase', 'Showcasing diverse forms of art and creativity', 'Art and Culture', false),
  ('Health and Wellness Campaign', 'Promoting healthy lifestyles and well-being', 'Health', false),
  ('Educational Innovation', 'Revolutionizing education for the digital age', 'Education', false),
  ('Cultural Heritage Preservation', 'Preserving and celebrating cultural heritage', 'Heritage', false),
  ('Business Expansion Strategy', 'Developing strategies for business growth', 'Business', false),
  ('Green Energy Revolution', 'Transitioning to clean and renewable energy sources', 'Energy', false),
  ('Music Festival Planning', 'Organizing a grand music festival event', 'Music', false),
  ('Urban Development Project', 'Transforming urban areas for the future', 'Urban Planning', false),
  ('Humanitarian Aid Mission', 'Providing aid to those in need worldwide', 'Humanitarian', false),
  ('Sports and Fitness Initiative', 'Promoting sports and fitness in the community', 'Sports', false),
  ('Scientific Research Exploration', 'Exploring the frontiers of scientific knowledge', 'Science', false),
  ('Culinary Delights Adventure', 'Discovering and savoring world cuisines', 'Food and Dining', true);

   -- Marked as archived


  INSERT INTO task (priority, content, isCompleted, dateCreation, deadLine, title, id_project)
VALUES
  -- Tasks for 'Research and Development' project (Project ID: 1)
  ( 'High', 'Conduct market research', false, '2023-10-15', '2023-11-15', 'Market Research', 1),
  ( 'Medium', 'Develop a prototype', false, '2023-10-16', '2023-11-30', 'Prototype Development', 1),
  ( 'Low', 'Prepare project documentation', false, '2023-10-20', '2023-12-15', 'Documentation Preparation', 1), -- Marked as completed

  -- Tasks for 'Community Outreach Initiative' project (Project ID: 2)
  ( 'Medium', 'Organize community event', false, '2023-10-18', '2023-11-25', 'Community Event Organization', 2),
  ( 'High', 'Create promotional materials', false, '2023-10-19', '2023-11-10', 'Promotional Materials Creation', 2),
  ( 'Low', 'Engage with local volunteers', false, '2023-10-22', '2023-11-30', 'Volunteer Engagement', 2),

  -- Tasks for 'Environmental Sustainability' project (Project ID: 3)
  ( 'Low', 'Conduct environmental audit', false, '2023-10-15', '2023-11-30', 'Environmental Audit', 3),
  ( 'High', 'Develop eco-friendly products', false, '2023-10-17', '2023-12-10', 'Product Development', 3),
  ( 'Medium', 'Create awareness campaigns', false, '2023-10-20', '2023-11-20', 'Awareness Campaigns', 3),

  -- Tasks for 'Artistic Expression Showcase' project (Project ID: 4)
  ( 'Medium', 'Curate art exhibitions', false, '2023-10-19', '2023-12-01', 'Art Exhibition Curation', 4),
  ( 'High', 'Coordinate performing artists', false, '2023-10-22', '2023-11-30', 'Artist Coordination', 4),
  ( 'Low', 'Design event posters', false, '2023-10-25', '2023-12-10', 'Poster Design', 4),

  -- Tasks for 'Health and Wellness Campaign' project (Project ID: 5)
  ( 'Low', 'Organize fitness classes', false, '2023-10-17', '2023-11-30', 'Fitness Class Organization', 5),
  ( 'High', 'Create health-related content', false, '2023-10-20', '2023-11-25', 'Content Creation', 5),
  ( 'Medium', 'Promote mental well-being', false, '2023-10-23', '2023-12-15', 'Mental Health Promotion', 5),

  -- Tasks for 'Educational Innovation' project (Project ID: 6)
  ( 'Medium', 'Develop online learning platform', false, '2023-10-16', '2023-12-05', 'Learning Platform Development', 6),
  ( 'Low', 'Design educational content', false, '2023-10-19', '2023-11-30', 'Content Design', 6),
  ( 'High', 'Conduct teacher training sessions', false, '2023-10-22', '2023-12-10', 'Teacher Training', 6),

  -- Tasks for 'Culinary Delights Adventure' project (Project ID: 15)
  ( 'Medium', 'Plan international cuisine showcase', false, '2023-10-16', '2023-12-05', 'Cuisine Showcase Planning', 15),
  ( 'High', 'Coordinate with local chefs', false, '2023-10-18', '2023-11-25', 'Chef Coordination', 15),
  ( 'Low', 'Select event venues', false, '2023-10-20', '2023-12-15', 'Venue Selection', 15);



-- Insert data into comment table with 10 comments for selected tasks
INSERT INTO comment (content, date)
VALUES
  ('Great progress on the market research!', '2023-10-20 09:15:00'),
  ('Looking forward to the prototype development.', '2023-10-22 14:30:00'),
  ('The volunteer engagement is going well.', '2023-10-25 16:45:00'),
  ('Awesome work on the eco-friendly products!', '2023-10-26 11:20:00'),
  ('Can we schedule a meeting for the project?', '2023-10-27 09:30:00'),
  ('The environmental audit results are promising.', '2023-10-28 12:00:00'),
  ('This art exhibition curation is fantastic!', '2023-10-30 15:55:00'),
  ('We need to finalize the artist coordination.', '2023-10-31 10:10:00'),
  ('The fitness class organization is a hit!', '2023-11-01 14:15:00'),
  ('Looking forward to the learning platform launch.', '2023-11-02 16:40:00');


-- Insert data into likes table with 15 likes for selected comments
INSERT INTO likes (comment_id, user_id)
VALUES
  (1, 1), -- Like for the first comment
  (2, 2), -- Like for the second comment
  (3, 3), -- Like for the third comment
  (4, 4), -- Like for the fourth comment
  (5, 5), -- Like for the fifth comment
  (6, 6), -- Like for the sixth comment
  (7, 7), -- Like for the seventh comment
  (8, 8), -- Like for the eighth comment
  (9, 9), -- Like for the ninth comment
  (10, 10), -- Like for the tenth comment
  (1, 11), -- Like for the first comment from a different user
  (2, 12), -- Like for the second comment from a different user
  (3, 13), -- Like for the third comment from a different user
  (4, 14), -- Like for the fourth comment from a different user
  (5, 15); -- Like for the fifth comment from a different user


INSERT INTO isAdmin (user_id)
VALUES
  (3), 
  (41), 
  (42),
  (43), 
  (44), 
  (45), 
  (46), 
  (47), 
  (48),
  (49), 
  (50); 




INSERT INTO favorite (users_id, project_id)
VALUES
  (1, 1),   
  (2, 2),
  (3, 3),   
  (4, 4),   
  (5, 5), 
  (6, 6),  
  (7, 7),   
  (8, 8),   
  (9, 9),   
  (10, 10), 
  (11, 1), 
  (12, 2),  
  (13, 3),  
  (14, 4),  
  (15, 5);  



INSERT INTO notification (description)
VALUES
  ('New project leader assigned.'),               
  ('You have been expelled from the project.'),    
  ('Project deleted by the administrator.'),       
  ('New member joined the project.'),               
  ('Task assigned to you.'),                       
  ('Task completed successfully.'),               
  ('You received a like on your comment.'),         
  ('You received a response on your comment.');     



INSERT INTO is_member (id_user, id_project)
VALUES
  (11, 1),  
  (12, 2),  
  (13, 3),  
  (14, 4),  
  (15, 5),  
  (16, 6), 
  (17, 7), 
  (18, 8),  
  (19, 9), 
  (20, 10), 
  (21, 1),  
  (22, 2),  
  (23, 3), 
  (24, 4),  
  (25, 5),  
  (26, 6),  
  (27, 7),  
  (28, 8),  
  (29, 9), 
  (30, 10), 
  (31, 1), 
  (32, 2),  
  (33, 3),
  (34, 4),  
  (35, 5),  
  (36, 6), 
  (37, 7), 
  (38, 8),
  (39, 9),  
  (40, 10); 
  



INSERT INTO is_leader (id_user, id_project)
VALUES
  (11, 1),  
  (12, 2), 
  (13, 3),  
  (14, 4),  
  (15, 5),  
  (16, 6), 
  (17, 7),
  (18, 8), 
  (19, 9), 
  (20, 15);
  


INSERT INTO taskowner (id_user, id_task)
VALUES
  (11, 1),  
  (12, 2), 
  (13, 3),  
  (14, 4),  
  (15, 5), 
  (16, 6),  
  (17, 7),  
  (18, 8),
  (19, 9),
  (20, 10), 
  (11, 11),
  (12, 12), 
  (13, 13), 
  (14, 14), 
  (15, 15), 
  (16, 16), 
  (17, 17), 
  (18, 18), 
  (19, 19),
  (20, 20), 
  (11, 21); 



INSERT INTO assigned (id_user, id_task)
VALUES
  (11, 1),  
  (12, 2), 
  (13, 3),  
  (14, 4),  
  (15, 5),  
  (16, 6),  -- User 16 is assigned to Task 6
  (17, 7),  -- User 17 is assigned to Task 7
  (18, 8),  -- User 18 is assigned to Task 8
  (19, 9),  -- User 19 is assigned to Task 9
  (20, 10), -- User 20 is assigned to Task 10
  (11, 11), -- User 11 is assigned to Task 11
  (12, 12), -- User 12 is assigned to Task 12
  (13, 13), -- User 13 is assigned to Task 13
  (14, 14), -- User 14 is assigned to Task 14
  (15, 15), -- User 15 is assigned to Task 15
  (16, 16), -- User 16 is assigned to Task 16
  (17, 17), -- User 17 is assigned to Task 17
  (18, 18), -- User 18 is assigned to Task 18
  (19, 19), -- User 19 is assigned to Task 19
  (20, 20), -- User 20 is assigned to Task 20
  (11, 21); -- User 11 is assigned to Task 21


-- Insert data into commentOwner table with comment-owner associations
INSERT INTO commentowner (id_user, id_comment)
VALUES
  (11, 1),  -- User 11 is the owner of Comment 1
  (12, 2),  -- User 12 is the owner of Comment 2
  (13, 3),  -- User 13 is the owner of Comment 3
  (14, 4),  -- User 14 is the owner of Comment 4
  (15, 5),  -- User 15 is the owner of Comment 5
  (16, 6),  -- User 16 is the owner of Comment 6
  (17, 7),  -- User 17 is the owner of Comment 7
  (18, 8),  -- User 18 is the owner of Comment 8
  (19, 9),  -- User 19 is the owner of Comment 9
  (20, 10); -- User 20 is the owner of Comment 10
  
  
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
        NEW.search = (SELECT setweight(to_tsvector(users.name), 'A') FROM users WHERE NEW.id=users.id);
    ELSIF TG_OP = 'UPDATE' AND (NEW.username <> OLD.username) THEN
        NEW.search = (SELECT setweight(to_tsvector(users.name), 'A') FROM users WHERE NEW.id=users.id);
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
DROP TRIGGER IF EXISTS add_favorite ON favorite;



CREATE OR REPLACE FUNCTION add_favorite() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((SELECT COUNT(*)
        FROM favorite
        WHERE NEW.users_id = users_id)>=5)
        THEN
            RAISE EXCEPTION 'A user cant have more than 5 favorite projects';
    END IF;
        RETURN NEW;
    END;
$BODY$
LANGUAGE plpgsql;
CREATE TRIGGER add_favorite
    BEFORE INSERT OR UPDATE ON favorite
    FOR EACH ROW
EXECUTE PROCEDURE add_favorite();

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



