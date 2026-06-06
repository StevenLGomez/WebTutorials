
-- From page 147

INSERT INTO joke
(joketext, jokedate, authorid)
VALUES
("Why did the chicken cross the road? To get to the other side.", "2012-04-01", 1),
("Knock, knock. Who\'s there? Boo !! Boo who, don\'t cry it\'s only a joke!", "2012-04-01", 1),
("Two peanuts were crossing the street.  One was a salted, peanut", "2020-05-01", 2),
("What did the fish say when it swam into a wall?  Dam!", "2020-05-01", 2),
("How many lawyers does it take to change a light bulb? How many can you afford?", "20200903", 3),
("How many lawyers does it take to change a light bulb? Three, One to change it and two to keep interrupting by standing up and shouting OBJECTION", "20200903", 3),
("How many lawyers does it take to change a light bulb? You won't find a lawyer to change a light bulb.  Now, if you're looking FOR a lawyer TO screw a light bulb...", "20200903", 3),
("How many lawyers does it take to change a light bulb? It only takes one to change your light bulb... to his", "20200903", 3),
("How many lawyers does it take to change a light bulb? Heck, you need 250 just to apply for the research grant", "20200903", 3),
("What is the price of rice in China? It\'s free, just pick it yourself.", "2020-05-01", 3);

INSERT INTO author
(name, email)
VALUES
('Kevin Yank', 'thatguy@kevinyank.com'),
('Joan Smith', 'joan@example.com'),
('Steve Gomez', 'steve.gomez.sg79@gmail.com'),
('John Goldfarb', 'john.goldfarb@nowhere.com'),
('Huckelberry Finn', 'huck.finn@hannibal.com');

-- Setting the passwords, per page 285
UPDATE author SET password = MD5('Kevinijdb') WHERE id = 1;
UPDATE author SET password = MD5('Joanijdb') WHERE id = 2;
UPDATE author SET password = MD5('Steveijdb') WHERE id = 3;
UPDATE author SET password = MD5('Johnijdb') WHERE id = 4;
UPDATE author SET password = MD5('Huckijdb') WHERE id = 5;

INSERT INTO category 
(id, name) 
VALUES
(1, 'Knock-knock'),
(2, 'Cross the road'),
(3, 'Lawyers'),
(4, 'Walk the bar');

INSERT INTO jokecategory (jokeid, categoryid)
VALUES
(1, 2),
(2, 1),
(3, 4),
(4, 3);

-- From page 285
INSERT INTO role (id, description) VALUES
    ('Content Editor', 'Add, remove, and edit jokes'),
    ('Account Administrator', 'Add, remove, and edit authors'),
    ('Site Administrator', 'Add, remove, and edit categories');

-- From page 286
INSERT INTO authorrole (authorid, roleid) VALUES
    (1, 'Content Editor'),
    (2, 'Content Editor'),
    (3, 'Content Editor'),
    (4, 'Content Editor'),
    (5, 'Content Editor'),
    (1, 'Account Administrator'),
    (3, 'Site Administrator');

-- From page 46, examples for modifying or deleting a row
-- UPDATE joke SET jokedate WHERE id = "1";
-- DELETE FROM joke WHERE joketext LIKE "%chicken%";

-- Reference page 150, added author. for clarity
-- SELECT joke.id, LEFT"(joketext, 20), author.name, author.email FROM joke INNER JOIN author ON authorid = author.id 

