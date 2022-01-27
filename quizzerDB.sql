create table Questions(
	idQuestion serial primary key,
	idexam int not null,
	question varchar(300) not null,
	questionnumber int not null,
	deleted date,
	constraint IDexa foreign key (idexam) references Exams (idexam)
);

create table Exams(
	idexam serial primary key,
	title varchar(300) not null,
	deleted date
);

create table Student_Exam(
	idstudExam serial primary key,
	idstudent int not null,
	idexam int not null,
	dateexam date not null,
	timeexam time not null,
	score int not null,
	check(score>=0),
	deleted date,
	constraint IDest foreign key (idstudent) references Users (iduser),
	constraint IDexa foreign key (idexam) references Exams (idexam)
);

create table Choices(
	idChoice serial primary key,
	idquest int not null,
	isCorrect int not null,
	choice varchar(300) not null,
	deletedc date,
	constraint IDQ foreign key (idquest) references Questions (idQuestion)
);

create table Users(
	iduser serial primary key,
	nameus varchar(50) not null,
	username varchar(50) not null,
	userpassword varchar(100) not null,
	email varchar(60) not null,
	category varchar(20) not null,
	deleted date
);

delete from choices;

select *
from Users;

select *
from student_exam;


select *
from Exams;

insert into Exams(title)values('PHP knowledge exam');


insert into USERS(nameus,username,userpassword,email,category)values('Gaston','tongas97','$2y$10$qpvhyM/QHiDoNE7uv4otbOOdFaMunMbx9EefVgTm6kk2g1MzrWa4e','tongas@gmail.com','teacher');
insert into USERS(nameus,username,userpassword,email,category)values('Alfred','testuser123','$2y$10$Prnuj22JSFyxWr2LOEf4Iu4Yin9zbkwuTbkqc2B8kqBxp02i3YhEG','test@test.com','student');

select *
from CHOICES;

select *
from QUESTIONS;

insert into QUESTIONS (question,idexam,questionnumber) values ('A  cuantos bits equivalen dos bytes?',1,1);
insert into CHOICES (idquest,iscorrect,choice) values (1,1,'16');
insert into CHOICES (idquest,iscorrect,choice) values (1,0,'8');
insert into CHOICES (idquest,iscorrect,choice) values (1,0,'32');
insert into CHOICES (idquest,iscorrect,choice) values (1,0,'10');

select *
from QUESTIONS inner join CHOISES on QUESTIONS.idquestion=CHOISES.idquest;

insert into QUESTIONS (question,idexam,questionnumber) values ('Quien es el actual guitarrista lider de GNR?',1,2);
insert into CHOICES (idquest,iscorrect,choice) values (2,0,'Richard Fortus');
insert into CHOICES (idquest,iscorrect,choice) values (2,0,'DJ Ashba');
insert into CHOICES (idquest,iscorrect,choice) values (2,1,'Slash');
insert into CHOICES (idquest,iscorrect,choice) values (2,0,'Bumblefoot');

insert into QUESTIONS (question,idexam,questionnumber) values ('Where is James Webb Telescope?',2,1);
insert into CHOICES (idquest,iscorrect,choice) values (3,0,'Richard Fortus');
insert into CHOICES (idquest,iscorrect,choice) values (3,0,'DJ Ashba');
insert into CHOICES (idquest,iscorrect,choice) values (3,1,'Slash');
insert into CHOICES (idquest,iscorrect,choice) values (3,0,'Bumblefoot');

delete from QUESTIONS
where idquestion=4;

select idexam, count(question) as total
from Questions
group by idexam
order by idexam;
