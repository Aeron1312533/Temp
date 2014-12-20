CREATE DATABASE strateg;
USE strateg; 

CREATE TABLE IF NOT EXISTS `problem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nazov` varchar(150) NOT NULL,
  `popis` text NULL,
  `autor` varchar(50) NULL,
  `zdroj` text NULL,
  `subjektivny` BOOL NOT NULL DEFAULT 1,
  CONSTRAINT pk_id_problem PRIMARY KEY (id),
  CONSTRAINT uc_nazov_problem UNIQUE (nazov)
);

CREATE TABLE IF NOT EXISTS `analyza` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nazov` varchar(150) NOT NULL,
  `popis` text NULL,
  `autor` varchar(50) NULL,
  CONSTRAINT pk_id_analyza PRIMARY KEY (id),
  CONSTRAINT uc_nazov_analyza UNIQUE (nazov)
);

CREATE TABLE IF NOT EXISTS `navrh` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nazov` varchar(150) NOT NULL,
  `popis` text NULL,
  `autor` varchar(50) NULL,
  CONSTRAINT pk_id_navrh PRIMARY KEY (id),
  CONSTRAINT uc_nazov_navrh UNIQUE (nazov)
);

CREATE TABLE IF NOT EXISTS `problem_analyza` (
  `id_problem` int(10) unsigned NOT NULL,
  `id_analyza` int(10) unsigned NOT NULL,
  `popis` text NULL,
  CONSTRAINT pk_id_problem_analyza PRIMARY KEY (id_problem, id_analyza),
  CONSTRAINT fk_id_problem_problem_analyza FOREIGN KEY (id_problem)
  REFERENCES problem(id) ON DELETE CASCADE,
  CONSTRAINT fk_id_analyza_problem_analyza FOREIGN KEY (id_analyza)
  REFERENCES analyza(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `problem_navrh` (
  `id_problem` int(10) unsigned NOT NULL,
  `id_navrh` int(10) unsigned NOT NULL,
  `popis` text NULL,
  CONSTRAINT pk_id_problem_navrh PRIMARY KEY (id_problem, id_navrh),
  CONSTRAINT fk_id_problem_problem_navrh FOREIGN KEY (id_problem)
  REFERENCES problem(id) ON DELETE CASCADE,
  CONSTRAINT fk_id_navrh_problem_navrh FOREIGN KEY (id_navrh)
  REFERENCES navrh(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `subor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `meno` varchar(150) NOT NULL,
  CONSTRAINT pk_id_subor PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS `objekt_subor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_objekt` int(10) unsigned NOT NULL,
  `id_subor` int(10) unsigned NOT NULL,
  `typ_objekt` varchar(10) NULL,
  CONSTRAINT pk_id_objekt_subor PRIMARY KEY (id),
  CONSTRAINT fk_id_subor_objekt_subor FOREIGN KEY (id_subor)
  REFERENCES subor(id) ON DELETE CASCADE
);