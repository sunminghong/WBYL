-- MySQL dump 10.13  Distrib 5.1.41, for Win32 (ia32)
--
-- Host: localhost    Database: iWeibo
-- ------------------------------------------------------
-- Server version	5.1.41

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
-- Current Database: `iWeibo`
--
DROP DATABASE IF EXISTS `iWeibo`;
CREATE DATABASE /*!32312 IF NOT EXISTS*/ `iWeibo` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `iWeibo`;

--
-- Table structure for table `iwb_comment_filter`
--

DROP TABLE IF EXISTS `iwb_comment_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_comment_filter` (
  `filter_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `filter_tweet_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '评论ID',
  `filter_tweet_text` varchar(420) NOT NULL DEFAULT '' COMMENT '评论内容',
  `filter_add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `filter_operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  PRIMARY KEY (`filter_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_comment_filter`
--

LOCK TABLES `iwb_comment_filter` WRITE;
/*!40000 ALTER TABLE `iwb_comment_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `iwb_comment_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iwb_hot_ht`
--

DROP TABLE IF EXISTS `iwb_hot_ht`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_hot_ht` (
  `hot_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `hot_ht_text` varchar(140) NOT NULL DEFAULT '' COMMENT '话题名',
  `hot_ht_id` varchar(140) NOT NULL DEFAULT '0' COMMENT '话题ID',
  `hot_sort_id` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `hot_status` int(11) NOT NULL DEFAULT '0' COMMENT '状态',
  `hot_tweet_count` int(11) NOT NULL DEFAULT '0' COMMENT '微博数',
  `hot_add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `hot_operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  PRIMARY KEY (`hot_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_hot_ht`
--

LOCK TABLES `iwb_hot_ht` WRITE;
/*!40000 ALTER TABLE `iwb_hot_ht` DISABLE KEYS */;
/*!40000 ALTER TABLE `iwb_hot_ht` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iwb_keywords`
--

DROP TABLE IF EXISTS `iwb_keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_keywords` (
  `key_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `key_words` varchar(50) NOT NULL DEFAULT '' COMMENT '关键字',
  `key_add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `key_operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者ID',
  `key_type_id` int(11) NOT NULL DEFAULT '0' COMMENT '关健字类别',
  PRIMARY KEY (`key_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_keywords`
--

LOCK TABLES `iwb_keywords` WRITE;
/*!40000 ALTER TABLE `iwb_keywords` DISABLE KEYS */;
/*!40000 ALTER TABLE `iwb_keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iwb_mod`
--

DROP TABLE IF EXISTS `iwb_mod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_mod` (
  `mod_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `mod_sort_id` int(11) DEFAULT '0' COMMENT '排序ID',
  `mod_name` varchar(50) DEFAULT '' COMMENT '模块名',
  `mod_status` int(11) DEFAULT '0' COMMENT '状态',
  `mod_param1` int(11) DEFAULT '0' COMMENT '参数1',
  `mod_param2` int(11) DEFAULT '0' COMMENT '参数1',
  `mod_param3` int(11) DEFAULT '0' COMMENT '参数1',
  `mod_param4` int(11) DEFAULT '0' COMMENT '参数1',
  `mod_add_time` int(11) DEFAULT '0' COMMENT '添加时间',
  `mod_operator_id` int(11) DEFAULT '0' COMMENT '操作者',
  PRIMARY KEY (`mod_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_mod`
--

LOCK TABLES `iwb_mod` WRITE;
/*!40000 ALTER TABLE `iwb_mod` DISABLE KEYS */;
INSERT INTO `iwb_mod` (`mod_id`, `mod_sort_id`, `mod_name`, `mod_status`, `mod_param1`, `mod_param2`, `mod_param3`, `mod_param4`, `mod_add_time`, `mod_operator_id`) VALUES (1, 0, '最热话题', 1, 1, 1, 0, 0, 0, 0),(2, 0, '热门话题排行榜', 1, 2, 10, 0, 0, 0, 0),(3, 0, '用户推荐', 1, 3, 6, 0, 0, 0, 0);
/*!40000 ALTER TABLE `iwb_mod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iwb_recomm_ht`
--

DROP TABLE IF EXISTS `iwb_recomm_ht`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_recomm_ht` (
  `ht_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ht_text` varchar(140) NOT NULL DEFAULT '' COMMENT '话题名',
  `ht_add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `ht_enable_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '启用时间',
  `ht_operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  PRIMARY KEY (`ht_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_recomm_ht`
--

LOCK TABLES `iwb_recomm_ht` WRITE;
/*!40000 ALTER TABLE `iwb_recomm_ht` DISABLE KEYS */;
/*!40000 ALTER TABLE `iwb_recomm_ht` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `iwb_recomm_user`
--

DROP TABLE IF EXISTS `iwb_recomm_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_recomm_user` (
  `recomm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `recomm_weibo_url` varchar(250) NOT NULL DEFAULT '' COMMENT '微博URL',
  `recomm_user_introduction` varchar(420) NOT NULL DEFAULT '' COMMENT '用户介绍',
  `recomm_sort_id` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `recomm_operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  `recomm_add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `recomm_weibo_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户帐户',
  PRIMARY KEY (`recomm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_recomm_user`
--

LOCK TABLES `iwb_recomm_user` WRITE;
/*!40000 ALTER TABLE `iwb_recomm_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `iwb_recomm_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iwb_system_config`
--

DROP TABLE IF EXISTS `iwb_system_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_system_config` (
  `config_name` varchar(50) NOT NULL DEFAULT '' COMMENT '配置名',
  `config_value` varchar(1000) NOT NULL DEFAULT '' COMMENT '配置值',
  `config_desc` varchar(250) NOT NULL DEFAULT '' COMMENT '配置说明',
  PRIMARY KEY (`config_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_system_config`
--

LOCK TABLES `iwb_system_config` WRITE;
/*!40000 ALTER TABLE `iwb_system_config` DISABLE KEYS */;
INSERT INTO `iwb_system_config` VALUES ('logo_url','default_logo_url','LOGO链接'),('page_tail_text','default_page_tail_text','页面尾'),('search','1','搜索功能开关');
/*!40000 ALTER TABLE `iwb_system_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iwb_user`
--

DROP TABLE IF EXISTS `iwb_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `u_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `u_add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `u_status` int(11) NOT NULL DEFAULT '0' COMMENT '状态',
  `u_priority` int(11) NOT NULL DEFAULT '2' COMMENT '权限',
  `u_password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  PRIMARY KEY (`u_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_user`
--

LOCK TABLES `iwb_user` WRITE;
/*!40000 ALTER TABLE `iwb_user` DISABLE KEYS */;
INSERT INTO `iwb_user`(u_name, u_add_time, u_status,u_priority,u_password) VALUES ('u_name_value',u_add_time_value,1,2,'u_password_value');
/*!40000 ALTER TABLE `iwb_user` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `iwb_weibo_filter`
--

DROP TABLE IF EXISTS `iwb_weibo_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iwb_weibo_filter` (
  `filter_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `filter_tweet_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '微博ID',
  `filter_tweet_text` varchar(420) NOT NULL DEFAULT '' COMMENT '微博内容',
  `filter_add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `filter_operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  PRIMARY KEY (`filter_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iwb_weibo_filter`
--

LOCK TABLES `iwb_weibo_filter` WRITE;
/*!40000 ALTER TABLE `iwb_weibo_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `iwb_weibo_filter` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-01-07 14:55:34
