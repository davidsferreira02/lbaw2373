SET search_path TO public;


-- TRAN01
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

INSERT INTO userss(username, password, email) VALUES ('poppy', 'poppy123', 'poppy@example.com');
  

INSERT INTO generic_user(name, birthdate, profilePic, isBanned)
VALUES ('Poppy Deyes', '2023-10-22', 'profile60.jpg', false);

END TRANSACTION;
COMMIT;



--TRAN02
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

INSERT INTO task(priority, content, isCompleted, dateCreation, deadLine, title, id_project)
VALUES ('High', 'Conduct market research', false, '2023-10-22', '2023-11-30', 'Market Research', 3);
  

INSERT INTO assigned(id_user, id_task)
VALUES (20, 15);

END TRANSACTION;
COMMIT;
