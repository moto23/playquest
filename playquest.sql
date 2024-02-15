create database playquest;

use playquest;

create table category(
	category_id int auto_increment primary key,
    category_name varchar(50)
);

create table championship(
	champ_id int auto_increment primary key,
    champ_name varchar(50),
    category_id int,
    start_date DATE,
    end_date DATE,
    start_time TIME,
    end_time TIME,
    foreign key(category_id) references category(category_id)
);

create table user(
	user_id int auto_increment primary key,
    user_name varchar(50),
    user_qualification varchar(50),
    user_key varchar(50),
    user_year int,
    phone_no varchar(12),
    first_login DATE,
    recent_login DATE
);

create table game_mode(
	mode_id int auto_increment primary key,
    mode_name varchar(50),
    tot_coins int,
    description text,
    no_of_question int,
    time_minutes int,
    champ_id int,
    user_id int,
    user_qualification varchar(50),
    foreign key(champ_id) references championship(champ_id),
	foreign key(user_id) references user(user_id)
);



create table label(
	label_id int auto_increment primary key,
    label_name varchar(50)
);

create table participated(
    user_id int,
    mode_id int,
    foreign key (user_id) references user(user_id),
    foreign key (mode_id) references game_mode(mode_id)
);

create table question(
	question_id int auto_increment primary key,
    label_id int,
    question_text text,
    question_image longblob,
    option1_text text,
    option2_text text,
    option3_text text,
    option4_text text,
	option1_img longblob,
    option2_img longblob,
    option3_img longblob,
    option4_img longblob,
	foreign key (label_id) references label(label_id)
);

create table answer(
    question_id int,
    correct_answer int,
    foreign key (question_id) references question(question_id)
);

create table chosen_questions(
    question_id int, 
    mode_id int, 
    foreign key (question_id) references question(question_id),
    foreign key (mode_id) references game_mode(mode_id)
);

create table user_points(
	user_id int,
    mode_id int,
    points int,
    reward varchar(50),
    foreign key (user_id) references user(user_id),
    foreign key (mode_id) references game_mode(mode_id)
);

create table wrong_question(
	user_id int,
    question_id int,
    foreign key (user_id) references user(user_id),
    foreign key (question_id) references question(question_id),
);


