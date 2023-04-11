CREATE TABLE users (
	userID			INTEGER NOT NULL AUTO_INCREMENT,
	email			VARCHAR(350) NOT NULL,
	pass			VARCHAR(100) NOT NULL,
	displayName		VARCHAR(25) NOT NULL,
	profileImage	LONGBLOB DEFAULT NULL,
	imageType		VARCHAR(35) DEFAULT NULL, 
	userBio			varchar(200) DEFAULT NULL,
	isAdmin			BOOLEAN DEFAULT FALSE,
	dateCreated		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (userID)
);
CREATE TABLE slices (
	sliceID			INTEGER NOT NULL AUTO_INCREMENT,
	sliceName		VARCHAR(25) NOT NULL,
	sliceImage		LONGBLOB DEFAULT NULL,
	imageType		VARCHAR(35) DEFAULT NULL, 
	PRIMARY KEY (sliceID)
);
CREATE TABLE posts (
	postID			INTEGER NOT NULL AUTO_INCREMENT,
	title			VARCHAR(25) NOT NULL,
	content			VARCHAR(8000) DEFAULT '',
	images			LONGBLOB DEFAULT NULL,
	imageType		VARCHAR(35) DEFAULT NULL, 
	votes			INTEGER NOT NULL DEFAULT 0,
	dateCreated		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	sliceID			INTEGER NOT NULL,
	userID			INTEGER NOT NULL,
	PRIMARY KEY (postID),
	FOREIGN KEY (sliceID) REFERENCES slices(sliceID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE comments (
	commentID		INTEGER NOT NULL AUTO_INCREMENT,
	content			VARCHAR(8000) NOT NULL,
	votes			INTEGER NOT NULL DEFAULT 0,
	dateCreated		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	postID			INTEGER NOT NULL,
	commentParent	INTEGER,
	userID			INTEGER NOT NULL,
	PRIMARY KEY (commentID),
	FOREIGN KEY (postID) REFERENCES posts(postID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (commentParent) REFERENCES comments(commentID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE voted (
	votedID			INTEGER NOT NULL AUTO_INCREMENT,
	postID			INTEGER,
	commentID		INTEGER,
	userID			INTEGER NOT NULL,
	PRIMARY KEY (votedID),
	FOREIGN KEY (postID) REFERENCES posts(postID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (commentID) REFERENCES comments(commentID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE isHidden (
	userID			INTEGER NOT NULL,
	postID			INTEGER NOT NULL,
	hiddenBool		BOOLEAN,
	PRIMARY KEY (userID, postID),
	FOREIGN KEY (postID) REFERENCES posts(postID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE notifications (
	notificationID			INTEGER NOT NULL AUTO_INCREMENT,
	commentingUserID		INTEGER NOT NULL,
	notificationUserID		INTEGER NOT NULL,
	postID					INTEGER NOT NULL,
	commentParentID			INTEGER,
	PRIMARY KEY (notificationID),
	FOREIGN KEY (commentingUserID) REFERENCES users(userID) ON DELETE NO ACTION ON UPDATE CASCADE,
	FOREIGN KEY (notificationUserID) REFERENCES users(userID) ON DELETE NO ACTION ON UPDATE CASCADE,
	FOREIGN KEY (postID) REFERENCES posts(postID) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (commentParentID) REFERENCES comments(commentID) ON DELETE CASCADE ON UPDATE CASCADE
);


INSERT INTO users VALUES (-1,"deletedemail","NOONESHOULDEVERUSETHISEVER$!@*#!*!*#!)(*!&!@*9832519823498101283489!(@*#()*!@*$(!%*(&!@(*&!@#(*!@#*8932982140981239048!(*!@#*(!@&(*!@(*!@*","deleted","none.png",DEFAULT,DEFAULT,DEFAULT);

INSERT INTO slices VALUES (DEFAULT,"Sourdough", NULL, NULL);
INSERT INTO slices VALUES (DEFAULT,"Flatbread", NULL, NULL);
INSERT INTO slices VALUES (DEFAULT,"Croissant", NULL, NULL);

INSERT INTO posts VALUES (DEFAULT,"Test Post 1", "", NULL, NULL, DEFAULT, DEFAULT, 1, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 2", "1", NULL, NULL, DEFAULT, DEFAULT, 2, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 3", "1", NULL, NULL, DEFAULT, DEFAULT, 3, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 4", "1", NULL, NULL, DEFAULT, DEFAULT, 2, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 5", "1", NULL, NULL, DEFAULT, DEFAULT, 3, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 6", "1", NULL, NULL, DEFAULT, DEFAULT, 1, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 7", "1", NULL, NULL, DEFAULT, DEFAULT, 3, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 9", "1", NULL, NULL, DEFAULT, DEFAULT, 1, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 10", "1", NULL, NULL, DEFAULT, DEFAULT, 2, -1 );
INSERT INTO posts VALUES (DEFAULT,"Test Post 11", "1", NULL, NULL, DEFAULT, DEFAULT, 3, -1 );

INSERT INTO comments VALUES 
(DEFAULT, "1", DEFAULT, DEFAULT, 1, NULL, -1),
(DEFAULT, "2", DEFAULT, DEFAULT, 1, NULL, -1),
(DEFAULT, "3", DEFAULT, DEFAULT, 1, NULL, -1),
(DEFAULT, "1.1", DEFAULT, DEFAULT, 1, 1, -1),
(DEFAULT, "1.2", DEFAULT, DEFAULT, 1, 1, -1),
(DEFAULT, "1.3", DEFAULT, DEFAULT, 1, 1, -1),
(DEFAULT, "2.1", DEFAULT, DEFAULT, 1, 2, -1),
(DEFAULT, "2.2", DEFAULT, DEFAULT, 1, 2, -1),
(DEFAULT, "2.3", DEFAULT, DEFAULT, 1, 2, -1),
(DEFAULT, "3.1", DEFAULT, DEFAULT, 1, 3, -1),
(DEFAULT, "3.2", DEFAULT, DEFAULT, 1, 3, -1),
(DEFAULT, "3.3", DEFAULT, DEFAULT, 1, 3, -1),
(DEFAULT, "1.1.1", DEFAULT, DEFAULT, 1, 4, -1),
(DEFAULT, "2.2.1", DEFAULT, DEFAULT, 1, 8, -1),
(DEFAULT, "3.3.1", DEFAULT, DEFAULT, 1, 12, -1);