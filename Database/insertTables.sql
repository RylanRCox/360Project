

INSERT INTO users VALUES 
(-1,"deletedemail","sexysingledaddies","deleted","none.png",DEFAULT,DEFAULT),
(DEFAULT,"BreaditDefault@gmail.com","MattRylan","DefaultUser","random.png",DEFAULT,DEFAULT);

INSERT INTO slices VALUES (DEFAULT,"Sourdough", , null);
INSERT INTO slices VALUES (DEFAULT,"Flatbread", , null);
INSERT INTO slices VALUES (DEFAULT,"Croissant", , null);

INSERT INTO posts VALUES (DEFAULT,"Test Post Please Ignore", "See Title", NULL, NULL, DEFAULT, DEFAULT, 1, 1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 1", "1", NULL, NULL, DEFAULT, DEFAULT, 1, 1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 2", "1", NULL, NULL, DEFAULT, DEFAULT, 2, 2 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 3", "1", NULL, NULL, DEFAULT, DEFAULT, 3, 3 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 4", "1", NULL, NULL, DEFAULT, DEFAULT, 2, 1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 5", "1", NULL, NULL, DEFAULT, DEFAULT, 3, 2 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 6", "1", NULL, NULL, DEFAULT, DEFAULT, 1, 3 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 7", "1", NULL, NULL, DEFAULT, DEFAULT, 3, 1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 9", "1", NULL, NULL, DEFAULT, DEFAULT, 1, 2 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 10", "1", NULL, NULL, DEFAULT, DEFAULT, 2, 3 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 11", "1", NULL, NULL, DEFAULT, DEFAULT, 3, 1 );

INSERT INTO comments VALUES 
(DEFAULT, "tree", DEFAULT, DEFAULT, 1, NULL, 1),
(DEFAULT, "thanks I grew it myself", DEFAULT, DEFAULT, 1, 1, 1),
(DEFAULT, "heyo", DEFAULT, DEFAULT, 1, 2, 1);

INSERT INTO comments VALUES 
(DEFAULT, "1", DEFAULT, DEFAULT, 1, NULL, 1),
(DEFAULT, "2", DEFAULT, DEFAULT, 1, NULL, 1),
(DEFAULT, "3", DEFAULT, DEFAULT, 1, NULL, 1),
(DEFAULT, "1.1", DEFAULT, DEFAULT, 1, 1, 1),
(DEFAULT, "1.2", DEFAULT, DEFAULT, 1, 1, 1),
(DEFAULT, "1.3", DEFAULT, DEFAULT, 1, 1, 1),
(DEFAULT, "2.1", DEFAULT, DEFAULT, 1, 2, 1),
(DEFAULT, "2.2", DEFAULT, DEFAULT, 1, 2, 1),
(DEFAULT, "2.3", DEFAULT, DEFAULT, 1, 2, 1),
(DEFAULT, "3.1", DEFAULT, DEFAULT, 1, 3, 1),
(DEFAULT, "3.2", DEFAULT, DEFAULT, 1, 3, 1),
(DEFAULT, "3.3", DEFAULT, DEFAULT, 1, 3, 1),
(DEFAULT, "1.1.1", DEFAULT, DEFAULT, 1, 4, 1),
(DEFAULT, "2.2.1", DEFAULT, DEFAULT, 1, 8, 1),
(DEFAULT, "3.3.1", DEFAULT, DEFAULT, 1, 12, 1);





DROP TABLE comments;
DROP TABLE posts;
DROP TABLE slices;
DROP TABLE users;

select * FROM comments where user = 1 in

select * from comments where postID = 1 and commentParent = NULL;


select  commentID,
        content,
        commentParent
from    (select * from comments
         order by commentParent, commentID) comments_sorted,
        (select @pv := 1) initialisation
where   find_in_set(commentParent, @pv)
and     length(@pv := concat(@pv, ',', commentID));


DELETE FROM comments;
DELETE FROM posts;
DELETE FROM slices;
DELETE FROM users;


