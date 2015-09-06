-- phpMyAdmin SQL Dump
-- version 4.4.11
-- Version du serveur :  5.5.40-log
-- Version de PHP :  5.4.42

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) unsigned NOT NULL,
  `manage_news` tinyint(1) NOT NULL DEFAULT '0',
  `manage_mixed_content` tinyint(1) NOT NULL DEFAULT '0',
  `manage_user_category` tinyint(1) NOT NULL DEFAULT '0',
  `manage_user` tinyint(1) NOT NULL DEFAULT '0',
  `manage_menu` tinyint(1) NOT NULL DEFAULT '0',
  `manage_submenu` tinyint(1) NOT NULL DEFAULT '0',
  `manage_file` tinyint(1) NOT NULL DEFAULT '0',
  `manage_right` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `manage_news`, `manage_mixed_content`, `manage_user_category`, `manage_user`, `manage_menu`, `manage_submenu`, `manage_file`, `manage_right`) VALUES
(2, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `category_has_language`
--

CREATE TABLE IF NOT EXISTS `category_has_language` (
  `category_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `category_has_language`
--

INSERT INTO `category_has_language` (`category_id`, `language_abbreviation`, `name`, `description`) VALUES
(2, 'en', 'farmer', 'the lowest status'),
(2, 'fr', 'paysan', 'le statut de peknou'),
(3, 'en', 'Me, God', 'the highest status'),
(3, 'fr', 'Moi, Dieu', 'ben moi, quoi !');

-- --------------------------------------------------------

--
-- Structure de la table `controller`
--

CREATE TABLE IF NOT EXISTS `controller` (
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `controller`
--

INSERT INTO `controller` (`name`) VALUES
('contenu'),
('liste'),
('update-password');

-- --------------------------------------------------------

--
-- Structure de la table `field`
--

CREATE TABLE IF NOT EXISTS `field` (
  `id` int(10) unsigned NOT NULL,
  `type` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `field`
--

INSERT INTO `field` (`id`, `type`) VALUES
(1, 'TextField'),
(2, 'StringField'),
(3, 'CheckboxField'),
(4, 'PasswordField');

-- --------------------------------------------------------

--
-- Structure de la table `field_has_language`
--

CREATE TABLE IF NOT EXISTS `field_has_language` (
  `field_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `field_has_language`
--

INSERT INTO `field_has_language` (`field_id`, `language_abbreviation`, `name`) VALUES
(1, 'en', 'plain text'),
(1, 'fr', 'texte plein'),
(2, 'en', 'text field'),
(2, 'fr', 'champ texte');

-- --------------------------------------------------------

--
-- Structure de la table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `path` varchar(120) NOT NULL,
  `date` date NOT NULL,
  `size` int(11) NOT NULL,
  `description` mediumtext,
  `user_login` varchar(45) NOT NULL,
  `user_category_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `form`
--

CREATE TABLE IF NOT EXISTS `form` (
  `id` int(10) unsigned NOT NULL,
  `default_receiver` varchar(60) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `form`
--

INSERT INTO `form` (`id`, `default_receiver`) VALUES
(2, 'website@fai.com');

-- --------------------------------------------------------

--
-- Structure de la table `form_has_field`
--

CREATE TABLE IF NOT EXISTS `form_has_field` (
  `id` int(10) unsigned NOT NULL,
  `form_id` int(10) unsigned NOT NULL,
  `field_id` int(10) unsigned NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `metric` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `form_has_field`
--

INSERT INTO `form_has_field` (`id`, `form_id`, `field_id`, `required`, `metric`) VALUES
(4, 2, 2, 1, 1),
(5, 2, 2, 1, 2),
(6, 2, 2, 1, 3),
(7, 2, 1, 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `form_has_field_has_language`
--

CREATE TABLE IF NOT EXISTS `form_has_field_has_language` (
  `form_has_field_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(25) DEFAULT NULL,
  `help` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `form_has_field_has_language`
--

INSERT INTO `form_has_field_has_language` (`form_has_field_id`, `language_abbreviation`, `name`, `description`, `help`) VALUES
(4, 'en', 'Name', '', NULL),
(4, 'fr', 'No content for this language', '', NULL),
(5, 'en', 'Surname', '', NULL),
(5, 'fr', 'No content for this language', '', NULL),
(6, 'en', 'Email', '', NULL),
(6, 'fr', 'No content for this language', '', NULL),
(7, 'en', 'Your message', '', NULL),
(7, 'fr', 'No content for this language', '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `form_has_language`
--

CREATE TABLE IF NOT EXISTS `form_has_language` (
  `form_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `name` varchar(90) DEFAULT NULL,
  `description` text,
  `receiver` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `form_has_language`
--

INSERT INTO `form_has_language` (`form_id`, `language_abbreviation`, `name`, `description`, `receiver`) VALUES
(2, 'en', 'Contact us', '<p>lorem ipsum..</p>', 'website@fai.com'),
(2, 'fr', 'No content for this language', '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `galery`
--

CREATE TABLE IF NOT EXISTS `galery` (
  `id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `galery_has_language`
--

CREATE TABLE IF NOT EXISTS `galery_has_language` (
  `galery_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `title` varchar(90) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `abbreviation` varchar(10) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `language`
--

INSERT INTO `language` (`abbreviation`, `name`) VALUES
('en', 'english'),
('fr', 'franÃ§ais');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) unsigned NOT NULL,
  `module_has_controller_module_name` varchar(45) NOT NULL,
  `module_has_controller_controller_name` varchar(45) NOT NULL,
  `metric` int(10) unsigned NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `options` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `menu`
--

INSERT INTO `menu` (`id`, `module_has_controller_module_name`, `module_has_controller_controller_name`, `metric`, `private`, `options`) VALUES
(16, 'news', 'liste', 43, 0, ''),
(23, 'texte', 'contenu', 1, 0, '35'),
(24, 'texte', 'contenu', 2, 0, '36'),
(25, 'texte', 'contenu', 3, 0, '37'),
(26, 'formulaire', 'liste', 10, 0, '2'),
(27, 'news-category', 'liste', 5, 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `menu_has_language`
--

CREATE TABLE IF NOT EXISTS `menu_has_language` (
  `menu_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `menu_has_language`
--

INSERT INTO `menu_has_language` (`menu_id`, `language_abbreviation`, `visible`, `name`, `description`) VALUES
(23, 'en', 1, 'Home', ''),
(23, 'fr', 0, 'No content for this language', ''),
(24, 'en', 1, 'History', ''),
(24, 'fr', 0, 'No content for this language', ''),
(25, 'en', 1, 'Our mission', ''),
(25, 'fr', 0, 'No content for this language', ''),
(26, 'en', 1, 'Contact', ''),
(26, 'fr', 0, 'No content for this language', ''),
(27, 'en', 1, 'News and Views', ''),
(27, 'fr', 0, 'No content for this language', '');

-- --------------------------------------------------------

--
-- Structure de la table `mixed_content`
--

CREATE TABLE IF NOT EXISTS `mixed_content` (
  `id` int(10) unsigned NOT NULL,
  `mixed_page_id` int(10) unsigned NOT NULL,
  `contentType` varchar(45) NOT NULL,
  `metric` int(10) unsigned NOT NULL,
  `vars` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `mixed_content_list`
--

CREATE TABLE IF NOT EXISTS `mixed_content_list` (
  `id` int(10) unsigned NOT NULL,
  `private` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `module`
--

INSERT INTO `module` (`name`) VALUES
('connexion'),
('deconnexion'),
('divers'),
('formulaire'),
('galery'),
('news'),
('news-category'),
('texte'),
('utilisateurs');

-- --------------------------------------------------------

--
-- Structure de la table `module_has_controller`
--

CREATE TABLE IF NOT EXISTS `module_has_controller` (
  `module_name` varchar(45) NOT NULL,
  `controller_name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `module_has_controller`
--

INSERT INTO `module_has_controller` (`module_name`, `controller_name`) VALUES
('news', 'contenu'),
('texte', 'contenu'),
('utilisateurs', 'contenu'),
('connexion', 'liste'),
('deconnexion', 'liste'),
('formulaire', 'liste'),
('galery', 'liste'),
('news', 'liste'),
('news-category', 'liste'),
('texte', 'liste'),
('utilisateurs', 'liste'),
('utilisateurs', 'update-password');

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL,
  `news_subject_id` int(10) unsigned NOT NULL,
  `created_date` date NOT NULL,
  `picture_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`id`, `news_subject_id`, `created_date`, `picture_id`) VALUES
(8, 5, '2013-06-06', 8),
(10, 5, '2013-06-12', 7),
(12, 8, '2013-11-06', 11),
(13, 4, '2014-10-27', 12),
(14, 4, '2014-11-09', 13),
(15, 4, '2015-08-23', 15),
(16, 4, '2015-08-23', 18);

-- --------------------------------------------------------

--
-- Structure de la table `news_has_language`
--

CREATE TABLE IF NOT EXISTS `news_has_language` (
  `news_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(120) DEFAULT NULL,
  `content` longtext,
  `modified_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `news_has_language`
--

INSERT INTO `news_has_language` (`news_id`, `language_abbreviation`, `visible`, `title`, `content`, `modified_date`) VALUES
(8, 'en', 1, 'Title 1', '<p>lorem ipsum..</p>', '2013-12-18'),
(8, 'fr', 0, 'No content for this language', 'No content for this language', '2013-06-06'),
(10, 'en', 1, 'Title 2', '<p>lorem ipsum..</p>', '2013-06-06'),
(10, 'fr', 0, 'No content for this language', 'No content for this language', '2013-06-05'),
(12, 'en', 1, 'Title 3', '<p>lorem ipsum..</p>', '2013-11-07'),
(12, 'fr', 0, 'No content for this language', 'No content for this language', '2013-11-06'),
(13, 'en', 1, 'Title 4', '<p>lorem ipsum..</p>', '2014-10-27'),
(13, 'fr', 0, 'No content for this language', 'No content for this language', '2014-10-27'),
(14, 'en', 1, 'Title 5', '<p>lorem ipsum..</p>', '2014-11-09'),
(14, 'fr', 0, 'No content for this language', 'No content for this language', '2014-11-09'),
(15, 'en', 1, 'Title 6', '<p>lorem ipsum..</p>', '2015-08-23'),
(15, 'fr', 0, 'No content for this language', 'No content for this language', '2015-08-23'),
(16, 'en', 1, 'Title 7', '<p>lorem ipsum..</p>', '2015-08-24'),
(16, 'fr', 0, 'No content for this language', 'No content for this language', '2015-08-23');

-- --------------------------------------------------------

--
-- Structure de la table `news_subject`
--

CREATE TABLE IF NOT EXISTS `news_subject` (
  `id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `news_subject`
--

INSERT INTO `news_subject` (`id`) VALUES
(4),
(5),
(8);

-- --------------------------------------------------------

--
-- Structure de la table `news_subject_has_language`
--

CREATE TABLE IF NOT EXISTS `news_subject_has_language` (
  `news_subject_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `news_subject_has_language`
--

INSERT INTO `news_subject_has_language` (`news_subject_id`, `language_abbreviation`, `name`) VALUES
(4, 'en', 'Events'),
(4, 'fr', 'No content for this language'),
(5, 'en', 'News and Views'),
(5, 'fr', 'No content for this language'),
(8, 'en', 'Event of the Month'),
(8, 'fr', 'No content for this language');

-- --------------------------------------------------------

--
-- Structure de la table `picture`
--

CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(10) unsigned NOT NULL,
  `galery_id` int(10) unsigned DEFAULT '0',
  `format` varchar(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `picture`
--

INSERT INTO `picture` (`id`, `galery_id`, `format`) VALUES
(7, NULL, 'jpeg'),
(8, NULL, 'jpeg'),
(11, NULL, 'jpeg'),
(12, NULL, 'jpeg'),
(13, NULL, 'jpeg'),
(15, NULL, 'jpeg'),
(18, NULL, 'jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `picture_has_language`
--

CREATE TABLE IF NOT EXISTS `picture_has_language` (
  `picture_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `picture_has_language`
--

INSERT INTO `picture_has_language` (`picture_id`, `language_abbreviation`, `name`, `description`) VALUES
(7, 'en', 'No content for this language', ''),
(7, 'fr', 'No content for this language', ''),
(8, 'en', 'No content for this language', ''),
(8, 'fr', 'No content for this language', ''),
(11, 'en', 'No content for this language', ''),
(11, 'fr', 'No content for this language', ''),
(12, 'en', 'No content for this language', ''),
(12, 'fr', 'No content for this language', ''),
(13, 'en', 'No content for this language', ''),
(13, 'fr', 'No content for this language', ''),
(15, 'en', 'No content for this language', ''),
(15, 'fr', 'No content for this language', ''),
(18, 'en', 'No content for this language', ''),
(18, 'fr', 'No content for this language', '');

-- --------------------------------------------------------

--
-- Structure de la table `simple_text`
--

CREATE TABLE IF NOT EXISTS `simple_text` (
  `id` int(10) unsigned NOT NULL,
  `private` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `simple_text`
--

INSERT INTO `simple_text` (`id`, `private`) VALUES
(1, 0),
(9, 0),
(11, 0),
(13, 0),
(14, 0),
(15, 0),
(16, 0),
(19, 0),
(20, 0),
(21, 0),
(22, 0),
(23, 0),
(27, 0),
(28, 0),
(29, 0),
(30, 0),
(32, 0),
(35, 0),
(36, 0),
(37, 0),
(38, 0),
(39, 0);

-- --------------------------------------------------------

--
-- Structure de la table `simple_text_has_language`
--

CREATE TABLE IF NOT EXISTS `simple_text_has_language` (
  `simple_text_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `content` longtext,
  `modified_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `simple_text_has_language`
--

INSERT INTO `simple_text_has_language` (`simple_text_id`, `language_abbreviation`, `content`, `modified_date`) VALUES
(1, 'en', '', '2013-10-05'),
(1, 'fr', 'bla', '2013-10-05'),
(9, 'en', 'No content for this language', '2013-10-13'),
(9, 'fr', '', '2013-10-13'),
(11, 'en', 'No content for this language', '2013-10-13'),
(11, 'fr', '', '2013-10-13'),
(13, 'en', 'No content for this language', '2013-10-13'),
(13, 'fr', '<p>lorem ipsum..</p>', '2013-10-13'),
(14, 'en', 'No content for this language', '2013-10-13'),
(14, 'fr', '<p>lorem ipsum..</p>', '2013-10-13'),
(15, 'en', 'No content for this language', '2013-10-13'),
(15, 'fr', '<p>lorem ipsum..</p>', '2013-10-13'),
(16, 'en', 'No content for this language', '2013-10-13'),
(16, 'fr', '<p>lorem ipsum..</p>', '2013-10-13'),
(19, 'en', 'No content for this language', '2013-10-13'),
(19, 'fr', '', '2013-10-13'),
(20, 'en', 'No content for this language', '2013-10-13'),
(20, 'fr', '', '2013-10-13'),
(21, 'en', 'No content for this language', '2013-10-13'),
(21, 'fr', '', '2013-10-13'),
(22, 'en', 'No content for this language', '2013-10-13'),
(22, 'fr', '', '2013-10-13'),
(23, 'en', 'No content for this language', '2013-10-13'),
(23, 'fr', '', '2013-10-13'),
(27, 'en', 'No content for this language', '2013-10-13'),
(27, 'fr', '', '2013-10-13'),
(28, 'en', 'No content for this language', '2013-10-13'),
(28, 'fr', '', '2013-10-13'),
(29, 'en', 'No content for this language', '2013-10-13'),
(29, 'fr', '', '2013-10-13'),
(30, 'en', 'No content for this language', '2013-10-13'),
(30, 'fr', '', '2013-10-13'),
(32, 'en', 'No content for this language', '2013-10-24'),
(32, 'fr', '', '2013-10-24'),
(35, 'en', '<h1><strong>Who we are</strong></h1>\r\n<p>lorem ipsum..</p>', '2014-02-10'),
(35, 'fr', 'No content for this language', '2013-10-24'),
(36, 'en', '<h1>History</h1>', '2014-10-27'),
(36, 'fr', 'No content for this language', '2013-10-24'),
(37, 'en', '<h1>Our mission</h1>', '2013-10-24'),
(37, 'fr', 'No content for this language', '2013-10-24'),
(38, 'en', '<h1>Publications</h1>\r\n<p>lorem ipsum..</p>', '2014-11-09'),
(38, 'fr', 'No content for this language', '2013-10-24'),
(39, 'en', '<h1>Other</h1>\r\n<p>lorem ipsum..</p>', '2014-11-09'),
(39, 'fr', 'No content for this language', '2013-10-27');

-- --------------------------------------------------------

--
-- Structure de la table `submenu`
--

CREATE TABLE IF NOT EXISTS `submenu` (
  `id` int(10) unsigned NOT NULL,
  `menu_id` int(10) unsigned NOT NULL,
  `module_has_controller_module_name` varchar(45) NOT NULL,
  `module_has_controller_controller_name` varchar(45) NOT NULL,
  `metric` int(10) unsigned NOT NULL,
  `options` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `submenu`
--

INSERT INTO `submenu` (`id`, `menu_id`, `module_has_controller_module_name`, `module_has_controller_controller_name`, `metric`, `options`) VALUES
(4, 25, 'texte', 'contenu', 1, '38'),
(5, 25, 'texte', 'contenu', 2, '39');

-- --------------------------------------------------------

--
-- Structure de la table `submenu_has_language`
--

CREATE TABLE IF NOT EXISTS `submenu_has_language` (
  `submenu_id` int(10) unsigned NOT NULL,
  `language_abbreviation` varchar(10) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `submenu_has_language`
--

INSERT INTO `submenu_has_language` (`submenu_id`, `language_abbreviation`, `visible`, `name`, `description`) VALUES
(4, 'en', 1, 'Publications', ''),
(4, 'fr', 0, 'No content for this language', ''),
(5, 'en', 1, 'Lorem ipsum', ''),
(5, 'fr', 0, 'No content for this language', '');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `login` varchar(45) NOT NULL,
  `category_id` int(10) unsigned NOT NULL DEFAULT '3',
  `password` varchar(45) NOT NULL,
  `salt` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `surname` varchar(45) DEFAULT NULL,
  `mail` varchar(75) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`login`, `category_id`, `password`, `salt`, `name`, `surname`, `mail`) VALUES
('gartugozul', 3, '782debf62e2e9da3a2d3848585343512f9bf693b', 1850437953, 'adm', 'adm', 'robin.herzog8@gmail.com');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_category`
--
CREATE TABLE IF NOT EXISTS `v_category` (
`id` int(10) unsigned
,`name` varchar(45)
,`description` mediumtext
,`manage_news` tinyint(1)
,`manage_mixed_content` tinyint(1)
,`manage_user_category` tinyint(1)
,`manage_user` tinyint(1)
,`manage_menu` tinyint(1)
,`manage_submenu` tinyint(1)
,`manage_file` tinyint(1)
,`manage_right` tinyint(1)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_field`
--
CREATE TABLE IF NOT EXISTS `v_field` (
`id` int(10) unsigned
,`name` varchar(45)
,`type` varchar(45)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_form`
--
CREATE TABLE IF NOT EXISTS `v_form` (
`id` int(10) unsigned
,`name` varchar(90)
,`description` text
,`receiver` varchar(60)
,`defaultReceiver` varchar(60)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_form_has_field`
--
CREATE TABLE IF NOT EXISTS `v_form_has_field` (
`formId` int(10) unsigned
,`name` varchar(90)
,`description` text
,`receiver` varchar(60)
,`defaultReceiver` varchar(60)
,`fieldId` int(10) unsigned
,`fieldName` varchar(45)
,`fieldTypeId` int(10) unsigned
,`fieldTypeName` varchar(45)
,`fieldType` varchar(45)
,`fieldDescription` varchar(25)
,`fieldHelp` varchar(90)
,`required` tinyint(1)
,`metric` int(11)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_galery`
--
CREATE TABLE IF NOT EXISTS `v_galery` (
`id` int(10) unsigned
,`title` varchar(90)
,`description` text
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_menu`
--
CREATE TABLE IF NOT EXISTS `v_menu` (
`id` int(10) unsigned
,`name` varchar(45)
,`description` varchar(90)
,`metric` int(10) unsigned
,`visible` tinyint(1)
,`private` tinyint(1)
,`options` varchar(45)
,`module_name` varchar(45)
,`controller_name` varchar(45)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_news`
--
CREATE TABLE IF NOT EXISTS `v_news` (
`id` int(10) unsigned
,`subject_id` int(10) unsigned
,`subject_name` varchar(45)
,`title` varchar(120)
,`content` longtext
,`visible` tinyint(1)
,`created_date` date
,`modified_date` date
,`picture_id` int(10) unsigned
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_news_subject`
--
CREATE TABLE IF NOT EXISTS `v_news_subject` (
`id` int(10) unsigned
,`name` varchar(45)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_picture`
--
CREATE TABLE IF NOT EXISTS `v_picture` (
`id` int(10) unsigned
,`galeryId` int(10) unsigned
,`format` varchar(6)
,`name` varchar(45)
,`description` varchar(120)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_simple_text`
--
CREATE TABLE IF NOT EXISTS `v_simple_text` (
`id` int(10) unsigned
,`content` longtext
,`private` tinyint(1)
,`modified_date` date
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_submenu`
--
CREATE TABLE IF NOT EXISTS `v_submenu` (
`id` int(10) unsigned
,`name` varchar(45)
,`description` varchar(90)
,`menu_id` int(10) unsigned
,`metric` int(10) unsigned
,`options` varchar(45)
,`visible` tinyint(1)
,`module_name` varchar(45)
,`controller_name` varchar(45)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_user`
--
CREATE TABLE IF NOT EXISTS `v_user` (
`login` varchar(45)
,`password` varchar(45)
,`salt` int(11)
,`name` varchar(45)
,`surname` varchar(45)
,`mail` varchar(75)
,`category_name` varchar(45)
,`category_description` mediumtext
,`manage_news` tinyint(1)
,`manage_mixed_content` tinyint(1)
,`manage_user_category` tinyint(1)
,`manage_user` tinyint(1)
,`manage_menu` tinyint(1)
,`manage_submenu` tinyint(1)
,`manage_file` tinyint(1)
,`manage_right` tinyint(1)
,`language` varchar(10)
);

-- --------------------------------------------------------

--
-- Structure de la vue `v_category`
--
DROP TABLE IF EXISTS `v_category`;

CREATE VIEW `v_category` AS select `c`.`id` AS `id`,`chl`.`name` AS `name`,`chl`.`description` AS `description`,`c`.`manage_news` AS `manage_news`,`c`.`manage_mixed_content` AS `manage_mixed_content`,`c`.`manage_user_category` AS `manage_user_category`,`c`.`manage_user` AS `manage_user`,`c`.`manage_menu` AS `manage_menu`,`c`.`manage_submenu` AS `manage_submenu`,`c`.`manage_file` AS `manage_file`,`c`.`manage_right` AS `manage_right`,`chl`.`language_abbreviation` AS `language` from (`category` `c` join `category_has_language` `chl` on((`c`.`id` = `chl`.`category_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `v_field`
--
DROP TABLE IF EXISTS `v_field`;

CREATE VIEW `v_field` AS select `f`.`id` AS `id`,`fhl`.`name` AS `name`,`f`.`type` AS `type`,`fhl`.`language_abbreviation` AS `language` from (`field` `f` join `field_has_language` `fhl` on((`f`.`id` = `fhl`.`field_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `v_form`
--
DROP TABLE IF EXISTS `v_form`;

CREATE VIEW `v_form` AS select `f`.`id` AS `id`,`fhl`.`name` AS `name`,`fhl`.`description` AS `description`,`fhl`.`receiver` AS `receiver`,`f`.`default_receiver` AS `defaultReceiver`,`fhl`.`language_abbreviation` AS `language` from (`form` `f` join `form_has_language` `fhl` on((`f`.`id` = `fhl`.`form_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `v_form_has_field`
--
DROP TABLE IF EXISTS `v_form_has_field`;

CREATE VIEW `v_form_has_field` AS select `fo`.`id` AS `formId`,`fo`.`name` AS `name`,`fo`.`description` AS `description`,`fo`.`receiver` AS `receiver`,`fo`.`defaultReceiver` AS `defaultReceiver`,`fhf`.`id` AS `fieldId`,`fhfhl`.`name` AS `fieldName`,`fi`.`id` AS `fieldTypeId`,`fi`.`name` AS `fieldTypeName`,`fi`.`type` AS `fieldType`,`fhfhl`.`description` AS `fieldDescription`,`fhfhl`.`help` AS `fieldHelp`,`fhf`.`required` AS `required`,`fhf`.`metric` AS `metric`,`fo`.`language` AS `language` from (((`v_form` `fo` join `form_has_field` `fhf` on((`fo`.`id` = `fhf`.`form_id`))) join `v_field` `fi` on(((`fi`.`id` = `fhf`.`field_id`) and (`fi`.`language` = `fo`.`language`)))) join `form_has_field_has_language` `fhfhl` on(((`fhfhl`.`form_has_field_id` = `fhf`.`id`) and (`fhfhl`.`language_abbreviation` = `fi`.`language`))));

-- --------------------------------------------------------

--
-- Structure de la vue `v_galery`
--
DROP TABLE IF EXISTS `v_galery`;

CREATE VIEW `v_galery` AS select `galery_has_language`.`galery_id` AS `id`,`galery_has_language`.`title` AS `title`,`galery_has_language`.`description` AS `description`,`galery_has_language`.`language_abbreviation` AS `language` from `galery_has_language`;

-- --------------------------------------------------------

--
-- Structure de la vue `v_menu`
--
DROP TABLE IF EXISTS `v_menu`;

CREATE VIEW `v_menu` AS select `m`.`id` AS `id`,`mhl`.`name` AS `name`,`mhl`.`description` AS `description`,`m`.`metric` AS `metric`,`mhl`.`visible` AS `visible`,`m`.`private` AS `private`,`m`.`options` AS `options`,`m`.`module_has_controller_module_name` AS `module_name`,`m`.`module_has_controller_controller_name` AS `controller_name`,`mhl`.`language_abbreviation` AS `language` from (`menu` `m` join `menu_has_language` `mhl` on((`m`.`id` = `mhl`.`menu_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `v_news`
--
DROP TABLE IF EXISTS `v_news`;

CREATE VIEW `v_news` AS select `n`.`id` AS `id`,`ns`.`id` AS `subject_id`,`ns`.`name` AS `subject_name`,`nhl`.`title` AS `title`,`nhl`.`content` AS `content`,`nhl`.`visible` AS `visible`,`n`.`created_date` AS `created_date`,`nhl`.`modified_date` AS `modified_date`,`n`.`picture_id` AS `picture_id`,`ns`.`language` AS `language` from ((`news` `n` join `v_news_subject` `ns` on((`n`.`news_subject_id` = `ns`.`id`))) join `news_has_language` `nhl` on((`n`.`id` = `nhl`.`news_id`))) where (convert(`nhl`.`language_abbreviation` using utf8) = convert(`ns`.`language` using utf8));

-- --------------------------------------------------------

--
-- Structure de la vue `v_news_subject`
--
DROP TABLE IF EXISTS `v_news_subject`;

CREATE VIEW `v_news_subject` AS select `ns`.`id` AS `id`,`nshl`.`name` AS `name`,`nshl`.`language_abbreviation` AS `language` from (`news_subject` `ns` join `news_subject_has_language` `nshl` on((`ns`.`id` = `nshl`.`news_subject_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `v_picture`
--
DROP TABLE IF EXISTS `v_picture`;

CREATE VIEW `v_picture` AS select `p`.`id` AS `id`,`p`.`galery_id` AS `galeryId`,`p`.`format` AS `format`,`phl`.`name` AS `name`,`phl`.`description` AS `description`,`phl`.`language_abbreviation` AS `language` from (`picture` `p` join `picture_has_language` `phl` on((`p`.`id` = `phl`.`picture_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `v_simple_text`
--
DROP TABLE IF EXISTS `v_simple_text`;

CREATE VIEW `v_simple_text` AS select `st`.`id` AS `id`,`sthl`.`content` AS `content`,`st`.`private` AS `private`,`sthl`.`modified_date` AS `modified_date`,`sthl`.`language_abbreviation` AS `language` from (`simple_text` `st` join `simple_text_has_language` `sthl` on((`st`.`id` = `sthl`.`simple_text_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `v_submenu`
--
DROP TABLE IF EXISTS `v_submenu`;

CREATE VIEW `v_submenu` AS select `s`.`id` AS `id`,`shl`.`name` AS `name`,`shl`.`description` AS `description`,`s`.`menu_id` AS `menu_id`,`s`.`metric` AS `metric`,`s`.`options` AS `options`,`shl`.`visible` AS `visible`,`s`.`module_has_controller_module_name` AS `module_name`,`s`.`module_has_controller_controller_name` AS `controller_name`,`shl`.`language_abbreviation` AS `language` from (`submenu` `s` join `submenu_has_language` `shl` on((`s`.`id` = `shl`.`submenu_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `v_user`
--
DROP TABLE IF EXISTS `v_user`;

CREATE VIEW `v_user` AS select `u`.`login` AS `login`,`u`.`password` AS `password`,`u`.`salt` AS `salt`,`u`.`name` AS `name`,`u`.`surname` AS `surname`,`u`.`mail` AS `mail`,`c`.`name` AS `category_name`,`c`.`description` AS `category_description`,`c`.`manage_news` AS `manage_news`,`c`.`manage_mixed_content` AS `manage_mixed_content`,`c`.`manage_user_category` AS `manage_user_category`,`c`.`manage_user` AS `manage_user`,`c`.`manage_menu` AS `manage_menu`,`c`.`manage_submenu` AS `manage_submenu`,`c`.`manage_file` AS `manage_file`,`c`.`manage_right` AS `manage_right`,`c`.`language` AS `language` from (`user` `u` join `v_category` `c` on((`u`.`category_id` = `c`.`id`)));

--
-- Index pour les tables exportées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category_has_language`
--
ALTER TABLE `category_has_language`
  ADD PRIMARY KEY (`category_id`,`language_abbreviation`),
  ADD KEY `fk_category_has_language_category1_idx` (`category_id`),
  ADD KEY `fk_category_has_language_language1_idx` (`language_abbreviation`);

--
-- Index pour la table `controller`
--
ALTER TABLE `controller`
  ADD PRIMARY KEY (`name`);

--
-- Index pour la table `field`
--
ALTER TABLE `field`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `field_has_language`
--
ALTER TABLE `field_has_language`
  ADD PRIMARY KEY (`field_id`,`language_abbreviation`),
  ADD KEY `fk_field_has_language_language1_idx` (`language_abbreviation`),
  ADD KEY `fk_field_has_language_field1_idx` (`field_id`);

--
-- Index pour la table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_file_user1_idx` (`user_login`,`user_category_id`);

--
-- Index pour la table `form`
--
ALTER TABLE `form`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `form_has_field`
--
ALTER TABLE `form_has_field`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_table1_field1_idx` (`field_id`),
  ADD KEY `fk_table1_form1_idx` (`form_id`);

--
-- Index pour la table `form_has_field_has_language`
--
ALTER TABLE `form_has_field_has_language`
  ADD PRIMARY KEY (`form_has_field_id`,`language_abbreviation`),
  ADD KEY `fk_form_has_field_has_language_language1_idx` (`language_abbreviation`),
  ADD KEY `fk_form_has_field_has_language_form_has_field1_idx` (`form_has_field_id`);

--
-- Index pour la table `form_has_language`
--
ALTER TABLE `form_has_language`
  ADD PRIMARY KEY (`form_id`,`language_abbreviation`),
  ADD KEY `fk_form_has_language_language1_idx` (`language_abbreviation`),
  ADD KEY `fk_form_has_language_form1_idx` (`form_id`);

--
-- Index pour la table `galery`
--
ALTER TABLE `galery`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `galery_has_language`
--
ALTER TABLE `galery_has_language`
  ADD PRIMARY KEY (`galery_id`,`language_abbreviation`),
  ADD KEY `fk_galery_has_language_language1_idx` (`language_abbreviation`),
  ADD KEY `fk_galery_has_language_galery1_idx` (`galery_id`);

--
-- Index pour la table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`abbreviation`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD UNIQUE KEY `abreviation_UNIQUE` (`abbreviation`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_module_has_controller1_idx` (`module_has_controller_module_name`,`module_has_controller_controller_name`);

--
-- Index pour la table `menu_has_language`
--
ALTER TABLE `menu_has_language`
  ADD PRIMARY KEY (`menu_id`,`language_abbreviation`),
  ADD KEY `fk_menu_has_language_language1_idx` (`language_abbreviation`),
  ADD KEY `fk_menu_has_language_menu1_idx` (`menu_id`);

--
-- Index pour la table `mixed_content`
--
ALTER TABLE `mixed_content`
  ADD PRIMARY KEY (`id`,`mixed_page_id`),
  ADD KEY `fk_Mixed_Content_Mixed_Page1_idx` (`mixed_page_id`);

--
-- Index pour la table `mixed_content_list`
--
ALTER TABLE `mixed_content_list`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `module_has_controller`
--
ALTER TABLE `module_has_controller`
  ADD PRIMARY KEY (`module_name`,`controller_name`),
  ADD KEY `fk_module_has_controller_controller1_idx` (`controller_name`),
  ADD KEY `fk_module_has_controller_module1_idx` (`module_name`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_news_subject1_idx` (`news_subject_id`);

--
-- Index pour la table `news_has_language`
--
ALTER TABLE `news_has_language`
  ADD PRIMARY KEY (`news_id`,`language_abbreviation`),
  ADD KEY `fk_news_has_language1_language1_idx` (`language_abbreviation`),
  ADD KEY `fk_news_has_language1_news1_idx` (`news_id`);

--
-- Index pour la table `news_subject`
--
ALTER TABLE `news_subject`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `news_subject_has_language`
--
ALTER TABLE `news_subject_has_language`
  ADD PRIMARY KEY (`news_subject_id`,`language_abbreviation`),
  ADD KEY `fk_news_subject_has_language_news_subject1_idx` (`news_subject_id`),
  ADD KEY `fk_news_subject_has_language_language1_idx` (`language_abbreviation`);

--
-- Index pour la table `picture`
--
ALTER TABLE `picture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_picture_galery1_idx` (`galery_id`);

--
-- Index pour la table `picture_has_language`
--
ALTER TABLE `picture_has_language`
  ADD PRIMARY KEY (`picture_id`,`language_abbreviation`),
  ADD KEY `fk_picture_has_language_language1_idx` (`language_abbreviation`),
  ADD KEY `fk_picture_has_language_picture1_idx` (`picture_id`);

--
-- Index pour la table `simple_text`
--
ALTER TABLE `simple_text`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `simple_text_has_language`
--
ALTER TABLE `simple_text_has_language`
  ADD PRIMARY KEY (`simple_text_id`,`language_abbreviation`),
  ADD KEY `fk_simple_text_has_language_simple_text1_idx` (`simple_text_id`),
  ADD KEY `fk_simple_text_has_language_language1_idx` (`language_abbreviation`);

--
-- Index pour la table `submenu`
--
ALTER TABLE `submenu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Submenu_Menu1_idx` (`menu_id`),
  ADD KEY `fk_submenu_module_has_controller1_idx` (`module_has_controller_module_name`,`module_has_controller_controller_name`);

--
-- Index pour la table `submenu_has_language`
--
ALTER TABLE `submenu_has_language`
  ADD PRIMARY KEY (`submenu_id`,`language_abbreviation`),
  ADD KEY `fk_submenu_has_language_submenu1_idx` (`submenu_id`),
  ADD KEY `fk_submenu_has_language_language1_idx` (`language_abbreviation`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`login`),
  ADD UNIQUE KEY `pseudo_UNIQUE` (`login`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `field`
--
ALTER TABLE `field`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `form`
--
ALTER TABLE `form`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `form_has_field`
--
ALTER TABLE `form_has_field`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `galery`
--
ALTER TABLE `galery`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT pour la table `mixed_content`
--
ALTER TABLE `mixed_content`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `mixed_content_list`
--
ALTER TABLE `mixed_content_list`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `news_subject`
--
ALTER TABLE `news_subject`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `picture`
--
ALTER TABLE `picture`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `simple_text`
--
ALTER TABLE `simple_text`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT pour la table `submenu`
--
ALTER TABLE `submenu`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `category_has_language`
--
ALTER TABLE `category_has_language`
  ADD CONSTRAINT `fk_category_has_language_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_category_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `field_has_language`
--
ALTER TABLE `field_has_language`
  ADD CONSTRAINT `fk_field_has_language_field1` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_field_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `fk_file_user1` FOREIGN KEY (`user_login`) REFERENCES `user` (`login`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `form_has_field`
--
ALTER TABLE `form_has_field`
  ADD CONSTRAINT `fk_table1_field1` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_form1` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `form_has_field_has_language`
--
ALTER TABLE `form_has_field_has_language`
  ADD CONSTRAINT `fk_form_has_field_has_language_form_has_field1` FOREIGN KEY (`form_has_field_id`) REFERENCES `form_has_field` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_form_has_field_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `form_has_language`
--
ALTER TABLE `form_has_language`
  ADD CONSTRAINT `fk_form_has_language_form1` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_form_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `galery_has_language`
--
ALTER TABLE `galery_has_language`
  ADD CONSTRAINT `fk_galery_has_language_galery1` FOREIGN KEY (`galery_id`) REFERENCES `galery` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_galery_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_module_has_controller1` FOREIGN KEY (`module_has_controller_module_name`, `module_has_controller_controller_name`) REFERENCES `module_has_controller` (`module_name`, `controller_name`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `menu_has_language`
--
ALTER TABLE `menu_has_language`
  ADD CONSTRAINT `fk_menu_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_menu_has_language_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `mixed_content`
--
ALTER TABLE `mixed_content`
  ADD CONSTRAINT `fk_Mixed_Content_Mixed_Page1` FOREIGN KEY (`mixed_page_id`) REFERENCES `mixed_content_list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `module_has_controller`
--
ALTER TABLE `module_has_controller`
  ADD CONSTRAINT `fk_module_has_controller_controller1` FOREIGN KEY (`controller_name`) REFERENCES `controller` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_module_has_controller_module1` FOREIGN KEY (`module_name`) REFERENCES `module` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_news_subject1` FOREIGN KEY (`news_subject_id`) REFERENCES `news_subject` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `news_has_language`
--
ALTER TABLE `news_has_language`
  ADD CONSTRAINT `fk_news_has_language1_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_news_has_language1_news1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `news_subject_has_language`
--
ALTER TABLE `news_subject_has_language`
  ADD CONSTRAINT `fk_news_subject_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_news_subject_has_language_news_subject1` FOREIGN KEY (`news_subject_id`) REFERENCES `news_subject` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `picture`
--
ALTER TABLE `picture`
  ADD CONSTRAINT `fk_picture_galery1` FOREIGN KEY (`galery_id`) REFERENCES `galery` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `picture_has_language`
--
ALTER TABLE `picture_has_language`
  ADD CONSTRAINT `fk_picture_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_picture_has_language_picture1` FOREIGN KEY (`picture_id`) REFERENCES `picture` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `simple_text_has_language`
--
ALTER TABLE `simple_text_has_language`
  ADD CONSTRAINT `fk_simple_text_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_simple_text_has_language_simple_text1` FOREIGN KEY (`simple_text_id`) REFERENCES `simple_text` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `submenu`
--
ALTER TABLE `submenu`
  ADD CONSTRAINT `fk_Submenu_Menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_submenu_module_has_controller1` FOREIGN KEY (`module_has_controller_module_name`, `module_has_controller_controller_name`) REFERENCES `module_has_controller` (`module_name`, `controller_name`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `submenu_has_language`
--
ALTER TABLE `submenu_has_language`
  ADD CONSTRAINT `fk_submenu_has_language_language1` FOREIGN KEY (`language_abbreviation`) REFERENCES `language` (`abbreviation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_submenu_has_language_submenu1` FOREIGN KEY (`submenu_id`) REFERENCES `submenu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
