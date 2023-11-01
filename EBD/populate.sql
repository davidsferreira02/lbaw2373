SET search_path TO public;

-- Insert data into userss table with 50 users
INSERT INTO userss (username, password, email) VALUES
  ('alice', 'P@ssw0rd1', 'alice@example.com'),
  ('bob', 'Secur3P@ss', 'bob@example.com'),
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


-- Insert data into generic_user table with auto-incremented IDs
INSERT INTO generic_user (name, birthdate, profilePic, isBanned)
VALUES
  ('Alice Smith', '1990-05-15', 'profile1.jpg', false),
  ('Bob Johnson', '1985-09-23', 'profile2.jpg', false),
  ('Charlie Brown', '1988-11-30', 'profile3.jpg', false),
  ('Diana Davis', '1992-03-18', 'profile4.jpg', false),
  ('Edward Wilson', '1991-07-07', 'profile5.jpg', false),
  ('Frank Martin', '1987-12-02', 'profile6.jpg', false),
  ('Grace Taylor', '1989-02-14', 'profile7.jpg', false),
  ('Henry Anderson', '1993-06-20', 'profile8.jpg', false),
  ('Isabel White', '1986-10-10', 'profile9.jpg', false),
  ('Jack Harris', '1990-04-03', 'profile10.jpg', false),
  ('Kate Wilson', '1985-08-29', 'profile11.jpg', false),
  ('Liam Brown', '1988-05-16', 'profile12.jpg', false),
  ('Mary Jackson', '1992-11-08', 'profile13.jpg', false),
  ('Nathan Davis', '1991-09-07', 'profile14.jpg', false),
  ('Olivia Taylor', '1987-03-21', 'profile15.jpg', false),
  ('Peter Martinez', '1994-07-15', 'profile16.jpg', false),
  ('Quinn Rodriguez', '1986-12-10', 'profile17.jpg', false),
  ('Ryan Lee', '1990-01-25', 'profile18.jpg', false),
  ('Sophia Johnson', '1989-04-13', 'profile19.jpg', false),
  ('Thomas Smith', '1988-08-07', 'profile20.jpg', false),
  ('Violet Wilson', '1993-05-12', 'profile21.jpg', false),
  ('William Brown', '1992-09-28', 'profile22.jpg', false),
  ('Xander Lee', '1987-07-04', 'profile23.jpg', false),
  ('Yasmine Davis', '1991-06-09', 'profile24.jpg', false),
  ('Zane Martin', '1985-12-30', 'profile25.jpg', false),
  ('Aurora Jackson', '1990-10-18', 'profile26.jpg', false),
  ('Beckett Taylor', '1986-04-27', 'profile27.jpg', false),
  ('Clara Anderson', '1993-02-03', 'profile28.jpg', false),
  ('Dexter Smith', '1988-11-01', 'profile29.jpg', false),
  ('Ella Wilson', '1989-07-22', 'profile30.jpg', false),
  ('Finn Brown', '1992-03-29', 'profile31.jpg', false),
  ('Gabriella Harris', '1994-06-05', 'profile32.jpg', true), -- Marked as banned
  ('Hudson Johnson', '1987-08-14', 'profile33.jpg', false),
  ('Isla Taylor', '1990-11-26', 'profile34.jpg', false),
  ('Jaxon Davis', '1988-09-16', 'profile35.jpg', false),
  ('Kayla Lee', '1986-05-08', 'profile36.jpg', false),
  ('Luca Martin', '1991-02-27', 'profile37.jpg', false),
  ('Mia Rodriguez', '1989-04-19', 'profile38.jpg', false),
  ('Noah Smith', '1985-06-28', 'profile39.jpg', false),
  ('Oliver Johnson', '1992-08-11', 'profile40.jpg', false);



-- Insert data into projectt table with 15 projects (one archived)
INSERT INTO projectt (title, description, theme, archived)
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
  ('Culinary Delights Adventure', 'Discovering and savoring world cuisines', 'Food and Dining', true); -- Marked as archived


-- Insert data into task table with 21 tasks for selected projects
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
INSERT INTO likes (comment_id, generic_user_id)
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


-- Insert data into isAdmin table with 15 different admin-user associations
INSERT INTO isAdmin (user_id)
VALUES
  (40), -- User 40 is an admin
  (41), -- User 41 is an admin
  (42), -- User 42 is an admin
  (43), -- User 43 is an admin
  (44), -- User 44 is an admin
  (45), -- User 45 is an admin
  (46), -- User 46 is an admin
  (47), -- User 47 is an admin
  (48), -- User 48 is an admin
  (49), -- User 49 is an admin
  (50); -- User 50 is an admin



-- Insert data into favorite table with 15 favorite project associations (generic_user_id, project_id)
INSERT INTO favorite (generic_user_id, project_id)
VALUES
  (1, 1),   -- First user's favorite project
  (2, 2),   -- Second user's favorite project
  (3, 3),   -- Third user's favorite project
  (4, 4),   -- Fourth user's favorite project
  (5, 5),   -- Fifth user's favorite project
  (6, 6),   -- Sixth user's favorite project
  (7, 7),   -- Seventh user's favorite project
  (8, 8),   -- Eighth user's favorite project
  (9, 9),   -- Ninth user's favorite project
  (10, 10), -- Tenth user's favorite project
  (11, 1),  -- First user's another favorite project
  (12, 2),  -- Second user's another favorite project
  (13, 3),  -- Third user's another favorite project
  (14, 4),  -- Fourth user's another favorite project
  (15, 5);  -- Fifth user's another favorite project


-- Insert data into the notification table with 10 notifications
INSERT INTO notification (description)
VALUES
  ('New project leader assigned.'),                 -- 1 project
  ('You have been expelled from the project.'),     -- 2 project
  ('Project deleted by the administrator.'),        -- 3 project
  ('New member joined the project.'),               -- 4 project
  ('Task assigned to you.'),                        -- 5 task
  ('Task completed successfully.'),                 -- 6 task
  ('You received a like on your comment.'),         -- 7 comment
  ('You received a response on your comment.');     -- 8 comment


-- Insert data into isMember table with 40 user-project associations (excluding leaders)
INSERT INTO isMember (id_user, id_project)
VALUES
  (11, 1),  -- User 11 is a member of Project 1
  (12, 2),  -- User 12 is a member of Project 2
  (13, 3),  -- User 13 is a member of Project 3
  (14, 4),  -- User 14 is a member of Project 4
  (15, 5),  -- User 15 is a member of Project 5
  (16, 6),  -- User 16 is a member of Project 6
  (17, 7),  -- User 17 is a member of Project 7
  (18, 8),  -- User 18 is a member of Project 8
  (19, 9),  -- User 19 is a member of Project 9
  (20, 10), -- User 20 is a member of Project 10
  (21, 1),  -- User 21 is a member of Project 1
  (22, 2),  -- User 22 is a member of Project 2
  (23, 3),  -- User 23 is a member of Project 3
  (24, 4),  -- User 24 is a member of Project 4
  (25, 5),  -- User 25 is a member of Project 5
  (26, 6),  -- User 26 is a member of Project 6
  (27, 7),  -- User 27 is a member of Project 7
  (28, 8),  -- User 28 is a member of Project 8
  (29, 9),  -- User 29 is a member of Project 9
  (30, 10), -- User 30 is a member of Project 10
  (31, 1),  -- User 31 is a member of Project 1
  (32, 2),  -- User 32 is a member of Project 2
  (33, 3),  -- User 33 is a member of Project 3
  (34, 4),  -- User 34 is a member of Project 4
  (35, 5),  -- User 35 is a member of Project 5
  (36, 6),  -- User 36 is a member of Project 6
  (37, 7),  -- User 37 is a member of Project 7
  (38, 8),  -- User 38 is a member of Project 8
  (39, 9),  -- User 39 is a member of Project 9
  (40, 10); -- User 40 is a member of Project 10


-- Insert data into isLeader table with one leader per project
INSERT INTO isLeader (id_user, id_project)
VALUES
  (11, 1),  -- User 11 is the leader of Project 1
  (12, 2),  -- User 12 is the leader of Project 2
  (13, 3),  -- User 13 is the leader of Project 3
  (14, 4),  -- User 14 is the leader of Project 4
  (15, 5),  -- User 15 is the leader of Project 5
  (16, 6),  -- User 16 is the leader of Project 6
  (17, 7),  -- User 17 is the leader of Project 7
  (18, 8),  -- User 18 is the leader of Project 8
  (19, 9),  -- User 19 is the leader of Project 9
  (20, 15); -- User 20 is the leader of Project 15


-- Insert data into taskOwner table with task-owner associations
INSERT INTO taskOwner (id_user, id_task)
VALUES
  (11, 1),  -- User 11 is the owner of Task 1
  (12, 2),  -- User 12 is the owner of Task 2
  (13, 3),  -- User 13 is the owner of Task 3
  (14, 4),  -- User 14 is the owner of Task 4
  (15, 5),  -- User 15 is the owner of Task 5
  (16, 6),  -- User 16 is the owner of Task 6
  (17, 7),  -- User 17 is the owner of Task 7
  (18, 8),  -- User 18 is the owner of Task 8
  (19, 9),  -- User 19 is the owner of Task 9
  (20, 10), -- User 20 is the owner of Task 10
  (11, 11), -- User 11 is the owner of Task 11
  (12, 12), -- User 12 is the owner of Task 12
  (13, 13), -- User 13 is the owner of Task 13
  (14, 14), -- User 14 is the owner of Task 14
  (15, 15), -- User 15 is the owner of Task 15
  (16, 16), -- User 16 is the owner of Task 16
  (17, 17), -- User 17 is the owner of Task 17
  (18, 18), -- User 18 is the owner of Task 18
  (19, 19), -- User 19 is the owner of Task 19
  (20, 20), -- User 20 is the owner of Task 20
  (11, 21); -- User 11 is the owner of Task 21


-- Insert data into assigned table with assigned-user associations
INSERT INTO assigned (id_user, id_task)
VALUES
  (11, 1),  -- User 11 is assigned to Task 1
  (12, 2),  -- User 12 is assigned to Task 2
  (13, 3),  -- User 13 is assigned to Task 3
  (14, 4),  -- User 14 is assigned to Task 4
  (15, 5),  -- User 15 is assigned to Task 5
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
INSERT INTO commentOwner (id_user, id_comment)
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
