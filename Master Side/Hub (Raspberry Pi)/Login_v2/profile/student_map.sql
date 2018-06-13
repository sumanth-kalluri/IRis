USE whiz;
CREATE TABLE student_map(
  u_id varchar(100) COLLATE ascii_general_ci,
  roll int(2) NOT NULL AUTO_INCREMENT,
  mentor_id varchar(100) COLLATE ascii_general_ci,
  first_name varchar(100) COLLATE ascii_general_ci,
  last_name varchar(100) COLLATE ascii_general_ci,
  remarks varchar(100) COLLATE ascii_general_ci,
  class int(2),
  PRIMARY KEY(class, roll)
)ENGINE=MyISAM;
