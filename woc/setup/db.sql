/*********************************************************************
*
* This file is a part of Web Interface to Octave project.
*
*********************************************************************/



-- Enter database name here
-- It is assumed that database is already created and it is empty.
USE your_database_name;



-- You need not to change anything below this line
CREATE TABLE session (
  id varchar(32) NOT NULL,
  userID int NOT NULL DEFAULT 0,
  time int NOT NULL DEFAULT 0,
  PRIMARY KEY  (id)
);
 
CREATE TABLE user (
  id int NOT NULL AUTO_INCREMENT,
  login varchar(32) NOT NULL,
  password varchar(32) NOT NULL,
  lastvisit datetime NOT NULL,
  PRIMARY KEY  (id)
);

