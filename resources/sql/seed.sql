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


INSERT INTO users (name,username, password, email,photo)
VALUES
('david','davidsferreira02','$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W','david@lbaw.com','img1.jpg'),
('alice','alice02', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'alice@example.com','img2.jpg'),
('bob', 'bob02' ,'$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'bob@example.com','img3.jpg'),
('John Doe', 'john_doe', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'john@example.com','img4.jpg'),
('Alice Smith', 'alice_smith', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'alice2@example.com','img5.jpg'),
('Bob Johnson', 'bob_johnson', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'bob2@example.com','img6.jpg'),
('Emma Davis', 'emma_davis', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'emma@example.com','img7.jpg'),
('Michael Wilson', 'michael_wilson', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'michael@example.com','img8.jpg'),
('Emily Brown', 'emily_brown', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'emily@example.com','img9.jpg'),
('William Martinez', 'william_martinez', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'william@example.com','img10.jpg'),
('Olivia Garcia', 'olivia_garcia', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'olivia@example.com','img11.jpg'),
('James Miller', 'james_miller', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'james@example.com','img12.jpg'),
('Sophia Lopez', 'sophia_lopez', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'sophia@example.com','img13.jpg'),
('Alexander Hernandez', 'alexander_hernandez', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'alexander@example.com','img14.jpg'),
('Mia Scott', 'mia_scott', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'mia@example.com','img15.jpg'),
('Benjamin Torres', 'benjamin_torres', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'benjamin@example.com','img16.jpg'),
('Charlotte King', 'charlotte_king', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'charlotte@example.com','img17.jpg'),
('Daniel Perez', 'daniel_perez', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'daniel@example.com','img18.jpg'),
('Amelia Young', 'amelia_young', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'amelia@example.com','img19.jpg'),
('Liam Flores', 'liam_flores', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'liam2@example.com','img20.jpg'),
('Ava Washington', 'ava_washington', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'ava@example.com','img21.jpg'),
('Lucas Clark', 'lucas_clark', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'lucas@example.com','img22.jpg'),
('Harper Adams', 'harper_adams', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'harper@example.com','img23.jpg'),
('Elijah Ramirez', 'elijah_ramirez', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'elijah@example.com','img24.jpg'),
('Avery Hill', 'avery_hill', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'avery@example.com','img25.jpg'),
('Logan Ward', 'logan_ward', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'logan@example.com','img26.jpg'),
('Evelyn Baker', 'evelyn_baker', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'evelyn@example.com','img27.jpg'),
('Jayden Cook', 'jayden_cook', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'jayden@example.com','img28.jpg'),
('Luna Gonzales', 'luna_gonzales', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'luna@example.com','img29.jpg'),
('Liam Murphy', 'liam_murphy', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'liam@example.com','img30.jpg'),
('Aria Hall', 'aria_hall', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'aria@example.com','img31.jpg'),
('Carter Ward', 'carter_ward', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'carter@example.com','img32.jpg'),
('Arianna Russell', 'arianna_russell', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'arianna@example.com','img33.jpg'),
('Lincoln Turner', 'lincoln_turner', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'lincoln@example.com','img34.jpg'),
('Scarlett Ward', 'scarlett_ward', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'scarlett@example.com','img35.jpg'),
('Grayson Mitchell', 'grayson_mitchell', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'grayson@example.com','img36.jpg'),
('Adeline Garcia', 'adeline_garcia', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'adeline@example.com','img37.jpg'),
('Ezra Ward', 'ezra_ward', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'ezra@example.com','img38.jpg'),
('Nova Nelson', 'nova_nelson', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'nov@example.com','img39.jpg'),
('Sebastian Thompson', 'sebastian_thompson', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'sebastian@example.com','img40.jpg'),
('Nova Rodriguez', 'nova_rodriguez', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'nova@example.com','img41.jpg'),
('Levi Powell', 'levi_powell', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'levi@example.com','img42.jpg'),
('Eva Watson', 'eva_watson', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'eva@example.com','img43.jpg'),
('Noah Adams', 'noah_adams', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'noah@example.com','img44.jpg'),
('Isabella Wood', 'isabella_wood', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'isabella@example.com','img45.jpg'),
('James White', 'james_white', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'james2@example.com','img46.jpg'),
('Sophia Harris', 'sophia_harris', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'sophia2@example.com','img47.jpg'),
('Oliver Young', 'oliver_young', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'oliver@example.com','img48.jpg'),
('Amelia King', 'amelia_king', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'amelia2@example.com','img49.jpg'),
('Lucas Baker', 'lucas_baker', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'lucas2@example.com','img50.jpg'),
('Aria Evans', 'aria_evans', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'aria2@example.com','img51.jpg'),
('Logan Parker', 'logan_parker', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'logan2@example.com','img52.jpg'),
('Evelyn Coleman', 'evelyn_coleman', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'evelyn2@example.com','img53.jpg'),
('Carter Wright', 'carter_wright', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'carter2@example.com','img54.jpg'),
('Luna Ross', 'luna_ross', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'luna2@example.com','img55.jpg'),
('Mason Ward', 'mason_ward', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'mason2@example.com','img56.jpg'),
('Harper Peterson', 'harper_peterson', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'harper2@example.com','img57.jpg'),
('Elijah Sanchez', 'elijah_sanchez', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'elijah2@example.com','img58.jpg'),
('Avery Price', 'avery_price', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'avery2@example.com','img59.jpg'),
('Nova Martinez', 'nova_martinez', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'novamar@example.com','img60.jpg'),
('Anonimo', 'anonimo', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'anonimo@example.com','default.jpg');
 
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
('Culinary Recipes App', 'App for sharing and discovering culinary recipes.', 'Culinary'),
('TechSavvy Connect', 'Revolutionizing tech networking for professionals worldwide.', 'Technology'),
('EcoBiz Hub', 'A sustainable e-commerce platform promoting eco-friendly products.', 'E-commerce'),
('VitaHealth Tracker', 'Next-gen health app for personalized wellness monitoring.', 'Health'),
('GreenUtopia Initiative', 'Empowering local communities towards environmental sustainability.', 'Sustainability'),
('ProLink Network', 'Connecting professionals globally across diverse industries.', 'Technology'),
('EduSphere Platform', 'Innovative online learning hub offering diverse courses.', 'Education'),
('FinancePal App', 'Your ultimate finance companion for smart money management.', 'Finance'),
('ArtScape Project', 'Transforming urban spaces into vibrant hubs for artistic expression.', 'Culture'),
('HarmonyBeats', 'Explore a world of music with our dynamic streaming platform.', 'Entertainment'),
('TasteBuds App', 'Discover and share culinary delights from around the globe.', 'Culinary'),
('InnoTech Hub', 'Fostering innovation and collaboration in the tech world.', 'Technology'),
('ShopStream Pro', 'An immersive e-commerce experience redefining online shopping.', 'E-commerce'),
('HealthWave App', 'Revolutionizing healthcare access through cutting-edge tech.', 'Health'),
('EcoRevolution Project', 'Join us in creating a greener and more sustainable future.', 'Sustainability'),
('ProfessionConnect', 'Where professionals meet, collaborate, and grow together.', 'Technology'),
('Learnopia Network', 'Unlimited access to knowledge through our diverse course offerings.', 'Education'),
('CashGenius App', 'Transform the way you manage your finances with ease.', 'Finance'),
('ArtVibe Initiative', 'Bringing art to the heart of urban landscapes for all to enjoy.', 'Culture'),
('SonicHarbor', 'Immerse yourself in an ocean of music through our streaming service.', 'Entertainment'),
('CuisineCraze', 'Embark on a culinary journey exploring flavors from every corner.', 'Culinary');

INSERT INTO task (priority, content, title, id_project, deadLine)
VALUES
('High', 'Develop user authentication module', 'User Authentication', 1, '2023-12-31'),
('Medium', 'Design database schema', 'Database Design', 1, '2023-12-25'),
('High', 'Implement secure payment gateway', 'Payment Gateway Integration', 2, '2023-12-28'),
('Low', 'Create homepage UI mockup', 'Homepage UI Design', 2, '2023-12-20'),
('Medium', 'Develop activity tracking feature', 'Activity Tracking', 3, '2023-12-30'),
('High', 'Organize community cleanup event', 'Community Cleanup', 4, '2023-12-31'),
('Medium', 'Create awareness campaign materials', 'Awareness Campaign', 4, '2023-12-25'),
('High', 'Implement user profile customization', 'Profile Customization', 5, '2023-12-28'),
('Medium', 'Develop course recommendation algorithm', 'Recommendation Algorithm', 6, '2023-12-30'),
('High', 'Integrate expense tracking feature', 'Expense Tracking', 7, '2023-12-31'),
('Low', 'Design logo and branding', 'Logo Design', 7, '2023-12-20'),
('Medium', 'Identify suitable locations for art installations', 'Location Scouting', 8, '2023-12-25'),
('High', 'Implement music recommendation system', 'Recommendation System', 9, '2023-12-31'),
('Low', 'Gather user feedback on current recipe list', 'User Feedback Collection', 10, '2023-12-20'),
('High', 'Develop cooking timer feature', 'Cooking Timer', 10, '2023-12-31'),
('Medium', 'Implement user messaging functionality', 'Messaging Feature', 11, '2023-12-30'),
('High', 'Integrate carbon footprint calculator', 'Carbon Calculator', 12, '2023-12-31'),
('Medium', 'Develop user health data visualization', 'Health Data Visualization', 13, '2023-12-25'),
('High', 'Organize tree-planting drive', 'Tree-Planting Event', 14, '2023-12-31'),
('Low', 'Conduct user survey for feature preferences', 'Feature Survey', 15, '2023-12-20'),
('High', 'Develop certification system for courses', 'Certification System', 16, '2023-12-31'),
('Medium', 'Implement budget planning tool', 'Budget Planning', 17, '2023-12-30'),
('High', 'Coordinate artist workshops', 'Workshop Coordination', 18, '2023-12-31'),
('Medium', 'Enhance music discovery algorithms', 'Discovery Algorithms', 19, '2023-12-25'),
('Low', 'Curate list of popular regional cuisines', 'Cuisine Curation', 20, '2023-12-20'),
('High', 'Implement ingredient substitution feature', 'Ingredient Substitution', 20, '2023-12-31');

INSERT INTO comment (content, id_task, date)
VALUES
('Great progress on the user authentication module.', 1, '2023-12-15 09:30:00'),
('The database schema looks well-structured.', 2, '2023-12-16 10:45:00'),
('Payment gateway integration completed successfully.', 3, '2023-12-17 11:20:00'),
('The homepage UI mockup is impressive!', 4, '2023-12-18 13:00:00'),
('Activity tracking feature development is underway.', 5, '2023-12-19 14:25:00'),
('Community cleanup event planning initiated.', 6, '2023-12-20 15:45:00'),
('Materials for the awareness campaign are being prepared.', 7, '2023-12-21 16:55:00'),
('User profile customization feature progressing smoothly.', 8, '2023-12-22 17:30:00'),
('Course recommendation algorithm development in progress.', 9, '2023-12-23 18:10:00'),
('Expense tracking feature integration almost done.', 10, '2023-12-24 19:00:00');

INSERT into isadmin (user_id)
VALUES
(3),
(5),
(7),
(9),
(11);

INSERT INTO is_leader (id_user, id_project)
VALUES
(1, 1),
(2, 2),
(4, 3),
(6, 4),
(8, 5),
(10, 6),
(12, 7),
(13, 8),
(14, 9),
(15, 10),
(16, 11),
(17, 12),
(18, 13),
(19, 14),
(20, 15),
(21, 16),
(22, 17),
(23, 18),
(24, 19),
(25, 20);

INSERT INTO is_member (id_user, id_project)
VALUES

(1, 1),
(2, 1),
(2,2),
(4, 2),
(4,3),
(6, 2),
(6,4),
(8, 3),
(8,5),
(10, 3),
(10,6),
(12, 4),
(12,7),
(13, 4),
(13,8),
(14, 5),
(14,9),
(15, 5),
(15,10),
(16,11),
(17, 6),
(17,12),
(18, 7),
(18,13),
(19, 8),
(19,14),
(20, 8),
(20,15),
(21, 9),
(21,16),
(22, 9),
(22,17),
(23, 10),
(23,18),
(24, 10),
(24,19),
(25, 11),
(25,20),
(26, 11);

INSERT INTO favorite (project_id, users_id)
VALUES
(1, 1),
(1, 2),
(2, 4),
(2, 6),
(3, 8),
(3, 10),
(4, 12),
(4, 13),
(5, 14),
(5, 15),
(6, 17),
(7, 18),
(8, 19),
(8, 20),
(9, 21);

INSERT INTO likes (comment_id, user_id) VALUES
(1, 1),
(1, 2),
(3, 4),
(5, 6),
(6, 7),
(7, 8),
(9, 10);


INSERT INTO taskowner(id_user, id_task)
VALUES
(1, 1),
(2, 2),
(4, 3),
(6, 4),
(8, 5),
(10, 6),
(12, 7),
(14, 8),
(16, 9),
(18, 10);

INSERT INTO assigned(id_user, id_task)
VALUES
(1, 1),
(2, 2),
(4, 3),
(6, 4),
(8, 5),
(10, 6),
(12, 7),
(14, 8),
(16, 9),
(18, 10);

INSERT INTO commentowner(id_user, id_comment)
VALUES
(1, 1),
(2, 2),
(4, 3),
(6, 4),
(8, 5),
(10, 6),
(12, 7),
(14, 8),
(16, 9),
(18, 10);

INSERT INTO inviteproject (id_user, id_project)
VALUES
(1, 1),
(2, 3),
(4, 2),
(4, 4);


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
/*CREATE OR REPLACE FUNCTION add_new_member_notification() RETURNS TRIGGER AS
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
*/

-- TRIGGER03
/*CREATE OR REPLACE FUNCTION add_deleted_notification() RETURNS TRIGGER AS
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
*/
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



