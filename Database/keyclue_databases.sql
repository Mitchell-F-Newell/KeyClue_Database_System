CREATE DATABASE  IF NOT EXISTS `cpsc471` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `cpsc471`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: cpsc471
-- ------------------------------------------------------
-- Server version	5.7.21-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `Username` varchar(256) NOT NULL,
  `Name` varchar(256) DEFAULT NULL,
  `Password` varchar(256) NOT NULL,
  `Email` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES ('DRoman','Davis Roman','$2y$10$c40bpw/BX0Wqoz0lVKTXbuSI4zSDzl.yA5QD/WrnfsXp8Lg8tgWlG','davis.roman@ucalgary.ca'),('JBrintnell','Jared Brintnell','$2y$10$NZbtJ/8NfT6LamfYK0achOmkQG5yGwC79Y.mG1xpTNQQr7I5okQ.u','jdbrintnell@outlook.com'),('JDoe','John Doe','$2y$10$mXdHBpCubbWIz5ID19ko4u9GDHxVBPsslCy2rnHSdI7OTRF8jtJZi','jdoe@gmail.com'),('MNewell','Mitchell Newell','$2y$10$b5EUdQVnLi3U0Z2MC.7RT.TMvMEzqiioB39zmPK3B7dR7nfXyl8nu','mitchell.newell@ucalgary.ca');
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `Account_Username` varchar(256) NOT NULL,
  `Permissions` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Account_Username`),
  CONSTRAINT `A_Account` FOREIGN KEY (`Account_Username`) REFERENCES `account` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES ('JBrintnell','admin'),('MNewell','admin');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attachment`
--

DROP TABLE IF EXISTS `attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachment` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Clue_ID` int(11) NOT NULL,
  `File_Name` varchar(256) DEFAULT NULL,
  `File_Location` varchar(256) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`,`Clue_ID`),
  KEY `Att_Clue_idx` (`Clue_ID`),
  CONSTRAINT `Att_Clue` FOREIGN KEY (`Clue_ID`) REFERENCES `clue` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachment`
--

LOCK TABLES `attachment` WRITE;
/*!40000 ALTER TABLE `attachment` DISABLE KEYS */;
INSERT INTO `attachment` VALUES (23,82,'1523854889_HistorianEmails.jpg','../files/2018/04/16/','2018-04-15 23:01:29'),(24,83,'1523855960_DeathNote.jpg','../files/2018/04/16/','2018-04-15 23:19:20'),(25,84,'1523856198_attachment.txt','../files/2018/04/16/','2018-04-15 23:23:18'),(26,85,'1523856362_Mosaic.7z.zip','../files/2018/04/16/','2018-04-15 23:26:02');
/*!40000 ALTER TABLE `attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clue`
--

DROP TABLE IF EXISTS `clue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clue` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Date` datetime DEFAULT NULL,
  `Time_Sensitivity` datetime DEFAULT NULL,
  `Title` varchar(256) DEFAULT NULL,
  `State` int(11) DEFAULT NULL,
  `Author` varchar(256) DEFAULT NULL,
  `Description` varchar(2048) DEFAULT NULL,
  `Acc_Username` varchar(256) DEFAULT NULL,
  `Comp_Date` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Competition_idx` (`Comp_Date`),
  KEY `Account_idx` (`Acc_Username`),
  CONSTRAINT `Account` FOREIGN KEY (`Acc_Username`) REFERENCES `account` (`Username`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `Competition` FOREIGN KEY (`Comp_Date`) REFERENCES `competition_year` (`Year`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clue`
--

LOCK TABLES `clue` WRITE;
/*!40000 ALTER TABLE `clue` DISABLE KEYS */;
INSERT INTO `clue` VALUES (81,'2018-04-15 22:41:29',NULL,'Temperature Sensitive Clay Pot with Hieroglyphics',0,'Laura McKracken','Clay pot received from Laura McKracken, we have reason to believe it is temperature sensitive as it started to melt as it was being brought back.','JBrintnell',2018),(82,'2018-04-15 23:01:29',NULL,'Historian Email',1,'McKracken Family','Logic puzzle to determine the email of the historian we need to contact. Image with puzzle attached.','MNewell',2018),(83,'2018-04-15 23:19:20','2018-04-17 12:30:00','Letter Preceding Christopher McKracken\'s Death',0,'Christopher McKracken','Letter written by Christopher McKracken before he died is attached. We have reason to believe this clue must be completed before April 17 at 12:30pm.','DRoman',2018),(84,'2018-04-15 23:23:18',NULL,'Number Box Sequence',0,'Unknown','When entering up/down onto switches on a box (up/down obtained from engineer letters) a number sequence started. Attached is this full sequence from before key hit to end of #s. KMs said that this is a sequential pattern (pairs don\'t matter) that goes with something that looks like it.','JBrintnell',2018),(85,'2018-04-15 23:26:02',NULL,'Mosaic.7z',0,'Christopher McKracken','Contents of unlocked Mosaic.7z folder.','DRoman',2018);
/*!40000 ALTER TABLE `clue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Account_Username` varchar(256) NOT NULL,
  `Clue_ID` int(11) DEFAULT NULL,
  `Disc_ID` int(11) DEFAULT NULL,
  `Body` varchar(2048) DEFAULT NULL,
  `Date_Posted` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`,`Account_Username`),
  KEY `C_Clue_idx` (`Clue_ID`),
  KEY `C_Discussion_idx` (`Disc_ID`),
  KEY `Cl_Username_idx` (`Account_Username`),
  CONSTRAINT `C_Clue` FOREIGN KEY (`Clue_ID`) REFERENCES `clue` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `C_Discussion` FOREIGN KEY (`Disc_ID`) REFERENCES `discussion` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `C_Username` FOREIGN KEY (`Account_Username`) REFERENCES `account` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (50,'JBrintnell',NULL,33,'Jared Brintnell\r\nPhone: 403-111-2222\r\nEmail: jared.brintnell@ucalgary.ca','2018-04-15 22:50:19'),(51,'MNewell',NULL,35,'\"ZOO\" (What an odd name!),\r\n\r\nThank you so much for replying to our help wanted ad. We didn\'t know much about our grandfather due to his lifelong passion for traveling and work. We were so hoping to find a group of students to help us find out more about our grandfather\'s life. We trust that your team will use good discretion, and as this is a family matter, we ask that you ensure security in all endeavors related to the investigation. We wouldn\'t want any information to be leaked.\r\n\r\nUnfortunately, we will not be available to start the investigation until the new year, as we are catching a flight overseas to spend the holidays with some extended family. Aunt Gertrude is expecting us soon and she lives in a small village so we will not have access to the internet.\r\n\r\nPlease provide us a contact person for your team - I trust we can reach you by this email address. We will be in touch in the new year!\r\n\r\nCheers and Happy Holidays!\r\n\r\nJesse McKraken & siblings','2018-04-15 22:53:27'),(52,'MNewell',82,NULL,'On first run-through I am getting:\r\n\r\nharley.warner48@gmail.com \r\n\r\nAs the correct email. If someone else could do a sanity check that would be lovely.','2018-04-15 23:02:12'),(53,'DRoman',82,NULL,'Ran through it a second time and also got: harley.warner48@gmail.com. I\'ll send them an email.','2018-04-15 23:04:00'),(54,'JBrintnell',82,NULL,'KM\'s confirmed this is the answer by email','2018-04-15 23:05:29'),(55,'JBrintnell',82,NULL,'Email to Harley has been sent!','2018-04-15 23:06:14'),(56,'JBrintnell',NULL,35,'Team Zoo,\r\n\r\nAfter an excellent holiday season with family, we are back in town and are making preparations for starting the investigations. We hope you and your team are well-rested and ready to help us uncover some truths regarding our grandfather\'s life.\r\n\r\nWe ask that you read and follow the below (we have several student groups participating in this endeavor)\r\n\r\n-There is to be no sabotage or espionage of any kind of other teams\' progress in the investigation. Any violations will be met with severe consequences.\r\n-Please ensure you dress warmly and equip yourselves with flashlights and some basic tools. You may need to work outside over the course of the investigation.\r\n-Please do not disturb any private property during the investigation.\r\n-Members of our family need our own time. Do not attempt to follow anyone. Any team found to be following a family member will be immediately terminated from the investigation.\r\n\r\nThank you and we will be in touch regarding a meeting time and place to provide you with what we have and would like investigated.\r\n\r\nWe look forward to working with you.\r\n\r\nThank you,\r\n\r\nJesse C. McKraken','2018-04-15 23:07:00'),(57,'JBrintnell',NULL,35,'Team Zoo,\r\n\r\nWe would like to meet a few representatives from your student group (preferably the leaders) at Moose McGuire\'s at 1pm this Sunday to discuss the beginning of the investigation. Please ensure you have a maximum of 2 people from your group as there may be limited space. Unofrtunately at this stage in the investigation we do not have a lot for the investigation teams to look into, but we hope that in the next few days we have a chance to look through the house a little more and potentially uncover some more.\r\n\r\nPlease confirm you received this message as we need to know who to expect.\r\n\r\nCheers and we look forward to meeting you.\r\n\r\nJ. McKraken & siblings','2018-04-15 23:07:11'),(58,'DRoman',NULL,35,'Team Zoo,\r\n\r\nI\'ve spoken with Matt and Laura and we agree that we have definitely heard that name before. However, we are unsure how Harley knew Chris. It could be that he was part of one of his many adventures...\r\n\r\nKeep us posted,\r\n\r\nJ.M.','2018-04-15 23:07:59'),(59,'DRoman',NULL,35,'Team Zoo,\r\n\r\nDue to today\'s less than conspicuous meeting, we now fear that we are being followed. We do not want to reveal the location of your work area to anyone due to the possibility for information to get out.\r\n\r\nI need to pass along some information we found this afternoon at our grandfather\'s house to you. I will not have any time to talk. Please meet me in the lobby of Hotel Alma tomorrow morning at 8am.\r\n\r\nYou will need to ask me for directions to Santa Fe to prove you are part of the investigation team and not someone trying to intercept information.\r\n\r\nPlease make sure you are there!\r\n\r\nM.M.','2018-04-15 23:08:39'),(60,'DRoman',NULL,33,'Davis Roman Phone: 403-222-3333 Email: davis.roman@ucalgary.ca','2018-04-15 23:12:28'),(61,'DRoman',NULL,34,'Can you send us the password separately?','2018-04-15 23:13:13'),(62,'JBrintnell',83,NULL,'Transcribed: November 20, 2017\r\n\r\n\r\nMy Colleagues and Friends,\r\n\r\n\r\nI have known about my declining health for quite some time. My family and friends have no idea as I have been able to hide it well and I did not want them to worry.\r\n\r\nMy friends, we share a wonderful secret from long ago. We went so far as to swear each other to silence to protect what we found. However, I have reason to believe that something nefarious is taking place.  I have been followed numerous times over the last few months in my daily travels, and I have been hearing strange noises on my phone conversations. I do not know how anyone found me or why they are following me, but I fear it has to do with our secret.\r\n\r\nEven though we vowed to never reveal the secret, I feel that our years of work would be for nothing if we let the secret die with us in our old age. As you all know, for some time not I have been devising a way to protect and conceal the knowledge of where to find this wonderful place. If the secret were to fall to unscrupulous individuals, the consequences could be devastating. Your individual skill sets were indispensable on our adventures, and for that reason a few years ago I entrusted you with information and asked you to conceal it in a meaningful way. Thank you all for your contributions.\r\n\r\nI fear my days are numbered and I will not live past the end of the year...The key to revealing the secret will be left in my estate when I pass. I am counting on your knowledge and skills to protect this, and only entrust it to individuals in the pursuit of knowledge, not money.\r\n\r\nI will leave the beginning of this long journey close to this letter...\r\n\r\nThank you to each and everyone of you for the experiences we have shared. This was my life\'s purpose, and I am grateful to have shared it with you.\r\n\r\nYours always,\r\nChristopher F. McKraken','2018-04-15 23:20:13'),(63,'JBrintnell',83,NULL,'Still working on a solution.','2018-04-15 23:20:32'),(64,'MNewell',85,NULL,'I have a possible solution for puzzle 2 and an updated key on paper in ZOO homeroom if someone wants to look at it.','2018-04-15 23:28:03'),(65,'JBrintnell',85,NULL,'Got solution for all logic puzzles: https://imgur.com/a/xmEbq','2018-04-15 23:29:27'),(66,'JBrintnell',85,NULL,'Added a filled in key: https://imgur.com/a/xmEbq','2018-04-15 23:30:04'),(67,'DRoman',85,NULL,'The key maps a sequence of 3 colors to a character. Purple yellow cyan to 0, for line 1. Go through each pixel of the mosaic image and we should get a string.','2018-04-15 23:30:42'),(69,'DRoman',85,NULL,'Decoded Mosaic: 1000908011160910009080111\r\n0009080111609100090801116\r\n2010043160A08011100321003\r\n2080471003216090801108011\r\n1000916091000916091000916\r\n2008053080121609100320804\r\n0162710039160908011080110\r\n8011160908011100091609100\r\n4310010100091003210032100\r\n3208047162708011080110801\r\n1160908011080110801110009\r\n1004308012100090801108047\r\n0804710009100090801110009\r\n1609100091000916090805310\r\n0100801108011080400804710\r\n0390801116090801116090801\r\n110009080111609162B100100\r\n8011080110804010032162716\r\n2710009080110801116090801\r\n1080111609100430801208011\r\n0801110032100321003210039\r\n1627160916091000910009160\r\n9160908011162B160A0801110\r\n0091609100391627080111003\r\n2160908011100091609160910\r\n009162B100101000916090801\r\n1100321627100391000910009\r\n0801116090801116090801110\r\n0431001016091609100091620\r\n1003210039162710009100091\r\n0009160908040100430805310\r\n043162B162B08053162B10010\r\n1000916091000910032100321\r\n0032162716270804708047100\r\n3908047162716270804710039\r\n0804710039100391627080471\r\n0039080400805308012160916\r\n090801108040162B10043162B\r\n162B10043162B080530805308\r\n0530805308053100431004310\r\n043162B100430805310043162\r\nB100101000916091620162B16\r\n2B08053080530801116091000\r\n9100091003216200804710010\r\n1003210043080531004310043\r\n1004310043080531004310043\r\n1000910009100091000908011\r\n1620080401627160A08011100\r\n0916090801108011080111000\r\n9100321620162708012080111\r\n0009080110801110009160916\r\n09100321003208047160A0801\r\n1100091000908011160908011\r\n1000916200804008047160A10\r\n0091000916091000916091000\r\n9080110804010032080471001\r\n0160910009160908011100090\r\n8011160908040080400804710\r\n0101609100091609160916091\r\n6091609100321620100390801\r\n2100091000908011080110801\r\n1080110801108040162016270\r\n8012100090801110009080111\r\n6091000910009100321003216\r\n2710010100091609080111000\r\n9100090801116091003216200\r\n8047080120801108011080111\r\n0009160910009080111003210\r\n0320804708012100091609160\r\n9080111609080111000916200\r\n8040162708012080111000916\r\n0916091000916090801110032\r\n1620162710010100090801110\r\n0090801108011100091609162\r\n016201627','2018-04-15 23:32:59');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `competition_year`
--

DROP TABLE IF EXISTS `competition_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competition_year` (
  `Year` int(11) NOT NULL,
  `Theme` varchar(256) DEFAULT NULL,
  `Admin_Username` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Year`),
  KEY `CY_Admin_idx` (`Admin_Username`),
  CONSTRAINT `CY_Admin` FOREIGN KEY (`Admin_Username`) REFERENCES `admin` (`Account_Username`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competition_year`
--

LOCK TABLES `competition_year` WRITE;
/*!40000 ALTER TABLE `competition_year` DISABLE KEYS */;
INSERT INTO `competition_year` VALUES (2016,'Prison Break','JBrintnell'),(2017,'Space / Time Travel','JBrintnell'),(2018,'Explorers','JBrintnell'),(2019,'Future Year','JBrintnell');
/*!40000 ALTER TABLE `competition_year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `digital`
--

DROP TABLE IF EXISTS `digital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `digital` (
  `Clue_ID` int(11) NOT NULL,
  `Source` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Clue_ID`),
  CONSTRAINT `D_Clue` FOREIGN KEY (`Clue_ID`) REFERENCES `clue` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `digital`
--

LOCK TABLES `digital` WRITE;
/*!40000 ALTER TABLE `digital` DISABLE KEYS */;
INSERT INTO `digital` VALUES (85,'Day-1 USB');
/*!40000 ALTER TABLE `digital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discussion`
--

DROP TABLE IF EXISTS `discussion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discussion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(256) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Description` varchar(2048) DEFAULT NULL,
  `Competition_Date` int(11) DEFAULT NULL,
  `Admin_Username` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `D_Competition_idx` (`Competition_Date`),
  KEY `D_Admin_idx` (`Admin_Username`),
  CONSTRAINT `D_Admin` FOREIGN KEY (`Admin_Username`) REFERENCES `admin` (`Account_Username`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `D_Competition` FOREIGN KEY (`Competition_Date`) REFERENCES `competition_year` (`Year`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discussion`
--

LOCK TABLES `discussion` WRITE;
/*!40000 ALTER TABLE `discussion` DISABLE KEYS */;
INSERT INTO `discussion` VALUES (33,'Contact Information','2018-04-15 22:48:20','For anyone new to the ZOO KeyClue team, feel free to post your contact info here for others to get in touch with you.',2018,'JBrintnell'),(34,'KeyClue Email','2018-04-15 22:49:23','The KeyClue email is: keyclue@zooengg.ca\r\nIt can be accessed at: https://www.zoho.com/mail/',2018,'JBrintnell'),(35,'Email Correspondence with the McKrackens','2018-04-15 22:52:56','Post any emails from the McKrackens here!',2018,'MNewell');
/*!40000 ALTER TABLE `discussion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `physical`
--

DROP TABLE IF EXISTS `physical`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `physical` (
  `Clue_ID` int(11) NOT NULL,
  `Storage` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Clue_ID`),
  CONSTRAINT `P_Clue` FOREIGN KEY (`Clue_ID`) REFERENCES `clue` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `physical`
--

LOCK TABLES `physical` WRITE;
/*!40000 ALTER TABLE `physical` DISABLE KEYS */;
INSERT INTO `physical` VALUES (81,'ZOO Homeroom Fridge'),(82,'ZOOfice'),(83,'ZOOfice'),(85,'ZOO Homeroom');
/*!40000 ALTER TABLE `physical` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solution`
--

DROP TABLE IF EXISTS `solution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solution` (
  `Clue_ID` int(11) NOT NULL,
  `Date` datetime DEFAULT NULL,
  `Body` varchar(2048) DEFAULT NULL,
  `Account_Username` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Clue_ID`),
  KEY `S_Account_idx` (`Account_Username`),
  CONSTRAINT `S_Account` FOREIGN KEY (`Account_Username`) REFERENCES `account` (`Username`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `S_Clue` FOREIGN KEY (`Clue_ID`) REFERENCES `clue` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solution`
--

LOCK TABLES `solution` WRITE;
/*!40000 ALTER TABLE `solution` DISABLE KEYS */;
INSERT INTO `solution` VALUES (82,'2018-04-15 23:04:15','harley.warner48@gmail.com','DRoman');
/*!40000 ALTER TABLE `solution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `Account_Username` varchar(256) NOT NULL,
  `Expiry` date DEFAULT NULL,
  PRIMARY KEY (`Account_Username`),
  CONSTRAINT `U_Account` FOREIGN KEY (`Account_Username`) REFERENCES `account` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('DRoman','2019-04-16'),('JDoe','2019-04-16');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `year_keymaster`
--

DROP TABLE IF EXISTS `year_keymaster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `year_keymaster` (
  `Comp_Date` int(11) NOT NULL,
  `Keymaster_Name` varchar(256) NOT NULL,
  PRIMARY KEY (`Comp_Date`,`Keymaster_Name`),
  CONSTRAINT `YK_Competition` FOREIGN KEY (`Comp_Date`) REFERENCES `competition_year` (`Year`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `year_keymaster`
--

LOCK TABLES `year_keymaster` WRITE;
/*!40000 ALTER TABLE `year_keymaster` DISABLE KEYS */;
INSERT INTO `year_keymaster` VALUES (2016,'Joseph Miller'),(2016,'Simon Wu'),(2017,'Dan Gibson'),(2017,'James Thorne'),(2018,'Jesse McGrady'),(2018,'Laura Tucker'),(2018,'Matthew Thompson');
/*!40000 ALTER TABLE `year_keymaster` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-17 20:52:23
