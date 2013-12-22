-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Дек 23 2013 г., 00:36
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
(4, 3, 'Регистрация', 0, 0, 'register', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2013-03-18 01:15:27'),
(5, 1, 'Так просто ;)', 0, 3, 'simple', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', 'main', 1, '2013-03-19 04:43:50'),
(6, 2, 'Вложенная папка', 0, 0, 'inner', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2013-03-19 04:47:22'),
(7, 1, '22222222222222', 0, 0, '22222222', 0, 0, '22', 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2013-08-10 11:14:06'),
(8, 1, 'Новости', 0, 0, 'news', 1, 0, NULL, 'N;', NULL, NULL, 0, 'N;', 'N;', NULL, 1, '2013-12-22 21:45:42');

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
(6, 1, 2, 1, 'Breadcrumbs', 'a:2:{s:9:"delimiter";s:7:"&raquo;";s:17:"hide_if_only_home";b:1;}', 0, 0, NULL, 1, '2013-03-11 16:42:33', 0),
(7, 3, 1, 1, 'UserAccount', 'a:0:{}', 0, 0, NULL, 1, '2013-03-11 16:42:33', NULL),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `aaa_fos_user`
--

INSERT INTO `aaa_fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `firstname`, `lastname`, `facebookId`) VALUES
(1, 'root', 'root', 'artem@mail.ru', 'artem@mail.ru', 1, 'rvmppg4hla80gw0c88wwkogkc8cg88c', 'pSRvk1iSFWol6tPyvrt8ULb6A03pa3jT8LNsVv9eYC9DSQMFLL91dzHBNvPFUFuICMMvFqzYBnyDVaW+Eg3eRg==', '2013-12-22 21:38:50', 0, 0, NULL, '34wbhmcffz28w08g4sg44gww4kow4gg8ggwogo4c88sgokwkck', '2012-06-27 21:34:48', 'a:1:{i:0;s:9:"ROLE_ROOT";}', 0, NULL, '', '', ''),
(2, 'demo', 'demo', 'demo@mail.com', 'demo@mail.com', 1, '15lr4t5s1pdwowoc8k88goc88k00w8', 'MdaZxuZKbcCL1IePGhILE6v+iUUKrINsdpdMMmsc1+LZ7ZBERkb8s+Q6hlp9n4lhU9QKUwnhFpGi8vvjHOPORw==', '2012-11-27 22:27:01', 0, 0, NULL, '2d8juw95z1gkgwc4wgw4ccsosk8k0cogsocog0g4o4wkggc8ks', NULL, 'a:0:{}', 0, NULL, '', '', ''),
(3, 'aaa', 'aaa', 'aaa@aaa.ru', 'aaa@aaa.ru', 0, 'teyhcartb3ks0kw4sw0co0k8ko0gk48', '+Qtvl5uc9knUH6z2ZB/7qqZLueaGSfs1yS7TVt4h6CQtNY/a/wG4gdDV+hxR/eSnotc4PGGrRvqnHfdzOmyJNA==', NULL, 0, 0, NULL, '5vavjduimask4s0w4sw088c4cwwgkc84skg8k4884g8s4kco4g', NULL, 'a:0:{}', 0, NULL, '', '', '');

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
(1, 0, 'Главное меню', NULL, 1, '2013-05-06 03:54:13', '2013-05-06 14:20:15');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

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
(7, 1, 7, 0, 2, NULL, NULL, NULL, 1, '2013-08-10 11:14:29', '2013-12-18 15:50:54', NULL),
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
(1, 'ru', 'Футер', 'a:0:{}', '2012-08-27 03:16:57', 1),
(2, 'ru', '<h1>Главная страница!</h1>\r\n<p>С точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма, концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих геотрансплантационных квазипузлистатов всех кинетически коррелирующих аспектов. Исходя из этого, мы пришли к выводу, что каждый произвольно выбранный предикативно абсорбирующий объект.</p>\r\n<img src="/uploads/Advanced%20C%20Asana.jpg" alt="" width="891" height="666" />', 'a:1:{s:8:"keywords";s:3:"123";}', '2012-08-27 03:17:27', 1),
(3, 'ru', '<h2>Пример страницы с 2-мя колонками</h2>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>', 'a:1:{s:8:"keywords";s:3:"sdf";}', '2012-08-27 03:51:05', 1),
(4, 'ru', '<p><img src="/uploads/images/bscap0001.jpg" alt="" width="300" height="124" /><br />Сервисная стратегия деятельно искажает продвигаемый медиаплан, опираясь на опыт западных коллег. Внутрифирменная реклама, согласно Ф.Котлеру, откровенно цинична. Торговая марка исключительно уравновешивает презентационный материал, полагаясь на инсайдерскую информацию. Наряду с этим, узнавание бренда вполне выполнимо. Организация слубы маркетинга, согласно Ф.Котлеру, усиливает фактор коммуникации, осознавая социальную ответственность бизнеса. Экспертиза выполненного проекта восстанавливает потребительский презентационный материал, полагаясь на инсайдерскую информацию. 234234</p>', 'a:0:{}', '2012-08-27 03:51:27', 1),
(5, 'ru', 'Текстер №5', 'a:0:{}', '2013-03-21 06:03:37', 1),
(6, 'ru', 'Under menu 2.', 'a:0:{}', '2013-03-25 21:53:12', 1),
(7, 'ru', 'sdf gsdfg dsf gsdf gdsfg sdf g', 'a:0:{}', '2013-08-10 11:14:55', 1),
(8, 'ru', '<p>Нельзя так просто взять и написать цмс-ку ;) <br /><br /><img src="/uploads/images/bscap0001_big.jpg" alt="" width="1680" height="693" /></p>', 'a:0:{}', '2013-12-20 20:11:42', 1);

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
-- Структура таблицы `engine_folders_translation`
--

DROP TABLE IF EXISTS `engine_folders_translation`;
CREATE TABLE IF NOT EXISTS `engine_folders_translation` (
  `folder_id` int(10) unsigned NOT NULL,
  `language_id` varchar(8) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `meta` text COMMENT 'Мета-данные',
  PRIMARY KEY (`folder_id`,`language_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Переводы заголовков и описаний папок.';

--
-- Дамп данных таблицы `engine_folders_translation`
--


-- --------------------------------------------------------

--
-- Структура таблицы `engine_languages`
--

DROP TABLE IF EXISTS `engine_languages`;
CREATE TABLE IF NOT EXISTS `engine_languages` (
  `language_id` varchar(8) NOT NULL,
  `pos` tinyint(2) NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `uri_part` varchar(10) NOT NULL COMMENT '@todo может не нужно т.е. как ури парт рассматривать ID языка',
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Языки';

--
-- Дамп данных таблицы `engine_languages`
--

INSERT INTO `engine_languages` (`language_id`, `pos`, `name`, `uri_part`) VALUES
('en', 2, 'English', 'en'),
('ru', 1, 'Русский', 'ru');

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
-- Структура таблицы `engine_settings`
--

DROP TABLE IF EXISTS `engine_settings`;
CREATE TABLE IF NOT EXISTS `engine_settings` (
  `variable` varchar(100) NOT NULL,
  `group_id` tinyint(3) NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  `default_value` text NOT NULL COMMENT 'Значение по уполчанию',
  `optioncode` text NOT NULL COMMENT 'Код отображения для удобства редактирования в админке.',
  `datatype` enum('string','number','bool') NOT NULL DEFAULT 'number' COMMENT 'Тип данных',
  `descr` text NOT NULL COMMENT 'Описание параметра',
  PRIMARY KEY (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Настройки';

--
-- Дамп данных таблицы `engine_settings`
--

INSERT INTO `engine_settings` (`variable`, `group_id`, `value`, `default_value`, `optioncode`, `datatype`, `descr`) VALUES
('component.editor.editor_css', 0, '', '/styles/editor.css', '', 'string', 'CSS стиль для визульного редактора.'),
('module.texter.filemanager_path', 0, '', 'filemanager/', '', 'string', ''),
('module.user_registration.send_welcome_email', 0, '', '1', '', 'bool', 'Посылать зарегистрированному пользователю E-mail'),
('site.admin_group_id', 0, '', '3', '', 'number', 'Группа адмнистраторов'),
('site.default_group_id', 0, '', '2', '', 'number', 'Группа пользовалелей по умолчанию, задаётся при регистрации новых пользователей.'),
('site.http_compression_level', 0, '', '0', '', 'number', 'HTTP сжатие (0 - 9).'),
('site.root_group_id', 0, '', '1', '', 'number', 'Группа пользователей, которая считатется рутовой, для неё всегда будут доступны все привелегии и запретить их будет нельзя.'),
('site.timezone', 0, 'UTC', 'UTC', '', 'number', 'Разница во времени сервера.'),
('site.time_format', 0, '', '%d %B %Y, %H:%M:%S', '', 'string', 'Формат отображени даты');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_settings_groups`
--

DROP TABLE IF EXISTS `engine_settings_groups`;
CREATE TABLE IF NOT EXISTS `engine_settings_groups` (
  `group_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Группы настроек сайта' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `engine_settings_groups`
--

INSERT INTO `engine_settings_groups` (`group_id`, `name`) VALUES
(1, 'Настройки доменов');

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

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` varchar(50) NOT NULL,
  `pos` smallint(5) unsigned NOT NULL DEFAULT '0',
  `descr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Роли пользователей';

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`role_id`, `pos`, `descr`) VALUES
('ROLE_ADMIN', 0, 'Админ'),
('ROLE_GUEST', 0, 'Анонимный посетитель'),
('ROLE_NEWSMAKER', 0, 'Новостеписатель'),
('ROLE_ROOT', 0, 'Супер админ'),
('ROLE_USER', 0, 'Зарегистрированный пользователь');

-- --------------------------------------------------------

--
-- Структура таблицы `roles_hierarchy`
--

DROP TABLE IF EXISTS `roles_hierarchy`;
CREATE TABLE IF NOT EXISTS `roles_hierarchy` (
  `role_id` varchar(50) NOT NULL,
  `parent_role_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Иерархия ролей';

--
-- Дамп данных таблицы `roles_hierarchy`
--

INSERT INTO `roles_hierarchy` (`role_id`, `parent_role_id`) VALUES
('ROLE_NEWSMAKER', 'ROLE_USER');

-- --------------------------------------------------------

--
-- Структура таблицы `roles_users_relation`
--

DROP TABLE IF EXISTS `roles_users_relation`;
CREATE TABLE IF NOT EXISTS `roles_users_relation` (
  `role_id` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связи полей и юзеров.';

--
-- Дамп данных таблицы `roles_users_relation`
--

INSERT INTO `roles_users_relation` (`role_id`, `user_id`) VALUES
('ROLE_ROOT', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(48) DEFAULT NULL,
  `create_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания аккаунта',
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` datetime DEFAULT NULL,
  `nickname` varchar(100) NOT NULL COMMENT 'Псевдоним @OPTIONAL',
  `fullname` varchar(100) DEFAULT NULL COMMENT 'Полное имя @OPTIONAL',
  `dob` date DEFAULT NULL COMMENT 'Дата рождения @OPTIONAL',
  `gender` enum('M','F') DEFAULT NULL COMMENT 'Пол @OPTIONAL',
  `language` varchar(2) DEFAULT NULL COMMENT '@OPTIONAL',
  `timezone` varchar(100) DEFAULT NULL COMMENT 'Временная зона. @OPTIONAL',
  `create_on_site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'С какого проекта создан аккаунт @OPTIONAL',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `is_locked` (`is_locked`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Учетные записи пользователей' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `is_locked`, `email`, `create_datetime`, `expired`, `expires_at`, `nickname`, `fullname`, `dob`, `gender`, `language`, `timezone`, `create_on_site_id`) VALUES
(1, 0, 'god@world.com', '0000-00-00 00:00:00', 0, NULL, 'asd', NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users_activity`
--

DROP TABLE IF EXISTS `users_activity`;
CREATE TABLE IF NOT EXISTS `users_activity` (
  `user_id` int(10) unsigned NOT NULL,
  `last_login_datetime` datetime NOT NULL COMMENT 'Дата последнего входа в систему',
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Активность';

--
-- Дамп данных таблицы `users_activity`
--

INSERT INTO `users_activity` (`user_id`, `last_login_datetime`) VALUES
(1, '2012-05-21 15:02:43');

-- --------------------------------------------------------

--
-- Структура таблицы `users_activity_log`
--

DROP TABLE IF EXISTS `users_activity_log`;
CREATE TABLE IF NOT EXISTS `users_activity_log` (
  `log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `login` varchar(100) NOT NULL,
  `action` varchar(20) NOT NULL,
  `referer` text,
  `datetime` datetime NOT NULL,
  `ip` varchar(40) NOT NULL,
  `browser` varchar(50) NOT NULL,
  `browser_version` varchar(50) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `user_agent` text NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `login` (`login`),
  KEY `datetime` (`datetime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Лог активности пользователей' AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `users_activity_log`
--

INSERT INTO `users_activity_log` (`log_id`, `user_id`, `login`, `action`, `referer`, `datetime`, `ip`, `browser`, `browser_version`, `platform`, `user_agent`) VALUES
(1, 1, 'root', 'cookie_login', 'http://site.com/', '2012-01-11 19:26:11', '127.0.0.1', 'Firefox', '3.6.22', 'Windows', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.22) Gecko/20110902 Firefox/3.6.22'),
(3, 1, 'root', 'logout', 'http://site.com/', '2012-01-12 02:13:52', '127.0.0.1', 'Firefox', '3.6.22', 'Windows', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.22) Gecko/20110902 Firefox/3.6.22'),
(4, 2, 'admin', 'login', 'http://site.com/user/', '2012-01-12 02:16:08', '127.0.0.1', 'Firefox', '3.6.22', 'Windows', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.22) Gecko/20110902 Firefox/3.6.22'),
(6, 2, 'admin', 'cookie_login', NULL, '2012-01-14 08:34:03', '127.0.0.1', 'Firefox', '3.6.22', 'Windows', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.22) Gecko/20110902 Firefox/3.6.22');

-- --------------------------------------------------------

--
-- Структура таблицы `users_inactive`
--

DROP TABLE IF EXISTS `users_inactive`;
CREATE TABLE IF NOT EXISTS `users_inactive` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `login` varchar(150) NOT NULL,
  `login_canonical` varchar(150) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `email_canonical` varchar(128) NOT NULL,
  `properties` longtext COMMENT 'Дополнительные свойства',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email_canonical`),
  KEY `is_active` (`is_enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Учетные записи пользователей ожидающие активации' AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `users_inactive`
--


-- --------------------------------------------------------

--
-- Структура таблицы `users_inactive_confirmation`
--

DROP TABLE IF EXISTS `users_inactive_confirmation`;
CREATE TABLE IF NOT EXISTS `users_inactive_confirmation` (
  `login` varchar(150) NOT NULL,
  `start_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `key` varchar(50) NOT NULL,
  PRIMARY KEY (`login`),
  UNIQUE KEY `key` (`key`),
  KEY `end_datetime` (`end_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Ключи подтверждения регистрации';

--
-- Дамп данных таблицы `users_inactive_confirmation`
--

INSERT INTO `users_inactive_confirmation` (`login`, `start_datetime`, `end_datetime`, `key`) VALUES
('root', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '34wbhmcffz28w08g4sg44gww4kow4gg8ggwogo4c88sgokwkck');

-- --------------------------------------------------------

--
-- Структура таблицы `users_local`
--

DROP TABLE IF EXISTS `users_local`;
CREATE TABLE IF NOT EXISTS `users_local` (
  `user_id` int(10) unsigned NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `create_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания аккаунта',
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `is_locked` (`is_locked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Локальные пользователи платформы.';

--
-- Дамп данных таблицы `users_local`
--

INSERT INTO `users_local` (`user_id`, `is_locked`, `create_datetime`, `expired`, `expires_at`) VALUES
(1, 0, '0000-00-00 00:00:00', 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users_logins`
--

DROP TABLE IF EXISTS `users_logins`;
CREATE TABLE IF NOT EXISTS `users_logins` (
  `user_id` int(10) unsigned NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(90) DEFAULT NULL,
  `salt` varchar(32) DEFAULT NULL,
  `hash_version` tinyint(3) unsigned DEFAULT NULL COMMENT 'Версия алгоритма хеширования',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `create_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания логина',
  `create_on_site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'С какого проекта создан логин @TODO возможно вынести, а может и оставить.',
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  `expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`login`),
  KEY `password` (`password`),
  KEY `is_active` (`is_locked`),
  KEY `hash_version` (`hash_version`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Логины пользователей';

--
-- Дамп данных таблицы `users_logins`
--

INSERT INTO `users_logins` (`user_id`, `login`, `password`, `salt`, `hash_version`, `is_locked`, `create_datetime`, `create_on_site_id`, `expired`, `expire_at`) VALUES
(1, 'http://artem.id.mail.ru', NULL, NULL, NULL, 0, '2011-00-00 00:00:00', 0, 0, NULL),
(1, 'https://www.google.com/accounts/o8/id?id=khbhedwsUBusydfbgjsdhfb_ZdfgjBhyw3ehbhh', NULL, NULL, NULL, 0, '2012-00-00 00:00:00', 0, 0, '2012-06-12 17:55:55'),
(1, 'root', 'pSRvk1iSFWol6tPyvrt8ULb6A03pa3jT8LNsVv9eYC9DSQMFLL91dzHBNvPFUFuICMMvFqzYBnyDVaW+Eg3eRg==', 'rvmppg4hla80gw0c88wwkogkc8cg88c', 3, 0, '2011-00-00 00:00:00', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users_names`
--

DROP TABLE IF EXISTS `users_names`;
CREATE TABLE IF NOT EXISTS `users_names` (
  `user_id` int(10) unsigned NOT NULL,
  `login` varchar(150) NOT NULL,
  `login_canonical` varchar(150) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `salt` varchar(32) DEFAULT NULL,
  `hash_version` tinyint(3) unsigned DEFAULT NULL COMMENT 'Версия алгоритма хеширования',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `create_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания логина',
  `create_on_site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'С какого проекта создан логин',
  PRIMARY KEY (`login_canonical`),
  KEY `password` (`password`),
  KEY `is_active` (`is_active`),
  KEY `hash_version` (`hash_version`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Имена пользователей';

--
-- Дамп данных таблицы `users_names`
--

INSERT INTO `users_names` (`user_id`, `login`, `login_canonical`, `password`, `salt`, `hash_version`, `is_active`, `create_datetime`, `create_on_site_id`) VALUES
(1, 'http://artem.id.mail.ru', 'http://artem.id.mail.ru', NULL, NULL, NULL, 1, '2011-00-00 00:00:00', 0),
(1, 'root', 'root', 'd9b1d7db4cd6e70935368a1efb10e377', '413a86af', 2, 1, '2011-00-00 00:00:00', 0),
(101, 'test', 'test', 'd9b1d7db4cd6e70935368a1efb10e377', 'b120a00e', 2, 1, '2011-06-03 23:19:33', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users_recover`
--

DROP TABLE IF EXISTS `users_recover`;
CREATE TABLE IF NOT EXISTS `users_recover` (
  `code` varchar(128) NOT NULL,
  `email` varchar(48) NOT NULL,
  `create_datetime` datetime NOT NULL COMMENT 'Время создания запроса.',
  `valid_to_datetime` datetime NOT NULL COMMENT 'Колюч действителен до указанного времени',
  PRIMARY KEY (`code`),
  KEY `email` (`email`),
  KEY `valid_to_datetime` (`valid_to_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица ключей для восстановления паролей';

--
-- Дамп данных таблицы `users_recover`
--


-- --------------------------------------------------------

--
-- Структура таблицы `users_reg`
--

DROP TABLE IF EXISTS `users_reg`;
CREATE TABLE IF NOT EXISTS `users_reg` (
  `user_reg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email` varchar(48) NOT NULL,
  `properties` longtext COMMENT 'Дополнительные свойства',
  PRIMARY KEY (`user_reg_id`),
  UNIQUE KEY `email` (`email`),
  KEY `is_locked` (`is_locked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Учетные записи пользователей ожидающие активации' AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `users_reg`
--


-- --------------------------------------------------------

--
-- Структура таблицы `users_reg_confirmation`
--

DROP TABLE IF EXISTS `users_reg_confirmation`;
CREATE TABLE IF NOT EXISTS `users_reg_confirmation` (
  `user_reg_id` int(10) unsigned NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(90) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `expire_at` datetime NOT NULL,
  `key` varchar(50) NOT NULL,
  PRIMARY KEY (`user_reg_id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `key` (`key`),
  KEY `expire_at` (`expire_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Ключи подтверждения регистрации';

--
-- Дамп данных таблицы `users_reg_confirmation`
--

INSERT INTO `users_reg_confirmation` (`user_reg_id`, `login`, `password`, `salt`, `start_datetime`, `expire_at`, `key`) VALUES
(1, 'root', 'd9b1d7db4cd6e70935368a1efb10e377', '413a86af', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '34wbhmcffz28w08g4sg44gww4kow4gg8ggwogo4c88sgokwkck');

-- --------------------------------------------------------

--
-- Структура таблицы `users_tokens`
--

DROP TABLE IF EXISTS `users_tokens`;
CREATE TABLE IF NOT EXISTS `users_tokens` (
  `user_id` int(10) unsigned NOT NULL,
  `login` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `valid_to_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Валиден до даты',
  PRIMARY KEY (`token`),
  KEY `valid_to_datetime` (`valid_to_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Токены для утентификации через cookie';

--
-- Дамп данных таблицы `users_tokens`
--

INSERT INTO `users_tokens` (`user_id`, `login`, `token`, `valid_to_datetime`) VALUES
(1, 'root', '4223b57e529fe2ce1e84c455391a4c7b8c35ff405a76e48496642b5e061413aa', '2012-05-04 16:03:09');

-- --------------------------------------------------------

--
-- Структура таблицы `_engine_folders`
--

DROP TABLE IF EXISTS `_engine_folders`;
CREATE TABLE IF NOT EXISTS `_engine_folders` (
  `folder_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `pos` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uri_part` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Удалена ли папка? (для истории)',
  `is_file` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'является ли папка файлом',
  `title` varchar(255) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `meta` text,
  `redirect_to` varchar(255) DEFAULT NULL,
  `router_node_id` int(10) unsigned DEFAULT NULL COMMENT '@ Нода, которой передаётся дальнейший парсинг URI  (бывший parser_node_id)',
  `is_inherit_nodes` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'В этой папке ЕСТЬ ноды, которые наследуются.',
  `owner_id` int(10) unsigned DEFAULT NULL COMMENT 'id создателя',
  `permissions` text COMMENT 'Права доступа',
  `lockout_nodes` text,
  `layout` varchar(30) DEFAULT NULL COMMENT 'Применяемый макет темы',
  `create_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания',
  PRIMARY KEY (`folder_id`),
  UNIQUE KEY `pid-uri_part` (`pid`,`uri_part`),
  KEY `pos` (`pos`),
  KEY `is_active` (`is_active`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=1 ROW_FORMAT=COMPACT COMMENT='Папки' AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `_engine_folders`
--

INSERT INTO `_engine_folders` (`folder_id`, `pid`, `pos`, `uri_part`, `is_active`, `is_deleted`, `is_file`, `title`, `descr`, `meta`, `redirect_to`, `router_node_id`, `is_inherit_nodes`, `owner_id`, `permissions`, `lockout_nodes`, `layout`, `create_datetime`) VALUES
(1, 0, 0, '', 1, 0, 0, 'Главная', 'Smart Core CMS', 'a:5:{s:8:"keywords";s:14:"123 ффыв 3";s:11:"description";s:0:"";s:6:"robots";s:3:"all";s:8:"language";s:5:"ru-RU";s:6:"author";s:10:"Артём";}', NULL, NULL, 1, 1, NULL, NULL, 'main', '0000-00-00 00:00:00'),
(2, 1, 0, 'about', 1, 0, 0, 'О компании', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'inner', '0000-00-00 00:00:00'),
(3, 1, 0, 'user', 1, 0, 0, 'Аккаунт пользователя', NULL, NULL, NULL, 7, 0, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4, 3, 0, 'register', 1, 0, 0, 'Регистрация', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `_engine_nodes`
--

DROP TABLE IF EXISTS `_engine_nodes`;
CREATE TABLE IF NOT EXISTS `_engine_nodes` (
  `node_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `folder_id` int(10) unsigned NOT NULL DEFAULT '0',
  `block_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pos` smallint(5) unsigned NOT NULL DEFAULT '0',
  `module_id` varchar(50) NOT NULL,
  `controller` varchar(50) DEFAULT 'index',
  `is_cached` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Может ли эта нода кешироваться.',
  `cache_params` text COMMENT 'Параметры кеширования',
  `cache_params_yaml` text COMMENT '@ ВРЕМЕННО - на этапе разработки',
  `params` text,
  `plugins` text COMMENT 'Плагины',
  `owner_id` int(10) unsigned DEFAULT NULL COMMENT 'ид создателя',
  `permissions` text COMMENT 'Права доступа',
  `database_id` smallint(5) NOT NULL DEFAULT '0',
  `node_action_mode` enum('popup','built-in','ajax') NOT NULL DEFAULT 'popup',
  `descr` varchar(255) DEFAULT NULL COMMENT 'Краткий технический комментарий',
  `create_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата создания',
  PRIMARY KEY (`node_id`,`folder_id`),
  KEY `is_active` (`is_active`),
  KEY `pos` (`pos`),
  KEY `container_id` (`block_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `_engine_nodes`
--

INSERT INTO `_engine_nodes` (`node_id`, `is_active`, `folder_id`, `block_id`, `pos`, `module_id`, `controller`, `is_cached`, `cache_params`, `cache_params_yaml`, `params`, `plugins`, `owner_id`, `permissions`, `database_id`, `node_action_mode`, `descr`, `create_datetime`) VALUES
(1, 1, 1, 1, 0, 'Texter', NULL, 1, 'a:2:{s:4:"type";s:4:"html";s:8:"lifetime";s:4:"7000";}', 'type: html\r\nlifetime: 7000', 'a:2:{s:12:"text_item_id";s:1:"2";s:6:"editor";s:1:"1";}', NULL, 1, NULL, 0, 'popup', NULL, '0000-00-00 00:00:00'),
(2, 1, 1, 3, 0, 'Texter', NULL, 1, 'a:2:{s:4:"type";s:4:"html";s:8:"lifetime";s:4:"2000";}', 'type: html\r\nlifetime: 2000', 'a:2:{s:12:"text_item_id";s:1:"1";s:6:"editor";s:1:"1";}', NULL, 1, NULL, 0, 'popup', NULL, '0000-00-00 00:00:00'),
(3, 1, 2, 1, 0, 'Texter', NULL, 1, 'a:2:{s:4:"type";s:4:"html";s:8:"lifetime";s:4:"1000";}', 'type: html\r\nlifetime: 1000', 'a:2:{s:12:"text_item_id";s:1:"3";s:6:"editor";s:1:"1";}', NULL, 1, NULL, 0, 'popup', NULL, '0000-00-00 00:00:00'),
(4, 1, 2, 5, 0, 'Texter', NULL, 1, 'a:2:{s:4:"type";s:4:"html";s:8:"lifetime";s:4:"1000";}', 'type: html\r\nlifetime: 1000', 'a:2:{s:12:"text_item_id";s:1:"4";s:6:"editor";s:1:"1";}', NULL, 1, NULL, 0, 'popup', NULL, '0000-00-00 00:00:00'),
(5, 1, 1, 2, 0, 'Menu', NULL, 1, NULL, NULL, 'a:5:{s:13:"menu_group_id";s:1:"1";s:9:"max_depth";s:1:"3";s:9:"css_class";s:9:"main_menu";s:20:"selected_inheritance";s:1:"1";s:3:"tpl";s:4:"Menu";}', NULL, NULL, NULL, 0, 'popup', NULL, '0000-00-00 00:00:00'),
(6, 1, 1, 4, 0, 'Breadcrumbs', NULL, 1, NULL, NULL, 'N;', NULL, NULL, NULL, 0, 'popup', NULL, '0000-00-00 00:00:00'),
(7, 1, 3, 1, 0, 'UserAccount', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'popup', 'Учетная запись пользователя', '0000-00-00 00:00:00'),
(8, 1, 4, 1, 0, 'SmartCoreUserRegistration', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'popup', 'Регистрация пользователей', '0000-00-00 00:00:00');

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
