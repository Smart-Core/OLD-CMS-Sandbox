-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Янв 19 2014 г., 22:26
-- Версия сервера: 5.6.13
-- Версия PHP: 5.4.14

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `aaa_engine_blocks`
--

INSERT INTO `aaa_engine_blocks` (`block_id`, `position`, `name`, `descr`, `create_by_user_id`, `create_datetime`) VALUES
(1, 0, 'content', 'Рабочая область', 1, '2013-03-11 01:09:17'),
(2, 2, 'breadcrumbs', 'Хлебные крошки', 1, '2013-03-11 01:09:33'),
(3, 1, 'main_menu', 'Навигационное меню', 1, '2013-03-11 04:00:50'),
(4, 3, 'footer', 'Футер', 1, '2013-03-11 04:01:30'),
(5, 5, 'right_column', 'Правая колонка', 1, '2013-03-23 23:46:01');

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
(4, 1);

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
  `template` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_by_user_id` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  PRIMARY KEY (`folder_id`),
  UNIQUE KEY `folder_pid_uri_part` (`folder_pid`,`uri_part`),
  KEY `IDX_6B4611ABA640A07B` (`folder_pid`),
  KEY `is_active` (`is_active`),
  KEY `is_deleted` (`is_deleted`),
  KEY `position` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `aaa_engine_folders`
--

INSERT INTO `aaa_engine_folders` (`folder_id`, `folder_pid`, `title`, `is_file`, `position`, `uri_part`, `is_active`, `is_deleted`, `descr`, `meta`, `redirect_to`, `router_node_id`, `has_inherit_nodes`, `permissions`, `lockout_nodes`, `template`, `create_by_user_id`, `create_datetime`) VALUES
(1, NULL, 'Главная', 0, 0, NULL, 1, 0, ':)', 'a:5:{s:8:"keywords";s:14:"123 ффыв 3";s:11:"description";s:0:"";s:6:"robots";s:3:"all";s:8:"language";s:5:"ru-RU";s:6:"author";s:10:"Артём";}', NULL, NULL, 1, NULL, NULL, 'main', 1, '2013-03-19 00:44:38'),
(2, 1, 'О компании', 0, 10, 'about', 1, 0, NULL, 'N;', NULL, NULL, 0, NULL, NULL, 'inner', 1, '2013-03-11 16:42:33'),
(3, 1, 'Аккаунт пользователя', 0, 0, 'user', 1, 0, NULL, 'N;', NULL, 7, 0, 'N;', 'N;', NULL, 1, '2013-03-18 01:15:06'),
(4, 3, 'Регистрация', 0, 0, 'register', 1, 0, NULL, 'N;', NULL, 8, 0, 'N;', 'N;', NULL, 1, '2013-03-18 01:15:27'),
(5, 1, 'Так просто ;)', 0, 3, 'simple', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', 'main', 1, '2013-03-19 04:43:50'),
(6, 2, 'Вложенная папка', 0, 0, 'inner', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2013-03-19 04:47:22'),
(7, 1, '22222222222222', 0, 0, '22222222', 0, 0, '22', 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2013-08-10 11:14:06'),
(8, 1, 'Новости', 0, 0, 'news', 1, 0, NULL, 'N;', NULL, 12, 0, 'N;', 'N;', NULL, 1, '2013-12-22 21:45:42');

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
  PRIMARY KEY (`node_id`),
  KEY `IDX_F4FF528B162CB942` (`folder_id`),
  KEY `IDX_F4FF528BE9ED820C` (`block_id`),
  KEY `is_active` (`is_active`),
  KEY `position` (`position`),
  KEY `module` (`module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `aaa_engine_nodes`
--

INSERT INTO `aaa_engine_nodes` (`node_id`, `folder_id`, `block_id`, `is_active`, `module`, `params`, `position`, `priority`, `descr`, `create_by_user_id`, `create_datetime`, `is_cached`) VALUES
(1, 1, 4, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:1;s:6:"editor";s:1:"1";}', 20, 0, 'Футер', 1, '2013-03-20 05:46:40', 0),
(2, 2, 5, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:4;s:6:"editor";s:1:"1";}', 0, 0, 'Правая колонка', 1, '2013-03-20 09:07:33', 0),
(3, 2, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:3;s:6:"editor";s:1:"1";}', 0, 0, 'Хедер', 1, '2013-03-21 06:03:37', 0),
(4, 1, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:2;s:6:"editor";s:1:"1";}', 0, 0, 'Главная', 1, '2013-03-11 16:42:33', NULL),
(5, 1, 3, 1, 'Menu', 'a:4:{s:5:"depth";N;s:8:"group_id";i:1;s:9:"css_class";s:9:"main_menu";s:20:"selected_inheritance";b:0;}', 1, 0, NULL, 1, '2013-03-11 16:42:33', 1),
(6, 1, 2, 1, 'Breadcrumbs', 'a:2:{s:9:"delimiter";s:2:"»";s:17:"hide_if_only_home";b:1;}', 0, 0, NULL, 1, '2013-03-11 16:42:33', 0),
(7, 3, 1, 1, 'UserAccount', 'a:0:{}', 0, 0, NULL, 1, '2013-03-11 16:42:33', 0),
(8, 4, 1, 1, 'UserRegistration', 'a:0:{}', 0, 0, NULL, 1, '2013-03-11 16:42:33', NULL),
(9, 3, 3, 1, 'Texter', 'a:1:{s:12:"text_item_id";i:6;}', 0, 0, 'Текст под меню', 1, '2013-03-25 21:53:12', NULL),
(10, 7, 1, 1, 'Texter', 'a:1:{s:12:"text_item_id";i:7;}', 0, 0, NULL, 1, '2013-08-10 11:14:55', 0),
(11, 5, 1, 1, 'Texter', 'a:1:{s:12:"text_item_id";i:8;}', 0, 0, NULL, 1, '2013-12-20 20:11:41', 0),
(12, 8, 1, 1, 'News', 'a:0:{}', 0, 0, NULL, 1, '2013-12-22 21:58:57', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_fos_user`
--

DROP TABLE IF EXISTS `aaa_fos_user`;
CREATE TABLE IF NOT EXISTS `aaa_fos_user` (
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
  `facebookId` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `aaa_fos_user`
--

INSERT INTO `aaa_fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `firstname`, `lastname`, `facebookId`) VALUES
(1, 'root', 'root', 'artem@mail.ru', 'artem@mail.ru', 1, 'rvmppg4hla80gw0c88wwkogkc8cg88c', 'pSRvk1iSFWol6tPyvrt8ULb6A03pa3jT8LNsVv9eYC9DSQMFLL91dzHBNvPFUFuICMMvFqzYBnyDVaW+Eg3eRg==', '2014-01-19 21:19:57', 0, 0, NULL, '34wbhmcffz28w08g4sg44gww4kow4gg8ggwogo4c88sgokwkck', '2012-06-27 21:34:48', 'a:1:{i:0;s:9:"ROLE_ROOT";}', 0, NULL, '', '', ''),
(2, 'demo', 'demo', 'demo@mail.com', 'demo@mail.com', 1, '15lr4t5s1pdwowoc8k88goc88k00w8', 'MdaZxuZKbcCL1IePGhILE6v+iUUKrINsdpdMMmsc1+LZ7ZBERkb8s+Q6hlp9n4lhU9QKUwnhFpGi8vvjHOPORw==', '2014-01-19 18:56:18', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, '', '', ''),
(3, 'aaa', 'aaa', 'aaa@aaa.ru', 'aaa@aaa.ru', 1, 'teyhcartb3ks0kw4sw0co0k8ko0gk48', '+Qtvl5uc9knUH6z2ZB/7qqZLueaGSfs1yS7TVt4h6CQtNY/a/wG4gdDV+hxR/eSnotc4PGGrRvqnHfdzOmyJNA==', '2014-01-19 18:41:30', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, '', '', '');

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
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `UNIQ_E8E3E5515E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `aaa_menu_groups`
--

INSERT INTO `aaa_menu_groups` (`group_id`, `position`, `name`, `descr`, `create_by_user_id`, `created`, `updated`) VALUES
(1, 0, 'Главное меню', NULL, 1, '2013-05-06 03:54:13', '2014-01-19 15:29:08');

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_menu_items`
--

DROP TABLE IF EXISTS `aaa_menu_items`;
CREATE TABLE IF NOT EXISTS `aaa_menu_items` (
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
  PRIMARY KEY (`item_id`),
  KEY `IDX_D885BF9AFE54D947` (`group_id`),
  KEY `IDX_D885BF9A5550C4ED` (`pid`),
  KEY `IDX_D885BF9A162CB942` (`folder_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `aaa_menu_items`
--

INSERT INTO `aaa_menu_items` (`item_id`, `group_id`, `folder_id`, `is_active`, `position`, `title`, `descr`, `url`, `create_by_user_id`, `created`, `updated`, `pid`) VALUES
(1, 1, 1, 1, 0, NULL, NULL, NULL, 1, '2013-05-06 05:25:48', '2013-05-06 11:13:53', NULL),
(2, 1, 2, 1, 10, NULL, '123 561', NULL, 1, '2013-05-06 05:48:06', '2013-12-22 08:48:50', NULL),
(3, 1, 3, 1, 999, NULL, NULL, NULL, 1, '2013-05-06 07:28:54', '2013-12-22 08:49:04', NULL),
(4, 1, 4, 0, 0, NULL, 'Новых пользователей', NULL, 1, '2013-05-06 07:49:59', '2013-05-06 11:14:07', 5),
(5, 1, 6, 1, 0, NULL, NULL, NULL, 1, '2013-05-06 08:45:04', NULL, 2),
(6, 1, 5, 1, 5, NULL, NULL, NULL, 1, '2013-05-06 09:38:51', '2013-12-22 08:49:00', NULL),
(7, 1, 7, 0, 2, NULL, NULL, NULL, 1, '2013-08-10 11:14:29', '2014-01-19 15:29:17', NULL),
(8, 1, 8, 1, 22, NULL, NULL, NULL, 1, '2013-12-22 21:45:59', '2013-12-22 21:46:15', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_news_items`
--

DROP TABLE IF EXISTS `aaa_news_items`;
CREATE TABLE IF NOT EXISTS `aaa_news_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `annotation` longtext COLLATE utf8_unicode_ci,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_33961BA3989D9B62` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `aaa_news_items`
--

INSERT INTO `aaa_news_items` (`id`, `title`, `slug`, `annotation`, `text`, `created`, `updated`) VALUES
(1, 'Первая', 'first', 'Анонс первой новости.', 'Тема: «Сублимированный рейтинг в XXI веке»\r\nВзаимодействие корпорации и клиента амбивалентно. Агентская комиссия специфицирует мониторинг активности, используя опыт предыдущих кампаний. Ассортиментная политика предприятия развивает стратегический маркетинг, используя опыт предыдущих кампаний. Более того, взаимодействие корпорации и клиента искажает бренд, расширяя долю рынка.', '2013-12-22 22:17:46', NULL),
(2, 'Вторая', 'second', 'Анонс второй новости.', 'Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.', '2013-12-16 22:18:21', NULL);

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F2FA10BE8852ACDC` (`loc`),
  KEY `title_hash` (`title_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `aaa_sitemap_urls`
--


-- --------------------------------------------------------

--
-- Структура таблицы `aaa_text_items`
--

DROP TABLE IF EXISTS `aaa_text_items`;
CREATE TABLE IF NOT EXISTS `aaa_text_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(8) DEFAULT NULL,
  `text` longtext,
  `meta` longtext NOT NULL COMMENT '(DC2Type:array)',
  `datetime` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `aaa_text_items`
--

INSERT INTO `aaa_text_items` (`item_id`, `language`, `text`, `meta`, `datetime`, `user_id`) VALUES
(1, 'ru', 'Футер 2', 'a:0:{}', '2012-08-27 03:16:57', 1),
(2, 'ru', '<h1>Главная страница!</h1>\r\n<p>С точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма, концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих геотрансплантационных квазипузлистатов всех кинетически коррелирующих аспектов. Исходя из этого, мы пришли к выводу, что каждый произвольно выбранный предикативно абсорбирующий объект.</p>\r\n<img src="/uploads/Advanced%20C%20Asana.jpg" alt="" width="891" height="666" />', 'a:1:{s:8:"keywords";s:3:"123";}', '2012-08-27 03:17:27', 1),
(3, 'ru', '<h2>Пример страницы с 2-мя колонками</h2>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>', 'a:1:{s:8:"keywords";s:3:"sdf";}', '2012-08-27 03:51:05', 1),
(4, 'ru', '<p><img src="/uploads/images/bscap0001.jpg" alt="" width="300" height="124" /><br />Сервисная стратегия деятельно искажает продвигаемый медиаплан, опираясь на опыт западных коллег. Внутрифирменная реклама, согласно Ф.Котлеру, откровенно цинична. Торговая марка исключительно уравновешивает презентационный материал, полагаясь на инсайдерскую информацию. Наряду с этим, узнавание бренда вполне выполнимо. Организация слубы маркетинга, согласно Ф.Котлеру, усиливает фактор коммуникации, осознавая социальную ответственность бизнеса. Экспертиза выполненного проекта восстанавливает потребительский презентационный материал, полагаясь на инсайдерскую информацию.</p>', 'a:0:{}', '2012-08-27 03:51:27', 1),
(5, 'ru', 'Текстер №5', 'a:0:{}', '2013-03-21 06:03:37', 1),
(6, 'ru', 'Under menu 2.', 'a:0:{}', '2013-03-25 21:53:12', 1),
(7, 'ru', 'sdf gsdfg dsf gsdf gdsfg sdf g', 'a:0:{}', '2013-08-10 11:14:55', 1),
(8, 'ru', '<p>Нельзя так просто взять и написать цмс-ку ;)&nbsp; 1<br /><br /><img src="/uploads/images/bscap0001_big.jpg" alt="" width="1680" height="693" /></p>', 'a:0:{}', '2013-12-20 20:11:42', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `aaa_text_items_history`
--

DROP TABLE IF EXISTS `aaa_text_items_history`;
CREATE TABLE IF NOT EXISTS `aaa_text_items_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_deleted` tinyint(1) NOT NULL,
  `item_id` int(11) NOT NULL,
  `language` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `text_archive` longtext COLLATE utf8_unicode_ci NOT NULL,
  `meta_archive` longtext COLLATE utf8_unicode_ci NOT NULL,
  `update_datetime` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`history_id`),
  KEY `item_id` (`item_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `aaa_text_items_history`
--


-- --------------------------------------------------------

--
-- Структура таблицы `engine_modules`
--

DROP TABLE IF EXISTS `engine_modules`;
CREATE TABLE IF NOT EXISTS `engine_modules` (
  `module_id` varchar(50) NOT NULL,
  `class` text NOT NULL,
  `install_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Общий список модулей со сводной информацией';

--
-- Дамп данных таблицы `engine_modules`
--

INSERT INTO `engine_modules` (`module_id`, `class`, `install_datetime`, `user_id`) VALUES
('SmartCoreBreadcrumbs', '\\SmartCore\\Module\\Breadcrumbs\\SmartCoreBreadcrumbsModule', '0000-00-00 00:00:00', 0),
('SmartCoreMenu', '\\SmartCore\\Module\\Menu\\SmartCoreMenuModule', '0000-00-00 00:00:00', 1),
('SmartCoreTexter', '\\SmartCore\\Module\\Texter\\SmartCoreTexterModule', '0000-00-00 00:00:00', 1),
('SmartCoreUserAccount', '\\SmartCore\\Module\\UserAccount\\SmartCoreUserAccountModule', '0000-00-00 00:00:00', 0),
('SmartCoreUserRegistration', '\\SmartCore\\Module\\UserRegistration\\SmartCoreUserRegistrationModule', '0000-00-00 00:00:00', 0);

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

-- --------------------------------------------------------

--
-- Структура таблицы `javascript_library`
--

DROP TABLE IF EXISTS `javascript_library`;
CREATE TABLE IF NOT EXISTS `javascript_library` (
  `script_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `pos` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Приоритет, чем выше, тем раньше будет подключена либа',
  `related_by` varchar(255) DEFAULT NULL COMMENT 'Зависит от',
  `title` varchar(200) NOT NULL,
  `current_version` varchar(20) NOT NULL,
  `homepage` varchar(200) NOT NULL,
  `files` text COMMENT 'Файлы',
  `descr` text,
  PRIMARY KEY (`script_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Библиотека скриптов' AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `javascript_library`
--

INSERT INTO `javascript_library` (`script_id`, `name`, `pos`, `related_by`, `title`, `current_version`, `homepage`, `files`, `descr`) VALUES
(1, 'jquery', 1000, '', 'jQuery is a new kind of JavaScript Library.', '1.9.1', 'http://jquery.com/', 'jquery.min.js', 'jQuery is a fast and concise JavaScript Library that simplifies HTML document traversing, event handling, animating, and Ajax interactions for rapid web development. jQuery is designed to change the way that you write JavaScript.'),
(2, 'tinymce', 0, '', 'TinyMCE - Javascript WYSIWYG Editor', '3.4.5', 'http://tinymce.moxiecode.com/', 'tiny_mce.js', 'TinyMCE is a platform independent web based Javascript HTML WYSIWYG editor control released as Open Source under LGPL by Moxiecode Systems AB. It has the ability to convert HTML TEXTAREA fields or other HTML elements to editor instances. TinyMCE is very easy to integrate into other Content Management Systems. '),
(3, 'extjs', 0, '', 'Ext JS is a cross-browser JavaScript library for building rich internet applications.', '3.2.1', 'http://www.sencha.com/products/js/', '', NULL),
(4, 'highslide', 0, '', 'Highslide JS - JavaScript thumbnail viewer', '4.1.4', 'http://highslide.com/', '', 'Highslide JS is an image, media and gallery viewer written in JavaScript.'),
(5, 'ckeditor', 0, '', 'CKEditor - WYSIWYG Text and HTML Editor for the Web', '3.4.1', 'http://ckeditor.com/', '', 'CKEditor is a text editor to be used inside web pages. It''s a WYSIWYG  editor, which means that the text being edited on it looks as similar as possible to the results users have when publishing it. It brings to the web common editing features found on desktop editing applications like Microsoft Word and OpenOffice.'),
(6, 'jquery-ui', 0, '', 'jQuery user interface', '1.8.5', 'http://jqueryui.com/', '', 'jQuery UI provides abstractions for low-level interaction and animation, advanced effects and high-level, themeable widgets, built on top of the jQuery JavaScript Library, that you can use to build highly interactive web applications.'),
(7, 'mediabox', 0, '', 'Mediabox Advanced', '1.3.4', 'http://iaian7.com/webcode/mediaboxAdvanced', '', 'Based on Lightbox, Slimbox, and the Mootools javascript library, mediaboxAdvanced  is a modal overlay that can handle images, videos, animations, social video sites, twitter media links, inline elements, and external pages with ease.'),
(8, 'mootools', 0, '', 'MooTools JS Framework', '1.2.5', 'http://mootools.net/', 'mootools.min.js', 'MooTools is a compact, modular, Object-Oriented JavaScript framework designed for the intermediate to advanced JavaScript developer. It allows you to write powerful, flexible, and cross-browser code with its elegant, well documented, and coherent API.'),
(9, 'scriptaculous', 99, 'prototype', 'script.aculo.us - web 2.0 javascript', '1.9.0', 'http://script.aculo.us/', 'scriptaculous.js', 'script.aculo.us provides you with\r\neasy-to-use, cross-browser user\r\ninterface JavaScript libraries to make\r\nyour web sites and web applications fly.'),
(10, 'prototype', 100, '', 'JavaScript Framework', '1.7.0', 'http://www.prototypejs.org/', 'prototype.min.js', 'Prototype is a JavaScript Framework that aims to ease development of dynamic web applications.'),
(11, 'lightview', 0, 'scriptaculous', 'Lightview', '2.7.4', 'http://www.nickstakenburg.com/projects/lightview/', 'css/lightview.css,js/lightview.js', NULL),
(12, 'jquery-cookie', 0, 'jquery', 'Cookie', '1.3.1', 'http://plugins.jquery.com/project/cookie', 'jquery.cookie.js', 'A simple, lightweight utility plugin for reading, writing and deleting cookies.'),
(13, 'less', 0, NULL, 'The dynamic stylesheet language.', '1.3.3', 'http://lesscss.org/', 'less.min.js', 'LESS extends CSS with dynamic behavior such as variables, mixins, operations and functions. LESS runs on both the client-side (IE 6+, Webkit, Firefox) and server-side, with Node.js.'),
(14, 'backbone', 0, 'jquery', 'backbone.js', '0.9.2', 'http://documentcloud.github.com/backbone/', 'backbone-min.js', 'Backbone.js gives structure to web applications by providing models with key-value binding and custom events, collections with a rich API of enumerable functions, views with declarative event handling, and connects it all to your existing API over a RESTful JSON interface. '),
(15, 'bootstrap', 0, 'jquery', 'Twitter Bootstrap', '2.3.2', '', 'css/bootstrap.min.css,css/bootstrap-responsive.min.css,js/bootstrap.min.js', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `javascript_library_paths`
--

DROP TABLE IF EXISTS `javascript_library_paths`;
CREATE TABLE IF NOT EXISTS `javascript_library_paths` (
  `script_id` mediumint(8) unsigned NOT NULL,
  `version` varchar(10) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`script_id`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Пути к скриптам';

--
-- Дамп данных таблицы `javascript_library_paths`
--

INSERT INTO `javascript_library_paths` (`script_id`, `version`, `path`) VALUES
(1, '1.4.2', 'jquery/1.4.2/'),
(1, '1.6.4', 'jquery/1.6.4/'),
(1, '1.9.1', 'jquery/1.9.1/'),
(2, '3.3.9.4', 'tinymce/3.3.9.4/'),
(2, '3.4.5', 'tinymce/3.4.5/'),
(9, '1.9.0', 'scriptaculous/1.9.0/src/'),
(10, '1.7.0', 'prototype/1.7.0/'),
(11, '2.7.4', 'lightview/2.7.4/'),
(12, '1.3.1', 'jquery-cookie/1.3.1/'),
(13, '1.3.0', 'less/1.3.0/'),
(13, '1.3.3', 'less/1.3.3/'),
(14, '0.9.2', 'backbone/0.9.2/'),
(15, '2.3.2', 'bootstrap/2.3.2/');

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
-- Ограничения внешнего ключа таблицы `aaa_menu_items`
--
ALTER TABLE `aaa_menu_items`
  ADD CONSTRAINT `FK_D885BF9A162CB942` FOREIGN KEY (`folder_id`) REFERENCES `aaa_engine_folders` (`folder_id`),
  ADD CONSTRAINT `FK_D885BF9A5550C4ED` FOREIGN KEY (`pid`) REFERENCES `aaa_menu_items` (`item_id`),
  ADD CONSTRAINT `FK_D885BF9AFE54D947` FOREIGN KEY (`group_id`) REFERENCES `aaa_menu_groups` (`group_id`);
