create table utente(
	ute_email varchar(100) primary key,
	ute_password varchar(75),
	ute_passwordTemp int default 1,
	ute_flag int default 1,
	ute_tipo int default 0
);

create table email(
	ema_id int auto_increment primary key,
	ema_messaggio text,
	ema_flag int default 1,
	ema_data timestamp default NOW(),
	ema_oggetto varchar(50),
	ute_email varchar(100),
	foreign key(ute_email) references utente(ute_email)
	ON UPDATE CASCADE
	ON DELETE NO ACTION
);

create table datore(
	dat_id int auto_increment primary key,
	dat_nome varchar(100),
	dat_indirizzo varchar(50),
	dat_domicilio varchar(50),
	dat_telefono varchar(15),
	dat_flag int default 1,
	dat_emailHR varchar(100),
	dat_nomeHR varchar(100),
	dat_telefonoHR varchar (15)
);

create table formatore(
	for_email varchar(100) primary key,
	for_nome varchar(100),
	for_osservazioni text,
	for_telefono varchar(15),
	for_flag int default 1,
	dat_id int,
	foreign key(dat_id) references datore(dat_id)
	ON UPDATE CASCADE
	ON DELETE NO ACTION
);

create table gruppoEmail(
	grue_id int auto_increment primary key,
	grue_nome varchar(50),
	grue_flag int default 1
);

create table gruefor(
	grue_id int,
	for_email varchar(100),
	primary key(grue_id,for_email),
	foreign key(grue_id) references gruppoEmail(grue_id)
	ON UPDATE CASCADE
	ON DELETE NO ACTION,
	foreign key(for_email) references formatore(for_email)
	ON UPDATE CASCADE
	ON DELETE NO ACTION
	
);
create table forema(
	for_email varchar(100),
	ema_id int,
	primary key(for_email,ema_id),
	foreign key(for_email) references formatore(for_email)
	ON UPDATE CASCADE
	ON DELETE NO ACTION,
	foreign key(ema_id) references email(ema_id)
	ON UPDATE CASCADE
	ON DELETE NO ACTION
);
create table gruppoInserimento(
	grui_id int auto_increment primary key,
	grui_nome varchar(10)
);
create table sede(
	sed_id int auto_increment primary key,
	sed_nome varchar(150)
);

create table apprendista(
	app_idContratto varchar(9) not null,
	app_nome varchar(100),
	app_telefono varchar(15),
	app_dataNascita date,
	app_rappresentante varchar(100),
	app_statuto varchar(15),
	app_indirizzo varchar(50),
	app_domicilio varchar(50),
	app_osservazioni text,
	app_professione varchar(150),
	app_flag int default 1,
	app_annoScolastico int,
	app_annoFine int,
	primary key(app_idContratto,app_annoFine,app_annoScolastico),
	app_dataInizio date,
	grui_id int,
	foreign key(grui_id) references gruppoInserimento(grui_id)
	ON UPDATE CASCADE
	ON DELETE NO ACTION,
	sed_id int,
	foreign key(sed_id) references sede(sed_id)
	ON UPDATE CASCADE
	ON DELETE NO ACTION,
	dat_id int,
	foreign key(dat_id) references datore(dat_id)
	ON UPDATE CASCADE
	ON DELETE NO ACTION,
	for_email varchar(100),
	foreign key(for_email) references formatore(for_email)
	ON UPDATE CASCADE
	ON DELETE NO ACTION
);
create table file_(
	fil_id int auto_increment primary key,
	fil_nome varchar(50),
	ute_email varchar(100),
	foreign key(ute_email) references utente(ute_email)
	ON UPDATE CASCADE
	ON DELETE NO ACTION
);
INSERT into utente() values('andrea.lupica@samtrevano.ch', '1a1dc91c907325c69271ddf0c944bc72', 0,1,2);
INSERT INTO `datore` (`dat_id`, `dat_nome`, `dat_indirizzo`, `dat_domicilio`, `dat_telefono`, `dat_flag`, `dat_emailHR`, `dat_nomeHR`, `dat_telefonoHR`) VALUES (1, 'azienda', 'via', 'domicilio', '3292189283', '1', 'ok', 'ok', 'ok')
INSERT INTO `formatore` (`for_email`, `for_nome`, `for_osservazioni`, `for_telefono`, `for_flag`, `dat_id`) VALUES ('andrea.lupica@samtrevano.ch', 'andrea', 'osservazioni', '183948', '1', '1')
INSERT INTO `sede` (`sed_id`, `sed_nome`) VALUES ('1', 'scuola')
INSERT INTO `apprendista` (`app_idContratto`, `app_nome`, `app_telefono`, `app_dataNascita`, `app_rappresentante`, `app_stato`, `app_indirizzo`, `app_domicilio`, `app_osservazioni`, `app_professione`, `app_flag`, `app_annoScolastico`, `app_annoFine`, `app_dataInizio`, `grui_id`, `sed_id`, `dat_id`, `for_email`) VALUES ('2016.1029', 'apprendista1', '1234567890', '2017-05-02 00:00:00', 'papa apprendista 1', 'vivo', 'via delle vie', '1515 casa', 'prova', 'apprendista', '1', '0', '0', '2017-05-01 00:00:00', '1', '1', '1', 'andrea.lupica@samtrevano.ch')
INSERT INTO `apprendista` (`app_idContratto`, `app_nome`, `app_telefono`, `app_dataNascita`, `app_rappresentante`, `app_stato`, `app_indirizzo`, `app_domicilio`, `app_osservazioni`, `app_professione`, `app_flag`, `app_annoScolastico`, `app_annoFine`, `app_dataInizio`, `grui_id`, `sed_id`, `dat_id`, `for_email`) VALUES ('2016.1021', 'apprendista2', '1234567890', '2017-05-02 00:00:00', 'papa apprendista 2', 'vivo', 'via delle vie', '1515 casa', 'prova', 'apprendista', '1', '0', '0', '2017-05-01 00:00:00', '1', '1', '1', 'andrea.lupica@samtrevano.ch')
