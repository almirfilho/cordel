-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2012 at 11:36 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cordel`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `appear` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `controller` varchar(25) NOT NULL,
  `controller_label` varchar(50) DEFAULT NULL,
  `action` varchar(25) NOT NULL,
  `action_label` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `appear` (`appear`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=11 ;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` VALUES(1, NULL, 1, 'Users', 'Usu&aacute;rios', 'index', 'Todos');
INSERT INTO `areas` VALUES(2, NULL, 0, 'Users', 'Usu&aacute;rios', 'add', 'Criar Novo');
INSERT INTO `areas` VALUES(3, NULL, 0, 'Users', 'Usu&aacute;rios', 'edit', 'Editar');
INSERT INTO `areas` VALUES(4, NULL, 0, 'Users', 'Usu&aacute;rios', 'delete', 'Excluir');
INSERT INTO `areas` VALUES(5, 1, 1, 'Profiles', 'Perf&iacute;s de Usu&aacute;rio', 'index', 'Todos');
INSERT INTO `areas` VALUES(6, NULL, 0, 'Profiles', 'Perf&iacute;s de Usu&aacute;rio', 'add', 'Criar Novo');
INSERT INTO `areas` VALUES(7, NULL, 0, 'Profiles', 'Perf&iacute;s de Usu&aacute;rio', 'edit', 'Editar');
INSERT INTO `areas` VALUES(8, NULL, 0, 'Profiles', 'Perf&iacute;s de Usu&aacute;rio', 'delete', 'Excluir');
INSERT INTO `areas` VALUES(9, NULL, 0, 'Profiles', 'Perf&iacute;s de Usu&aacute;rio', 'view', 'Visualizar');
INSERT INTO `areas` VALUES(10, NULL, 0, 'Users', 'Usu&aacute;rios', 'view', 'Visualizar');

-- --------------------------------------------------------

--
-- Table structure for table `areas_profiles`
--

CREATE TABLE `areas_profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` int(11) unsigned NOT NULL,
  `profile_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `area_profile` (`area_id`,`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `areas_profiles`
--

INSERT INTO `areas_profiles` VALUES(7, 1, 1);
INSERT INTO `areas_profiles` VALUES(25, 1, 2);
INSERT INTO `areas_profiles` VALUES(1, 2, 1);
INSERT INTO `areas_profiles` VALUES(26, 2, 2);
INSERT INTO `areas_profiles` VALUES(2, 3, 1);
INSERT INTO `areas_profiles` VALUES(27, 3, 2);
INSERT INTO `areas_profiles` VALUES(3, 4, 1);
INSERT INTO `areas_profiles` VALUES(28, 4, 2);
INSERT INTO `areas_profiles` VALUES(4, 5, 1);
INSERT INTO `areas_profiles` VALUES(5, 6, 1);
INSERT INTO `areas_profiles` VALUES(8, 7, 1);
INSERT INTO `areas_profiles` VALUES(6, 8, 1);
INSERT INTO `areas_profiles` VALUES(14, 9, 1);
INSERT INTO `areas_profiles` VALUES(13, 10, 1);
INSERT INTO `areas_profiles` VALUES(29, 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` VALUES(1, 'Admin', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `profiles` VALUES(2, 'Perfil Teste', '2012-03-25 00:21:45', '2012-04-02 17:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL,
  `password` char(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `pass_switched` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(1, 1, 'a78f76a37a7f699f39b324ba58b2aad5', 'Administrador', 'admin', '2012-04-03 21:10:59', 1, '0000-00-00 00:00:00', '2012-04-03 21:10:59');
INSERT INTO `users` VALUES(5, 2, '9bc5f2b8fb63d9ec807dc1d6e35ebf6e', 'Teste', 'teste@teste.com', '2012-04-02 22:41:30', 1, '2012-03-23 15:49:04', '2012-04-02 22:41:30');
