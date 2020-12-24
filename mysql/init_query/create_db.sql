-- DROP DATABASE IF EXISTS backendkenshu;
USE backendkenshu;

DROP TABLE IF EXISTS autologin;
DROP TABLE IF EXISTS article_images;
DROP TABLE IF EXISTS article_tags;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS users;




-- tags table
CREATE TABLE tags(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name NVARCHAR(255) NOT NULL UNIQUE,
    INDEX(name)
);

-- users table 
CREATE TABLE users(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nickname VARCHAR(20) NOT NULL UNIQUE,
	email VARCHAR(255) NOT NULL UNIQUE,
	pass VARCHAR(255) NOT NULL,
    -- isactive SMALLINT,
    INDEX (nickname)
);

-- articles table 
CREATE TABLE articles(
	id                          INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	title                       NVARCHAR(255) NOT NULL,
	content                     TEXT NOT NULL,
	author_id                   INT UNSIGNED NOT NULL,
    created_at                  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_at                 DATETIME DEFAULT NULL,
    published                   TINYINT(1) DEFAULT 1,
    -- published_at             DATETIME DEFAULT NULL,
    FOREIGN KEY (author_id)     REFERENCES users(id),
    -- FULLTEXT (title, content),
    KEY (title)
);

-- images table
CREATE TABLE images(
	id                          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id                     INT UNSIGNED,
	url                         TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- junction table of articles and images
CREATE TABLE article_images(
    article_id                  INT UNSIGNED NOT NULL,
    image_id                    INT UNSIGNED NOT NULL,
    isthumbnail                  TINYINT(1),
    CONSTRAINT PK_articleimage PRIMARY KEY
    (
        article_id,
        image_id
    ),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (image_id) REFERENCES  images(id) ON DELETE CASCADE
);

-- junction table of articles and tags 
CREATE TABLE article_tags(
	article_id INT UNSIGNED NOT NULL,
	tag_id INT UNSIGNED NOT NULL,
	CONSTRAINT PK_articletag PRIMARY KEY
    (
        article_id,
        tag_id
    ),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- autologin table
CREATE TABLE autologin(
    user_id INT UNSIGNED PRIMARY KEY,
    token VARCHAR(255),
    expired_time INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

USE backendkenshu;

-- DELETE FROM article_tags;
-- DELETE FROM tags;
-- DELETE FROM images;
-- DELETE FROM articles;
-- DELETE FROM users;

-- tags seeder
INSERT INTO tags(name)
	VALUES
	( "Hobbiton" ),
	( "Imladris" ),
	( "Mithlond" ),
	( "Khazad-dûm" );

-- users seeder
INSERT INTO users(nickname, email, pass)
	VALUES
	( "Frodo",  "email1@test.com", MD5('hashedpassword') ),
    ( "Sam",    "email2@test.com", MD5('hashedpassword') ),
    ( "Pippin", "email3@test.com", MD5('hashedpassword') ),
    ( "Merry",  "email4@test.com", MD5('hashedpassword') );

-- articles seeder

INSERT INTO articles(title, content, created_at, author_id)
	VALUES
    ( 'MySQL Tutorial',         'DBMS stands for DataBase ...',                         "2020-10-28 09:00:00", 1 ),
    ( '1001 MySQL Tricks',      '1. Never run mysqld ddaaatabaseee as root. 2. ...',    "2020-10-28 18:00:00", 2 ),
    ( 'How To Use MySQL Well',  'After you went through a ...',                         "2020-10-29 09:00:00", 3 ),
    ( 'Optimizing MySQL',       'In this tutorial, we show ...',                        "2020-10-29 18:00:00", 4 ),
    ( 'MySQL vs. YourSQL',      'In the following database comparison ...',             "2020-10-30 09:00:00", 2 ),
    ( 'MySQL vs. OurSQL',       'When configured properly, MySQL ...',                  "2020-10-30 18:00:00", 1 ),
    ( 'MySQL Security',         'Whennn configuuureeed properly, MySQL ...',            "2020-11-02 09:00:00", 3);

-- articles-tags's relation seeder
DROP PROCEDURE IF EXISTS insertarticletag;
delimiter //
CREATE PROCEDURE insertarticletag()
BEGIN
    DECLARE articleid int DEFAULT 1;
    DECLARE tagid int;
    WHILE articleid <= 7 DO
		SET tagid = FLOOR(RAND()*(5-1)+1);
        INSERT INTO article_tags VALUES ( articleid, tagid ), ( articleid, (tagid+1)%4+1 );
        SET articleid = articleid + 1;
    END WHILE;
END//
delimiter ;
CALL insertarticletag();

-- 
DROP PROCEDURE IF EXISTS insertimagesproc;
delimiter //
CREATE PROCEDURE insertimagesproc()
BEGIN
    DECLARE i int DEFAULT 1;
    DECLARE image_id int;
    DECLARE user_id int;
    WHILE i <= 28 DO
        SET user_id = (SELECT author_id FROM articles WHERE id = (i%7)+1);
        SET image_id = FLOOR(RAND()*(100-1)+1);
		IF (i%4) = 1 THEN
			INSERT INTO images(id, user_id, url) VALUES (i, user_id, CONCAT('https://picsum.photos/seed/',image_id,'/1100/500'));
            INSERT INTO article_images(article_id, image_id, isthumbnail) VAlUES ( (i%7)+1, i, 1);
		ELSE
			INSERT INTO images(id, user_id, url) VALUES ( i, user_id, CONCAT('https://picsum.photos/seed/',image_id,'/1100/500'));
            INSERT INTO article_images(article_id, image_id, isthumbnail) VAlUES ( (i%7)+1, i, 0);
        END IF;
        SET i = i + 1;
    END WHILE;
END//
delimiter ;
CALL insertimagesproc();

-- delimiter //
-- CREATE PROCEDURE insertarticleimageproc()
-- BEGIN

-- END//
-- delimiter ;
-- CALL insertarticleimageproc();

/*
SELECT * FROM images
	INNER JOIN articles
	ON images.articleid = articles.articleid
	WHERE isthumbnail = 1;


/*
select * from articles where content like '%database%';

SELECT * FROM articles
        WHERE MATCH (title,content)
        AGAINST ('database' IN NATURAL LANGUAGE MODE);
*/
