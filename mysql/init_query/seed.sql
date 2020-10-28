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
	( "Frodo",  "email1@test.com", "hashedpassword" ),
    ( "Sam",    "email2@test.com", "hashedpassword" ),
    ( "Pippin", "email3@test.com", "hashedpassword" ),
    ( "Merry",  "email4@test.com", "hashedpassword" );

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
        INSERT INTO article_tags VALUES ( article_id, tag_id ), ( articleid, (tagid+1)%4+1 );
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
    DECLARE i int DEFAULT 0;
    WHILE i <= 70 DO
		IF (i%7) = 1 THEN
			INSERT INTO images(article_id, content, isthumbnail) VALUES ( i, (i%7)+1, 'https://picsum.photos/400', 1 );
		ELSE
			INSERT INTO images(article_id, content, isthumbnail) VALUES ( i, (i%7)+1, 'https://picsum.photos/400', 0 );
        END IF;
        SET i = i + 1;
    END WHILE;
END//
delimiter ;
CALL insertimagesproc();

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
