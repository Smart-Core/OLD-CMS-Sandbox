-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Янв 27 2014 г., 09:53
-- Версия сервера: 5.6.13
-- Версия PHP: 5.5.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `smart_core`
--

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_engine_blocks`
--

DROP TABLE IF EXISTS `aaa_engine_blocks`;
CREATE TABLE IF NOT EXISTS `aaa_engine_blocks` (
  `block_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `position` smallint(6) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `create_by_user_id` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  PRIMARY KEY (`block_id`),
  UNIQUE KEY `name` (`name`),
  KEY `position` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `aaa_engine_blocks`
--

INSERT INTO `aaa_engine_blocks` (`block_id`, `position`, `name`, `descr`, `create_by_user_id`, `create_datetime`) VALUES
(1, 0, 'content', 'Рабочая область', 1, '2013-03-11 01:09:17'),
(2, 2, 'breadcrumbs', 'Хлебные крошки', 1, '2013-03-11 01:09:33'),
(3, 1, 'main_menu', 'Навигационное меню', 1, '2013-03-11 04:00:50'),
(4, 3, 'footer', 'Футер', 1, '2013-03-11 04:01:30'),
(5, 5, 'right_column', 'Правая колонка', 1, '2013-03-23 23:46:01'),
(6, 3, 'footer_right', 'Футер справа', 1, '2014-01-20 04:04:24');

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_engine_blocks_inherit`
--

DROP TABLE IF EXISTS `aaa_engine_blocks_inherit`;
CREATE TABLE IF NOT EXISTS `aaa_engine_blocks_inherit` (
  `block_id` smallint(6) NOT NULL,
  `folder_id` int(11) NOT NULL,
  PRIMARY KEY (`block_id`,`folder_id`),
  KEY `IDX_4B3EA624E9ED820C` (`block_id`),
  KEY `IDX_4B3EA624162CB942` (`folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `aaa_engine_blocks_inherit`
--

INSERT INTO `aaa_engine_blocks_inherit` (`block_id`, `folder_id`) VALUES
(2, 1),
(3, 1),
(4, 1),
(6, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_engine_folders`
--

DROP TABLE IF EXISTS `aaa_engine_folders`;
CREATE TABLE IF NOT EXISTS `aaa_engine_folders` (
  `folder_id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_pid` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_file` tinyint(1) DEFAULT NULL,
  `position` smallint(6) DEFAULT NULL,
  `uri_part` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `descr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `redirect_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `router_node_id` int(11) DEFAULT NULL,
  `has_inherit_nodes` tinyint(1) DEFAULT NULL,
  `permissions` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `lockout_nodes` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `template_inheritable` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_by_user_id` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `template_self` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`folder_id`),
  UNIQUE KEY `folder_pid_uri_part` (`folder_pid`,`uri_part`),
  KEY `IDX_6B4611ABA640A07B` (`folder_pid`),
  KEY `is_active` (`is_active`),
  KEY `is_deleted` (`is_deleted`),
  KEY `position` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `aaa_engine_folders`
--

INSERT INTO `aaa_engine_folders` (`folder_id`, `folder_pid`, `title`, `is_file`, `position`, `uri_part`, `is_active`, `is_deleted`, `descr`, `meta`, `redirect_to`, `router_node_id`, `has_inherit_nodes`, `permissions`, `lockout_nodes`, `template_inheritable`, `create_by_user_id`, `create_datetime`, `template_self`) VALUES
(1, NULL, 'Главная', 0, 0, NULL, 1, 0, ':)', 'a:5:{s:8:"keywords";s:14:"123 ффыв 3";s:11:"description";s:0:"";s:6:"robots";s:3:"all";s:8:"language";s:5:"ru-RU";s:6:"author";s:10:"Артём";}', NULL, NULL, 1, NULL, NULL, 'main', 1, '2013-03-19 00:44:38', NULL),
(2, 1, 'О компании', 0, 10, 'about', 1, 0, NULL, 'N;', NULL, NULL, 0, NULL, NULL, 'inner', 1, '2013-03-11 16:42:33', NULL),
(3, 1, 'Аккаунт пользователя', 0, 999, 'user', 1, 0, NULL, 'N;', NULL, 7, 0, 'N;', 'N;', NULL, 1, '2013-03-18 01:15:06', NULL),
(4, 3, 'Регистрация', 0, 0, 'register_REMOVE', 0, 0, NULL, 'N;', NULL, 8, 0, 'N;', 'N;', NULL, 1, '2013-03-18 01:15:27', NULL),
(5, 1, 'Так просто ;)', 0, 3, 'simple', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', 'main', 1, '2013-03-19 04:43:50', NULL),
(6, 2, 'Вложенная папка', 0, 0, 'inner', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2013-03-19 04:47:22', NULL),
(7, 1, '22222222222222', 0, 10, '22222222', 0, 0, '22', 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2013-08-10 11:14:06', NULL),
(8, 1, 'Новости', 0, 0, 'news', 1, 0, NULL, 'N;', NULL, 12, 0, 'N;', 'N;', NULL, 1, '2013-12-22 21:45:42', NULL),
(9, 1, 'Обратная связь', 0, 0, 'feedback', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2014-01-21 13:35:11', NULL),
(10, 1, 'test', 0, 0, '10', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2014-01-23 23:06:27', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_engine_nodes`
--

DROP TABLE IF EXISTS `aaa_engine_nodes`;
CREATE TABLE IF NOT EXISTS `aaa_engine_nodes` (
  `node_id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) DEFAULT NULL,
  `block_id` smallint(6) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `position` smallint(6) DEFAULT NULL,
  `priority` smallint(6) NOT NULL,
  `descr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_by_user_id` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `is_cached` tinyint(1) DEFAULT NULL,
  `template` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`node_id`),
  KEY `IDX_F4FF528B162CB942` (`folder_id`),
  KEY `IDX_F4FF528BE9ED820C` (`block_id`),
  KEY `is_active` (`is_active`),
  KEY `position` (`position`),
  KEY `module` (`module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `aaa_engine_nodes`
--

INSERT INTO `aaa_engine_nodes` (`node_id`, `folder_id`, `block_id`, `is_active`, `module`, `params`, `position`, `priority`, `descr`, `create_by_user_id`, `create_datetime`, `is_cached`, `template`) VALUES
(1, 1, 4, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:1;s:6:"editor";s:1:"1";}', 20, 0, 'Футер', 1, '2013-03-20 05:46:40', 0, NULL),
(2, 2, 5, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:4;s:6:"editor";s:1:"1";}', 0, 0, 'Правая колонка', 1, '2013-03-20 09:07:33', 0, NULL),
(3, 2, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:3;s:6:"editor";s:1:"1";}', 0, 0, 'Хедер', 1, '2013-03-21 06:03:37', 0, NULL),
(4, 1, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:2;s:6:"editor";s:1:"1";}', 0, 0, 'Главная', 1, '2013-03-11 16:42:33', NULL, NULL),
(5, 1, 3, 1, 'Menu', 'a:4:{s:5:"depth";N;s:8:"group_id";i:1;s:9:"css_class";s:9:"main_menu";s:20:"selected_inheritance";b:0;}', 1, 0, NULL, 1, '2013-03-11 16:42:33', 1, NULL),
(6, 1, 2, 1, 'Breadcrumbs', 'a:2:{s:9:"delimiter";s:2:"»";s:17:"hide_if_only_home";b:1;}', 0, -255, NULL, 1, '2013-03-11 16:42:33', 0, NULL),
(7, 3, 1, 1, 'User', 'a:2:{s:18:"allow_registration";b:1;s:24:"allow_password_resetting";b:1;}', 0, 255, NULL, 1, '2013-03-11 16:42:33', 0, NULL),
(9, 3, 3, 1, 'Texter', 'a:1:{s:12:"text_item_id";i:6;}', 0, 0, 'Текст под меню', 1, '2013-03-25 21:53:12', NULL, NULL),
(10, 7, 1, 1, 'Texter', 'a:1:{s:12:"text_item_id";i:7;}', 0, 0, NULL, 1, '2013-08-10 11:14:55', 0, NULL),
(11, 5, 1, 1, 'Texter', 'a:1:{s:12:"text_item_id";i:8;}', 0, 0, NULL, 1, '2013-12-20 20:11:41', 0, NULL),
(12, 8, 1, 1, 'News', 'a:0:{}', 1, 0, NULL, 1, '2013-12-22 21:58:57', 0, NULL),
(13, 1, 6, 1, 'Texter', 'a:1:{s:12:"text_item_id";i:9;}', 0, 0, NULL, 1, '2014-01-20 03:47:18', 0, NULL),
(14, 9, 1, 1, 'Feedback', 'a:0:{}', 0, 0, NULL, 1, '2014-01-21 19:32:26', 0, NULL),
(15, 8, 1, 1, 'Texter', 'a:1:{s:12:"text_item_id";i:10;}', 0, 0, NULL, 1, '2014-01-22 19:02:27', 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_engine_roles`
--

DROP TABLE IF EXISTS `aaa_engine_roles`;
CREATE TABLE IF NOT EXISTS `aaa_engine_roles` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `position` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5FFC79B05E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `aaa_engine_roles`
--

INSERT INTO `aaa_engine_roles` (`id`, `name`, `position`) VALUES
(1, 'ROLE_ADMIN', 0),
(2, 'ROLE_ROOT', 0),
(3, 'ROLE_NEWSMAKER', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_feedbacks`
--

DROP TABLE IF EXISTS `aaa_feedbacks`;
CREATE TABLE IF NOT EXISTS `aaa_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `aaa_feedbacks`
--


-- --------------------------------------------------------

--
-- Структура таблицы `aaa_front_end_libraries`
--

DROP TABLE IF EXISTS `aaa_front_end_libraries`;
CREATE TABLE IF NOT EXISTS `aaa_front_end_libraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `related_by` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `proirity` smallint(6) NOT NULL,
  `current_version` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `files` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_23D980CD5E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `aaa_front_end_libraries`
--

INSERT INTO `aaa_front_end_libraries` (`id`, `name`, `related_by`, `proirity`, `current_version`, `files`) VALUES
(4, 'jquery', NULL, 1000, '1.9.1', 'jquery.min.js'),
(5, 'bootstrap', 'jquery', 0, '2.3.2', 'css/bootstrap.min.css,css/bootstrap-responsive.min.css,js/bootstrap.min.js'),
(6, 'less', NULL, 0, '1.3.3', 'less.min.js');

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_front_end_libraries_paths`
--

DROP TABLE IF EXISTS `aaa_front_end_libraries_paths`;
CREATE TABLE IF NOT EXISTS `aaa_front_end_libraries_paths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lib_id` int(11) NOT NULL,
  `version` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version_lib` (`version`,`lib_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `aaa_front_end_libraries_paths`
--

INSERT INTO `aaa_front_end_libraries_paths` (`id`, `lib_id`, `version`, `path`) VALUES
(1, 4, '1.9.1', 'jquery/1.9.1/'),
(2, 5, '2.3.2', 'bootstrap/2.3.2/'),
(3, 6, '1.3.3', 'less/1.3.3/');

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_menu`
--

DROP TABLE IF EXISTS `aaa_menu`;
CREATE TABLE IF NOT EXISTS `aaa_menu` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `position` smallint(6) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_by_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  PRIMARY KEY (`item_id`),
  KEY `IDX_D885BF9AFE54D947` (`group_id`),
  KEY `IDX_D885BF9A5550C4ED` (`pid`),
  KEY `IDX_D885BF9A162CB942` (`folder_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `aaa_menu`
--

INSERT INTO `aaa_menu` (`item_id`, `group_id`, `folder_id`, `is_active`, `position`, `title`, `descr`, `url`, `create_by_user_id`, `created`, `updated`, `pid`, `properties`) VALUES
(1, 1, 1, 1, 0, NULL, NULL, NULL, 1, '2013-05-06 05:25:48', '2013-05-06 11:13:53', NULL, NULL),
(2, 1, 2, 1, 3, NULL, '123 561', NULL, 1, '2013-05-06 05:48:06', '2014-01-21 15:53:20', NULL, NULL),
(3, 1, 3, 1, 999, NULL, NULL, NULL, 1, '2013-05-06 07:28:54', '2013-12-22 08:49:04', NULL, NULL),
(5, 1, 6, 1, 0, NULL, NULL, NULL, 1, '2013-05-06 08:45:04', NULL, 2, NULL),
(6, 1, 5, 1, 2, NULL, NULL, NULL, 1, '2013-05-06 09:38:51', '2014-01-21 15:52:24', NULL, NULL),
(7, 1, 7, 0, 2, NULL, NULL, NULL, 1, '2013-08-10 11:14:29', '2014-01-20 06:36:00', NULL, NULL),
(8, 1, 8, 1, 1, NULL, NULL, NULL, 1, '2013-12-22 21:45:59', '2014-01-21 15:52:18', NULL, NULL),
(9, 1, 9, 1, 4, NULL, NULL, NULL, 1, '2014-01-21 15:51:46', '2014-01-21 15:53:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_menu_groups`
--

DROP TABLE IF EXISTS `aaa_menu_groups`;
CREATE TABLE IF NOT EXISTS `aaa_menu_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `position` smallint(6) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_by_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `properties` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `UNIQ_E8E3E5515E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `aaa_menu_groups`
--

INSERT INTO `aaa_menu_groups` (`group_id`, `position`, `name`, `descr`, `create_by_user_id`, `created`, `properties`) VALUES
(1, 0, 'Главное меню', NULL, 1, '2013-05-06 03:54:13', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_news`
--

DROP TABLE IF EXISTS `aaa_news`;
CREATE TABLE IF NOT EXISTS `aaa_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `annotation` longtext COLLATE utf8_unicode_ci,
  `text` longtext COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_33961BA3989D9B62` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `aaa_news`
--

INSERT INTO `aaa_news` (`id`, `title`, `slug`, `annotation`, `text`, `created`, `updated`) VALUES
(1, 'Первая', 'first', 'Анонс первой новости.', 'Тема: &laquo;Сублимированный рейтинг в XXI веке&raquo; Взаимодействие корпорации и клиента амбивалентно. Агентская комиссия специфицирует мониторинг активности, используя опыт предыдущих кампаний. Ассортиментная политика предприятия развивает стратегический маркетинг, используя опыт предыдущих кампаний. Более того, взаимодействие корпорации и клиента искажает бренд, расширяя долю рынка.', '2013-12-22 22:17:46', NULL),
(2, 'Вторая', 'second', 'Анонс второй новости.', 'Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.', '2013-12-16 22:18:21', NULL),
(3, 'PHP: Hypertext Preprocessor', 'php', 'Server-side HTML embedded scripting language. It provides web developers with a full suite of tools for building dynamic websites: native APIs to Apache and ...', '<div class="blurb">\r\n<p>PHP is a popular general-purpose scripting language that is especially suited to web development.</p>\r\n<p>Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.<br /><br /><acronym title="PHP: Hypertext Preprocessor">PHP</acronym> (рекурсивный акроним словосочетания <em>PHP: Hypertext Preprocessor</em>) - это распространенный язык программирования общего назначения с открытым исходным кодом. PHP сконструирован специально для ведения Web-разработок и его код может внедряться непосредственно в HTML.</p>\r\n</div>', '2014-01-20 02:33:25', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_session`
--

DROP TABLE IF EXISTS `aaa_session`;
CREATE TABLE IF NOT EXISTS `aaa_session` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `aaa_session`
--


-- --------------------------------------------------------

--
-- Структура таблицы `aaa_sitemap_urls`
--

DROP TABLE IF EXISTS `aaa_sitemap_urls`;
CREATE TABLE IF NOT EXISTS `aaa_sitemap_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_visited` tinyint(1) NOT NULL,
  `loc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` longtext COLLATE utf8_unicode_ci,
  `title_dublicates` int(11) NOT NULL,
  `lastmod` datetime DEFAULT NULL,
  `changefreq` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority` double DEFAULT NULL,
  `status` smallint(6) NOT NULL,
  `referer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F2FA10BE8852ACDC` (`loc`),
  KEY `title_hash` (`title_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `aaa_sitemap_urls`
--


-- --------------------------------------------------------

--
-- Структура таблицы `aaa_texter`
--

DROP TABLE IF EXISTS `aaa_texter`;
CREATE TABLE IF NOT EXISTS `aaa_texter` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) DEFAULT NULL,
  `text` longtext,
  `meta` longtext NOT NULL COMMENT '(DC2Type:array)',
  `created` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `editor` smallint(6) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `aaa_texter`
--

INSERT INTO `aaa_texter` (`item_id`, `locale`, `text`, `meta`, `created`, `user_id`, `editor`) VALUES
(1, 'ru', 'Футер 1', 'a:0:{}', '2012-08-27 03:16:57', 1, 0),
(2, 'ru', '<h1>Главная страница!</h1>\r\n<p>С точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма, концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих геотрансплантационных квазипузлистатов всех кинетически коррелирующих аспектов. Исходя из этого, мы пришли к выводу, что каждый произвольно выбранный предикативно абсорбирующий объект.</p>\r\n<img src="/uploads/Advanced%20C%20Asana.jpg" alt="" width="891" height="666" />', 'a:1:{s:8:"keywords";s:3:"123";}', '2012-08-27 03:17:27', 1, 0),
(3, 'ru', '<h2>Пример страницы с 2-мя колонками</h2>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>', 'a:1:{s:8:"keywords";s:3:"sdf";}', '2012-08-27 03:51:05', 1, 0),
(4, 'ru', '<p><img src="/uploads/images/bscap0001.jpg" alt="" width="300" height="124" /><br />Сервисная стратегия деятельно искажает продвигаемый медиаплан, опираясь на опыт западных коллег. Внутрифирменная реклама, согласно Ф.Котлеру, откровенно цинична. Торговая марка исключительно уравновешивает презентационный материал, полагаясь на инсайдерскую информацию. Наряду с этим, узнавание бренда вполне выполнимо. Организация слубы маркетинга, согласно Ф.Котлеру, усиливает фактор коммуникации, осознавая социальную ответственность бизнеса. Экспертиза выполненного проекта восстанавливает потребительский презентационный материал, полагаясь на инсайдерскую информацию.</p>', 'a:0:{}', '2012-08-27 03:51:27', 1, 0),
(5, 'ru', 'Текстер №5', 'a:0:{}', '2013-03-21 06:03:37', 1, 0),
(6, 'ru', 'Under menu 2.', 'a:0:{}', '2013-03-25 21:53:12', 1, 0),
(7, 'ru', 'sdf gsdfg dsf gsdf gdsfg sdf g', 'a:0:{}', '2013-08-10 11:14:55', 1, 0),
(8, 'ru', '<p>Нельзя так просто взять и написать цмс-ку ;)<br /><br /><img src="/uploads/images/bscap0001_big.jpg" alt="" width="1680" height="693" /></p>', 'a:0:{}', '2013-12-20 20:11:42', 1, 0),
(9, 'ru', 'Powered by <a href="http://symfony.com" target="_blank">Symfony2</a>', 'a:0:{}', '2014-01-20 03:47:18', 1, 0),
(10, 'ru', 'Очень интересные новости ;)', 'a:0:{}', '2014-01-22 19:02:28', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_texter_history`
--

DROP TABLE IF EXISTS `aaa_texter_history`;
CREATE TABLE IF NOT EXISTS `aaa_texter_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_deleted` tinyint(1) NOT NULL,
  `item_id` int(11) NOT NULL,
  `locale` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `editor` smallint(6) NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `aaa_texter_history`
--


-- --------------------------------------------------------

--
-- Структура таблицы `aaa_users`
--

DROP TABLE IF EXISTS `aaa_users`;
CREATE TABLE IF NOT EXISTS `aaa_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `username_canonical` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_canonical` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `facebook_id` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `aaa_users`
--

INSERT INTO `aaa_users` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `firstname`, `lastname`, `facebook_id`, `created`) VALUES
(1, 'root', 'root', 'artem@mail.ru', 'artem@mail.ru', 1, 'rvmppg4hla80gw0c88wwkogkc8cg88c', 'pSRvk1iSFWol6tPyvrt8ULb6A03pa3jT8LNsVv9eYC9DSQMFLL91dzHBNvPFUFuICMMvFqzYBnyDVaW+Eg3eRg==', '2014-01-27 09:19:22', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:9:"ROLE_ROOT";}', 0, NULL, '', '', '', '2014-01-20 00:00:00'),
(2, 'demo', 'demo', 'demo@mail.com', 'demo@mail.com', 1, '15lr4t5s1pdwowoc8k88goc88k00w8', 'MdaZxuZKbcCL1IePGhILE6v+iUUKrINsdpdMMmsc1+LZ7ZBERkb8s+Q6hlp9n4lhU9QKUwnhFpGi8vvjHOPORw==', '2014-01-19 18:56:18', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:14:"ROLE_NEWSMAKER";}', 0, NULL, '', '', '', '2014-01-20 00:00:00'),
(3, 'aaa', 'aaa', 'aaa@aaa.ru', 'aaa@aaa.ru', 1, 'teyhcartb3ks0kw4sw0co0k8ko0gk48', '+Qtvl5uc9knUH6z2ZB/7qqZLueaGSfs1yS7TVt4h6CQtNY/a/wG4gdDV+hxR/eSnotc4PGGrRvqnHfdzOmyJNA==', '2014-01-19 18:41:30', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, '', '', '', '2014-01-20 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_permissions`
--

DROP TABLE IF EXISTS `engine_permissions`;
CREATE TABLE IF NOT EXISTS `engine_permissions` (
  `group_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `default_access` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `descr` text COMMENT 'Описание',
  PRIMARY KEY (`group_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Права доступа';

--
-- Дамп данных таблицы `engine_permissions`
--

INSERT INTO `engine_permissions` (`group_id`, `name`, `default_access`, `title`, `descr`) VALUES
('engine', 'folder.read', 1, 'Чтение папок', NULL),
('engine', 'folder.view', 1, 'Отображение', NULL),
('engine', 'folder.write', 0, 'Создание в папке других папок и нод.', NULL),
('engine', 'node.read', 1, 'Чтение ноды', 'Отображается нода или нет, соответственно обрабатывает её движок или нет.'),
('engine', 'node.write', 0, 'Запись ноды', 'Возможность передачи ноде POST данных. '),
('module.smartcore.news', 'create', 0, 'Создание новости', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `engine_permissions_defaults`
--

DROP TABLE IF EXISTS `engine_permissions_defaults`;
CREATE TABLE IF NOT EXISTS `engine_permissions_defaults` (
  `permission` varchar(100) NOT NULL,
  `role_id` varchar(50) NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '0',
  `descr` text NOT NULL,
  PRIMARY KEY (`role_id`),
  KEY `access` (`access`),
  KEY `permission` (`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Переопределения прав по умолчанию для различных ролей';

--
-- Дамп данных таблицы `engine_permissions_defaults`
--

INSERT INTO `engine_permissions_defaults` (`permission`, `role_id`, `access`, `descr`) VALUES
('engine:folder.write', 'ROLE_ADMIN', 1, 'Для администраторов разрешена запись в папки.'),
('engine:folder.write', 'ROLE_USER', 0, 'Юзеры не могут создавать папки.');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_permissions_groups`
--

DROP TABLE IF EXISTS `engine_permissions_groups`;
CREATE TABLE IF NOT EXISTS `engine_permissions_groups` (
  `group_id` varchar(50) NOT NULL,
  `descr` text COMMENT 'Описание',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Группы прав доступа';

--
-- Дамп данных таблицы `engine_permissions_groups`
--

INSERT INTO `engine_permissions_groups` (`group_id`, `descr`) VALUES
('engine', 'Ядро'),
('module.smartcore.news', 'Модуль Новости'),
('module.smartcore.texter', 'Модуль Текстер');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_roles`
--

DROP TABLE IF EXISTS `engine_roles`;
CREATE TABLE IF NOT EXISTS `engine_roles` (
  `role_id` varchar(50) NOT NULL,
  `pos` smallint(5) unsigned NOT NULL DEFAULT '0',
  `descr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Роли пользователей';

--
-- Дамп данных таблицы `engine_roles`
--

INSERT INTO `engine_roles` (`role_id`, `pos`, `descr`) VALUES
('ROLE_ADMIN', 0, '-'),
('ROLE_GUEST', 0, 'Анонимный посетитель'),
('ROLE_NEWSMAKER', 0, 'Новостеписатель'),
('ROLE_ROOT', 0, 'Супер админ'),
('ROLE_USER', 0, 'Зарегистрированный пользователь');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_roles_hierarchy`
--

DROP TABLE IF EXISTS `engine_roles_hierarchy`;
CREATE TABLE IF NOT EXISTS `engine_roles_hierarchy` (
  `role_id` varchar(50) NOT NULL,
  `parent_role_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Иерархия ролей';

--
-- Дамп данных таблицы `engine_roles_hierarchy`
--

INSERT INTO `engine_roles_hierarchy` (`role_id`, `parent_role_id`) VALUES
('ROLE_NEWSMAKER', 'ROLE_USER');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_roles_users_relation`
--

DROP TABLE IF EXISTS `engine_roles_users_relation`;
CREATE TABLE IF NOT EXISTS `engine_roles_users_relation` (
  `role_id` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связи полей и юзеров.';

--
-- Дамп данных таблицы `engine_roles_users_relation`
--

INSERT INTO `engine_roles_users_relation` (`role_id`, `user_id`) VALUES
('ROLE_ROOT', 1);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `aaa_engine_blocks_inherit`
--
ALTER TABLE `aaa_engine_blocks_inherit`
  ADD CONSTRAINT `FK_4B3EA624162CB942` FOREIGN KEY (`folder_id`) REFERENCES `aaa_engine_folders` (`folder_id`),
  ADD CONSTRAINT `FK_4B3EA624E9ED820C` FOREIGN KEY (`block_id`) REFERENCES `aaa_engine_blocks` (`block_id`);

--
-- Ограничения внешнего ключа таблицы `aaa_engine_folders`
--
ALTER TABLE `aaa_engine_folders`
  ADD CONSTRAINT `FK_6B4611ABA640A07B` FOREIGN KEY (`folder_pid`) REFERENCES `aaa_engine_folders` (`folder_id`);

--
-- Ограничения внешнего ключа таблицы `aaa_engine_nodes`
--
ALTER TABLE `aaa_engine_nodes`
  ADD CONSTRAINT `FK_F4FF528B162CB942` FOREIGN KEY (`folder_id`) REFERENCES `aaa_engine_folders` (`folder_id`),
  ADD CONSTRAINT `FK_F4FF528BE9ED820C` FOREIGN KEY (`block_id`) REFERENCES `aaa_engine_blocks` (`block_id`);

--
-- Ограничения внешнего ключа таблицы `aaa_menu`
--
ALTER TABLE `aaa_menu`
  ADD CONSTRAINT `FK_D885BF9A162CB942` FOREIGN KEY (`folder_id`) REFERENCES `aaa_engine_folders` (`folder_id`),
  ADD CONSTRAINT `FK_D885BF9A5550C4ED` FOREIGN KEY (`pid`) REFERENCES `aaa_menu` (`item_id`),
  ADD CONSTRAINT `FK_D885BF9AFE54D947` FOREIGN KEY (`group_id`) REFERENCES `aaa_menu_groups` (`group_id`);
