DROP TABLE IF EXISTS project;
CREATE TABLE project (
  id         INT(10)     UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(50)          NOT NULL                 COMMENT 'The name of the project',
  created_at DATETIME             NOT NULL                 COMMENT 'The creation time of the inserted project entity',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the translated projects';



DROP TABLE IF EXISTS language;
CREATE TABLE language (
  id   INT(10)     UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(2)           NOT NULL                 COMMENT 'The ISO 639-1 Code of the language',
  name VARCHAR(30)          NOT NULL                 COMMENT 'The english name of the language',
  PRIMARY KEY (id),
  UNIQUE KEY idx_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the usable languages';



INSERT INTO
  language
  (code, name)
VALUES
  ('en', 'English'),
  ('de', 'German'),
  ('fr', 'French'),
  ('es', 'Spanish');


DROP TABLE IF EXISTS user;
CREATE TABLE user (
  id         INT(10)     UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(30)          NOT NULL                 COMMENT 'The name of the user',
  is_enabled TINYINT(1)  UNSIGNED NOT NULL                 COMMENT 'Flag which indicates the status of the user',
  is_admin   TINYINT(1)  UNSIGNED NOT NULL                 COMMENT 'Flag which indicates that the user is an administrator)',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the users who can manage the translations';



DROP TABLE IF EXISTS phrase;
CREATE TABLE phrase (
  id         INT(10)     UNSIGNED NOT NULL AUTO_INCREMENT,
  `key`      VARCHAR(32)          NOT NULL                 COMMENT 'The key of the prase, generated from the phrase',
  phrase     TEXT                 NOT NULL                 COMMENT 'The phrase',
  project_id INT(10)     UNSIGNED NOT NULL                 COMMENT 'The identifier of the project which the phrase belongs',
  created_at DATETIME             NOT NULL                 COMMENT 'The creation time of the inserted phrase entity',
  PRIMARY KEY (id),
  UNIQUE KEY idx_key_project_id (`key`, project_id),
  CONSTRAINT fk_phrase_project_id
    FOREIGN KEY (project_id)
    REFERENCES project (id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the keys of the translatable phrases';



DROP TRIGGER IF EXISTS trigger_ai_generate_key;
DROP TRIGGER IF EXISTS trigger_au_generate_key;

DELIMITER $$

CREATE TRIGGER
  trigger_ai_generate_key
    BEFORE INSERT
    ON phrase
    FOR EACH ROW
BEGIN
    IF (@DISABLE_TRIGGERS IS NULL) THEN
        SET NEW.key = MD5(NEW.phrase);
    END IF;
END$$

CREATE TRIGGER
  trigger_au_generate_key
    BEFORE UPDATE
    ON phrase
    FOR EACH ROW
BEGIN
    IF (@DISABLE_TRIGGERS IS NULL) THEN
        SET NEW.key = MD5(NEW.phrase);
    END IF;
END$$

DELIMITER ;


DROP TABLE IF EXISTS translation;
CREATE TABLE translation (
  id          INT(10)  UNSIGNED NOT NULL AUTO_INCREMENT,
  phrase_id   INT(10)  UNSIGNED NOT NULL                 COMMENT 'The identifier of the translated phrase',
  language_id INT(10)  UNSIGNED NOT NULL                 COMMENT 'The identifier of thetranslations language',
  translation TEXT              NOT NULL                 COMMENT 'The translated phrase',
  created_at  DATETIME          NOT NULL                 COMMENT 'The creation time of the inserted translation entity',
  user_id     INT(10)  UNSIGNED NOT NULL                 COMMENT 'The identifier of the translator',
  PRIMARY KEY (id),
  UNIQUE KEY idx_phrase_language (phrase_id, language_id),
  CONSTRAINT fk_translation_phrase_id
    FOREIGN KEY (phrase_id)
    REFERENCES phrase (id)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  CONSTRAINT fk_translation_language_id
    FOREIGN KEY (language_id)
    REFERENCES language (id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  CONSTRAINT fk_translation_user_id
    FOREIGN KEY (user_id)
    REFERENCES user (id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the supervised and accepted translations of the phrases';


DROP TABLE IF EXISTS translation_temp;
CREATE TABLE translation_temp (
  id          INT(10)  UNSIGNED NOT NULL AUTO_INCREMENT,
  phrase_id   INT(10)  UNSIGNED NOT NULL                 COMMENT 'The identifier of the translated phrase',
  language_id INT(10)  UNSIGNED NOT NULL                 COMMENT 'The identifier of thetranslations language',
  translation TEXT              NOT NULL                 COMMENT 'The translated phrase',
  created_at  DATETIME          NOT NULL                 COMMENT 'The creation time of the inserted translation entity',
  user_id     INT(10)  UNSIGNED NOT NULL                 COMMENT 'The identifier of the translator',
  PRIMARY KEY (id),
  UNIQUE KEY idx_phrase_language (phrase_id, language_id),
  CONSTRAINT fk_translation_temp_phrase_id
    FOREIGN KEY (phrase_id)
    REFERENCES phrase (id)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  CONSTRAINT fk_translation_temp_language_id
    FOREIGN KEY (language_id)
    REFERENCES language (id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  CONSTRAINT fk_translation_temp_user_id
    FOREIGN KEY (user_id)
    REFERENCES user (id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the unsupervised translations of the phrases';