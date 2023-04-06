create database electricityphp;
use electricityphp;

create table if not exists customer
(
customerid int(5) primary key auto_increment,
customername varchar(50),
contact varchar(10),
email varchar(50) unique key,
password varchar(50),
address varchar(100),
city varchar(30),
state varchar(50)
);

insert into customer(customerid,customername,contact,email,password,address,city,state) values('123','Test','7767947467','test@gmail.com','12345','123 Howard Road','Leicester','England');

create table if not exists connection
(
connectionid varchar(50) primary key,
customerid int(5),
connectiontype varchar(20),
connectionstartdate varchar(50),
occupation varchar(20),
connectionload varchar(30) ,
plotno varchar(100),
city varchar(30),
pincode varchar(30),
address varchar(30),
state varchar(50),
description varchar(100)
);

insert into connection(connectionid,customerid,connectiontype,connectionstartdate,occupation,connectionload,plotno,city,pincode,address,state,description) values('test@gmail.com','Null','flat','2023-01-15','Student','5','12','Howard Road','le21xp','Leicester','England','Hello');

create table if not exists bill
(
billid int(5) primary key auto_increment,
connectionid varchar(50),
billformonth varchar(30),
dayelectricitycurrentreading varchar(10),
dayelectricitypreviousreading varchar(10),
dayelectricitytotalunit varchar(10),
dayelectricitychargeperunit varchar(10),
nightelectricitycurrentreading varchar(10),
nightelectricitypreviousreading varchar(10),
nightelectricitytotalunit varchar(10),
nightelectricitychargeperunit varchar(10),
gascurrentreading varchar(10),
gaspreviousreading varchar(10),
gastotalunit varchar(10),
gaschargeperunit varchar(10),
standingcharge varchar(10),
finalamount varchar(30),
duedate varchar(50),
status varchar(20)
);

insert into bill(billid,connectionid,billformonth,dayelectricitycurrentreading,dayelectricitypreviousreading,dayelectricitytotalunit, dayelectricitychargeperunit,nightelectricitycurrentreading,nightelectricitypreviousreading,nightelectricitytotalunit,nightelectricitychargeperunit,gascurrentreading,gaspreviousreading,gastotalunit,gaschargeperunit,standingcharge,finalamount,duedate,status) values('123','test@gmail.com','2023-01-15','300','200','Null','0.34','800','500','Null','0.2','2500','1600','Null','0.1','0.74','206.94','2023-02-15','not paid');


create table if not exists admin
(
adminname varchar(30)  not null,
password varchar(10) default null,
PRIMARY KEY(adminname)
);

insert into admin(adminname,password) values('gse@shangrila.gov.un','gse@energy');
