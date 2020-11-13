CREATE TABLE user(
    user_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_name TINYTEXT NOT NULL,
    user_uid TINYTEXT NOT NULL,
    user_email TEXT NOT NULL,
    user_pwd TEXT NOT NULL,
    user_pic_id INT NOT NULL,
	user_title TINYTEXT,
    user_txt TEXT
);

CREATE TABLE user_link(
    user_id INT NOT NULL,
    link_id INT NOT NULL
);

CREATE TABLE link(
    link_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    link_url TINYTEXT
);

CREATE TABLE user_media(
    user_id INT NOT NULL,
    media_id INT NOT NULL
);


CREATE TABLE project(
    project_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    project_name TINYTEXT NOT NULL,
    project_txt TEXT NOT NULL,
	project_link TINYTEXT,
    category_id INT NOT NULL,
    type_id INT,
	user_id INT NOT NULL
);

CREATE TABLE project_media(
    project_id INT NOT NULL,
    media_id INT NOT NULL
);

CREATE TABLE media(
    media_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    media_type INT,
    media_path TINYTEXT NOT NULL
);

CREATE TABLE project_tag(
    project_id INT NOT NULL,
    tag_id INT NOT NULL
);

CREATE TABLE tag(
    tag_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    tag_name TINYTEXT NOT NULL
);

CREATE TABLE category(
    category_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    category_name TINYTEXT NOT NULL
);

CREATE TABLE type(
    type_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    type_name TINYTEXT NOT NULL
);



/* TEST MODIFICATIONS */

INSERT INTO `tag`(`tag_name`) VALUES ('tag 1');
INSERT INTO `tag`(`tag_name`) VALUES ('tag 2');

INSERT INTO `project`(`project_name`, `project_txt`, `project_link`, `category_id`, `type_id`, `user_id`) VALUES ('project test', 'project text', 'http://lesitedelabiere.nicofilizzola.com/', '0', '0', '0');
INSERT INTO `project`(`project_name`, `project_txt`, `project_link`, `category_id`, `type_id`, `user_id`) VALUES ('project test 2', 'project text 2', 'https://www.google.com/', '1', '1', '1');

INSERT INTO `media`(`media_type`, `media_path`) VALUES (0, 'resources/upload/img/1.jpg');
INSERT INTO `media`(`media_type`, `media_path`) VALUES (0, 'resources/upload/img/2.jpg');

INSERT INTO `project_media`(`project_id`, `media_id`) VALUES (0, 0);
INSERT INTO `project_media`(`project_id`, `media_id`) VALUES (1, 1);

INSERT INTO `user`(`user_name`, `user_uid`, `user_email`, `user_pwd`, `user_pic_id`, `user_title`, `user_txt`) VALUES ('Nicolas Filizzola', 'Admin', 'admin@admin.com', 'test', '0', 'MonFolio Founder', 'Just chilling');

INSERT INTO `project_tag`(`project_id`, `tag_id`) VALUES (0, 0);
INSERT INTO `project_tag`(`project_id`, `tag_id`) VALUES (0, 1);
