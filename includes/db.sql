CREATE TABLE ACCOUNT(
    id varchar(10) primary key,
    username varchar(12),
    password varchar(32)
);

CREATE TABLE INVENTORY(
    studentID varchar(10) primary key,
    fullname varchar(100),
    email varchar(100),
    phone varchar(10),
    numberpages int,
    IDaccount varchar(10),
    foreign key (IDaccount) references ACCOUNT(id) on delete CASCADE
);

CREATE TABLE PAPER(
    papername varchar(10) primary key,
    xlength float,
    ylength float,
    thickness float,
    color varchar(10),
    price float
);

CREATE TABLE INVENTORYDETAILS(
    studentID varchar(10),
    papername varchar(10),
    quantity int,
    foreign key (studentID) references INVENTORY(studentID) on delete CASCADE,
    foreign key (papername) references PAPER(papername) on delete CASCADE
);

CREATE TABLE BUILDING(
    buildingid varchar(4) primary key
);

CREATE TABLE PRINTER(
    buildingid varchar(4),
    printerid varchar(10) primary key,
    printername varchar(30),
    printertype varchar(10),
    printerstatus varchar(10) DEFAULT "Available",
    foreign key (buildingid) REFERENCES BUILDING(buildingid) on delete cascade
);

CREATE TABLE PRINTERPAPERCANBEUSED(
    printerid varchar(10),
    papername varchar(10),
    foreign key (printerid) references PRINTER(printerid) on delete CASCADE,
    foreign key (papername) references PAPER(papername) on delete cascade
);

CREATE TABLE PRINTERCOLOR(
    printerid varchar(10),
    colorname varchar(10),
    primary key(printerid,colorname),
    foreign key (printerid) references PRINTER(printerid) on delete CASCADE,
    quantity float,
    availablity tinyint default 0
);

CREATE TABLE PRINTORDER(
    orderid INT AUTO_INCREMENT PRIMARY KEY,
    studentid varchar(10),
    orderedprinter varchar(10),
    overallprice float,
    payed tinyint(1) default 0,
    orderdate varchar(20),
    payeddate datetime,
    receiptdate datetime
);

create table printdetails(
    id INT AUTO_INCREMENT PRIMARY KEY,
    orderid int,
    usedpaper varchar(10),
    filename varchar(255),
    colorused varchar(10),
    price float,
    pagesperpaper int default 1,
    numpages int,
    numofcopies int,
    foreign key (orderid) REFERENCES printorder(orderid),
    foreign key (usedpaper) REFERENCES paper(papername)
);

CREATE TABLE PRINTHISTORY(
    id varchar(4) primary key,
    printtime datetime
);

CREATE TABLE BILL (
    id INT AUTO_INCREMENT PRIMARY KEY,
    price int,
    usedpaper varchar(9),
    numberpage int
);


insert into paper values ("A5",21,14.8,0.01,"White",500.0);
insert into paper values ("A4",29.7,21,0.01,"White",1000.0);
insert into paper values ("A3",42,29.7,0.01,"White",2000.0);
insert into paper values ("A2",59.4,42,0.01,"White",5000.0);
insert into paper values ("A1",84.1,59.4,0.01,"White",10000.0);
insert into paper values ("A0",118.9,84.1,0.01,"White",20000.0);


insert into building values ("H1");
insert into building values ("H2");
insert into building values ("H3");

insert into printer values ("H1","H101","SAMSUNG AB12","SAMSUNG","Available");
insert into printer values ("H1","H102","SAMSUNG AB23","SAMSUNG","Available");
insert into printer values ("H1","H103","EPSON AB34","EPSON","Available");
insert into printer values ("H1","H104","EPSON AB45","EPSON","Available");

insert into printercolor values ("H101","White",10.0,1);
insert into printercolor values ("H101","Black",10.0,1);
insert into printercolor values ("H101","Blue",10.0,1);
insert into printercolor values ("H101","Red",10.0,1);
insert into printercolor values ("H101","Yellow",10.0,1);

insert into printercolor values ("H104","White",10.0,1);
insert into printercolor values ("H104","Black",10.0,1);
insert into printercolor values ("H104","Blue",10.0,1);
insert into printercolor values ("H104","Red",10.0,1);
insert into printercolor values ("H104","Yellow",10.0,1);

insert into printercolor values ("H103","White",10.0,1);
insert into printercolor values ("H103","Black",10.0,1);
insert into printercolor values ("H103","Blue",10.0,1);
insert into printercolor values ("H103","Red",10.0,1);
insert into printercolor values ("H103","Yellow",10.0,1);

insert into printercolor values ("H102","White",10.0,1);
insert into printercolor values ("H102","Black",10.0,1);
insert into printercolor values ("H102","Blue",10.0,1);
insert into printercolor values ("H102","Red",10.0,1);
insert into printercolor values ("H102","Yellow",10.0,1);



insert into printer values ("H2","H201","CANON AB12","CANON","Available");
insert into printer values ("H2","H202","CANON AB23","CANON","Available");
insert into printer values ("H2","H203","HP AB34","HP","Available");
insert into printer values ("H2","H204","HP AB45","HP","Available");

insert into printercolor values ("H201","White",10.0,1);
insert into printercolor values ("H201","Black",10.0,1);
insert into printercolor values ("H201","Blue",10.0,1);
insert into printercolor values ("H201","Red",10.0,1);
insert into printercolor values ("H201","Yellow",10.0,1);

insert into printercolor values ("H204","White",10.0,1);
insert into printercolor values ("H204","Black",10.0,1);
insert into printercolor values ("H204","Blue",10.0,1);
insert into printercolor values ("H204","Red",10.0,1);
insert into printercolor values ("H204","Yellow",10.0,1);

insert into printercolor values ("H203","White",10.0,1);
insert into printercolor values ("H203","Black",10.0,1);
insert into printercolor values ("H203","Blue",10.0,1);
insert into printercolor values ("H203","Red",10.0,1);
insert into printercolor values ("H203","Yellow",10.0,1);

insert into printercolor values ("H202","White",10.0,1);
insert into printercolor values ("H202","Black",10.0,1);
insert into printercolor values ("H202","Blue",10.0,1);
insert into printercolor values ("H202","Red",10.0,1);
insert into printercolor values ("H202","Yellow",10.0,1);



insert into printer values ("H3","H301","EPSON AB12","EPSON","Available");
insert into printer values ("H3","H302","EPSON AB23","EPSON","Available");
insert into printer values ("H3","H303","SAMSUNG SS34","SAMSUNG","Available");
insert into printer values ("H3","H304","SAMSUNG SS45","SAMSUNG","Available");

insert into printercolor values ("H301","White",10.0,1);
insert into printercolor values ("H301","Black",10.0,1);
insert into printercolor values ("H301","Blue",10.0,1);
insert into printercolor values ("H301","Red",10.0,1);
insert into printercolor values ("H301","Yellow",10.0,1);

insert into printercolor values ("H304","White",10.0,1);
insert into printercolor values ("H304","Black",10.0,1);
insert into printercolor values ("H304","Blue",10.0,1);
insert into printercolor values ("H304","Red",10.0,1);
insert into printercolor values ("H304","Yellow",10.0,1);

insert into printercolor values ("H303","White",10.0,1);
insert into printercolor values ("H303","Black",10.0,1);
insert into printercolor values ("H303","Blue",10.0,1);
insert into printercolor values ("H303","Red",10.0,1);
insert into printercolor values ("H303","Yellow",10.0,1);

insert into printercolor values ("H302","White",10.0,1);
insert into printercolor values ("H302","Black",10.0,1);
insert into printercolor values ("H302","Blue",10.0,1);
insert into printercolor values ("H302","Red",10.0,1);
insert into printercolor values ("H302","Yellow",10.0,1);

/* THEM TAI KHOAN 1 VÀ THÔNG TIN LỊCH SỬ ĐƠN HẸN*/

insert into ACCOUNT VALUES ("1002" ,"nsydat", "1111");
insert into inventory values ("1234567", "Nông Sỹ Đạt", "dat.nongsy@hcmut.edu.vn", "0332030970", 100, "1002");
insert into inventorydetails values
("1234567", "A5", 0),
("1234567", "A4", 0),
("1234567", "A3", 0),
("1234567", "A2", 0),
("1234567", "A1", 0),
("1234567", "A0", 0);


insert into printorder values
('3517', '1234567', 'H102', 4000, 1, '18:00:00 27-11-2023', '2023-11-27 21:00:00', '2023-11-29 10:00:00');

insert into printdetails values
('1111', '3517', 'A4', 'PDF', 'Black', 4000, 2, 4, 2);

insert into printhistory values ("1111","2023-11-28 23:59:59");

/* THEM TAI KHOAN 2 VÀ THÔNG TIN LỊCH SỬ ĐƠN HẸN*/

insert into ACCOUNT VALUES ("1234" ,"user1", "user123");
insert into inventory values ("1111111", "Kim Jong Un", "user1@hcmut.edu.vn", "0111111111", 0, "1234");
insert into inventorydetails values
("1111111", "A5", 0),
("1111111", "A4", 0),
("1111111", "A3", 0),
("1111111", "A2", 0),
("1111111", "A1", 0),
("1111111", "A0", 0);

insert into printorder values
('3579', '1111111', 'H102', 4000, 1, '18:00:00 27-11-2023', '2023-11-27 21:00:00' , '2023-11-28 08:00:00'),
('2468', '1111111', 'H201', 30000, 1, '19:00:00 27-11-2023', '2023-11-27 21:00:00', '2023-11-28 08:00:00'),
('1234', '1111111', 'H303', 6000, 1, '20:00:00 27-11-2023', '2023-11-27 21:00:00', '2023-11-28 08:00:00');

insert into printdetails values
('0001', '3579', 'A3', 'Word', 'Black', 4000, 2, 4, 2),
('0002', '2468', 'A5', 'PDF', 'Black', 30000, 15, 30, 2),
('0003', '1234', 'A0', 'Word', 'Black', 6000, 6, 6, 1);

insert into printhistory values
("0001","2023-11-28 23:59:59"),
("0002","2023-11-29 23:59:59"),
("0003","2023-11-30 23:59:59");

/* THEM TAI KHOAN 3 VÀ THÔNG TIN LỊCH SỬ ĐƠN HẸN*/

insert into ACCOUNT VALUES ("0000" ,"admin", "admin123");
insert into inventory values ("0000000", "Amin", "admin@hcmut.edu.vn", "0111111111", 0, "0000");
insert into inventorydetails values
("0000000", "A5", 0),
("0000000", "A4", 0),
("0000000", "A3", 0),
("0000000", "A2", 0),
("0000000", "A1", 0),
("0000000", "A0", 0);
