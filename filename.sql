SET FOREIGN_KEY_CHECKS = 0;

-- 
-- Table structure for table `administrator` 
-- 

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE `administrator` (
`adm_pk_id` int(11) NOT NULL auto_increment,
`adm_name` varchar(100) NOT NULL,
`adm_surname` varchar(100) NOT NULL,
`adm_email` varchar(100) NOT NULL,
`adm_gg` varchar(11),
`adm_phone` varchar(100),
`adm_foto` varchar(100),
`use_id` int(11) NOT NULL,
  PRIMARY KEY  (`adm_pk_id`),
  KEY `useradmin` (`use_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24;

-- --------------------------------------------------------

-- 
-- Table structure for table `archive_group` 
-- 

DROP TABLE IF EXISTS `archive_group`;
CREATE TABLE `archive_group` (
`arc_id` int(11) NOT NULL auto_increment,
`arc_gro_name` varchar(10) NOT NULL,
`arc_gro_year` varchar(10) NOT NULL,
`arc_stu_name` varchar(100) NOT NULL,
`arc_stu_surname` varchar(100) NOT NULL,
`arc_stu_email` varchar(100),
  PRIMARY KEY  (`arc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4;

-- --------------------------------------------------------

-- 
-- Table structure for table `group` 
-- 

DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
`gro_pk_id` int(11) NOT NULL auto_increment,
`gro_name` varchar(10) NOT NULL,
`gro_idboss` int(11),
`gro_email` varchar(100),
`gro_year` varchar(10) DEFAULT '2000/2001' NOT NULL,
`gro_archive` int(1) NOT NULL,
  PRIMARY KEY  (`gro_pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8;

-- --------------------------------------------------------

-- 
-- Table structure for table `message` 
-- 

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
`mes_pk_id` int(11) NOT NULL auto_increment,
`mes_from` int(11),
`mes_to` int(11),
`mes_topic` varchar(100) NOT NULL,
`mes_body` text NOT NULL,
`mes_del_from` int(11) NOT NULL,
`mes_del_to` int(11) NOT NULL,
`mes_sendtime` datetime NOT NULL,
`mes_read` int(1) NOT NULL,
  PRIMARY KEY  (`mes_pk_id`),
  KEY `from` (`mes_from`),
  KEY `to` (`mes_to`)
) ENGINE=InnoDB AUTO_INCREMENT=24;

-- --------------------------------------------------------

-- 
-- Table structure for table `notify` 
-- 

DROP TABLE IF EXISTS `notify`;
CREATE TABLE `notify` (
`not_pk_id` int(11) NOT NULL auto_increment,
`not_body` text NOT NULL,
`not_type` int(2) NOT NULL,
`not_date` date NOT NULL,
`use_id` int(11),
  PRIMARY KEY  (`not_pk_id`),
  KEY `usernotify` (`use_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45;

-- --------------------------------------------------------

-- 
-- Table structure for table `professor` 
-- 

DROP TABLE IF EXISTS `professor`;
CREATE TABLE `professor` (
`pro_pk_id` int(11) NOT NULL auto_increment,
`pro_name` varchar(100) NOT NULL,
`pro_surname` varchar(100) NOT NULL,
`pro_email` varchar(100) NOT NULL,
`pro_gg` varchar(11),
`pro_phone` varchar(100),
`pro_about` text,
`pro_foto` varchar(100),
`use_id` int(11) NOT NULL,
  PRIMARY KEY  (`pro_pk_id`),
  KEY `userprofessor` (`use_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12;

-- --------------------------------------------------------

-- 
-- Table structure for table `student` 
-- 

DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
`stu_pk_id` int(11) NOT NULL auto_increment,
`stu_name` varchar(100) NOT NULL,
`stu_surname` varchar(100) NOT NULL,
`stu_email` varchar(100) NOT NULL,
`stu_gg` varchar(11),
`stu_phone` varchar(100),
`stu_foto` varchar(100),
`use_id` int(11) NOT NULL,
`gro_id` int(11) NOT NULL,
  PRIMARY KEY  (`stu_pk_id`),
  KEY `userstudent` (`use_id`),
  KEY `studentgroup` (`gro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14;

-- --------------------------------------------------------

-- 
-- Table structure for table `user` 
-- 

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
`use_pk_id` int(11) NOT NULL auto_increment,
`use_email` varchar(100) NOT NULL,
`use_password` varchar(100) NOT NULL,
`use_role` varchar(100) NOT NULL,
`use_code` int(11) NOT NULL,
`use_activate` int(11) NOT NULL,
`use_block` int(11) NOT NULL,
`use_verify` int(11) NOT NULL,
  PRIMARY KEY  (`use_pk_id`),
  UNIQUE KEY `email_UNIQUE` (`use_email`)
) ENGINE=InnoDB AUTO_INCREMENT=52;

-- --------------------------------------------------------

-- 
-- Dumping data for table `administrator` 
-- 

INSERT INTO `administrator` (`adm_pk_id`, `adm_name`, `adm_surname`, `adm_email`, `adm_gg`, `adm_phone`, `adm_foto`, `use_id`) VALUES ('7','Damian','Szymczuk','dasd@dasa','','','lszmivozr.jpg','8'),
 ('8','asdasd','sdfsdf','sdfsd@fdsfsdf','','','ymln6b9t7.jpg','9'),
 ('9','aga','szymc','aga@agusia.pl','','','','10'),
 ('10','kana','kanowska','dasdasd@sdgfsd','','','','11'),
 ('11','asdasdas','dasdasd','asdasd@dasfds','','','','12'),
 ('12','jhgkhgkgh','jghjghj','ghjgh@gfhfgh','','','','13'),
 ('13','Samsung','Ace','samsung@gmaul.kom','','','','16'),
 ('14','asdas','dasf','asfdsaffa@asfdas','','','','21'),
 ('15','dsfdsf','fsdgdfhg','dfhdfh@sdgsd.pl','','','','22'),
 ('16','sdfgdfg','dfgdf','gdfgdfg@fsdfsdf','','','','23'),
 ('17','fsdfsd','fsgdfg','dfg@dsfsd','','','','24'),
 ('18','fdgdfgdfg','sdfsdf','dsgsdg@dfsdfsdf','','','','25'),
 ('19','dfsd','gsdg','sdgsdgsdgd@afasdf','','','','26'),
 ('20','dsfsdf','sdfsd','fsdfsd@dsgdfg','','','','39'),
 ('21','admin1','admin1','admin1@gmail.com','','','','40'),
 ('22','2534','45865','sdf@9839.df','','','','42'),
 ('23','Damian','Admin','damian.admin@gmail.com','','','','51');

-- --------------------------------------------------------

-- 
-- Dumping data for table `archive_group` 
-- 

INSERT INTO `archive_group` (`arc_id`, `arc_gro_name`, `arc_gro_year`, `arc_stu_name`, `arc_stu_surname`, `arc_stu_email`) VALUES ('1','11B2','2002/2003','sadasdasd','sdfsdf','sdfsdf@sdfsg'),
 ('2','11B2','2002/2003','sadasdasdds','sdfsdf','sdfsdf@sdfsgs'),
 ('3','11B2','2002/2003','DamianStu','SzymczukStu','damian.krakow@gmail.com');

-- --------------------------------------------------------

-- 
-- Dumping data for table `group` 
-- 

INSERT INTO `group` (`gro_pk_id`, `gro_name`, `gro_idboss`, `gro_email`, `gro_year`, `gro_archive`) VALUES ('1','11A5','','','2001/2002','0'),
 ('2','11B2','','asdasd@asdasd.pl','2002/2003','1'),
 ('3','11C2','','asdasd@asdasd.pl','2002/2003','0'),
 ('5','11D1','','','2390/5623','1'),
 ('6','11B3','','','2002/2003','0'),
 ('7','11B2','','','2002/2003','0');

-- --------------------------------------------------------

-- 
-- Dumping data for table `message` 
-- 

INSERT INTO `message` (`mes_pk_id`, `mes_from`, `mes_to`, `mes_topic`, `mes_body`, `mes_del_from`, `mes_del_to`, `mes_sendtime`, `mes_read`) VALUES ('1','8','44','pierwsza wiadomość','CześćWysyłam Ci pierwszą wiadomość testową :)','0','0','0000-00-00 00:00:00','0'),
 ('2','8','44','pierwsza wiadomość','Cześć\r\n\r\nWysyłam Ci drugą wiadomość testową :)','0','0','0000-00-00 00:00:00','0'),
 ('3','8','44','pierwsza wiadomość','Cześć\r\n\r\nWysyłam Ci drugą wiadomość testową :)','0','0','2011-08-19 16:04:18','0'),
 ('4','8','43','temacik','testowa','0','0','2011-08-19 16:04:46','1'),
 ('5','8','44','tamcior','sdaasdasdas','0','0','2011-08-28 11:17:42','0'),
 ('6','8','44','Cześć Damian','To jest np wiadomość testowa. Ona jest bardzo fajna.','1','0','2011-08-29 00:06:47','0'),
 ('7','44','8','pierwsza odebrana','pierwsza oderbana wiadomosc','0','1','2011-08-29 01:01:07','0'),
 ('8','14','8','asd','asdasd','0','0','2011-08-29 01:15:14','1'),
 ('9','8','14','sada','sdasdasd','1','0','2011-08-29 01:23:30','0'),
 ('10','8','14','fsdfsdf','sdfsdgdfgdfgd','1','0','2011-08-29 17:01:13','1'),
 ('11','8','14','fsdfsdf','sdfsdgdfgdfgd','1','0','2011-08-29 17:04:45','0'),
 ('12','8','14','fsdfsdf','sdfsdgdfgdfgd','1','0','2011-08-29 17:05:30','0'),
 ('13','8','8','do admina','Treść\r\n\r\nDo administratora','1','0','2011-08-30 00:40:22','1'),
 ('14','8','44','Nowa wiadomość','Testuję nową wiadomość','1','0','2011-10-30 15:24:48','0'),
 ('15','8','44','Test notyfikacji','Testuje notyfikację','1','0','2011-10-30 15:41:41','0'),
 ('16','8','8','piszę sam do siebie','piszę sam do siebie','1','1','2011-10-30 22:03:30','1'),
 ('17','8','10','dfgdfgdf','dfgdfg','0','0','2011-11-05 15:59:01','0'),
 ('18','8','10','dfgdfgdf','dfgdfg','0','0','2011-11-05 15:59:13','0'),
 ('19','8','10','dfgdfgdf','dfgdfg','0','0','2011-11-05 16:02:05','0'),
 ('20','49','8','blablabla','asdasdasdasd','1','0','2011-11-14 01:38:15','1'),
 ('21','8','49','asd','adfsdgdfg','0','0','2011-11-14 01:54:28','1'),
 ('22','8','49','asd','adfsdgdfg','0','0','2011-11-15 01:54:48','1'),
 ('23','50','49','Testowy','Testowa wiadomość','1','1','2011-11-16 23:12:15','1');

-- --------------------------------------------------------

-- 
-- Dumping data for table `notify` 
-- 

INSERT INTO `notify` (`not_pk_id`, `not_body`, `not_type`, `not_date`, `use_id`) VALUES ('1','asdasdasd','1','2011-09-09','8'),
 ('2','Masz nową prywatną wiadomość.','1','2011-10-30','44'),
 ('3','Typ 2','2','2011-10-30','44'),
 ('4','Typ 0','0','2011-10-30','44'),
 ('5','Typ 3','3','2011-10-30','44'),
 ('6','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent elementum magna nec risus cursus luctus. Donec tincidunt lacus a erat convallis interdum vitae eu nibh. Fusce pharetra leo sed quam dictum viverra in vitae dui. Ut dictum eros id nisi congue lacinia. Curabitur iaculis, sapien eu interdum auctor, ligula risus laoreet dui, venenatis pulvinar sem massa a orci. Curabitur aliquam risus nec nisi pellentesque a congue tortor feugiat. Mauris ullamcorper, libero sit amet suscipit consectetur, diam arcu semper felis, id euismod libero ligula et neque. Aliquam erat volutpat. Aenean lacus enim, pretium commodo pellentesque sed, porttitor vitae est. Phasellus pellentesque venenatis tortor. Nulla faucibus, velit venenatis elementum venenatis, erat sem feugiat ligula, eu dapibus lacus metus ac velit. Pellentesque hendrerit malesuada risus, vel blandit ante tempus a. Ut eget leo justo, vitae feugiat dolor. Curabitur quis iaculis massa. Pellentesque faucibus consectetur nibh, eget rhoncus turpis feugiat sed. Vestibulum eget ipsum quam. ','4','2011-10-30','44'),
 ('7','Masz nową prywatną wiadomość.','1','2011-10-30','8'),
 ('8','Masz nową prywatną wiadomość.','1','2011-10-30','8'),
 ('9','Masz nową prywatną wiadomość.','1','2011-10-30','8'),
 ('10','Masz nową prywatną wiadomość.','1','2011-11-05','10'),
 ('11','Masz nową prywatną wiadomość.','1','2011-11-05','10'),
 ('12','Masz nową prywatną wiadomość.','1','2011-11-05','10'),
 ('13','Test wykładowcy','2','2011-11-14','49'),
 ('14','Masz nową prywatną wiadomość.','1','2011-11-14','8'),
 ('15','Masz nową prywatną wiadomość.','1','2011-11-14','49'),
 ('16','Masz nową prywatną wiadomość.','1','2011-11-15','49'),
 ('17','Masz nową prywatną wiadomość.','1','2011-11-16','49'),
 ('18','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','27'),
 ('19','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','30'),
 ('20','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','31'),
 ('21','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','32'),
 ('22','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','35'),
 ('23','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','36'),
 ('24','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','37'),
 ('25','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','38'),
 ('26','Testuję super wiadomości. Priorytet: normalny','2','2011-11-18','50'),
 ('27','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','27'),
 ('28','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','30'),
 ('29','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','31'),
 ('30','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','32'),
 ('31','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','35'),
 ('32','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','36'),
 ('33','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','37'),
 ('34','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','38'),
 ('35','Testuję super wiadomości. Priorytet: pilny','3','2011-11-18','50'),
 ('36','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','27'),
 ('37','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','30'),
 ('38','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','31'),
 ('39','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','32'),
 ('40','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','35'),
 ('41','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','36'),
 ('42','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','37'),
 ('43','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','38'),
 ('44','Testuję super wiadomości. Priorytet: krytyczny','4','2011-11-18','50');

-- --------------------------------------------------------

-- 
-- Dumping data for table `professor` 
-- 

INSERT INTO `professor` (`pro_pk_id`, `pro_name`, `pro_surname`, `pro_email`, `pro_gg`, `pro_phone`, `pro_about`, `pro_foto`, `use_id`) VALUES ('1','profek','profesorek','prof@profek.pla','','','','','14'),
 ('2','bvnfg','fghgfh','gfhf@dfgdfg','','','','','15'),
 ('3','Profesro','Profcio','profcio@gmail.com','12456','453453','fdgdfgdfgdfg','','17'),
 ('4','profek','profek','profek@gmail.com','','','','','18'),
 ('5','asdaa','asdasd','asdasd@asdasd','','','','','19'),
 ('6','qweqw','fdsafdsf','sdfsdf@dfdsaf','','','','','20'),
 ('7','prof1','prof1','prof1@gmail.com','','','','','41'),
 ('8','Damian','Szymczuk','damian.szymczuk@gmail.com','','','','','43'),
 ('9','Daniel','Szymkowski','rejestrator02@gmail.com','','','','','47'),
 ('10','Nowy','Szymakowski','rejestrator03@gmail.com','','','','','48'),
 ('11','Damian','Professor','damian.professor@gmail.com','123','456456','sadasdasf\r\nasdasf\r\nsdgdfj\r\n\r\ndfhdfh','jhvn9vblt.jpg','49');

-- --------------------------------------------------------

-- 
-- Dumping data for table `student` 
-- 

INSERT INTO `student` (`stu_pk_id`, `stu_name`, `stu_surname`, `stu_email`, `stu_gg`, `stu_phone`, `stu_foto`, `use_id`, `gro_id`) VALUES ('1','asdfsdf','sdfsdf','sdfsdf@dsfsdf','','','','27','1'),
 ('2','dasd','asdasdas','dasdasd@asfa','','','','30','1'),
 ('3','Damian','Testowy','asdas@asdasf','','','','31','1'),
 ('4','Damian','Testowy','asdasd@sadasf','','','','32','1'),
 ('5','sadasdasd','sdfsdf','sdfsdf@sdfsg','','','','33','2'),
 ('6','sadasdasdds','sdfsdf','sdfsdf@sdfsgs','','','','34','2'),
 ('7','asd','fsdgd','dfgdfg@dsffsd','','','','35','1'),
 ('8','Damian','Dymczuk','damian@gmail.com','','','','36','1'),
 ('9','Damian2','Dymczuk2','damian2@gmail.com','','','','37','1'),
 ('10','Damian3','Dymczuk3','damian3@gmail.com','','','','38','1'),
 ('11','DamianStu','SzymczukStu','damian.krakow@gmail.com','','','','44','2'),
 ('12','Anna','Testowa','asdjasd@asdasd.pl','','','','46','6'),
 ('13','Damian','Student','damian.student@gmail.com','123','456789','6269ck215z.jpg','50','1');

-- --------------------------------------------------------

-- 
-- Dumping data for table `user` 
-- 

INSERT INTO `user` (`use_pk_id`, `use_email`, `use_password`, `use_role`, `use_code`, `use_activate`, `use_block`, `use_verify`) VALUES ('8','dasd@dasa','9ee68db19083acc0745c8d01589135d01f2b99d4','administrator','316925','1','0','1'),
 ('9','sdfsd@fdsfsdf','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','administrator','543270','1','0','0'),
 ('10','aga@agusia.pl','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','administrator','997088','1','0','0'),
 ('11','dasdasd@sdgfsd','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','administrator','952209','1','0','0'),
 ('12','asdasd@dasfds','64fd4005e13d5860f2b03580b7c2e68e77f79838','administrator','452880','1','0','0'),
 ('13','ghjgh@gfhfgh','7c1fb39b27df9168e76cd51089a7d16157fe179b','administrator','820730','1','0','0'),
 ('14','prof@profek.pla','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','professor','250210','1','0','0'),
 ('15','gfhf@dfgdfg','dba5b4e6c3c10eb989d9866e51c71c0ccfd6b138','professor','874316','1','0','0'),
 ('16','samsung@gmaul.kom','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','administrator','637231','1','0','0'),
 ('17','profcio@gmail.com','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','professor','539727','1','0','0'),
 ('18','profek@gmail.com','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','professor','739486','1','0','0'),
 ('19','asdasd@asdasd','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','professor','753768','1','0','0'),
 ('20','sdfsdf@dfdsaf','a8249e28c16b2a0fc41392af6ccf53d8e87f346f','professor','11','1','0','0'),
 ('21','asfdsaffa@asfdas','1ca3188d858e161c2823d05d23b47182a270ec9a','administrator','161962','1','0','0'),
 ('22','dfhdfh@sdgsd.pl','0c5b67c05ff24954093ba35f55608cd52422e3de','administrator','840286','1','0','0'),
 ('23','gdfgdfg@fsdfsdf','ec97f2447cd48009c8eba6931e8ca3474764699d','administrator','566204','1','0','0'),
 ('24','dfg@dsfsd','5f34b57fd19cadc4946b531898a688cde0593e89','administrator','975527','1','0','0'),
 ('25','dsgsdg@dfsdfsdf','64e443df47aa42fde449fec47af0c5524979dd3f','administrator','668020','1','0','0'),
 ('26','sdgsdgsdgd@afasdf','4b24c51c54674559887ec4410f800c83b5464e27','administrator','782031','1','0','0'),
 ('27','sdfsdf@dsfsdf','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','student','820648','1','0','1'),
 ('28','asdas@asdas.pl','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','student','492843','1','1','0'),
 ('29','asdas@asdas','58212f269801d2bd640904306a604809758b6037','student','513800','0','1','0'),
 ('30','dasdasd@asfa','8d06b00a4630dee032c657a3b7026f9231a86c66','student','860583','1','0','1'),
 ('31','asdas@asdasf','a8249e28c16b2a0fc41392af6ccf53d8e87f346f','student','373916','1','0','1'),
 ('32','asdasd@bleble','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','student','433517','1','0','1'),
 ('33','sdfsdf@sdfsg','0cf07f2b76cdd8ca24cf2ea46276620f8bac70cf','student','998873','1','1','1'),
 ('34','sdfsdf@sdfsgs','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','student','565875','1','1','1'),
 ('35','dfgdfg@dsffsd','6c73e9ec851bbc4ccd7a47c20653f38eecf08e3c','student','116342','1','0','1'),
 ('36','damian@gmail.com','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','student','168197','1','0','1'),
 ('37','damian2@gmail.com','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','student','742919','1','0','0'),
 ('38','damian3@gmail.com','08cb1d6b7a0032b9d86e1eeb757d738131ba2404','student','330383','1','0','1'),
 ('39','fsdfsd@dsgdfg','2518c418322613daf6cc7fa28beaa29fbdea2f36','administrator','927462','1','0','0'),
 ('40','admin1@gmail.com','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','administrator','542337','1','0','1'),
 ('41','prof1@gmail.com','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','professor','940673','1','0','1'),
 ('42','sdf@9839.df','e768ae67a526a943cd710580198710461fd4d863','administrator','598120','1','0','1'),
 ('43','damian.szymczuk@gmail.com','08cb1d6b7a0032b9d86e1eeb757d738131ba2404','professor','868164','1','0','1'),
 ('44','damian.krakow@gmail.com','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','student','780960','1','0','1'),
 ('46','asdjasd@asdasd.pl','21a8bd898ae39d1e6b76e5a498c5c347bfb7f8ca','student','599658','1','0','1'),
 ('47','rejestrator02@gmail.com','9ee68db19083acc0745c8d01589135d01f2b99d4','professor','275231','1','0','1'),
 ('48','rejestrator03@gmail.com','9ee68db19083acc0745c8d01589135d01f2b99d4','professor','485208','1','0','1'),
 ('49','damian.professor@gmail.com','9ee68db19083acc0745c8d01589135d01f2b99d4','professor','382595','1','0','1'),
 ('50','damian.student@gmail.com','9ee68db19083acc0745c8d01589135d01f2b99d4','student','970199','1','0','1'),
 ('51','damian.admin@gmail.com','9ee68db19083acc0745c8d01589135d01f2b99d4','administrator','795956','1','0','1');

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

--------------
--FOREIGN KEYS
--------------
SET FOREIGN_KEY_CHECKS = 0

SET FOREIGN_KEY_CHECKS = 1