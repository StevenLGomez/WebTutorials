
-- From page 147

-- Table: joke
DROP TABLE IF EXISTS joke;
CREATE TABLE joke (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    joketext TEXT,
    jokedate DATE NOT NULL,
    authorid INT
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;
-- --------------------------------------------------------

-- Table: author
DROP TABLE IF EXISTS author;
CREATE TABLE author (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255)
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;

-- NOTE author table extended for password support (page 283)
--      These modifications were entered directly into phpMyAdmin
ALTER TABLE author ADD UNIQUE (email);
ALTER TABLE author ADD COLUMN password CHAR(32);

-- p285 example of setting password using MD5
-- NOTE single quotes were necessary in all cases in phpMyAdmin
--      but although the format warning wasn't shown, still failed.
UPDATE 'author' SET 'password' = MD5('passwordijdb') WHERE id = 3;
-- --------------------------------------------------------

-- Table: category
-- This table from page 189
DROP TABLE IF EXISTS category;
CREATE TABLE category (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;
-- --------------------------------------------------------

-- Table: jokecategory
-- This table from page 157
DROP TABLE IF EXISTS jokecategory;
CREATE TABLE jokecategory (
    jokeid INT NOT NULL,
    categoryid INT NOT NULL,
    PRIMARY KEY (jokeid, categoryid)
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;
-- --------------------------------------------------------

-- Table: role
-- This table from page 285
DROP TABLE IF EXISTS role;
CREATE TABLE role (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    description VARCHAR(255)
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;
-- --------------------------------------------------------

-- Table: authorrole
-- This table from page 286
DROP TABLE IF EXISTS authorrole;
CREATE TABLE authorrole (
    authorid INT NOT NULL,
    roleid VARCHAR(255) NOT NULL,
    PRIMARY KEY (authorid, roleid)
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;
-- --------------------------------------------------------

