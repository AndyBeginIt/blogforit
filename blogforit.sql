-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2013 年 03 月 10 日 10:46
-- 服务器版本: 5.5.23
-- PHP 版本: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_blogforit`
--
CREATE DATABASE `app_blogforit` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `app_blogforit`;

-- --------------------------------------------------------

--
-- 表的结构 `mb_administrator`
--

CREATE TABLE IF NOT EXISTS `mb_administrator` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `datetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `mb_blog`
--

CREATE TABLE IF NOT EXISTS `mb_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `c_id` int(10) unsigned NOT NULL,
  `tags` varchar(100) NOT NULL,
  `comment_conf` smallint(3) NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `c_id` (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=163 ;

-- --------------------------------------------------------

--
-- 表的结构 `mb_category`
--

CREATE TABLE IF NOT EXISTS `mb_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `u_id` int(10) unsigned NOT NULL,
  `category` varchar(20) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `mb_comment`
--

CREATE TABLE IF NOT EXISTS `mb_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blogId` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `avatar` varchar(7) NOT NULL COMMENT '头像图片序号',
  `email` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `datetime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `blogId` (`blogId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mb_tags`
--

CREATE TABLE IF NOT EXISTS `mb_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `u_id` int(10) unsigned NOT NULL,
  `tag` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- 表的结构 `mb_users`
--

CREATE TABLE IF NOT EXISTS `mb_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(25) NOT NULL,
  `password` char(32) NOT NULL,
  `nickname` varchar(25) NOT NULL,
  `icon` char(18) NOT NULL DEFAULT 'default_icon.jpg',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` smallint(1) unsigned NOT NULL DEFAULT '0',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
