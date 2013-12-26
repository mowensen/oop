drop table if exists cmsUsers;
create table cmsUsers(user char(15),interface char(2),blocks varchar(333),icons char(99),lngs char(33),ips char(33),xdate timestamp,xuser char(15),xip char(15),primary key(user))ENGINE=InnoDB DEFAULT CHARSET=utf8;
drop table if exists cmsBlocks;
create table cmsBlocks(idB int not null,ids char(33),fields text,fieldsLang text,types text,typesLang text,isModified char(1) default 0,isMenu char(1) default 0,isForm char(1) default 0,xdate timestamp,xuser char(15),xip char(15),primary key(idB))ENGINE=InnoDB DEFAULT CHARSET=utf8;
drop table if exists cmsTreesLang;
create table cmsTreesLang(tree char(33) not null,lngId char(2) not null,idT int not null,nb int,title text,isModified char(1) default 0,xdate timestamp,xuser char(15),xip char(15),primary key(tree,lngId,idT))ENGINE=InnoDB DEFAULT CHARSET=utf8;
drop table if exists cmsLabelsLang;
create table cmsLabelsLang(lngId char(2) not null,idB int not null,label varchar(33),title longtext,isModified char(1) default 0,primary key(lngId,idB,label))ENGINE=InnoDB DEFAULT CHARSET=utf8;
drop table if exists cmsLabels;
create table cmsLabels(idB int not null,nb int,label varchar(33),isModified char(1) default 0,xdate timestamp,xuser char(15),xip char(15),primary key(idB,label))ENGINE=InnoDB DEFAULT CHARSET=utf8;
#drop table if exists cmsRls;
#create table cmsRls (nb tinyint(4) not null default '0',lngId char(2) not null,id0 int(11) not null,pub varchar(99) not null default '',page varchar(99) not null default '',link varchar(99) not null default '',comment longtext not null,priority varchar(99) not null default '',active tinyint(1) not null default 0,xdate char(20) not null default '',primary key  (id0))ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create table if not exists cmsTrace (record char(245) default null,op char(15) default null,data longtext,xdate timestamp not null default '',xuser char(15) not null default '',xip char(15) not null default '',primary key(xuser,xip,xdate))ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create table if not exists cmsStat(id0 int not null,visits int,ns int,ie int,ns6 int,xdate timestamp not null,primary key(id0,xdate))ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into cmsUsers(user,blocks,icons,lngs)values('admin','all','all','all');

insert into cmsBlocks(idB,xdate)values('0','2011-01-01 00:00:00'),('1','2011-01-01 00:00:00'),('2','2011-01-01 00:00:00'),('3','2011-01-01 00:00:00');

insert into cmsTreesLang(tree,lngId,idT,nb,title,xdate)values('blocks-','fr','0','0','Accueil','2011-01-01 00:00:00');
insert into cmsTreesLang(tree,lngId,idT,nb,title,xdate)values('blocks-','fr','1','1','<font color=black><b>Blocs spéciaux</b></font>','2011-01-01 00:00:00');
insert into cmsTreesLang(tree,lngId,idT,nb,title,xdate)values('blocks-1','fr','2','0','Cadre html','2011-01-01 00:00:00');
insert into cmsTreesLang(tree,lngId,idT,nb,title,xdate)values('blocks-1','fr','3','1','Cadre principal','2011-01-01 00:00:00');

insert into cmsTreesLang(tree,lngId,idT,nb,title,xdate)values('blocks-','en','0','0','Home','2011-01-01 00:00:00');
insert into cmsTreesLang(tree,lngId,idT,nb,title,xdate)values('blocks-','en','1','1','<font color=black><b>Special blocks</b></font>','2011-01-01 00:00:00');
insert into cmsTreesLang(tree,lngId,idT,nb,title,xdate)values('blocks-1','en','2','0','Html frame','2011-01-01 00:00:00');
insert into cmsTreesLang(tree,lngId,idT,nb,title,xdate)values('blocks-1','en','3','1','Main frame','2011-01-01 00:00:00');

