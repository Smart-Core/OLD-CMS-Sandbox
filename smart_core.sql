-- MySQL dump 10.13  Distrib 5.6.26, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: smart_core
-- ------------------------------------------------------
-- Server version	5.6.26-log

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
-- Table structure for table `_engine_permissions`
--

DROP TABLE IF EXISTS `_engine_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_engine_permissions` (
  `group_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `default_access` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `descr` text COMMENT 'Описание',
  PRIMARY KEY (`group_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Права доступа';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_engine_permissions`
--

LOCK TABLES `_engine_permissions` WRITE;
/*!40000 ALTER TABLE `_engine_permissions` DISABLE KEYS */;
INSERT INTO `_engine_permissions` VALUES ('engine','folder.read',1,'Чтение папок',NULL),('engine','folder.view',1,'Отображение',NULL),('engine','folder.write',0,'Создание в папке других папок и нод.',NULL),('engine','node.read',1,'Чтение ноды','Отображается нода или нет, соответственно обрабатывает её движок или нет.'),('engine','node.write',0,'Запись ноды','Возможность передачи ноде POST данных. '),('module.smartcore.news','create',0,'Создание новости',NULL);
/*!40000 ALTER TABLE `_engine_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `_engine_permissions_defaults`
--

DROP TABLE IF EXISTS `_engine_permissions_defaults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_engine_permissions_defaults` (
  `permission` varchar(100) NOT NULL,
  `role_id` varchar(50) NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '0',
  `descr` text NOT NULL,
  PRIMARY KEY (`role_id`),
  KEY `access` (`access`),
  KEY `permission` (`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Переопределения прав по умолчанию для различных ролей';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_engine_permissions_defaults`
--

LOCK TABLES `_engine_permissions_defaults` WRITE;
/*!40000 ALTER TABLE `_engine_permissions_defaults` DISABLE KEYS */;
INSERT INTO `_engine_permissions_defaults` VALUES ('engine:folder.write','ROLE_ADMIN',1,'Для администраторов разрешена запись в папки.'),('engine:folder.write','ROLE_USER',0,'Юзеры не могут создавать папки.');
/*!40000 ALTER TABLE `_engine_permissions_defaults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `_engine_permissions_groups`
--

DROP TABLE IF EXISTS `_engine_permissions_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_engine_permissions_groups` (
  `group_id` varchar(50) NOT NULL,
  `descr` text COMMENT 'Описание',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Группы прав доступа';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_engine_permissions_groups`
--

LOCK TABLES `_engine_permissions_groups` WRITE;
/*!40000 ALTER TABLE `_engine_permissions_groups` DISABLE KEYS */;
INSERT INTO `_engine_permissions_groups` VALUES ('engine','Ядро'),('module.smartcore.news','Модуль Новости'),('module.smartcore.texter','Модуль Текстер');
/*!40000 ALTER TABLE `_engine_permissions_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_articles`
--

DROP TABLE IF EXISTS `blog_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `is_commentable` tinyint(1) NOT NULL,
  `is_enabled` tinyint(1) DEFAULT '1',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `annotation` longtext COLLATE utf8_unicode_ci,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_CB80154F989D9B62` (`slug`),
  KEY `IDX_CB80154FF675F31B` (`author_id`),
  KEY `IDX_CB80154F12469DE2` (`category_id`),
  KEY `IDX_CB80154F8B8E8428` (`created_at`),
  CONSTRAINT `FK_CB80154F12469DE2` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`),
  CONSTRAINT `FK_CB80154FF675F31B` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_articles`
--

LOCK TABLES `blog_articles` WRITE;
/*!40000 ALTER TABLE `blog_articles` DISABLE KEYS */;
INSERT INTO `blog_articles` VALUES (3,1,3,NULL,1,1,'Хлебные крошки в Yii','breadcrumbs_yii','Хлебные крошки (Breadcrumbs) - это строка навигации до текущей страницы, сделанная из ссылок на родительские элементы. В Yii есть удобное средство для работы с хлебными крошками - виджет zii CBreadcrumbs http://www.yiiframework.com/doc/api/1.1/CBreadcrumbs<br />\n	Хочу описать, как подключить CBreadcrumbs.','<p></p>\n<hr id=\"readmore\" />\n<p>\n	В контроллере определяем общедоступную переменную-массив хлебных крошек. public $breadcrumbs=array();<br />\n	В layout view вставляем</p>\n<div class=\"highlight\">\n	<pre class=\"brush:php\">\n	&lt;?php if(isset($this-&gt;breadcrumbs)):?&gt;\n		&lt;?php $this-&gt;widget(&#39;zii.widgets.CBreadcrumbs&#39;, array(\n			&#39;links&#39;=&gt;$this-&gt;breadcrumbs,\n                        &#39;homeLink&#39;=&gt;CHtml::link(&#39;Главная&#39;,&#39;/&#39; ),\n		)); ?&gt;&lt;!-- breadcrumbs --&gt;\n	&lt;?php endif?&gt;</pre>\n</div>\n<p>\n	Здесь links &ndash; массив ссылок навигации, мы берём его из текущего контроллера.<br />\n	homeLink &ndash; ссылка на главную страницу.<br />\n	Теперь во view не забываем определить массив:</p>\n<div class=\"highlight\">\n	<pre class=\"brush:php\">\n$this-&gt;breadcrumbs=array(\n	&#39;Записи&#39;=&gt;array(&#39;index&#39;),\n	$model-&gt;title,\n);</pre>\n</div>\n<p>\n	Вот и всё.</p>\n','Yii, хлебные крошки','Как создать хлебные крошки в Yii','2011-11-26 10:06:15',NULL),(4,1,3,NULL,1,1,'Как подключить Ckeditor к фреймворку Yii','how_to_connect_ckeditor_to_framework_yii','Часто возникает необходимость использовать визуальный редактор на сайте. Есть несколько весьма популярных WYSIWYNG-редакторов. Один из них - Ckeditor. Сегодня я расскажу, как подключить Ckeditor к Yii.','<p></p>\n<hr id=\"readmore\" />\n<p>\n	Шаг первый: скачиваем сам редактор с официального сайта: <a href=\"http://ckeditor.com/download\" target=\"_blank\">http://ckeditor.com/download</a><br />\n	Распаковываем архив в корень сайта.<br />\n	Шаг второй: скачиваем расширение Yii ckeditor-integration <a href=\"http://www.yiiframework.com/extension/ckeditor-integration/\">отсюда</a>.<br />\n	Распаковываем в папку protected/extensions.<br />\n	Шаг третий: подключаем к форме наш редактор:</p>\n<div class=\"highlight\">\n	<pre class=\"brush: php\">\n&lt;?php\n$this-&gt;widget(&#39;ext.ckeditor.CKEditorWidget&#39;,array(\n  &quot;model&quot;=&gt;$model,                 # Модель данных\n  &quot;attribute&quot;=&gt;&#39;content&#39;,          # Аттрибут в модели\n  &quot;defaultValue&quot;=&gt;$model-&gt;content, #Значение по умолчанию\n\n  &quot;config&quot; =&gt; array(\n      &quot;height&quot;=&gt;&quot;400px&quot;,\n      &quot;width&quot;=&gt;&quot;100%&quot;,\n      &quot;toolbar&quot;=&gt;&quot;Full&quot;, #панель инструментов\n      &quot;defaultLanguage&quot;=&gt;&quot;ru&quot;, # Язык по умолчанию\n      ),\n   &quot;ckEditor&quot;=&gt;Yii::app()-&gt;basePath.&quot;/../ckeditor/ckeditor.php&quot;,\n                                  # Путь к ckeditor.php\n  &quot;ckBasePath&quot;=&gt;Yii::app()-&gt;baseUrl.&quot;/ckeditor/&quot;,\n                                  # адрес к редактору\n  ) ); ?&gt;</pre>\n</div>\n<div class=\"code\">\n	Все параметры конфига редактора смотрим <a href=\"http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html\">здесь</a></div>\n','Yii, Ckeditor, подключение','В статье рассказывается о том, как быстро и правильно подключить Ckeditor к Yii','2011-11-23 13:20:50',NULL),(5,1,3,NULL,1,1,'Форматирование даты и времени в Yii','formatting_of_date_and_time_in_yii','Передо мной встала такая задача: как в Yii вывести дату, отформатированную в родном, русском формате. Оказывается, очень просто. Во-первых, надо установить русский язык в конфигурационном файле приложения, и, во-вторых, воспользоваться методом компонента&nbsp; приложения CDateFormatter-&gt;format().','<p></p>\n<hr id=\"readmore\" />\n<p>\n	Итак, приступим. В конфигурационном файле пропишем две строчки, которые установят русификацию для сайта:</p>\n<div class=\"highlight\">\n	<pre class=\"brush: php\">\n   &#39;sourceLanguage&#39; =&gt; &#39;en&#39;,\n    &#39;language&#39; =&gt; &#39;ru&#39;,</pre>\n</div>\n<p>\n	Здесь sourceLanguage &ndash; язык, на котором написан сам сайт. У меня он, естественно, английский. Ну а текущий язык &ndash; language &ndash; русский.<br />\n	Теперь в том месте, где хотим вывести отформатированную дату, добавим такой код:</p>\n<div class=\"highlight\">\n	<pre class=\"brush: php\">\n	echo Yii::app()-&gt;dateFormatter-&gt;format(&quot;dd MMMM y, HH:mm&quot;, $vardatetime);</pre>\n</div>\n<p>\n	Выведет дату и время в таком формате:&nbsp; 29 ноября 2011, 16:41<br />\n	Метод format принимает два параметра: первый &ndash; шаблон времени в стандарте Юникода, второй &ndash; время в unix timestamp или Mysql DATETIME. Вот и всё.<br />\n	Более подробно о CDateFormatter смотрите <a href=\"http://www.yiiframework.com/doc/api/1.1/CDateFormatter\" target=\"_blank\">здесь</a><br />\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n','Yii, формат, дата','Как правильно и грамотно отформатировать дату и время в Yii','2012-02-25 15:28:38',NULL),(6,1,6,NULL,1,1,'Symfony2: справочник команд','s2_sik_knd','В этой статье буду писать самые часто используемые команды Симфони. Как ни странно, но на Симфони без командной строки ну никак. Полгода-год назад помнил многие команды наизусть, а сейчас, особенно после работы с Магенто, в голове чистый лист.','<p>&nbsp;</p>\r\n<hr id=\"readmore\" />\r\n<p>Инсталляция проекта:</p>\r\n<pre class=\"brush: text;\"> composer install --prefer-dist</pre>\r\n<p>Обновление проекта:</p>\r\n<pre class=\"brush: text;\"> composer update --prefer-dist</pre>\r\n<p>Обновление бандла:</p>\r\n<pre class=\"brush: text;\"> composer update friendsofsymfony/user-bundle</pre>\r\n<p>Создание базы:</p>\r\n<pre class=\"brush: text;\">php app/console doctrine:database:create</pre>\r\n<p>Создание таблиц:</p>\r\n<pre class=\"brush: text;\">php app/console doctrine:schema:create</pre>\r\n<p>Загрузка фикстур:</p>\r\n<pre class=\"brush: text;\">php app/console doctrine:fixtures:load</pre>\r\n<p>Обновление схемы базы:</p>\r\n<pre class=\"brush: text;\">php app/console doctrine:schema:update --force</pre>\r\n<p>Создание бандла:</p>\r\n<pre class=\"brush: text;\">php app/console generate:bundle --namespace=Acme/HelloBundle --format=yml</pre>\r\n<p>Очистка кэша:</p>\r\n<pre class=\"brush: text;\">php app/console cache:clear --env=prod --no-debug</pre>\r\n<p>Отладка роутеров:</p>\r\n<pre class=\"brush: text;\">php app/console router:debug</pre>\r\n<p>Показать все сервисы и классы связанные с сервисом:</p>\r\n<pre class=\"brush: text;\">php app/console container:debug</pre>\r\n<p>Показать приватные сервисы :</p>\r\n<pre class=\"brush: text;\">php app/console container:debug --show-private</pre>\r\n<p>Показать сервис по его id :</p>\r\n<pre class=\"brush: text;\">php app/console container:debug my_mailer</pre>\r\n<p>Обновить ассеты :</p>\r\n<pre class=\"brush: text;\">php app/console assetic:dump\r\nphp app/console assets:install\r\n</pre>\r\n<p>Примечание. Иногда бывает нужно явно указать --env=prod</p>','Symfony, команды','Самые часто используемые команды  Symfony2','2013-08-07 17:19:21','2014-02-09 09:55:42'),(7,1,7,NULL,1,1,'Подсвечиваем код на сайте','highlight_code_on_site','Давно мечтал о нормальной подсветке кода php, html и css. Наконец-то у меня выдалось время и я посветил этому вопросу несколько часов. Итак, небольшой обзор существующих способов подсветки показал, что её (подсветку) можно делать или не стороне клиента, или на стороне сервера. Для себя я сразу решил, что свой сервер грузить лишней работой не стоит. В общем, решил искать реализацию на JavaScript. Конечно, при отключённом js мои посетители не увидят подсветки, но таких, надеюсь, будет мало))','<p>&nbsp;</p>\r\n<hr id=\"readmore\" />\r\n<p>После гугления наткнулся на симпатичную статью. В ней описывался компактный скрипт highlight: <a href=\"http://softwaremaniacs.org/soft/highlight/\">http://softwaremaniacs.org/soft/highlight/</a></p>\r\n<p>Увы, после подключения подсветки кода я не увидел. Зато мой &laquo;любимый&raquo; IE подсветил ошибку на JavaScript. Мол, объект не поддерживает какое-то там свойство. Как Вы наверное понимаете, копаться в чужом коде и искать ошибку я не стал. Не подключается &ndash; и ладно, ищем другой скрипт.</p>\r\n<p>Кандидатом номер два стал SyntaxHighlighter от Alex Gorbatchev. Особенность скрипта &ndash; что он не требует jQuery (хотя я не считаю это преимуществом) и можно указать только те языки, которые нужны. &nbsp;После скачивания и настройки подсветка кода тут же заработала, что очень и очень меня порадовало!</p>\r\n<p>Архив качаем отсюда: <a href=\"http://alexgorbatchev.com/SyntaxHighlighter/download/\">http://alexgorbatchev.com/SyntaxHighlighter/download/</a></p>\r\n<p>Расскажу о некоторых особенностях настройки. Извлеките из скаченного архива и подключите следующие файлы:</p>\r\n<ol>\r\n<li>shCore.js</li>\r\n<li>shCore.css</li>\r\n<li>shThemeDefault.css</li>\r\n</ol>\r\n<p>Далее определитесь с языками, подсветка коих Вам нужна. Так, я выбрал себе css, html и php. Чтобы они заработали, надо подключить следующие файлы:&nbsp; shBrushCss.js,&nbsp; shBrushXml.js, shBrushPhp.js.</p>\r\n<p>И последний шаг &ndash; инициализация скрипта. Добавьте скрипт со строчкой</p>\r\n<pre class=\"brush: js;\">SyntaxHighlighter.all();</pre>\r\n<p>- и подсветка заработает.</p>\r\n<p>&nbsp;</p>\r\n<p>На этом собственно все. Заключительный штрих &ndash; у себя я отключил боковую панельку (полоса прокрутки+ссылка на сайт автора) командой SyntaxHighlighter.defaults[\'toolbar\'] = false;</p>\r\n<p>Как пользоваться подсветкой? Используйте тег &lt;pre&gt;с классом brush:[язык подсветки]. Т.е. для php это будет выглядеть так:</p>\r\n<p>&nbsp;</p>\r\n<pre class=\"brush: html;\">	&lt;pre class=\"brush: php;\"&gt;echo \"Привет, мир!\"; &lt;/pre&gt;</pre>\r\n<p>&nbsp;</p>\r\n<p>Скриптом я доволен.</p>','подсветка кода, highlight','Как подсветить код на сайте: используем highlight','2013-01-29 17:28:47','2014-02-09 10:06:05'),(8,1,6,NULL,1,1,'Настройка Symfony2 в PhpStorm','adjustment_symfony2_in_phpstorm','По горячим следам, пока помню, напишу об интеграции поддержки Symfony2 в phpStorm.','<p></p>\n<hr id=\"readmore\" />\n<p>\n	Нам потребуется плагин: <a href=\"http://plugins.jetbrains.com/plugin/7219?pr=phpStorm\">http://plugins.jetbrains.com/plugin/7219?pr=phpStorm</a></p>\n<p>\n	Устанавливаем его (File-&gt;Settings-&gt;Plugins, кнопка Install From Disk)</p>\n<p>\n	Перезапуcкаем PhpStorm. Идем в File-&gt;Settings-&gt;Symfony2 Plugin, ставим галку на Enable Plugin, проверяем пути (у меня var/cache/dev/appDevUrlGenerator.php и var/cache/dev/translations), в Container добавляем путь.</p>\n<p>\n	Ввводим команду php bin/warmup_cache</p>','phpStorm, Symfony2','Интеграции поддержки Symfony2 в phpStorm','2013-08-10 10:14:05',NULL),(9,1,6,NULL,1,1,'Ссылки на Symfony2','fourth','Ссылки на полезную литературу по Symfony2','<p></p>\n<hr id=\"readmore\" /><p>Работа с контейнером сервисов: <a href=\"http://symfony.com/doc/current/book/service_container.html\" target=\"_blank\">http://symfony.com/doc/current/book/service_container.html</a></p>\n<p>Поиск бандлов для Symfony2: на сайте <a href=\"http://knpbundles.com/\" target=\"_blank\">KnpBundles</a></p>',NULL,NULL,'2013-08-10 10:14:05',NULL),(10,1,4,NULL,1,1,'Установка Memcached на Windows 7 x64 (php 5.4.17)','installation_memcached_on_windows7_x64_php_5_4_17','Встала задача поставить себе memcached. В интернете есть много мануалов, но они в основном под 32-разрядные версии. Т.к. у меня 64-разрядный php, то возникли определенные трудности…','<p>&nbsp;</p>\r\n<hr id=\"readmore\" />\r\n<p>Начать с того, что 64-раздяную версию самого &nbsp;memcache найти не так-то просто. На официальном сайт лежат сырые исходники: <a href=\"http://code.google.com/p/memcached/downloads/list\">http://code.google.com/p/memcached/downloads/list</a></p>\r\n<p>Компилировать их показалось задачей сложной и страшной. После интенсивного поиска в гугле нашел вот <a href=\"http://s3.amazonaws.com/downloads.northscale.com/memcached-win64-1.4.4-14.zip\">тут</a> файлы версии 1.4.4-14 под Windows x64. Версия устаревшая, но выхода у меня не было (гугл показывал еще более старые версия), скачал себе эту.</p>\r\n<p>Создал на диске себе папку <strong>memcached</strong> &nbsp;и распаковал туда архив. Далее запустил командную строку (от имени Администратора!) и выполнил</p>\r\n<pre class=\"brush: bash;\">	C:\\memcached\\memcached.exe -d install</pre>\r\n<p>Пошел смотреть в Службы, как встал memcached (Панель управления-&gt;Администрирование-&gt;Службы) &ndash; служба с таким именем появилась. Запустил её, в свойствах прописал автоматический запуск.</p>\r\n<p>Осталось только подключиться к php. После поисков нашел тут: <a href=\"http://www.mediafire.com/download/8d3vd26z3fg6bf1/php_memcache-svn20120301-5.4-VC9-x64.zip\">http://www.mediafire.com/download/8d3vd26z3fg6bf1/php_memcache-svn20120301-5.4-VC9-x64.zip</a> - похожее на нужную версию.</p>\r\n<p>Однако при копировании вдруг обнаружил, что расширение (у меня php 5.4.17) php_memcache.dll уже есть&hellip; Решил, что &laquo;из коробки&raquo; будет надежнее.</p>\r\n<p>Прописал в php.ini в разделе с расширениями</p>\r\n<pre class=\"brush: ini;\">	[PHP_MEMCACHED]\r\n	extension = php_memcache.dll</pre>\r\n<p>Перезапустил апач, убедился, что php_info() вывел memcache</p>\r\n<p>Запустил тестовый файлик, ничего не сломалось.&nbsp; Ну посмотрим, как дальше себя поведет php&hellip;</p>\r\n<p>P.S. Так файлы на просторах интернета имеет тенденцию теряться (сколько я нерабочих ссылок сегодня нашел!), то прикладываю свой архивчик: <a href=\"/media/memcached.zip\">скачать</a></p>','php 5.4, Memcached, Windows 7 x64','Как установить поддержку Memcached php 5.4 на Windows 7 x64','2013-08-27 19:38:21','2014-02-09 09:44:07'),(11,1,8,NULL,1,1,'Debain7 – горячие команды сервера','debain7_hot_commands_of_the_server','Тут собрал команды, которые все время приходится использовать на сервере (ОС – Debain7)','<hr id=\"readmore\" />\r\n<p>Запуск apache:</p>\r\n<pre class=\"brush: bash;\">/etc/init.d/apache2 start</pre>\r\n<p>Остановка apache:</p>\r\n<pre class=\"brush: bash\">/etc/init.d/apache2 stop</pre>\r\n<p>Перезапуск apache:</p>\r\n<pre class=\"brush: bash\">/etc/init.d/apache2 restart</pre>','Debain7, команды','Часто используемые команды в Debain7','2013-08-29 22:09:51','2014-02-09 09:40:52'),(12,1,10,NULL,1,1,'CSS – линейный градиент фона','css_linear_gradient_of_background','Как сделать градиент фону, не прибегая к помощи фоновых рисунков? Современные браузеры поддерживают градиентную заливку с помощью CSS.','<p></p>\n<hr id=\"readmore\" />\n<div class=\"highlight\">\n	<pre class=\"brush: css\">\nbackground:#EFEFEF; /*цвет фона кнопки для браузеров без поддержки CSS3*/\nbackground: -webkit-gradient(linear, left top, left bottom, from(#3437CD), to(#538BFF)); /* для Webkit браузеров */\nbackground: -moz-linear-gradient(top,  #3437CD, #538BFF); /* для Firefox */\nbackground-image: -o-linear-gradient(top,  #3437CD,  #538BFF); /* для Opera 11 */\nfilter:  progid:DXImageTransform.Microsoft.gradient(startColorstr=&#39;#3437CD&#39;, endColorstr=&#39;#538BFF&#39;); /* фильтр для IE */\n\n</pre>\n</div>\n<p>\n	Чтобы сохранить&nbsp; кроссбраузерность, приходиться писать под каждый интернет-браузер отдельное правило CSS. Особо обрабатывается IE.&nbsp; В каждом правиле участвует два цвета &ndash; начальный и конечный.</p>\n','градиент фона, css','Создание градиента без помощи фоновых рисунков','2012-02-25 17:03:11',NULL),(13,1,12,NULL,1,1,'Подключаем Twitter Bootstrap к Yii','connect_twitter_bootstrap_to_yii','Совсем недавно мне рассказали о такой классной вещи, как фреймворк css от Твиттера - Twitter Bootstrap. Раньше, максимум, что я использовал - это \"reset css\". Все остальное писал ручками. Каждый раз - одно и то же. Что, понятно, отрицательно сказывалось на производительности. Немного поработав с этим фреймворком (оформление админки на Симфони 2) - пришел к выводу, что вещь это безусловно полезная. Реально ускоряет работу в разы. И вот я решил перевести и свой блог на Yii к этому же виду.','<p>&nbsp;</p>\r\n<hr id=\"readmore\" />\r\n<p>Перво-наперво скачал сам Twitter Bootstrap с гитхаба: <a href=\"https://github.com/twitter/bootstrap\">https://github.com/twitter/bootstrap</a>. Т.е я качал вместе с исходниками на языку less, т.к. планировал самостоятельно компилировать из них css. Вы же может скачать уже скомпилированные файлы, например, отсюда: <a href=\"http://bootstrap.veliovgroup.com/\">http://bootstrap.veliovgroup.com/</a> Но в этом случае уже нельзя будет изменять расцветку ну и вообще вносить изменения&hellip; В общем, я остановился на сырых исходниках.</p>\r\n<p>Компилировать исходники less я решил с помощью расширения Yii-less: <a href=\"http://www.yiiframework.com/extension/yii-less/\">http://www.yiiframework.com/extension/yii-less/</a></p>\r\n<p>Скачиваем данное расширение, ложем его в папку protected/extensions. В конфиге регистрируем новый&nbsp; behaviors:</p>\r\n<pre class=\"brush: php;\">	\'behaviors\'=&gt;array(\r\n	    \'ext.yii-less.components.LessCompilationBehavior\',\r\n	)\r\n</pre>\r\n<p>Регистрируем расширение как компонент:</p>\r\n<pre class=\"brush: php;\">\'components\'=&gt;array(\r\n  \'lessCompiler\'=&gt;array(\r\n    \'class\'=&gt;\'ext.yii-less.components.LessCompiler\',\r\n    \'paths\'=&gt;array(\r\n      // you can access to the compiled file on this path\r\n      \'/css/bootstrap.css\' =&gt; array(\r\n        \'precompile\' =&gt; true, // whether you want to cache the generation\r\n        \'paths\' =&gt; array(\'/less/bootstrap.less\') //paths of less files. you can specify multiple files.\r\n      ),\r\n    ),\r\n  ),\r\n),\r\n</pre>\r\n<p>&nbsp;</p>\r\n<p>И в лайоте пишем Yii::app()-&gt;clientScript-&gt;registerCssFile(\'/css/bootstrap.css\')</p>\r\n<p>Все, теперь при первом запуске в нашем ассете будет новый файл. Как альтернатива &ndash; можно компилировать файлы на стороне клиента (<a href=\"https://github.com/cloudhead/less.js\">https://github.com/cloudhead/less.js</a>)&nbsp; &ndash; но, на мой взгляд, это сильно скажется на производительности&hellip;.</p>\r\n<p>Одной проблемой меньше. Остался вопрос с подсветкой кода на less. Мой редактор (NetBeans) по умолчанию не распознает less. Исправляем это с помощь плагина scss-editor <a href=\"http://code.google.com/p/scss-editor/\">http://code.google.com/p/scss-editor/</a></p>\r\n<ol>\r\n<li>Качаем плагин, ставим его в NetBeans</li>\r\n<li>Ассоциируем с ним файлы Less &ndash; Сервис -&gt;Параметры -&gt;Файлы,&nbsp; создаем новое расширение less и в списке &laquo;Связанный тип файлов&raquo; задаем ему &laquo;text/x-scss&raquo;</li>\r\n</ol>\r\n<p style=\"margin-left: 18.0pt;\">Перезапускаем NetBeans &ndash; и подсветка появилась!</p>\r\n<p style=\"margin-left: 18.0pt;\">Напоследок замечу, что для Yii есть готовое решение в виде расширения yii-bootstrap: <a href=\"http://www.cniska.net/yii-bootstrap/\" target=\"_blank\">http://www.cniska.net/yii-bootstrap/</a> - но я его не пробовал. Лень разбираться&hellip;</p>\r\n<p><strong>UPD</strong> На Symfony2 этот же дизайн встал без проблем</p>','yii, twitter bootstap, подключение','Как подключить Twitter Bootstrap к Yii','2013-01-29 17:42:26','2014-02-09 09:56:53'),(14,1,13,NULL,1,1,'Создаем формы в Visual Studio 2012','create_forms_in_visual_sStudio_2012','Сегодня решил повозиться с Microsoft Visual Studio 2012 C++ - попробовать создать свою форму. Начал искать компоненты (как в Delphi) - но нигде их не нашел!','<p></p>\n<hr id=\"readmore\" />\n<p>\n	Погуглил и понял, что именно в 2012 версии, именно для языка C++ разработчики решили убрать поддержку Windows Forms Application. На просторах буржуйского интернета нашел замечательное решение. Нужно скачать шаблон <a href=\"http://dmitxe.ru/media/VS2012CPPWinForms.zip\">http://dmitxe.ru/media/VS2012CPPWinForms.zip</a> и скопировать его в C:\\Program Files (x86)\\Microsoft Visual Studio 11.0\\VC\\vcprojects\\vcNET\\ - при этом лучше на всякий случай сделать бэкап файла &quot;vcNET.vsdir&quot;. Использование: Файл-&gt;Проект-&gt;Шаблоны-&gt;Visual C++ -&gt; CLR-&gt;MC++ WinApp</p>\n<p>\n	Источник:&nbsp; <a href=\"http://www.t-hart.org/vs2012/\">http://www.t-hart.org/vs2012/</a></p>\n','форма, Visual Studio 2012 C++','Как создать приложение Windows Forms Application в Visual Studio 2012 C++','2013-06-06 20:31:23',NULL),(15,1,14,NULL,1,1,'NotePad++','notepad_plus_plus','Часто возникает необходимость быстрой перекодировки файла (например, из ansi в utf8, или наоборот). Есть замечательный (и притом бесплатный) редактор - NotePad++. С помощью него можно легко перекодировать файл из одной кодировки в другую. В этом редакторе есть даже подсветка кода. Конечно, я предпочитаю работать где-нибудь в Adobe Dreamweaver, NuSphere PHPED или в NetBeans. Но эти монстры подолгу грузятся, а иногда хочется быстро подправить код и тут же закрыть файл. Для этого как раз подойдёт NotePad++','<p></p>\n<hr id=\"readmore\" />\n<p>\n	Есть одна особенность перекодирования в utf8. Для преобразования кодировки&nbsp; файла выбираем в меню &laquo;Кодировки&raquo;-&gt; &laquo;Преобразовать в utf8&nbsp; без BOM&raquo;. Если выбрать просто &laquo;Преобразовать в utf8&raquo;, тогда случиться трагедия &ndash; страница перестанет правильно отображаться в браузере. Преобразование в ANSI таких проблем не имеет &ndash; есть только одно действие.<br />\n	Программа качается <a href=\"http://notepad-plus-plus.org/download/\" target=\"_blank\">отсюда</a>.<br />\n	&nbsp;</p>\n','редактор, кодировка','Как перекодировать файл с помощью NotePad++','2012-02-25 15:34:43',NULL),(16,1,15,NULL,1,1,'Что выбрать: фреймворк или CMS','framework_vs_cms','Свое знакомство с сайтостроением я начал с написания простейшего кода на HTML. Сайт получился, естественно, статическим. Следующий проект делал уже на PHP. Времени на написание ушло много, в результате у меня начала создаваться собственная CMS. К сожалению, данный факт осмыслил не сразу. А как только понял, что приду к CMS, решил не изобретать велосипед, освоил Joomla и WordPress.','<p></p>\n<hr id=\"readmore\" />\n<p>\n	&nbsp;Разработка стандартных сайтов (блогов, форумов и т.д.) пошла на ура. Но вся проблема оказалась в том, что многим заказчикам нужна некая особая, нестандартная функциональность. Реализовать которую в рамках данной CMS оказывается совсем непросто. Приходиться писать новые расширения или модифицировать существующий код. Времени такая работа занимает много, к тому же из-за взаимодействия с ядром CMS код не оптимальный. В общем, встал вопрос &ndash; что же проще &ndash; писать свою CMS или мучиться с существующими.</p>\n<p>\n	И тут я вспомнил о фреймворках. &nbsp;Фреймворк &ndash; это каркас для веб-приложения, а CMS &ndash; готовая система управления контентом. Наверное, можно фреймворк можно сравнить с кирпичами, из которых можно построить самые причудливые строения, а CMS &ndash; это стандартный дом.</p>\n<p>\n	После обзора самых популярных фреймворков я остановил свой выбор на Yii. Понравился достаточно строгий подход, относительная простота изучения (конечно, CodeIgniter осваивается легче, но возможности Yii богаче).</p>\n<p>\n	Теперь написать собственную, уникальную CMS стало гораздо проще. Конечно, стандартные проекты быстрее реализовать на готовой CMS, но многие проекты имею тенденцию превращаться из стандартных в нестандартные.</p>\n<p>\n	Этот блог я написал на Yii. А вот другой мой блог &ndash; netopus.ru написан CMS WordPress. Использовалась одна из бесплатных тем для WordPress.</p>\n<p><b>UPD</b> В сентябре 2013 года блог перешел на Symfony2 (движок SmartCore)</p>\n','фреймворк, CMS, выбор','Преимущества и недостатки фреймворка над CMS','2011-11-23 13:15:19',NULL),(17,1,6,NULL,1,1,'Twig в Symfony2: работа с датой и временем.','twig_in_symfony2_work_with_date_and_time','Поначалу возник недоуменный вопрос: как в twig отдать дату в нужном формате? Неужели дату можно форматировать только в контролере? Но погуглив, нашел ответы на свои вопросы.','<p>&nbsp;</p>\r\n<hr id=\"readmore\" />\r\n<p>Форматирование даты:</p>\r\n<pre class=\"brush: php;\">	var_date|date(\"d.m.y\")\r\n</pre>\r\n<p>Получение текущей даты:</p>\r\n<pre class=\"brush: php;\">	\"new\"|date(\"d.m.y\")\r\n</pre>\r\n<p>Интернационализация:</p>\r\n<p>1. Подключаем сервис в конфиге Symfony2</p>\r\n<pre class=\"brush: yaml;\">	services:\r\n        twig_extension.intl:\r\n            class: Twig_Extensions_Extension_Intl\r\n            tags: [{ name: \"twig.extension\" }]\r\n</pre>\r\n<p>2. Пример вызова</p>\r\n<pre class=\"brush: twig;\">	{{ item.date|localizeddate(\"none\", \"none\", null, null, \"dd. LLLL YYYY\") }}\r\n</pre>','Symfony2, Twig, дата и время','Symfony2 работа с датой и временем из Twig','2013-09-05 18:19:56','2014-02-09 09:36:49'),(18,NULL,NULL,NULL,1,1,'Проверка на изменение сущности','entity_change',NULL,'/* @var $em EntityManager */<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $uow = $em-&gt;getUnitOfWork();<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $uow-&gt;computeChangeSets();<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $r = $uow-&gt;getEntityChangeSet($entity);<br /><br />',NULL,NULL,'2014-03-05 19:35:31','2014-11-07 01:25:37');
/*!40000 ALTER TABLE `blog_articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_articles_tags_relations`
--

DROP TABLE IF EXISTS `blog_articles_tags_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_articles_tags_relations` (
  `article_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `IDX_512A6F437294869C` (`article_id`),
  KEY `IDX_512A6F43BAD26311` (`tag_id`),
  CONSTRAINT `FK_512A6F437294869C` FOREIGN KEY (`article_id`) REFERENCES `blog_articles` (`id`),
  CONSTRAINT `FK_512A6F43BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `blog_tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_articles_tags_relations`
--

LOCK TABLES `blog_articles_tags_relations` WRITE;
/*!40000 ALTER TABLE `blog_articles_tags_relations` DISABLE KEYS */;
INSERT INTO `blog_articles_tags_relations` VALUES (3,2),(3,3),(4,3),(4,4),(4,5),(5,3),(5,6),(5,7),(6,8),(6,9),(7,10),(8,8),(8,11),(9,8),(10,12),(10,13),(11,9),(11,14),(12,15),(12,16),(13,3),(13,5),(13,17),(14,18),(14,19),(15,20),(15,21),(16,22),(16,23),(16,24),(17,7),(17,8),(17,25),(18,8);
/*!40000 ALTER TABLE `blog_articles_tags_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(10) unsigned DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_DC356481989D9B62` (`slug`),
  KEY `IDX_DC3564813D8E604F` (`parent`),
  CONSTRAINT `FK_DC3564813D8E604F` FOREIGN KEY (`parent`) REFERENCES `blog_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (3,4,'yii','Yii','2014-02-08 20:57:52'),(4,5,'php','PHP','2014-02-08 20:57:52'),(5,NULL,'programing','Программирование','2014-02-08 20:57:52'),(6,4,'symfony2','Symfony2','2014-02-08 20:57:52'),(7,5,'js','JavaScript','2014-02-08 20:57:52'),(8,9,'debian','Debian','2014-02-08 20:57:52'),(9,NULL,'os','Операционные системы','2014-02-08 20:57:52'),(10,11,'css','CSS','2014-02-08 20:57:52'),(11,NULL,'imposition','Верстка','2014-02-08 20:57:52'),(12,10,'twitter_bootstrap','Twitter Bootstrap','2014-02-08 20:57:52'),(13,5,'cpp','C++','2014-02-08 20:57:52'),(14,NULL,'soft','Программы (софт)','2014-02-08 20:57:52'),(15,NULL,'other','Другое','2014-02-08 20:57:52');
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_tags`
--

DROP TABLE IF EXISTS `blog_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8F6C18B6989D9B62` (`slug`),
  UNIQUE KEY `UNIQ_8F6C18B62B36786B` (`title`),
  KEY `IDX_8F6C18B67CD5541` (`weight`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_tags`
--

LOCK TABLES `blog_tags` WRITE;
/*!40000 ALTER TABLE `blog_tags` DISABLE KEYS */;
INSERT INTO `blog_tags` VALUES (2,'breadcrumbs','Breadcrumbs','2014-02-08 20:57:52',0),(3,'yii','Yii','2014-02-08 20:57:52',0),(4,'ckeditor','Ckeditor','2014-02-08 20:57:52',0),(5,'connect','Подключение','2014-02-08 20:57:52',0),(6,'formatting','Форматирование','2014-02-08 20:57:52',0),(7,'date_and_time','Дата и время','2014-02-08 20:57:52',0),(8,'symfony2','Symfony2','2014-02-08 20:57:52',0),(9,'commands','Консольные команды','2014-02-08 20:57:52',0),(10,'code_illumination','Подсветка кода','2014-02-08 20:57:52',0),(11,'phpstorm','phpStorm','2014-02-08 20:57:52',0),(12,'php','PHP','2014-02-08 20:57:52',0),(13,'memcached','Memcached','2014-02-08 20:57:52',0),(14,'debian','Debian','2014-02-08 20:57:52',0),(15,'css','CSS','2014-02-08 20:57:52',0),(16,'linear_gradient','Линейный градиент','2014-02-08 20:57:52',0),(17,'twitter_bootstrap','Twitter Bootstrap','2014-02-08 20:57:52',0),(18,'forms','Формы','2014-02-08 20:57:52',0),(19,'visual_sStudio_2012_cpp','Visual Studio 2012 C++','2014-02-08 20:57:52',0),(20,'editor','Редактор','2014-02-08 20:57:52',0),(21,'encoding','Кодировка','2014-02-08 20:57:52',0),(22,'framework','Фреймворк','2014-02-08 20:57:52',0),(23,'cms','CMS','2014-02-08 20:57:52',0),(24,'select','Выбор','2014-02-08 20:57:52',0),(25,'twig','Twig','2014-02-08 20:57:52',0);
/*!40000 ALTER TABLE `blog_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `engine_appearance_history`
--

DROP TABLE IF EXISTS `engine_appearance_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_appearance_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9078E776D1B862B8` (`hash`),
  KEY `IDX_9078E776B548B0F` (`path`),
  KEY `IDX_9078E7763C0BE965` (`filename`),
  KEY `IDX_9078E776A76ED395` (`user_id`),
  CONSTRAINT `FK_9078E776A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `engine_appearance_history`
--

LOCK TABLES `engine_appearance_history` WRITE;
/*!40000 ALTER TABLE `engine_appearance_history` DISABLE KEYS */;
INSERT INTO `engine_appearance_history` VALUES (1,'/Resources/views/','_example.html.twig','{% extends \'@Html/base.html.twig\' %}\r\n\r\n{% block title %}{{ cms_html_title({\r\n    \'delimiter\': \'&ndash;\',\r\n    \'site_full_name\': \'Новый сайт 2\',\r\n    \'site_short_name\': \'Новый сайт\'\r\n}) }}{% endblock %}\r\n\r\n{% block styles %}\r\n    {{ parent() }}\r\n{% endblock %}\r\n\r\n{% block scripts %}\r\n    {{ parent() }}\r\n{% endblock %}\r\n\r\n{% block body %}\r\n<div class=\"container\">\r\n    <div class=\"row-fluid\">\r\n        <div class=\"span3\">\r\n            {{ main_menu }}\r\n        </div>\r\n\r\n        <div class=\"span9\">\r\n            <div id=\"breadcrumbs\">\r\n                {{ breadcrumbs }}\r\n            </div>\r\n\r\n            {% include \'@SmartCore/flash_messages.html.twig\' %}\r\n\r\n            <div id=\"content\">\r\n                {% block content content %}\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class=\"row-fluid\" id=\"footer\">\r\n        <div class=\"span12\">\r\n            {{ footer }}\r\n        </div>\r\n    </div>\r\n</div>\r\n{% endblock body %}\r\n','2015-10-12 00:31:51',1,'faf4d807b635f0856a9765b9ffa7db90');
/*!40000 ALTER TABLE `engine_appearance_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `engine_folders`
--

DROP TABLE IF EXISTS `engine_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_folders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder_pid` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_file` tinyint(1) NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `uri_part` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `description` longtext COLLATE utf8_unicode_ci,
  `meta` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `redirect_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `router_node_id` int(11) DEFAULT NULL,
  `permissions` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `lockout_nodes` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `template_inheritable` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `template_self` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6C047E64A640A07B79628CD` (`folder_pid`,`uri_part`),
  KEY `IDX_6C047E64A640A07B` (`folder_pid`),
  KEY `IDX_6C047E641B5771DD` (`is_active`),
  KEY `IDX_6C047E64462CE4F5` (`position`),
  KEY `IDX_6C047E64A76ED395` (`user_id`),
  KEY `IDX_6C047E644AF38FD1` (`deleted_at`),
  CONSTRAINT `FK_6C047E64A640A07B` FOREIGN KEY (`folder_pid`) REFERENCES `engine_folders` (`id`),
  CONSTRAINT `FK_6C047E64A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `engine_folders`
--

LOCK TABLES `engine_folders` WRITE;
/*!40000 ALTER TABLE `engine_folders` DISABLE KEYS */;
INSERT INTO `engine_folders` VALUES (1,NULL,'Главная',0,0,NULL,1,':)','a:4:{s:8:\"keywords\";s:3:\"cms\";s:11:\"description\";s:3:\"cms\";s:6:\"robots\";s:3:\"all\";s:6:\"author\";s:10:\"Артём\";}',NULL,NULL,NULL,NULL,'',1,'2013-03-19 00:44:38','',NULL),(2,1,'О компании',0,10,'about',1,NULL,'a:0:{}',NULL,NULL,NULL,NULL,'inner',1,'2013-03-11 16:42:33','inner',NULL),(3,1,'Личный кабинет',0,255,'user',1,NULL,'a:0:{}',NULL,7,'N;','N;','',1,'2013-03-18 01:15:06','',NULL),(4,8,'Вложенная',0,0,'under_news',1,NULL,'a:0:{}',NULL,NULL,'N;','N;',NULL,1,'2013-03-18 01:15:27',NULL,NULL),(5,1,'Так просто ;)',0,3,'simple',1,NULL,'a:0:{}',NULL,NULL,'N;','N;','',1,'2013-03-19 04:43:50','',NULL),(6,2,'Вложенная папка',0,0,'inner',1,NULL,'a:0:{}',NULL,NULL,'N;','N;','',1,'2013-03-19 04:47:22','default',NULL),(7,1,'22222222222222',0,10,'22222222',0,'22','N;',NULL,NULL,'N;','N;',NULL,1,'2013-08-10 11:14:06',NULL,NULL),(8,1,'Новости',0,0,'news',1,NULL,'a:0:{}',NULL,12,'N;','N;',NULL,1,'2013-12-22 21:45:42',NULL,NULL),(11,6,'Еще одна вложенная',0,0,'in2',1,NULL,'a:0:{}',NULL,NULL,'N;','N;',NULL,1,'2014-01-29 10:30:42',NULL,NULL),(12,1,'Слайдер',0,0,'slider',1,NULL,'N;',NULL,NULL,'N;','N;',NULL,1,'2014-01-30 20:38:12',NULL,NULL),(13,1,'Блог',0,0,'blog',1,NULL,'N;',NULL,22,'N;','N;',NULL,1,'2014-02-07 18:01:54',NULL,NULL),(14,12,'Nivo',0,0,'nivo',1,NULL,'N;',NULL,NULL,'N;','N;',NULL,1,'2014-02-10 07:55:59',NULL,NULL),(15,1,'Каталог',0,0,'catalog',1,NULL,'a:0:{}',NULL,28,'N;','N;','',1,'2014-02-12 16:12:18','inner',NULL),(16,1,'Блог на юникате',0,0,'unicat_blog',0,NULL,'a:0:{}',NULL,NULL,'N;','N;','',1,'2014-07-01 13:34:57','',NULL),(17,1,'Фотогалерея',0,0,'gallery',1,NULL,'a:0:{}',NULL,31,'N;','N;',NULL,1,'2014-07-15 03:28:01',NULL,NULL),(18,1,'Веб-форма',0,0,'web-form',1,NULL,'a:0:{}',NULL,34,'N;','N;',NULL,1,'2015-03-24 03:17:14',NULL,NULL),(19,3,'Моя корзина',0,0,'basket',1,NULL,'a:0:{}',NULL,36,'N;','N;','',1,'2015-10-09 01:11:07','',NULL),(20,3,'История заказов',0,0,'orders',1,NULL,'a:0:{}',NULL,37,'N;','N;','',1,'2015-10-14 17:25:33','',NULL);
/*!40000 ALTER TABLE `engine_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `engine_modules`
--

DROP TABLE IF EXISTS `engine_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BC84EEBC46C53D4C` (`is_enabled`),
  KEY `IDX_BC84EEBC8B8E8428` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `engine_modules`
--

LOCK TABLES `engine_modules` WRITE;
/*!40000 ALTER TABLE `engine_modules` DISABLE KEYS */;
INSERT INTO `engine_modules` VALUES (1,1,'2015-02-18 05:20:34','Blog','Блог',NULL,'\\SmartCore\\Module\\Blog\\BlogModule');
/*!40000 ALTER TABLE `engine_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `engine_nodes`
--

DROP TABLE IF EXISTS `engine_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder_id` int(10) unsigned DEFAULT NULL,
  `region_id` int(10) unsigned DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `position` smallint(6) DEFAULT '0',
  `priority` smallint(6) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_cached` tinyint(1) NOT NULL,
  `template` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `controls_in_toolbar` smallint(6) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_use_eip` tinyint(1) NOT NULL DEFAULT '1',
  `code_before` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_after` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3055D1B7162CB942` (`folder_id`),
  KEY `IDX_3055D1B71B5771DD` (`is_active`),
  KEY `IDX_3055D1B7462CE4F5` (`position`),
  KEY `IDX_3055D1B798260155` (`region_id`),
  KEY `IDX_3055D1B7C242628` (`module`),
  KEY `IDX_3055D1B7A76ED395` (`user_id`),
  KEY `IDX_3055D1B74AF38FD1` (`deleted_at`),
  CONSTRAINT `FK_3055D1B7162CB942` FOREIGN KEY (`folder_id`) REFERENCES `engine_folders` (`id`),
  CONSTRAINT `FK_3055D1B798260155` FOREIGN KEY (`region_id`) REFERENCES `engine_regions` (`id`),
  CONSTRAINT `FK_3055D1B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `engine_nodes`
--

LOCK TABLES `engine_nodes` WRITE;
/*!40000 ALTER TABLE `engine_nodes` DISABLE KEYS */;
INSERT INTO `engine_nodes` VALUES (1,1,4,1,'Texter','a:2:{s:12:\"text_item_id\";i:1;s:6:\"editor\";b:1;}',20,0,'Футер',1,'2013-03-20 05:46:40',0,NULL,0,NULL,1,'<h1 class=\"test2\">','</h1>'),(2,2,5,1,'Texter','a:2:{s:12:\"text_item_id\";i:4;s:6:\"editor\";b:1;}',0,1,'Правая колонка',1,'2013-03-20 09:07:33',0,NULL,1,NULL,1,NULL,NULL),(3,2,1,1,'Texter','a:2:{s:12:\"text_item_id\";i:3;s:6:\"editor\";b:1;}',0,2,'Левая колонка',1,'2013-03-21 06:03:37',0,NULL,1,NULL,1,NULL,NULL),(4,1,1,1,'Texter','a:2:{s:12:\"text_item_id\";i:2;s:6:\"editor\";b:1;}',0,0,'Главная',1,'2013-03-11 16:42:33',0,NULL,1,NULL,1,NULL,NULL),(5,1,3,1,'Menu','a:5:{s:5:\"depth\";N;s:9:\"css_class\";s:9:\"main_menu\";s:20:\"selected_inheritance\";b:0;s:13:\"current_class\";N;s:7:\"menu_id\";i:1;}',1,0,NULL,1,'2013-03-11 16:42:33',1,'test',0,NULL,1,NULL,NULL),(6,1,2,1,'Breadcrumbs','a:2:{s:9:\"delimiter\";s:2:\"»\";s:17:\"hide_if_only_home\";b:1;}',0,-255,NULL,1,'2013-03-11 16:42:33',0,NULL,0,NULL,1,NULL,NULL),(7,3,1,1,'User','a:2:{s:18:\"allow_registration\";b:1;s:24:\"allow_password_resetting\";b:1;}',0,255,NULL,1,'2013-03-11 16:42:33',0,NULL,0,NULL,1,NULL,NULL),(9,3,3,1,'Texter','a:2:{s:12:\"text_item_id\";i:6;s:6:\"editor\";b:1;}',1,0,'Текст под меню',1,'2013-03-25 21:53:12',0,NULL,0,NULL,1,NULL,NULL),(10,7,1,1,'Texter','a:2:{s:12:\"text_item_id\";i:7;s:6:\"editor\";b:1;}',0,0,NULL,1,'2013-08-10 11:14:55',0,NULL,0,NULL,1,NULL,NULL),(11,5,1,1,'Texter','a:2:{s:12:\"text_item_id\";i:8;s:6:\"editor\";b:1;}',0,0,NULL,1,'2013-12-20 20:11:41',0,NULL,1,NULL,1,NULL,NULL),(12,8,1,1,'SimpleNews','a:1:{s:14:\"items_per_page\";i:3;}',1,0,NULL,1,'2013-12-22 21:58:57',0,NULL,1,NULL,1,NULL,NULL),(13,1,6,1,'Texter','a:2:{s:12:\"text_item_id\";i:9;s:6:\"editor\";b:1;}',0,0,NULL,1,'2014-01-20 03:47:18',0,NULL,0,NULL,1,NULL,NULL),(15,8,1,1,'Texter','a:2:{s:12:\"text_item_id\";i:10;s:6:\"editor\";b:1;}',0,0,'Текст над новостями',1,'2014-01-22 19:02:27',0,NULL,0,NULL,1,NULL,NULL),(16,6,1,1,'Texter','a:2:{s:12:\"text_item_id\";i:11;s:6:\"editor\";b:1;}',0,0,NULL,1,'2014-01-29 10:01:55',0,NULL,1,NULL,1,NULL,NULL),(17,1,1,1,'Widget','a:5:{s:7:\"node_id\";s:2:\"12\";s:10:\"controller\";s:15:\"NewsWidget:last\";s:6:\"params\";s:8:\"count: 3\";s:8:\"open_tag\";s:48:\"<hr /><h3>Последние новости</h3>\";s:9:\"close_tag\";N;}',10,0,NULL,1,'2014-01-29 18:27:59',0,NULL,0,NULL,1,NULL,NULL),(18,1,3,0,'Texter','a:2:{s:12:\"text_item_id\";i:12;s:6:\"editor\";b:1;}',9,0,'Последние новости',1,'2014-01-29 19:43:16',0,NULL,0,NULL,1,NULL,NULL),(19,1,3,0,'Texter','a:2:{s:12:\"text_item_id\";i:13;s:6:\"editor\";b:1;}',0,0,'Надпись над меню',1,'2014-01-29 19:45:52',0,NULL,0,NULL,1,NULL,NULL),(20,11,1,1,'Texter','a:2:{s:12:\"text_item_id\";i:14;s:6:\"editor\";b:1;}',0,0,NULL,1,'2014-01-29 20:16:33',0,NULL,1,NULL,1,NULL,NULL),(21,12,1,1,'Slider','a:1:{s:9:\"slider_id\";i:1;}',0,0,'Цветочки!',1,'2014-01-30 20:38:27',0,NULL,1,NULL,1,NULL,NULL),(22,13,1,1,'Blog','a:0:{}',0,0,NULL,1,'2014-02-07 18:02:37',0,NULL,1,NULL,1,NULL,NULL),(23,13,3,1,'Widget','a:5:{s:7:\"node_id\";s:2:\"22\";s:10:\"controller\";s:19:\"BlogWidget:tagCloud\";s:6:\"params\";N;s:8:\"open_tag\";s:34:\"<hr /><h4>Тэги блога</h4>\";s:9:\"close_tag\";N;}',30,0,'Тэги блога',1,'2014-02-07 22:55:10',0,NULL,0,NULL,1,NULL,NULL),(24,1,3,0,'Texter','a:2:{s:12:\"text_item_id\";i:15;s:6:\"editor\";b:1;}',19,0,NULL,1,'2014-02-08 21:01:35',0,NULL,0,NULL,1,NULL,NULL),(25,4,1,1,'Texter','a:2:{s:12:\"text_item_id\";i:16;s:6:\"editor\";b:1;}',29,0,NULL,1,'2014-02-08 21:04:03',0,NULL,1,NULL,1,NULL,NULL),(26,13,3,1,'Widget','a:6:{s:7:\"node_id\";s:2:\"22\";s:10:\"controller\";s:23:\"BlogWidget:categoryTree\";s:6:\"params\";N;s:8:\"open_tag\";s:44:\"<hr /><h4>Категории блога</h4>\";s:9:\"close_tag\";N;s:14:\"tamplate_theme\";N;}',20,0,'Категории блога',1,'2014-02-08 21:04:50',0,NULL,0,NULL,1,NULL,NULL),(27,14,1,1,'Slider','a:1:{s:9:\"slider_id\";i:6;}',0,0,'Nivo',1,'2014-02-10 08:13:18',0,NULL,1,NULL,1,NULL,NULL),(28,15,1,1,'Unicat','a:4:{s:13:\"repository_id\";i:1;s:16:\"configuration_id\";i:1;s:19:\"use_item_id_as_slug\";b:0;s:20:\"use_layout_for_items\";s:5:\"inner\";}',0,0,NULL,1,'2014-02-12 16:23:22',0,'catalog',1,NULL,1,NULL,NULL),(29,15,3,1,'Widget','a:6:{s:7:\"node_id\";s:2:\"28\";s:10:\"controller\";s:22:\"UnicatWidget:taxonTree\";s:6:\"params\";s:41:\"structure: 1\r\nselected_inheritance: false\";s:8:\"open_tag\";s:50:\"<hr /><h4>Категории каталога</h4>\";s:9:\"close_tag\";N;s:14:\"tamplate_theme\";N;}',0,0,'Виджет категорий каталога',1,'2014-03-06 12:24:51',0,NULL,0,NULL,1,NULL,NULL),(30,16,1,1,'Catalog2','a:1:{s:13:\"repository_id\";i:3;}',0,0,'Тесты с каталогом',1,'2014-07-01 13:42:20',0,NULL,0,'2015-03-02 03:42:04',1,NULL,NULL),(31,17,1,1,'Gallery','a:1:{s:10:\"gallery_id\";i:1;}',0,0,NULL,1,'2014-07-15 03:38:38',0,NULL,1,NULL,1,NULL,NULL),(32,16,1,0,'Unicat','a:2:{s:16:\"configuration_id\";N;s:19:\"use_item_id_as_slug\";b:0;}',0,0,NULL,1,'2015-03-02 11:37:36',0,NULL,1,NULL,1,NULL,NULL),(33,16,3,1,'Widget','a:6:{s:7:\"node_id\";s:2:\"32\";s:10:\"controller\";s:22:\"UnicatWidget:taxonTree\";s:6:\"params\";s:12:\"structure: 3\";s:8:\"open_tag\";s:44:\"<hr /><h4>Категории блога</h4>\";s:9:\"close_tag\";N;s:14:\"tamplate_theme\";N;}',0,0,NULL,1,'2015-03-02 11:58:06',0,NULL,1,NULL,1,NULL,NULL),(34,18,1,1,'WebForm','a:1:{s:10:\"webform_id\";i:1;}',0,0,NULL,1,'2015-03-24 03:18:10',0,NULL,1,NULL,1,NULL,NULL),(35,1,3,1,'Shop','a:2:{s:4:\"mode\";s:13:\"basket_widget\";s:14:\"basket_node_id\";s:2:\"36\";}',0,0,'Виджет корзинки',1,'2015-10-09 00:50:43',0,NULL,0,NULL,1,NULL,NULL),(36,19,1,1,'Shop','a:1:{s:4:\"mode\";s:6:\"basket\";}',0,0,NULL,1,'2015-10-09 01:11:27',0,NULL,1,NULL,1,NULL,NULL),(37,20,1,1,'Shop','a:2:{s:14:\"basket_node_id\";s:2:\"36\";s:4:\"mode\";s:9:\"my_orders\";}',0,0,'Мои заказы',1,'2015-10-14 19:51:05',0,NULL,1,NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `engine_nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `engine_regions`
--

DROP TABLE IF EXISTS `engine_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position` smallint(6) DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `description` longtext,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3054D4985E237E06` (`name`),
  KEY `IDX_3054D498462CE4F5` (`position`),
  KEY `IDX_3054D498A76ED395` (`user_id`),
  CONSTRAINT `FK_3054D498A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `engine_regions`
--

LOCK TABLES `engine_regions` WRITE;
/*!40000 ALTER TABLE `engine_regions` DISABLE KEYS */;
INSERT INTO `engine_regions` VALUES (1,0,'content','Рабочая область',1,'2013-03-11 01:09:17'),(2,2,'breadcrumbs','Хлебные крошки',1,'2013-03-11 01:09:33'),(3,1,'main_menu','Навигационное меню',1,'2013-03-11 04:00:50'),(4,3,'footer','Футер',1,'2013-03-11 04:01:30'),(5,5,'right_column','Правая колонка',1,'2013-03-23 23:46:01'),(6,3,'footer_right','Футер справа',1,'2014-01-20 04:04:24');
/*!40000 ALTER TABLE `engine_regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `engine_regions_inherit`
--

DROP TABLE IF EXISTS `engine_regions_inherit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_regions_inherit` (
  `region_id` int(10) unsigned NOT NULL,
  `folder_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`region_id`,`folder_id`),
  KEY `IDX_41BBC12298260155` (`region_id`),
  KEY `IDX_41BBC122162CB942` (`folder_id`),
  CONSTRAINT `FK_41BBC122162CB942` FOREIGN KEY (`folder_id`) REFERENCES `engine_folders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_41BBC12298260155` FOREIGN KEY (`region_id`) REFERENCES `engine_regions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `engine_regions_inherit`
--

LOCK TABLES `engine_regions_inherit` WRITE;
/*!40000 ALTER TABLE `engine_regions_inherit` DISABLE KEYS */;
INSERT INTO `engine_regions_inherit` VALUES (2,1),(3,1),(4,1),(6,1);
/*!40000 ALTER TABLE `engine_regions_inherit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `engine_roles`
--

DROP TABLE IF EXISTS `engine_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `position` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9B56FA8C5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `engine_roles`
--

LOCK TABLES `engine_roles` WRITE;
/*!40000 ALTER TABLE `engine_roles` DISABLE KEYS */;
INSERT INTO `engine_roles` VALUES (1,'ROLE_ADMIN',0),(2,'ROLE_ROOT',0),(3,'ROLE_NEWSMAKER',0);
/*!40000 ALTER TABLE `engine_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galleries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `media_collection_id` int(11) unsigned NOT NULL,
  `order_albums_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F70E6EB7B52E685C` (`media_collection_id`),
  KEY `IDX_F70E6EB7A76ED395` (`user_id`),
  CONSTRAINT `FK_F70E6EB7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_F70E6EB7B52E685C` FOREIGN KEY (`media_collection_id`) REFERENCES `media_collections` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` VALUES (1,'Тестовая фотогалерея','2014-07-15 03:59:43',1,2,0);
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_albums`
--

DROP TABLE IF EXISTS `gallery_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_albums` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cover_image_id` int(11) DEFAULT NULL,
  `photos_count` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT '1',
  `last_image_id` int(11) DEFAULT NULL,
  `position` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_5661ABED4E7AF8F` (`gallery_id`),
  KEY `IDX_5661ABEDA76ED395` (`user_id`),
  CONSTRAINT `FK_5661ABED4E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`),
  CONSTRAINT `FK_5661ABEDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_albums`
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` VALUES (1,1,'Первый альбом',NULL,'2014-07-15 04:17:36',1,8,2,'2014-07-15 23:54:05',1,8,0),(2,1,'Второй альбом',NULL,'2014-07-15 04:18:21',1,NULL,0,'2016-09-20 03:18:45',0,NULL,0);
/*!40000 ALTER TABLE `gallery_albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_photos`
--

DROP TABLE IF EXISTS `gallery_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `album_id` int(11) unsigned DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `position` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_AAF50C7B1137ABCF` (`album_id`),
  KEY `IDX_AAF50C7BA76ED395` (`user_id`),
  CONSTRAINT `FK_AAF50C7B1137ABCF` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`),
  CONSTRAINT `FK_AAF50C7BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_photos`
--

LOCK TABLES `gallery_photos` WRITE;
/*!40000 ALTER TABLE `gallery_photos` DISABLE KEYS */;
INSERT INTO `gallery_photos` VALUES (1,7,1,NULL,'2014-07-15 23:49:51',1,0),(2,8,1,NULL,'2014-07-15 23:54:05',1,0);
/*!40000 ALTER TABLE `gallery_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_categories`
--

DROP TABLE IF EXISTS `media_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_30D688FC727ACA70` (`parent_id`),
  KEY `IDX_30D688FC989D9B62` (`slug`),
  CONSTRAINT `FK_30D688FC727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `media_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_categories`
--

LOCK TABLES `media_categories` WRITE;
/*!40000 ALTER TABLE `media_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_collections`
--

DROP TABLE IF EXISTS `media_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_collections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `default_storage_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_filter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `relative_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename_pattern` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `file_relative_path_pattern` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_244DA17D14E68FF3` (`default_storage_id`),
  CONSTRAINT `FK_244DA17D14E68FF3` FOREIGN KEY (`default_storage_id`) REFERENCES `media_storages` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_collections`
--

LOCK TABLES `media_collections` WRITE;
/*!40000 ALTER TABLE `media_collections` DISABLE KEYS */;
INSERT INTO `media_collections` VALUES (1,1,'Каталог товаров','300_300','N;','/catalog','{hour}_{minutes}_{rand(10)}','/{year}/{month}/{day}','2014-02-14 13:43:18'),(2,1,'Фотогалерея',NULL,'N;','/gallery','{hour}_{minutes}_{rand(10)}','/{year}/{month}/{day}','2014-07-15 04:46:03'),(3,1,'Блог',NULL,'N;','/blog','{hour}_{minutes}_{rand(10)}','/{year}/{month}/{day}','2015-03-08 15:07:15');
/*!40000 ALTER TABLE `media_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_files`
--

DROP TABLE IF EXISTS `media_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `collection_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned DEFAULT NULL,
  `storage_id` int(11) unsigned NOT NULL,
  `is_preuploaded` tinyint(1) NOT NULL,
  `relative_path` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filename` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `original_filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `mime_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `original_size` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_192C84E8514956FD` (`collection_id`),
  KEY `IDX_192C84E812469DE2` (`category_id`),
  KEY `IDX_192C84E85CC5DB90` (`storage_id`),
  KEY `IDX_192C84E88CDE5729` (`type`),
  KEY `IDX_192C84E8A76ED395` (`user_id`),
  KEY `IDX_192C84E8F7C0246A` (`size`),
  CONSTRAINT `FK_192C84E812469DE2` FOREIGN KEY (`category_id`) REFERENCES `media_categories` (`id`),
  CONSTRAINT `FK_192C84E8514956FD` FOREIGN KEY (`collection_id`) REFERENCES `media_collections` (`id`),
  CONSTRAINT `FK_192C84E85CC5DB90` FOREIGN KEY (`storage_id`) REFERENCES `media_storages` (`id`),
  CONSTRAINT `FK_192C84E8A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_files`
--

LOCK TABLES `media_files` WRITE;
/*!40000 ALTER TABLE `media_files` DISABLE KEYS */;
INSERT INTO `media_files` VALUES (1,1,NULL,1,0,'/2014/02/16','00_52_bbc4f846f6.jpeg','samsung-np900x3c-a02ru-1.jpg','image','image/jpeg',131476,131476,'2014-02-16 00:52:17',NULL,NULL),(3,1,NULL,1,0,'/2014/02/17','01_19_53bd2543df.jpeg','154655_0.1362072510.jpg','image','image/jpeg',29693,29693,'2014-02-17 01:19:23',NULL,NULL),(4,1,NULL,1,0,'/2014/02/17','01_41_ec3e194bc1.jpeg','pic_18468_1.jpg','image','image/jpeg',364655,364655,'2014-02-17 01:41:47',NULL,NULL),(5,1,NULL,1,0,'/2014/02/17','22_11_083fd66af8.jpeg','EOS_650D.jpg','image','image/jpeg',1695916,1695916,'2014-02-17 22:11:20',NULL,NULL),(6,1,NULL,1,1,'/2014/03/06','16_38_725b3ca498.jpg','3.jpg','image','image/jpeg',897389,897389,'2014-03-06 16:38:36',NULL,NULL),(7,2,NULL,1,1,'/2014/07/15','23_49_f6f9679959.jpg','patanjali.jpg','image','image/jpeg',18412,18412,'2014-07-15 23:49:51',NULL,NULL),(8,2,NULL,1,1,'/2014/07/15','23_54_519f730985.jpg','Code Complete.jpg','image','image/jpeg',45971,45971,'2014-07-15 23:54:05',NULL,NULL);
/*!40000 ALTER TABLE `media_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_files_transformed`
--

DROP TABLE IF EXISTS `media_files_transformed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_files_transformed` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(11) unsigned NOT NULL,
  `collection_id` int(11) unsigned NOT NULL,
  `storage_id` int(11) unsigned NOT NULL,
  `filter` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1084B87D7FC45F1D93CB796C` (`filter`,`file_id`),
  KEY `IDX_1084B87D93CB796C` (`file_id`),
  KEY `IDX_1084B87D514956FD` (`collection_id`),
  KEY `IDX_1084B87D5CC5DB90` (`storage_id`),
  CONSTRAINT `FK_1084B87D514956FD` FOREIGN KEY (`collection_id`) REFERENCES `media_collections` (`id`),
  CONSTRAINT `FK_1084B87D5CC5DB90` FOREIGN KEY (`storage_id`) REFERENCES `media_storages` (`id`),
  CONSTRAINT `FK_1084B87D93CB796C` FOREIGN KEY (`file_id`) REFERENCES `media_files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_files_transformed`
--

LOCK TABLES `media_files_transformed` WRITE;
/*!40000 ALTER TABLE `media_files_transformed` DISABLE KEYS */;
INSERT INTO `media_files_transformed` VALUES (1,1,1,1,'300_300',28215,'2015-07-04 17:58:40'),(2,3,1,1,'300_300',34430,'2015-07-04 17:58:40'),(3,5,1,1,'300_300',39337,'2015-07-04 18:41:54'),(4,6,1,1,'300_300',46972,'2015-07-04 18:41:55'),(5,4,1,1,'300_300',35813,'2015-07-04 18:42:01'),(6,8,2,1,'200_200',27695,'2015-07-04 18:42:49'),(7,6,1,1,'100_100',8506,'2015-07-11 15:09:58'),(8,5,1,1,'100_100',6411,'2015-10-09 00:39:32'),(9,3,1,1,'100_100',6289,'2015-10-09 01:52:42'),(10,7,2,1,'200_200',21122,'2015-10-13 03:21:05'),(11,4,1,1,'100_100',6138,'2015-10-13 04:35:45'),(12,1,1,1,'100_100',4622,'2015-12-08 18:56:46');
/*!40000 ALTER TABLE `media_files_transformed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_storages`
--

DROP TABLE IF EXISTS `media_storages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_storages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `relative_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_storages`
--

LOCK TABLES `media_storages` WRITE;
/*!40000 ALTER TABLE `media_storages` DISABLE KEYS */;
INSERT INTO `media_storages` VALUES (1,'SmartCore\\Bundle\\MediaBundle\\Provider\\LocalProvider','Локальное хранилище','/_media','N;','2014-02-14 13:41:32');
/*!40000 ALTER TABLE `media_storages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) unsigned DEFAULT NULL,
  `folder_id` int(10) unsigned DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `position` smallint(6) DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `pid` int(11) unsigned DEFAULT NULL,
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `open_in_new_window` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_70B2CA2A5550C4ED` (`pid`),
  KEY `IDX_70B2CA2ACCD7E912` (`menu_id`),
  KEY `IDX_70B2CA2A162CB942` (`folder_id`),
  KEY `IDX_70B2CA2AA76ED395` (`user_id`),
  CONSTRAINT `FK_70B2CA2A162CB942` FOREIGN KEY (`folder_id`) REFERENCES `engine_folders` (`id`),
  CONSTRAINT `FK_70B2CA2A5550C4ED` FOREIGN KEY (`pid`) REFERENCES `menu_items` (`id`),
  CONSTRAINT `FK_70B2CA2AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_70B2CA2ACCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,1,1,0,NULL,'Перейти на главную страницу',NULL,1,'2013-05-06 05:25:48','2016-09-23 20:26:56',NULL,'a:2:{s:11:\"description\";N;s:5:\"email\";N;}',0),(2,1,2,1,10,NULL,'123 561',NULL,1,'2013-05-06 05:48:06','2015-03-02 03:37:35',NULL,NULL,0),(3,1,3,1,999,NULL,NULL,NULL,1,'2013-05-06 07:28:54','2013-12-22 08:49:04',NULL,NULL,0),(5,1,6,1,0,NULL,NULL,NULL,1,'2013-05-06 08:45:04',NULL,2,NULL,0),(6,1,5,1,2,NULL,NULL,NULL,1,'2013-05-06 09:38:51','2014-01-21 15:52:24',NULL,NULL,0),(7,1,16,1,2,NULL,NULL,NULL,1,'2013-08-10 11:14:29','2015-03-02 03:36:03',NULL,NULL,0),(8,1,8,1,1,NULL,NULL,NULL,1,'2013-12-22 21:45:59','2014-01-21 15:52:18',NULL,NULL,0),(10,1,11,1,0,NULL,NULL,NULL,1,'2014-01-29 10:31:12','2016-09-23 16:32:15',5,'N;',0),(11,1,12,1,3,NULL,NULL,NULL,1,'2014-01-30 20:42:06','2015-03-02 03:37:08',NULL,'N;',0),(12,1,13,1,2,NULL,NULL,NULL,1,'2014-02-07 18:02:12','2014-02-07 18:02:22',NULL,'N;',0),(13,1,14,1,0,NULL,NULL,NULL,1,'2014-02-10 07:56:17',NULL,11,'N;',0),(14,1,4,1,0,NULL,NULL,NULL,1,'2014-02-10 11:28:48',NULL,8,'N;',0),(15,1,15,1,5,NULL,NULL,NULL,1,'2014-02-12 16:12:41','2014-02-12 16:12:51',NULL,'N;',0),(17,1,17,1,2,NULL,NULL,NULL,1,'2014-07-15 03:28:34',NULL,NULL,'N;',0),(18,2,7,1,0,NULL,NULL,NULL,1,'2015-02-15 21:41:46',NULL,NULL,'N;',0),(19,1,NULL,1,255,'Ссылка на яндекс','Откроется в новом окне','http://ya.ru',1,'2015-02-15 23:07:04','2015-02-21 01:29:55',NULL,'N;',1),(20,1,18,1,4,NULL,NULL,NULL,1,'2015-03-24 03:17:53',NULL,NULL,'N;',0),(21,1,20,1,0,NULL,NULL,NULL,1,'2015-10-14 17:28:58',NULL,3,'N;',0),(22,1,19,1,0,NULL,NULL,NULL,1,'2015-10-14 20:54:15',NULL,3,'N;',0);
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` smallint(6) DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `properties` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_727508CF5E237E06` (`name`),
  KEY `IDX_727508CFA76ED395` (`user_id`),
  CONSTRAINT `FK_727508CFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,0,'Главное',NULL,1,'2013-05-06 03:54:13','example_description: textarea\r\nexample_email: Symfony\\Component\\Form\\Extension\\Core\\Type\\EmailType'),(2,0,'Второе',NULL,1,'2015-02-15 20:24:38',NULL);
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `data` longblob NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bundle` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `is_serialized` tinyint(1) NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E545A0C5A57B32FD5E237E06` (`bundle`,`name`),
  KEY `IDX_E545A0C564C19C1` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'cms','site_full_name','Smart Core CMS (based on Symfony2 Framework)',0,NULL,'0000-00-00 00:00:00',NULL),(2,'cms','site_short_name','Smart Core CMS',0,NULL,'0000-00-00 00:00:00',NULL),(3,'cms','html_title_delimiter','&ndash;',0,NULL,'0000-00-00 00:00:00','2016-10-12 06:23:49'),(4,'cms','appearance_editor_theme','idle_fingers',0,NULL,'0000-00-00 00:00:00',NULL),(5,'cms','appearance_editor','ace',0,NULL,'0000-00-00 00:00:00','2016-10-12 06:22:54'),(8,'cms','twitter_bootstrap_version','3',0,NULL,'0000-00-00 00:00:00',NULL),(10,'shopmodule','catalog','catalog',0,'default','2016-10-12 06:03:56',NULL),(11,'cms','timezone','Asia/Novosibirsk',0,'default','2016-10-12 06:37:52','2016-10-11 20:12:29'),(12,'cms','languages','a:2:{i:0;s:2:\"ru\";i:1;s:2:\"en\";}',1,'default','2016-12-28 03:00:56','2016-12-28 03:03:20');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings_history`
--

DROP TABLE IF EXISTS `settings_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting_id` int(10) unsigned NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `is_personal` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_99BED87BEE35BD72` (`setting_id`),
  KEY `IDX_99BED87BA76ED395` (`user_id`),
  KEY `IDX_99BED87B64231B80` (`is_personal`),
  CONSTRAINT `FK_99BED87BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_99BED87BEE35BD72` FOREIGN KEY (`setting_id`) REFERENCES `settings` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings_history`
--

LOCK TABLES `settings_history` WRITE;
/*!40000 ALTER TABLE `settings_history` DISABLE KEYS */;
INSERT INTO `settings_history` VALUES (1,12,1,'a:2:{i:0;s:2:\"ru\";i:1;s:2:\"en\";}','2016-12-28 03:01:12',0),(2,12,1,'a:0:{}','2016-12-28 03:01:27',0),(3,12,1,'a:1:{i:0;s:2:\"ru\";}','2016-12-28 03:03:09',0),(4,12,1,'a:2:{i:0;s:2:\"ru\";i:1;s:2:\"en\";}','2016-12-28 03:03:20',0),(5,12,1,'a:2:{i:1;s:2:\"en\";i:0;s:2:\"ru\";}','2016-12-28 03:12:09',1),(6,12,1,'a:0:{}','2016-12-28 03:12:24',1),(7,12,1,'a:2:{i:0;s:2:\"ru\";i:1;s:2:\"en\";}','2016-12-28 03:12:30',1);
/*!40000 ALTER TABLE `settings_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings_personal`
--

DROP TABLE IF EXISTS `settings_personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings_personal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting_id` int(10) unsigned NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D7ED5980EE35BD72A76ED395` (`setting_id`,`user_id`),
  KEY `IDX_D7ED5980EE35BD72` (`setting_id`),
  KEY `IDX_D7ED5980A76ED395` (`user_id`),
  CONSTRAINT `FK_D7ED5980A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_D7ED5980EE35BD72` FOREIGN KEY (`setting_id`) REFERENCES `settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings_personal`
--

LOCK TABLES `settings_personal` WRITE;
/*!40000 ALTER TABLE `settings_personal` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings_personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_orders`
--

DROP TABLE IF EXISTS `shop_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `payment_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shipping_id` int(10) unsigned DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_608DDB6C8B8E8428` (`created_at`),
  KEY `IDX_608DDB6C5E38FE8A` (`payment_status`),
  KEY `IDX_608DDB6C505AA053` (`shipping_status`),
  KEY `IDX_608DDB6CA76ED395` (`user_id`),
  KEY `IDX_608DDB6C4887F3F8` (`shipping_id`),
  KEY `IDX_608DDB6C444F97DD` (`phone`),
  KEY `IDX_608DDB6C8B1DA00E` (`payment_date`),
  CONSTRAINT `FK_608DDB6C4887F3F8` FOREIGN KEY (`shipping_id`) REFERENCES `shop_shippings` (`id`),
  CONSTRAINT `FK_608DDB6CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_orders`
--

LOCK TABLES `shop_orders` WRITE;
/*!40000 ALTER TABLE `shop_orders` DISABLE KEYS */;
INSERT INTO `shop_orders` VALUES (5,0,5,NULL,NULL,'2015-10-13 04:10:18',NULL,'2015-10-13 04:10:18',1,NULL,NULL,NULL,NULL,NULL,'',NULL),(6,0,5,NULL,NULL,'2015-10-13 04:12:09',NULL,'2015-10-13 04:12:09',1,NULL,NULL,NULL,NULL,NULL,'',NULL),(7,40000,5,NULL,NULL,'2015-10-13 04:20:17',NULL,'2015-10-13 04:20:17',1,NULL,NULL,NULL,NULL,NULL,'',NULL),(8,0,5,NULL,NULL,'2015-10-13 04:23:38',NULL,'2015-10-13 04:23:38',1,NULL,NULL,NULL,NULL,NULL,'',NULL),(9,20000,5,NULL,NULL,'2015-10-13 04:27:23',NULL,'2015-10-13 04:27:23',1,NULL,NULL,NULL,NULL,NULL,'',NULL),(10,25000,5,NULL,NULL,'2015-10-13 04:35:03',NULL,'2015-10-13 04:35:03',1,NULL,NULL,NULL,NULL,NULL,'',NULL),(11,39000,1,NULL,NULL,'2015-10-13 04:35:34',NULL,'2015-10-13 04:35:34',1,2,'5555555555','+7-923-123-12-34',NULL,'artem@mail.ru','Piotr','123'),(13,20000,5,NULL,NULL,'2015-10-14 16:34:22',NULL,'2015-10-14 16:34:22',1,NULL,NULL,NULL,NULL,NULL,'root',NULL),(14,25000,0,NULL,NULL,'2015-10-14 16:35:51',NULL,'2015-10-14 16:35:51',1,NULL,NULL,NULL,NULL,NULL,'root',NULL),(15,25000,0,NULL,NULL,'2015-10-14 21:13:24',NULL,'2015-10-14 21:13:24',3,NULL,NULL,NULL,NULL,NULL,'aaa',NULL);
/*!40000 ALTER TABLE `shop_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_orders_items`
--

DROP TABLE IF EXISTS `shop_orders_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_orders_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BDD1217E8D9F6D38` (`order_id`),
  KEY `IDX_BDD1217E8B8E8428` (`created_at`),
  KEY `IDX_BDD1217E126F525E` (`item_id`),
  KEY `IDX_BDD1217EA76ED395` (`user_id`),
  CONSTRAINT `FK_BDD1217E8D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `shop_orders` (`id`),
  CONSTRAINT `FK_BDD1217EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_orders_items`
--

LOCK TABLES `shop_orders_items` WRITE;
/*!40000 ALTER TABLE `shop_orders_items` DISABLE KEYS */;
INSERT INTO `shop_orders_items` VALUES (10,11,5,20000,1,20000,'2015-10-13 04:35:34',1,'HTC One'),(11,11,2,19000,1,19000,'2015-10-13 04:35:42',1,'Canon 650D'),(13,14,4,25000,1,25000,'2015-10-14 16:35:51',1,'Samsung Galaxy S4'),(14,15,4,25000,1,25000,'2015-10-14 21:13:24',3,'Canon 650D'),(15,14,3,0,2,0,'2015-10-14 22:40:29',1,'Seagate 500Gb');
/*!40000 ALTER TABLE `shop_orders_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_shippings`
--

DROP TABLE IF EXISTS `shop_shippings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_shippings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_enabled` tinyint(1) DEFAULT '1',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_3E97B946A76ED395` (`user_id`),
  KEY `IDX_3E97B9468B8E8428` (`created_at`),
  CONSTRAINT `FK_3E97B946A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_shippings`
--

LOCK TABLES `shop_shippings` WRITE;
/*!40000 ALTER TABLE `shop_shippings` DISABLE KEYS */;
INSERT INTO `shop_shippings` VALUES (1,1,'2015-10-12 19:05:06',1,'Наложенный платеж почтой',NULL),(2,1,'2015-10-12 19:05:38',1,'Доставка по Москве курьером',NULL),(3,1,'2015-10-12 19:06:04',1,'Бесплатная доставка от 6000т.р.',NULL);
/*!40000 ALTER TABLE `shop_shippings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simple_news`
--

DROP TABLE IF EXISTS `simple_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `simple_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `annotation` longtext COLLATE utf8_unicode_ci,
  `text` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `end_publish_date` datetime DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT '1',
  `image_id` bigint(20) DEFAULT NULL,
  `annotation_widget` longtext COLLATE utf8_unicode_ci,
  `publish_date` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `instance_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B232FBE9989D9B62` (`slug`),
  KEY `IDX_B232FBE93A51721D` (`instance_id`),
  KEY `IDX_B232FBE946C53D4C` (`is_enabled`),
  KEY `IDX_B232FBE98B8E8428` (`created_at`),
  KEY `IDX_B232FBE978B553BA` (`publish_date`),
  KEY `IDX_B232FBE9B80531F1` (`end_publish_date`),
  CONSTRAINT `FK_B232FBE93A51721D` FOREIGN KEY (`instance_id`) REFERENCES `simple_news_instances` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simple_news`
--

LOCK TABLES `simple_news` WRITE;
/*!40000 ALTER TABLE `simple_news` DISABLE KEYS */;
INSERT INTO `simple_news` VALUES (1,'Первая','first','Анонс первой новости.','Тема: &laquo;Сублимированный рейтинг в XXI веке&raquo; Взаимодействие корпорации и клиента амбивалентно. Агентская комиссия специфицирует мониторинг активности, используя опыт предыдущих кампаний. Ассортиментная политика предприятия развивает стратегический маркетинг, используя опыт предыдущих кампаний. Более того, взаимодействие корпорации и клиента искажает бренд, расширяя долю рынка.','2013-12-22 22:17:46',NULL,1,NULL,NULL,'2013-12-22 22:17:46',NULL,1),(2,'Вторая','second','Анонс второй новости.','Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.','2013-12-16 22:18:21',NULL,1,NULL,NULL,'2013-12-16 22:18:00','2014-07-06 00:18:40',1),(3,'PHP: Hypertext Preprocessor','php','Server-side HTML embedded scripting language. It provides web developers with a full suite of tools for building dynamic websites: native APIs to Apache and ...','<div class=\"blurb\">\r\n<p>PHP is a popular general-purpose scripting language that is especially suited to web development.</p>\r\n<p>Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.<br /><br /><acronym title=\"PHP: Hypertext Preprocessor\">PHP</acronym> (рекурсивный акроним словосочетания <em>PHP: Hypertext Preprocessor</em>) - это распространенный язык программирования общего назначения с открытым исходным кодом. PHP сконструирован специально для ведения Web-разработок и его код может внедряться непосредственно в HTML.</p>\r\n</div>','2014-01-20 02:33:25',NULL,1,NULL,NULL,'2014-01-20 02:33:00','2014-07-06 00:19:06',1),(4,'Symfony 2 for PHP developers – Part 1','symfony-2-for-php-developers-part-1','So, you heard a lot about this web framework called Symfony 2 and everyone is banging on about how fantastic it is, but you don&rsquo;t understand what the big deal is and now you&rsquo;re reading this..','<p>To be honest, my introduction to Symfony 2 wasn&rsquo;t entirely voluntarily, it was forced upon me but I decided to take it and run with it. However, it wasn&rsquo;t that easy. It took me quite a bit of time to start &ldquo;thinking in Symfony&rdquo; and to be honest, after a period of denial, I now &ldquo;get it&rdquo;. I now understand what the fuss is about and now I&rsquo;m writing this in the hope that it might help some other developer out there to get up and running with Symfony 2.</p>\r\n<p>This article is not going to run you through a tutorial. Instead we&rsquo;re going to talk about philosophy and most importantly about architecture. To &ldquo;get&rdquo; Symfony you need to &ldquo;think in Symfony&rdquo;. It&rsquo;s here where most newcomers to the framework start to get into trouble. Usually when you come from another framework to Symfony you&rsquo;re simply not in the right frame of mind, there are certain things you need to learn and more importantly, things you need to unlearn. I hope that these articles are going to help you gab the bridge between having experience with some other framework and getting started with Symfony 2. My aim for this article, and the articles that follow, is that it will be the glue you need between the future and the past.</p>\r\n<p>When I started with Symfony 2 I actually wasn&rsquo;t really interested in working with it. I had plenty of experience writing all sorts of things with PHP and other languages such as Java and C++ and I had used other PHP web frameworks in the past such as CodeIgniter and Kohana and plenty of WordPress to through in. MVC here. MVC there. MVC everywhere. An age old design pattern milked dry and over hyped. I got it. How can this Symfony thing be any different, right?</p>\r\n<p>So, I started to read about Symfony, working my way through the documentation and cookbook and what&rsquo;s not. Dependency Injection this. Dependency Injection that. Bla bla.. I used what I could and ignored the rest. I had to get stuff done and didn&rsquo;t have time for all this theoretical mumbo jumbo. So I build stuff and it worked. Actually, to be honest, it didn&rsquo;t work very well. The problem was obviously that this Symfony thing that was standing in my way, obstructing my goals. The truth is, Symfony was standing in the way. It was standing in the way because I wasn&rsquo;t using it the way it was supposed to be used. But I ploughed on&hellip;</p>\r\n<p>Coincidentally I was looking into the <a href=\"http://spring.io\" target=\"_blank\">Spring Framework</a>. I love Java, always have. I love the Java ecosystem, the JVM and everything that runs on it. The Spring Framework is very similar to Symfony in the way that at its core it&rsquo;s all about Dependency Injection. The funny thing was, I &ldquo;was&rdquo; interested in learning Spring Framework so it was very easy for me to digest everything about it. It&rsquo;s only after I worked with Spring Framework and got my head around that, that I had my eureka moment in regards to Symfony.</p>\r\n<p>It&rsquo;s important to understand that Symfony is more than just a web framework. Yes, it&rsquo;s also a set of components but with Symfony also comes a workflow and if you come from another PHP web framework then it&rsquo;s this workflow that is going to make the real difference for you. If you haven&rsquo;t worked with <a href=\"https://getcomposer.org\" target=\"_blank\">Composer</a> yet, then well, that&rsquo;s going to change the way you think about building your projects. To work with Symfony you don&rsquo;t &ldquo;have&rdquo; to use Composer but you&rsquo;d be a fool if you didn&rsquo;t.</p>\r\n<p>Composer allows you to manage your dependencies in a way that hasn&rsquo;t been available to PHP developers until now. OK, PECL and PEAR are cool but Composer is so much better. For one thing, the problem with PEAR libraries is that they are tied to your operating system, not your project. So e.g, if your production server has version 1 of a PEAR library but your development system has version 2 which isn&rsquo;t compatible then somehow you need to manage all this, manually. With Composer your dependencies become part of your project, not your operating system. But the best thing about Composer is that anyone who clones your project only has to run a &ldquo;composer install&rdquo; command to get all the dependencies they need to run the project. Try doing that with PEAR.</p>\r\n<p>So, here we are. Starting with Symfony 2. In the next article I&rsquo;m going delve into to the philosophy of Dependency Injection and how this works within the context of Symfony.</p>\r\n<p>&nbsp;</p>','2014-01-27 12:05:22',NULL,1,NULL,NULL,'2014-01-27 12:05:00','2014-07-06 00:18:57',1),(5,'Symfony 2 for PHP developers – Part 2','symfony-2-for-php-developers-part-2','Dependency Injection is at the heart of Symfony 2. To understand Symfony 2 you need to understand Dependency Injection.','<div class=\"entry-content\">\r\n<p>Dependency Injection is at the heart of Symfony 2. To understand Symfony 2 you need to understand Dependency Injection.</p>\r\n<p>Fortunately for us, the principle of Dependency Injection is very simple. Rather than hard coding instantiations of objects into our classes we&rsquo;ll pass &lsquo;m in, something like this:</p>\r\n<div class=\"wp_syntax\">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class=\"line_numbers\">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\n11\r\n12\r\n13\r\n14\r\n15\r\n16\r\n17\r\n18\r\n19\r\n20\r\n21\r\n22\r\n23\r\n24\r\n25\r\n26\r\n27\r\n28\r\n29\r\n30\r\n31\r\n32\r\n33\r\n34\r\n35\r\n36\r\n</pre>\r\n</td>\r\n<td class=\"code\">\r\n<pre class=\"php\" style=\"font-family: monospace;\"><span style=\"color: #000000; font-weight: bold;\">class</span> Logger <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> write<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #b1b100;\">echo</span><span style=\"color: #000088;\">$message</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n<span style=\"color: #000000; font-weight: bold;\">class</span> SmtpDriver <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">protected</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> setLogger<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">logger</span><span style=\"color: #339933;\">=</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span> send<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">logger</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">write</span><span style=\"color: #009900;\">(</span><span style=\"color: #0000ff;\">\"Sending SMTP email to <span style=\"color: #006699; font-weight: bold;\">{$to}</span>...\"</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n      <span style=\"color: #666666; font-style: italic;\">// Sending email through SMTP...</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n<span style=\"color: #000000; font-weight: bold;\">class</span> Mailer <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">protected</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">;</span><span style=\"color: #000000; font-weight: bold;\">protected</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> setLogger<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">logger</span><span style=\"color: #339933;\">=</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> setDriver<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">driver</span><span style=\"color: #339933;\">=</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> send<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">driver</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">send</span><span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>The above example demonstrates the principle of Dependency Injection. To use the above defined classes and make them into a program we do the following:</p>\r\n<div class=\"wp_syntax\">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class=\"line_numbers\">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n</pre>\r\n</td>\r\n<td class=\"code\">\r\n<pre class=\"php\" style=\"font-family: monospace;\"><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">=</span><span style=\"color: #000000; font-weight: bold;\">new</span> Logger<span style=\"color: #009900;\">(</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n<span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">=</span><span style=\"color: #000000; font-weight: bold;\">new</span> SmtpDriver<span style=\"color: #009900;\">(</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">setLogger</span><span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n<span style=\"color: #000088;\">$mailer</span><span style=\"color: #339933;\">=</span><span style=\"color: #000000; font-weight: bold;\">new</span> Mailer<span style=\"color: #009900;\">(</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #000088;\">$mailer</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">setLogger</span><span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #000088;\">$mailer</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">setDriver</span><span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #000088;\">$mailer</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">send</span><span style=\"color: #009900;\">(</span><span style=\"color: #0000ff;\">\"john@example.com\"</span><span style=\"color: #339933;\">,</span><span style=\"color: #0000ff;\">\"luke@example.com\"</span><span style=\"color: #339933;\">,</span><span style=\"color: #0000ff;\">\"Dependency Injection Test\"</span><span style=\"color: #339933;\">,</span><span style=\"color: #0000ff;\">\"A message...\"</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>If you&rsquo;re new to Dependency Injection then you might have to look at the above a few times but you will realize that the above example demonstrates a few very important principles.</p>\r\n<p>The first is that the Mailer class doesn&rsquo;t send the email but it uses a &ldquo;driver&rdquo; instead. In our case we have an SmtpDriver class which we &ldquo;inject&rdquo; into the Mailer class by calling the setDriver method on the $mailer instance. Now, this is very important because it means that the Mailer class doesn&rsquo;t have a &ldquo;dependency&rdquo; on the StmpDriver class, it does have a dependency on a driver of some sort but as we&rsquo;ll see soon, not the SmtpDriver class in particular. In fact, we could create a new class and call it MockDriver and pass that into the $mailer instance. This would be really handy during testing where we don&rsquo;t want to actually send out real emails every time we run our tests but maybe just want to log a message.</p>\r\n<p>Let&rsquo;s look at an example of this:</p>\r\n<div class=\"wp_syntax\">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class=\"line_numbers\">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\n11\r\n</pre>\r\n</td>\r\n<td class=\"code\">\r\n<pre class=\"php\" style=\"font-family: monospace;\"><span style=\"color: #000000; font-weight: bold;\">class</span> MockDriver <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">protected</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> setLogger<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">logger</span><span style=\"color: #339933;\">=</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span> send<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">logger</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">write</span><span style=\"color: #009900;\">(</span><span style=\"color: #0000ff;\">\"Sending mock email to <span style=\"color: #006699; font-weight: bold;\">{$to}</span>...\"</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>As you can see, the MockDriver is very similar to the SmtpDriver but there is one big difference and that is the &ldquo;send&rdquo; method only calls the logger and doesn&rsquo;t actually send the email.</p>\r\n<p>To use the MockDriver, our program would look like this:</p>\r\n<div class=\"wp_syntax\">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class=\"line_numbers\">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n</pre>\r\n</td>\r\n<td class=\"code\">\r\n<pre class=\"php\" style=\"font-family: monospace;\"><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">=</span><span style=\"color: #000000; font-weight: bold;\">new</span> Logger<span style=\"color: #009900;\">(</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n<span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">=</span><span style=\"color: #000000; font-weight: bold;\">new</span> MockDriver<span style=\"color: #009900;\">(</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">setLogger</span><span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n<span style=\"color: #000088;\">$mailer</span><span style=\"color: #339933;\">=</span><span style=\"color: #000000; font-weight: bold;\">new</span> Mailer<span style=\"color: #009900;\">(</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #000088;\">$mailer</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">setLogger</span><span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #000088;\">$mailer</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">setDriver</span><span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #000088;\">$mailer</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">send</span><span style=\"color: #009900;\">(</span><span style=\"color: #0000ff;\">\"john@example.com\"</span><span style=\"color: #339933;\">,</span><span style=\"color: #0000ff;\">\"luke@example.com\"</span><span style=\"color: #339933;\">,</span><span style=\"color: #0000ff;\">\"Dependency Injection Test\"</span><span style=\"color: #339933;\">,</span><span style=\"color: #0000ff;\">\"A message...\"</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>As you can see, the only difference in our program is the line:</p>\r\n<div class=\"wp_syntax\">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class=\"code\">\r\n<pre class=\"php\" style=\"font-family: monospace;\"><span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">=</span><span style=\"color: #000000; font-weight: bold;\">new</span> MockDriver<span style=\"color: #009900;\">(</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>Instead of:</p>\r\n<div class=\"wp_syntax\">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class=\"code\">\r\n<pre class=\"php\" style=\"font-family: monospace;\"><span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">=</span><span style=\"color: #000000; font-weight: bold;\">new</span> SmtpDriver<span style=\"color: #009900;\">(</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>this bring us to the second important principle; All the above means is that we&rsquo;re controlling the functionality of our program from the &ldquo;outside&rdquo; at the highest level rather than on the inside at the lowest level. In other words, we&rsquo;re using Inversion of Control.</p>\r\n<p>If your brain just exploded, don&rsquo;t worry. All will be fine.</p>\r\n<p>One thing the careful observer might have noticed in the above example is that all our classes are POPO&rsquo;s, i.e. Plain Old PHP Objects. There&rsquo;s no Symfony in any of the above and this is exactly what we want. In the first code snippet where we defined the Logger, the SmtpDriver and the Mailer classes, we did just that, we defined classes. We could say we defined the &ldquo;architecture&rdquo; of our program but not the actual program itself, i.e, on their own the classes don&rsquo;t do anything but when you &ldquo;wire them up&rdquo; it&rsquo;s where you create your program. It&rsquo;s this &ldquo;wiring&rdquo; that Symfony helps us with but we&rsquo;ll get to that later.</p>\r\n<p>There is one major problem with the code we have so far and that is that it&rsquo;s fragile. By this I mean, the driver classes need to have a &ldquo;send&rdquo; method so we like to enforce this. Another problem is the duplication of injecting the logger functionality. So let&rsquo;s revise these issues:</p>\r\n<div class=\"wp_syntax\">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class=\"line_numbers\">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\n11\r\n12\r\n13\r\n14\r\n15\r\n16\r\n17\r\n18\r\n19\r\n20\r\n21\r\n22\r\n23\r\n24\r\n25\r\n26\r\n27\r\n28\r\n29\r\n30\r\n31\r\n32\r\n33\r\n34\r\n35\r\n36\r\n37\r\n38\r\n39\r\n40\r\n41\r\n42\r\n43\r\n</pre>\r\n</td>\r\n<td class=\"code\">\r\n<pre class=\"php\" style=\"font-family: monospace;\"><span style=\"color: #000000; font-weight: bold;\">class</span> Logger <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> write<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #b1b100;\">echo</span><span style=\"color: #000088;\">$message</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n<span style=\"color: #000000; font-weight: bold;\">abstract</span><span style=\"color: #000000; font-weight: bold;\">class</span> AbstractBase <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">protected</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> setLogger<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">logger</span><span style=\"color: #339933;\">=</span><span style=\"color: #000088;\">$logger</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n<span style=\"color: #000000; font-weight: bold;\">interface</span> MailerDriverInterface <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> send<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n<span style=\"color: #000000; font-weight: bold;\">class</span> SmtpDriver <span style=\"color: #000000; font-weight: bold;\">extends</span> AbstractBase implements MailerDriverInterface <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">public</span> send<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">logger</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">write</span><span style=\"color: #009900;\">(</span><span style=\"color: #0000ff;\">\"Sending SMTP email to <span style=\"color: #006699; font-weight: bold;\">{$to}</span>...\"</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n      <span style=\"color: #666666; font-style: italic;\">// Sending email through SMTP...</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n<span style=\"color: #000000; font-weight: bold;\">class</span> MockDriver <span style=\"color: #000000; font-weight: bold;\">extends</span> AbstractBase implements MailerDriverInterface <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">public</span> send<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">logger</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">write</span><span style=\"color: #009900;\">(</span><span style=\"color: #0000ff;\">\"Sending mock email to <span style=\"color: #006699; font-weight: bold;\">{$to}</span>...\"</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n<span style=\"color: #000000; font-weight: bold;\">class</span> Mailer <span style=\"color: #000000; font-weight: bold;\">extends</span> AbstractBase <span style=\"color: #009900;\">{</span><span style=\"color: #000000; font-weight: bold;\">protected</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">;</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> setDriver<span style=\"color: #009900;\">(</span> MailerDriverInterface <span style=\"color: #000088;\">$driver</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">driver</span><span style=\"color: #339933;\">=</span><span style=\"color: #000088;\">$driver</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span>\r\n&nbsp;\r\n   <span style=\"color: #000000; font-weight: bold;\">public</span><span style=\"color: #000000; font-weight: bold;\">function</span> send<span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #009900;\">{</span><span style=\"color: #000088;\">$this</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">driver</span><span style=\"color: #339933;\">-&gt;</span><span style=\"color: #004000;\">send</span><span style=\"color: #009900;\">(</span><span style=\"color: #000088;\">$to</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$from</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$subject</span><span style=\"color: #339933;\">,</span><span style=\"color: #000088;\">$message</span><span style=\"color: #009900;\">)</span><span style=\"color: #339933;\">;</span><span style=\"color: #009900;\">}</span><span style=\"color: #009900;\">}</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>Our program is exactly the same as it was before. The only thing we&rsquo;ve changed is that we&rsquo;ve moved the functionality that was shared by all classes (the logger) to an abstract base class. The reason we&rsquo;re making this class abstract is because we don&rsquo;t want to allow for instances to be created of this class. As you might have noticed, we named our abstract base class AbstractBase. By doing this we communicate the &ldquo;intent&rdquo; of the class itself by saying it&rsquo;s a &ldquo;base&rdquo; class and that it&rsquo;s &ldquo;abstract&rdquo;.</p>\r\n<p>The second thing we did was to create an interface for the drivers that can be used on the Mailer class. In our case, the interface does nothing more than defining the &ldquo;send&rdquo; method but you can imagine that when your classes are more complex that an interface will force you to implement all the required functionality. An interface is therefore like a contract and it will create stability within your architecture. To illustrate this, the setDriver method of the Mailer class now has a typed parameter. I.e. the parameter passed to the setDriver method needs to implement the MailerDriverInterface.</p>\r\n<p>However, still no sign of Symfony. We&rsquo;re soon going to change that in the next article&hellip;</p>\r\n</div>','2014-01-27 12:12:31',NULL,1,NULL,NULL,'2014-01-27 12:12:00','2014-07-06 00:18:49',1),(6,'Unifying PHP','unifying-php','Over the last few weeks, there has been lots of talk about PHP community, packages, and tribalism. So, I thought I would offer a few thoughts on the topic. Currently, Laravel is the most eclectic full-stack PHP framework in existence. In other words, Laravel is the only full-stack PHP framework that actively eschews tribalism.','<p>Over the last few weeks, there has been lots of talk about PHP community, packages, and tribalism. So, I thought I would offer a few thoughts on the topic.&nbsp;Currently, Laravel is the most eclectic full-stack PHP framework in existence. In other words, Laravel is the <strong>only</strong> full-stack PHP framework that <strong>actively</strong> eschews tribalism.</p>\r\n<p>Laravel, in additions to using its own custom packages like Eloquent and Blade, utilizes a whopping 23 packages from the wider PHP community. Using the &ldquo;best of the best&rdquo; PHP packages allows for greater interaction between Laravel and the wider PHP community. But, perhaps more importantly to you, it helps you write amazing applications at breakneck speed.</p>\r\n<p>We don&rsquo;t want to just speak about community, we want to participate! It&rsquo;s been a blast to coordinate and talk with developers of so many awesome packages, and I&rsquo;m very thankful that Laravel has been made better by their efforts.</p>\r\n<p>So, in this post, I want to highlight just a few of the wonderful packages that make Laravel awesome.</p>\r\n<h2>A few highlights:</h2>\r\n<p><strong>Carbon:</strong> An expressive date library by Brian Nesbitt. This library is used to power Eloquent&rsquo;s date mutators. It makes working with dates in PHP easy and enjoyable.</p>\r\n<p><strong>Predis:</strong> A robust Redis client authored by Daniele Alessandri. Predis powers all of the Redis interaction Laravel offers, including the Redis cache, session, and queue drivers.</p>\r\n<p><strong>Phenstalk:</strong> Full-featured PHP client for the Beanstalkd queue. Powers the Laravel Beanstalkd queue driver.</p>\r\n<p><strong>SuperClosure:</strong> Written by Jeremy Lindblom, this powerful library allows you to serialize and unserialize PHP Closures. It is used each time you push an anonymous function onto a queue.</p>\r\n<p><strong>Whoops:</strong> Displays the pretty error pages and stack traces while Laravel is in development mode.</p>\r\n<p><strong>Monolog:</strong> The de-facto standard of PHP logging libraries. Used for all logging. Primarily written by Jordi Boggiano.</p>\r\n<p><strong>Boris:</strong> Really slick PHP REPL which powers the amazing &ldquo;tinker&rdquo; console command.</p>\r\n<p><strong>PasswordCompat:</strong> Powers the secure Bcrypt hashing that is used by default by Laravel. Forward compatible with PHP 5.5. Written by Anthony Ferrara.</p>\r\n<p><strong>Symfony HttpFoundation:</strong> Extremely robust HTTP abstraction. Well tested and proven in many large, real-world applications. One of the most important community packages we use.</p>\r\n<p><strong>Symfony Routing:</strong> This package powers the compilation of Laravel routes to regular expressions, a trickier task than you might think! This library handles a lot of edge cases very well!</p>\r\n<p><strong>Symfony HttpKernel:</strong> The HttpKernel provides HTTP exceptions, which are used to indicate 404 responses in Laravel. Also, and perhaps more importantly, this package contains the HttpKernelInterface which is used as the bottom-level abstraction for a Laravel application.</p>\r\n<p><strong>Symfony BrowserKit:</strong> All that slick functional testing that Laravel offers? Powered by Symfony BrowserKit!</p>\r\n<strong>StackPHP:</strong> This project outlines a structure for building reusable, framework agnostic middlewares at the HTTP layer. Utilized in Laravel 4.1+ for all cookie encryption, sessions, and more. Developed by two of my favorite and most respected PHP developers: Igor Wiedler and Beau Simensen','2014-01-27 12:34:21',NULL,1,NULL,NULL,'2014-01-27 12:34:00','2014-07-06 00:18:23',1),(7,'Почему мы предпочли Symfony 2 вместо Yii','pochemu-my-predpochitaem-symfony-2-vmesto-yii','Перевод статьи <a href=\"http://weavora.com/blog/2013/03/26/why-we-prefer-symfony-2-over-yii-framework/\" target=\"_blank\">Why We Prefer Symfony 2 Over Yii Framework</a>.','<div class=\"post-bodycopy cf\">\r\n<p>Перевод статьи <a href=\"http://weavora.com/blog/2013/03/26/why-we-prefer-symfony-2-over-yii-framework/\" target=\"_blank\">Why We Prefer Symfony 2 Over Yii Framework</a>.</p>\r\n<p>В этой статье я собираюсь рассказать вам историю, которая объясняет причину по которой наша команда предпочла Symfony 2 вместо Yii, который мы использовали продолжительное время и делали на нём наши лучшие приложения. Как это случилось и по каким причинам мы приняли наше решение о смене фреймворка?</p>\r\n<p>Вы, возможно, подумали, что у нас было большое совещание на котором мы решили, что нашим следующим фреймворком станет Symfony потому что бла бла бла. Я боюсь разочаровать вас. Реальная история гораздо более проще и очевидней.</p>\r\n<p>Изначально мы были вынуждены использовать Symfony 2, как одно из важных технические требований в проекте, который мы собирались делать. Мы бегло прошлись по документации Symfony 2, новым фичам и т.д. И нам казалось всё выглядит очень похожим. Тот же MVC концепт, только другой шаблонизатор, ORM и пр. Да, Symfony 2 имеет Dependency Injection Container, но это не меняет положения вещей в значительно мере. Более того, у нас есть несколько членов команды с превосходным опытом разработки проектов на Symfony 1.</p>\r\n<p>Мы знали, что миграция будет не простой и что мы будем вынуждены потратить много человеко-часов для поддержания нашей нормальной скорости разработки.&nbsp;Мы были открыты для нового опыта и нового вызова.</p>\r\n<p>У нашего проекта был совершенно сумасшедший дедлайн. В добавок к этому, мы использовали Symfony тем же старым путем, как мы делали это с Yii. Мы пытались сохранить те-же принципы, стиль, подход, которые прекрасно работали с Yii. И это была действительно плохая идея. Но этого не было столь плохо на первое время.</p>\r\n<p>Следующий проект был также на Symfony. В нём мы пытались исправить все недоразумения и тщательно следовать философии Symfony. В основном, мы начали делать вещи на основе symfony философии и это исправило большинство архитектурных проблем. Там мы впервые встретились с composer, PSR-2 и другими клёвыми инструментами. В итоге мы стали ближе к сообществу Symfony.</p>\r\n<p>Я думаю, только в процессе создания нашего 3D проекта мы почувствовали успех и удовлетворение от нашей новой &ldquo;авантюры&rdquo;. С тех пор мы перестали рассматривать Yii.</p>\r\n<p>Сейчас я попытаюсь объяснить более детально почему мы решили остановиться на Symfony? Что нам особо&nbsp;понравилось?</p>\r\n<p>Немного забегая вперед, я бы хотел поделиться результатами нашего внутреннего голосования. Мы приняли решение опросить каждого члена команды по порядку, чтобы быть уверенным, что мы на верном пути и хочет ли кто-то вернуться обратно на Yii. Я был очень удивлен, что никто не проголосовал за Yii. C самого начала некоторые члены команды были очень скептично настроены, часто критиковали, говоря, что symfony &mdash; корпоративный фреймворк, Yii &mdash; проще в разработке. Но, в итоге, они позволили проникнуть концепции Symfony в их душу и теперь нет пути обратно. Они сделали несколько попыток в личных небольших проектах, но затем окончательно сдались.</p>\r\n<p><strong>Итак, почему мы перешли на Symfony 2?</strong></p>\r\n<p>В действительности &mdash; это всё об управлении и сопровождении кода. Выполнять код быстро с самого начала &mdash; это реально просто. Мы никогда не имели проблем с этим и, я уверен, вы тоже. Реальные проблемы появились позднее, когда проект рос и становился долгосрочным. Почти все наши проекты долгосрочные (от 2-3 месяцев до года и более).</p>\r\n<p>Это причина по которой&nbsp;для нас было критично использовать подходы и техники для эффективного управления кодом на долгий срок. Мы имели длительные проекты на Yii и было сложно сохранять их в хорошем состоянии после 3-ёх месяцев разработки. У нас всегда были небольшие проблемы, мы были вынуждены применять мелкие хаки, хуки и т.д. Конечно это работало, но было неприятно это поддерживать.</p>\r\n<p>Мы хотели сохранить наш код хорошо-организованным и следовать некоторым понятным принципам, которые будут беспокоиться о коде и сохранять его от повреждений. Это был наш основной концепт. И не важно, как долго длился проект, один месяц или один год. Мы хотели быть счастливы с нашим кодом в любое время и сохранять интерес к разработке. В коде не должно быть хаков. Честно, хаки убивают команду проекта. Кому нравится грязное белье? Это обычно начинается с этого: итак, сегодня я сделал&nbsp;по-быстрому небольшой хак и завтра я найду и заменю это более лучшим решением. Запомните &mdash; это путь в пропасть.</p>\r\n<p>Какая проверка кода была у нас в Yii?</p>\r\n<p><strong>TDD (разработка через тестирование)</strong></p>\r\n<p>Первый вопрос &mdash; тестирование кода. Тесты должны писаться легко. Никто не будет следовать TDD если один простой тест требует mock для половины приложения. Это большая головная боль. И Yii не&nbsp;достаточно помогал в этом вопросе.</p>\r\n<p>Глобальный объект Yii::app() только убивал попытки написать тесты. Вы начинали с одного теста, затем понимали что вам нужно создать mock для этого сервиса и ещё другого и оба они зависят от третьего&hellip; бах! Большинство сервисов в Yii пересекаются друг с другом. Это хреново.</p>\r\n<p>У нас была тесная связь между компонентами. Было сложно раскладывать проект на составляющие. В идеале нам не следует делать этого вовсе, они изначально должны быть отделены друг от друга.</p>\r\n<p>Реально сложно следовать TDD в Yii. Да, он имеет CWebTestCase, fixtures, базовую интеграцию с phpUnit. Но это простые вещи. Гораздо более важно &mdash; тестировать сервисы/модели без моков других сервисов, без моков других классов фреймворка.</p>\r\n<p><strong>ORM/ActiveRecord</strong></p>\r\n<p>Иметь ActiveRecords как часть ядра фреймворка &mdash; это круто. Это реально удобно для новичнов. Но AR в Yii очень упрощен и, опять же, очень сильно связан. Я знаю, что когда мы говорим об AR, мы будем, скорее всего, иметь тесную связь, потому что сущности должны быть соединены по порядку для сохранения чего-либо без прямой передачи этого. Но это не причина, чтобы распространять эту идею везде.</p>\r\n<p>Более серьезный вопрос: почему AR в Yii не покрывает наши потребности &mdash; это из-за отсутствия разделения между сущностью и запросом. В Yii мы вынуждены использовать статические методы для запросов и нестатические методы для логики моделей. В Yii ActiveRecord и ActiveFinder представлены одним экземпляром. Это не круто, когда запросы перемешиваются с сущностью (getter/setter).</p>\r\n<p>Другой момент относится к статическим методам для запросов. Статические методы не могут иметь другого состояния, кроме статического. И если вы хотите перемешать несколько условий вы вынуждены объединить критерии. В Propel мне нравится делать такое: $query-&gt;filterByThis(1)-&gt;filterByThat(2)-&gt;find() и сохранять мои фильтры/критерии отдельно. В Yii вы вынуждены определить их массивами (вы не можете создавать условия динамически) и затем объединить массив или критерии. Довольно скучная работа. Также у меня не может быть несколько классов для запросов, расширяющих базовый, где я буду разделять фильтры/условия базирующиеся на бизнес логике.</p>\r\n<p>Нам нужны более серьезные вещи. В Symfony 2 у нас есть Doctrine 2. Достаточно серьезная ORM. И это было достаточно важно для нас. Но в итоге мы прилипли к Propel ORM.<br /> Я знаю, там есть также некоторые проблемы и сгенерированный код далёк от лучших стандартов кодирования. Но это работает для нас лучшим образом и мы точно можем разделить сущности и запросы. Главные вещи которые мы любим в propel &mdash; реальный getter/setter (мы можем посмотреть как они реализуются и переопределить их в случае необходимости), схемы базы данных и генерация миграция, поведений, &nbsp;интеграция в некоторые компоненты Symfony, такие как формы и валидаторы.</p>\r\n<p><strong>Стиль кодирования</strong></p>\r\n<p>Я знаю, команда Yii следует их личному стилю написания кода и это нормально. Но внутренне мы разные и в этом заключалась проблема.</p>\r\n<p>На самом деле, ранее у нас не было документации об основных принципах кодирования. Мы сделали несколько попыток написать их, но это было очень скучно. Имена классов, отступы и прочее. Это требовало много времени. У нас в тайне были некоторые общие стандарты, но это не было задокументировано.</p>\r\n<p>Проблема была в том, что наш стиль написания кода несколько отличался от того, который использует команда Yii. Мы сделали вклад в развитие Yii сообщества, разработав несколько дополнений и, конечно, мы хотели сохранить наши расширения похожими на родной Yii код. В итоге, мы&nbsp;были вынуждены переключаться между разными стилями кодирования всё время. Для расширений Yii мы использовали принципы кодирования Yii команды, для реальных проектов мы использовали свой собственный. Не круто.</p>\r\n<p>Мы хотели использовать что-то глобальное. В нашем коде, в компонентах, в фреймворке.</p>\r\n<p>Теперь мы следуем PSR-2. Мы были близки к этому и миграция была супер легкой. Symfony 2 следует PSR-2. Большинство компонентов следует этому также. Это работает для нас и мы, на самом деле, ценим php-fig. Безусловно есть много полемики о PSR, но большинство из неё о PSR-3. PSR-2 достаточно хорош. По крайней мере для нас.</p>\r\n<p>Ещё одна вещь &mdash; пространства имен (namespaces). Как вы знаете, Yii не использует пространства имен. И это не помогает в построении приложения. Пространства имен помогают укоротить имена классов, помогают с автозагрузкой классов и т.д. В общем, мы ощущаем себя более комфортно с namespaces, чем без них.</p>\r\n<p><strong>Расширения</strong></p>\r\n<p>Давайте посмотрим как мы получали расширение в Yii. Первое, нам необходимо найти его на сайте Yii, скачать, скопировать вручную в директорию проекта, прикрепить в конфиге. Затем нам необходимо наблюдать за сайтом Yii для поиска обновления. Это хорошо, но не в 2013 году.</p>\r\n<p>Прямо сейчас мы используем composer. Это великолепно, когда вы можете просто объявить зависимость в проекте и запустить &laquo;update&raquo;. Он скачает расширение/библиотеку/компонент/бандл или всё что вы хотите, установит и добавить в автозагрузку и вы может использовать это. Также composer будет беспокоиться обо всех зависимостях компонентов и скачивать их, если это необходимо. Одной командой вы можете обновить все компоненты до наиболее актуальной версии. Вы можете указать специфичную версию. Возможно, вы хотите скачать test или dev версии. Это тоже просто.</p>\r\n<p>Это чрезвычайно легко опубликовать ваш пакет и сделать его доступным отовсюду. Проще определять версии, проще сделать fork расширения, легче посылать запросы на внесение изменений и так далее.</p>\r\n<p>Composer является чрезвычайно полезным инструментом. Это именно то, что необходимо php сообществу.</p>\r\n<p>Мы используем composer для управления нашими зависимостями. Мы не держим внешних зависимостей в нашем репозитории вовсе. Всё что нам нужно &mdash; только лишь сохранять composer.json в актуальном состоянии.</p>\r\n<p><strong>Архитектура</strong></p>\r\n<p>Давайте вернемся к обслуживанию кода.Чаще всего, когда люди хотят сказать, что код должен быть чище, они говорят, что нуждаются в правильной архитектуре. Что это значит?</p>\r\n<p>Во-первых, это означает, что программное обеспечение должно быть основано на некоторых принципах и следовать хорошим практикам. Так что да, мы хотим, чтобы наше программное обеспечение было принципиальным. И Symfony помогает нам в этом. Архитектура Symfony 2 основана на тех же принципах.</p>\r\n<p><strong>Заключительные мысли &nbsp;</strong></p>\r\n<p>Я хочу, чтобы вы поняли меня правильно. Мы по-прежнему испытываем теплые чувства к Yii, продолжаем выражать уважение Yii команде, они сделали и делают большую работу. Это превосходно, и мы потратили несколько человеко-лет, создавая код с этим фреймворком, способствовали созданию ряда расширений и консультировали многих новичков на различных форумах. &nbsp;Но на данный момент, это просто не соответствует нашем потребностям. Мы, по-прежнему, следим за обновлениями в отношении Yii2, и ждалиYii2 так долго&hellip; Но мы должны двигаться. И следующая точка нашего путешествия &mdash; <strong>Symfony 2</strong>.</p>\r\n</div>','2014-01-27 12:42:25',NULL,1,NULL,NULL,'2014-01-27 12:42:00','2016-09-15 00:03:43',1);
/*!40000 ALTER TABLE `simple_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simple_news_instances`
--

DROP TABLE IF EXISTS `simple_news_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `simple_news_instances` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `use_image` tinyint(1) NOT NULL,
  `use_annotation_widget` tinyint(1) NOT NULL,
  `media_collection_id` int(11) unsigned DEFAULT NULL,
  `use_end_publish_date` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_98EDD0015E237E06` (`name`),
  KEY `IDX_98EDD001B52E685C` (`media_collection_id`),
  CONSTRAINT `FK_98EDD001B52E685C` FOREIGN KEY (`media_collection_id`) REFERENCES `media_collections` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simple_news_instances`
--

LOCK TABLES `simple_news_instances` WRITE;
/*!40000 ALTER TABLE `simple_news_instances` DISABLE KEYS */;
INSERT INTO `simple_news_instances` VALUES (1,'Default news','2014-07-06 01:02:11',0,0,1,0);
/*!40000 ALTER TABLE `simple_news_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sitemap_urls`
--

DROP TABLE IF EXISTS `sitemap_urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sitemap_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_visited` tinyint(1) NOT NULL,
  `loc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` longtext COLLATE utf8_unicode_ci,
  `lastmod` datetime DEFAULT NULL,
  `changefreq` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority` double DEFAULT NULL,
  `status` smallint(6) NOT NULL,
  `referer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_365093828852ACDC` (`loc`),
  KEY `IDX_365093829A62B8C7` (`title_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sitemap_urls`
--

LOCK TABLES `sitemap_urls` WRITE;
/*!40000 ALTER TABLE `sitemap_urls` DISABLE KEYS */;
INSERT INTO `sitemap_urls` VALUES (1,1,'/','0a2aa0300fe47dca62d6950491c24f2a','Smart Core CMS (based on Symfony2 Framework)',NULL,NULL,1,200,NULL),(2,1,'/news/','e2ab3b05f5973bbc83b861933d3f37e8','Новости – Smart Core CMS',NULL,NULL,NULL,200,'/'),(3,1,'/news/under_news/','04e573e356e9002b03c4b37dc784b6b6','Вложенная – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/'),(4,1,'/simple/','fefcea3b739646c56c0d7c3e5584f64b','Так просто ;) – Smart Core CMS',NULL,NULL,NULL,200,'/'),(5,1,'/unicat_blog/','7ca6f457676b1223a432511f172b9b2a','Блог на юникате – Smart Core CMS',NULL,NULL,NULL,200,'/'),(6,1,'/blog/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/'),(7,1,'/gallery/','68a2aae6226d43f5a67de295b78b6ea9','Фотогалерея – Smart Core CMS',NULL,NULL,NULL,200,'/'),(8,1,'/slider/','293e958c4f1d6247bbb65c3584583a42','Слайдер – Smart Core CMS',NULL,NULL,NULL,200,'/'),(9,1,'/slider/nivo/','fab53b920a6e11d94f1cab3160aa72a1','Nivo – Слайдер – Smart Core CMS',NULL,NULL,NULL,200,'/'),(10,1,'/web-form/','15c434487db4a4b95670bdd0bdf1179e','Веб-форма – Smart Core CMS',NULL,NULL,NULL,200,'/'),(11,1,'/catalog/','a194b83b6a419cbae911d6e1548111cf','Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/'),(12,1,'/about/','3cc3581b75e69971d5898de216bc59f1','О компании – Smart Core CMS',NULL,NULL,NULL,200,'/'),(13,1,'/about/inner/','a1f5e1a2cad0e109c9042c684b0f23f0','Вложенная папка – О компании – Smart Core CMS',NULL,NULL,NULL,200,'/'),(14,1,'/about/inner/in2/','13ddc00a572e0bb508c1e09426a3ff3c','Еще одна вложенная – Вложенная папка – О компании – Smart Core CMS',NULL,NULL,NULL,200,'/'),(15,1,'/news/pochemu-my-predpochitaem-symfony-2-vmesto-yii.html','f6cff4877b82714527a9e7da5ed18f5a','Почему мы предпочли Symfony 2 вместо Yii – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/'),(16,1,'/news/unifying-php.html','62400782ef80ec6cf3d3424d271fb93a','Unifying PHP – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/'),(17,1,'/news/symfony-2-for-php-developers-part-2.html','91d930d98a3f38d15fc6a49348a81598','Symfony 2 for PHP developers – Part 2 – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/'),(18,1,'/news/page_2/','541e73dce2ef55e7142f932676706c95','Страница: 2 – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/news/'),(19,1,'/news/page_3/','855bbc5a5a38faaae2fa78eec5355373','Страница: 3 – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/news/'),(20,1,'/unicat_blog/programing/','30c9def8c42da07c6660fbcf4d18e67f','Программирование – Блог на юникате – Smart Core CMS',NULL,NULL,NULL,200,'/unicat_blog/'),(21,1,'/unicat_blog/programing/php/','1f370aadde05f80e75a66c8e8876f036','PHP – Программирование – Блог на юникате – Smart Core CMS',NULL,NULL,NULL,200,'/unicat_blog/'),(22,1,'/unicat_blog/os/','a6585caa0a9bcfe69808a740c8a568f6','Операционные системы – Блог на юникате – Smart Core CMS',NULL,NULL,NULL,200,'/unicat_blog/'),(23,1,'/unicat_blog/imposition/','d3f59c2bf72acb239c234b062d8787fb','Верстка – Блог на юникате – Smart Core CMS',NULL,NULL,NULL,200,'/unicat_blog/'),(24,1,'/unicat_blog/soft/','f2550ea502b9c31081e48a136456fb81','Программы (софт) – Блог на юникате – Smart Core CMS',NULL,NULL,NULL,200,'/unicat_blog/'),(25,1,'/unicat_blog/other/','b2deb3e242d2efc50ee79a1e0854f296','Другое – Блог на юникате – Smart Core CMS',NULL,NULL,NULL,200,'/unicat_blog/'),(26,1,'/unicat_blog/programing/php/twig_in_symfony2_work_with_date_and_time.html','4bf028bda3c3baf097f99846a4218a58','Twig в Symfony2: работа с датой и временем. – PHP – Программирование – Блог на юникате – Smart Core CMS',NULL,NULL,NULL,200,'/unicat_blog/'),(27,1,'/blog/category/programing/','39cde24f4e400d20b79ded6208e3c11d','Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(28,1,'/blog/category/programing/php/','7f9fa2565cc26f6117075b9031a16f1b','PHP – Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(29,1,'/blog/category/programing/php/yii/','f89f73ad53fae2beacf3b29825a6453d','Yii – PHP – Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(30,1,'/blog/category/programing/php/symfony2/','56e3251508926ebc7efbacabaee03119','Symfony2 – PHP – Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(31,1,'/blog/category/programing/js/','448aa425cc8f7a893d398ab560b39f0e','JavaScript – Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(32,1,'/blog/category/programing/cpp/','a46fe16299e72c3c13436c1dc89c9082','C++ – Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(33,1,'/blog/category/os/','ace36d691698ef5f88c0ddab00235ae8','Операционные системы – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(34,1,'/blog/category/os/debian/','bd283c21dadf40ac3d3e1e36f651c656','Debian – Операционные системы – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(35,1,'/blog/category/imposition/','9e3c90bbb5059f5bc462d29f6becf86c','Верстка – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(36,1,'/blog/category/imposition/css/','7e657c4a05b4142b2b03159346fc626b','CSS – Верстка – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(37,1,'/blog/category/imposition/css/twitter_bootstrap/','1376c76a8ad74ae22bda25ee504a3e29','Twitter Bootstrap – CSS – Верстка – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(38,1,'/blog/category/soft/','52aa92df122319c2701b78b80c450a04','Программы (софт) – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(39,1,'/blog/category/other/','7e4971217113ea9a2428ebf1bd716a82','Другое – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(40,1,'/blog/tag/breadcrumbs/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(41,1,'/blog/tag/yii/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(42,1,'/blog/tag/ckeditor/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(43,1,'/blog/tag/connect/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(44,1,'/blog/tag/formatting/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(45,1,'/blog/tag/date_and_time/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(46,1,'/blog/tag/symfony2/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(47,1,'/blog/tag/commands/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(48,1,'/blog/tag/code_illumination/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(49,1,'/blog/tag/phpstorm/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(50,1,'/blog/tag/php/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(51,1,'/blog/tag/memcached/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(52,1,'/blog/tag/debian/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(53,1,'/blog/tag/css/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(54,1,'/blog/tag/linear_gradient/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(55,1,'/blog/tag/twitter_bootstrap/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(56,1,'/blog/tag/forms/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(57,1,'/blog/tag/visual_sStudio_2012_cpp/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(58,1,'/blog/tag/editor/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(59,1,'/blog/tag/encoding/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(60,1,'/blog/tag/framework/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(61,1,'/blog/tag/cms/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(62,1,'/blog/tag/select/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(63,1,'/blog/tag/twig/','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(64,1,'/blog/entity_change.html','2aaf2e0bfbb55ec1879f71c080b44758','Проверка на изменение сущности – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(65,1,'/blog/twig_in_symfony2_work_with_date_and_time.html','e098508f9e5cb8b8f352cbb9b4db8f56','Twig в Symfony2: работа с датой и временем. – Symfony2 – Программирование – PHP – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(66,1,'/blog/debain7_hot_commands_of_the_server.html','bcec90f5bd0e4889727cef0ad241e945','Debain7 – горячие команды сервера – Debian – Операционные системы – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(67,1,'/blog/installation_memcached_on_windows7_x64_php_5_4_17.html','deab6191abab52fd013913fe0cd88a0e','Установка Memcached на Windows 7 x64 (php 5.4.17) – PHP – Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(68,1,'/blog/adjustment_symfony2_in_phpstorm.html','334a07fabcf6ca6daff36644e36944ca','Настройка Symfony2 в PhpStorm – Symfony2 – Программирование – PHP – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(69,1,'/blog/fourth.html','f597fe03e281139e83f77ac9f6aa74cd','Ссылки на Symfony2 – Symfony2 – Программирование – PHP – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(70,1,'/blog/s2_sik_knd.html','f4c7597d15f821c58d329c7444dd45b5','Symfony2: справочник команд – Symfony2 – Программирование – PHP – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(71,1,'/blog/create_forms_in_visual_sStudio_2012.html','3686a1864f46f0d1bf54391a7122d8a2','Создаем формы в Visual Studio 2012 – C++ – Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(72,1,'/blog/connect_twitter_bootstrap_to_yii.html','7781301242fdf3b22469dc6b3a05b0f4','Подключаем Twitter Bootstrap к Yii – Twitter Bootstrap – Верстка – CSS – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(73,1,'/blog/highlight_code_on_site.html','355eacd785a720d4ae15ea0f2ea8cd33','Подсвечиваем код на сайте – JavaScript – Программирование – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(74,1,'/blog/?page=2','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/'),(75,1,'/gallery/1/','f8ee178f078928561f41ccfa6ca2b390','Первый альбом – Фотогалерея – Smart Core CMS',NULL,NULL,NULL,200,'/gallery/'),(76,1,'/catalog/connection/','09da5af03d72b05f74d48f04bd0a7e3f','Техника для связи – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(77,1,'/catalog/connection/signal_amplifiers/','78ef56c34573ec08abeab2063f61da5d','Усилители сигнала – Техника для связи – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(78,1,'/catalog/connection/smartphones/','b1daaea4ad4591020ef5f5fb6138c96a','Смартфоны – Техника для связи – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(79,1,'/catalog/pc/','b5071f47a307e8456ae3bd673c2a0d1e','Компьютерная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(80,1,'/catalog/pc/notebooks/','7fba6f84e63945a7a8499c65e1827e47','Ноутбуки – Компьютерная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(81,1,'/catalog/pc/notebooks_stuff/','301b6c69d64fb9f97148e1b999e0f606','Комплектующие для ноутбуков – Компьютерная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(82,1,'/catalog/pc/notebooks_stuff/ram/','4ff59dbf5571d1370bbc3ed181cc7589','Модули памяти – Комплектующие для ноутбуков – Компьютерная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(83,1,'/catalog/pc/notebooks_stuff/hdd25/','1d2d7e09d15fcbf4b0312be5937fd6b4','Жесткие диски 2.5 – Комплектующие для ноутбуков – Компьютерная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(84,1,'/catalog/pc/monitors/','7e352b56837ba134a36380f8b2751de3','Мониторы – Компьютерная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(85,1,'/catalog/office/','be1e2548947bd4882bea02549941c956','Офисная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(86,1,'/catalog/office/printers/','4f945c204f4dfb80a1cf773b7bc92694','Принтеры – Офисная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(87,1,'/catalog/office/scanners/','b41b7f6e2a35026d56afe142d4c7d617','Сканеры – Офисная техника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(88,1,'/catalog/portable/','c50de2638e7b264e4cede8345d025cbb','Портативная электроника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(89,1,'/catalog/portable/reflex_cameras/','0d818c62e1707b3d6b659a7d4009594e','Зеркальные фотоаппараты – Портативная электроника – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(90,1,'/catalog/htc-one.html','4d272905fd192666e6e64fd5de3251a3','HTC One – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(91,1,'/catalog/canon-650d.html','929bbfded00e4fe287ef6e8ab59aee69','Canon 650D – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(92,1,'/catalog/?page=2','a194b83b6a419cbae911d6e1548111cf','Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(93,1,'/catalog/?page=3','a194b83b6a419cbae911d6e1548111cf','Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/'),(94,1,'/news/symfony-2-for-php-developers-part-1.html','5cf74108f3e520074c221d02c2547f2b','Symfony 2 for PHP developers – Part 1 – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/news/page_2/'),(95,1,'/news/php.html','c2dd1c903b1c61ffc26d1a6f08ffe2bc','PHP: Hypertext Preprocessor – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/news/page_2/'),(96,1,'/news/first.html','09574f89eb57ec2f0efc2213e09b93bb','Первая – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/news/page_2/'),(97,1,'/news/page_1/','e2ab3b05f5973bbc83b861933d3f37e8','Новости – Smart Core CMS',NULL,NULL,NULL,200,'/news/page_2/'),(98,1,'/news/second.html','d4216545a1486cacb07a3728dbf4e716','Вторая – Новости – Smart Core CMS',NULL,NULL,NULL,200,'/news/page_3/'),(99,1,'/blog/formatting_of_date_and_time_in_yii.html','7d1954c7dad93a3dc5ecb2356244ad9a','Форматирование даты и времени в Yii – Yii – Программирование – PHP – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/category/programing/'),(100,1,'/blog/breadcrumbs_yii.html','6075633c703ea7d9afa6e1b653532723','Хлебные крошки в Yii – Yii – Программирование – PHP – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/category/programing/'),(101,1,'/blog/how_to_connect_ckeditor_to_framework_yii.html','716636706d44ed713253fff328fc472e','Как подключить Ckeditor к фреймворку Yii – Yii – Программирование – PHP – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/category/programing/'),(102,1,'/blog/css_linear_gradient_of_background.html','67b1d592af0c6b05419a568063394810','CSS – линейный градиент фона – CSS – Верстка – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/category/imposition/'),(103,1,'/blog/notepad_plus_plus.html','7f0a9ad5daca6cbb24a71a59491525b7','NotePad++ – Программы (софт) – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/category/soft/'),(104,1,'/blog/framework_vs_cms.html','de2dd1c4ba7cf4104751641eb31bc407','Что выбрать: фреймворк или CMS – Другое – Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/category/other/'),(105,1,'/blog/?page=1','839b13fcffa3d89a822d51694e295bfb','Блог – Smart Core CMS',NULL,NULL,NULL,200,'/blog/?page=2'),(106,1,'/catalog/galaxy-s4.html','616a576a136a20825e2d52101392117b','Samsung Galaxy S4 – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/connection/smartphones/'),(107,1,'/catalog/np900.html','f718d9a472c1dbb50b0f15a146cb68b7','Samsung NP900 – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/pc/notebooks/'),(108,1,'/catalog/seagate-500g.html','d165beb642490bf78abe74b73711452c','Seagate 500Gb – Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/pc/notebooks_stuff/hdd25/'),(109,1,'/catalog/?page=1','a194b83b6a419cbae911d6e1548111cf','Каталог – Smart Core CMS',NULL,NULL,NULL,200,'/catalog/?page=2');
/*!40000 ALTER TABLE `sitemap_urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `width` smallint(6) DEFAULT NULL,
  `height` smallint(6) DEFAULT NULL,
  `mode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `library` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slide_properties` longtext COLLATE utf8_unicode_ci,
  `pause_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,'Цветочки',748,300,'INSET','jcarousel',NULL,3000),(6,'Nivo',618,246,'INSET','nivoslider',NULL,3000);
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slides` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(1) DEFAULT '1',
  `file_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `original_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` smallint(6) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `slider_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B8C02091D7DF1668` (`file_name`),
  KEY `IDX_B8C020912CCC9638` (`slider_id`),
  KEY `IDX_B8C02091462CE4F5` (`position`),
  KEY `IDX_B8C02091A76ED395` (`user_id`),
  CONSTRAINT `FK_B8C020912CCC9638` FOREIGN KEY (`slider_id`) REFERENCES `sliders` (`id`),
  CONSTRAINT `FK_B8C02091A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slides`
--

LOCK TABLES `slides` WRITE;
/*!40000 ALTER TABLE `slides` DISABLE KEYS */;
INSERT INTO `slides` VALUES (10,1,'e711d4c91a7deebfb9a1eabf1b6d012d.jpeg','img1.jpg',NULL,0,'2014-02-09 21:53:37',1,'a:0:{}',1),(11,1,'0722a36552cca4701498791b82992b89.jpeg','img2.jpg','На фоне реки',0,'2014-02-09 21:53:42',1,'a:0:{}',1),(12,1,'1268a90a72e1c513ecfd59adf20ccf22.jpeg','img3.jpg','В поле',0,'2014-02-09 21:53:46',1,'a:0:{}',1),(13,1,'17a47fc3272be3e25835650bf8e245a0.jpeg','nemo.jpg','Из мультика про рыбку Nemo',0,'2014-02-10 08:07:48',1,'a:0:{}',6),(14,1,'99b6811cb4f117ddc66472036b36d73f.jpeg','toystory.jpg',NULL,0,'2014-02-10 08:07:52',1,'a:0:{}',6),(15,1,'67c92c3ce3b80e665c2ecb4fb1be1a83.jpeg','up.jpg',NULL,0,'2014-02-10 08:07:56',1,'a:0:{}',6),(16,1,'c38749ffadba47cb79ee06f7d10d158c.jpeg','walle.jpg','Wall-E',0,'2014-02-10 08:08:00',1,'a:0:{}',6);
/*!40000 ALTER TABLE `slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `texter`
--

DROP TABLE IF EXISTS `texter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `texter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) DEFAULT NULL,
  `text` longtext,
  `meta` longtext NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `editor` smallint(6) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2B51D144A76ED395` (`user_id`),
  CONSTRAINT `FK_2B51D144A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `texter`
--

LOCK TABLES `texter` WRITE;
/*!40000 ALTER TABLE `texter` DISABLE KEYS */;
INSERT INTO `texter` VALUES (1,'ru','Футер','a:0:{}','2012-08-27 03:16:57',1,1,NULL),(2,'ru','<h1>\r\n  Главная страница!\r\n</h1>\r\n<p>\r\n  С точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма,\r\n  концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией\r\n  всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих геотрансплантационных квазипузлистатов\r\n  всех кинетически коррелирующих аспектов. Исходя из этого, мы пришли к выводу, что каждый произвольно выбранный предикативно абсорбирующий объект.\r\n</p>\r','a:1:{s:8:\"keywords\";s:3:\"123\";}','2012-08-27 03:17:27',1,1,NULL),(3,'ru','<h2>Пример страницы с 2-мя колонками</h2>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>','a:1:{s:8:\"keywords\";s:3:\"sdf\";}','2012-08-27 03:51:05',1,1,NULL),(4,'ru','<p><img src=\"/uploads/images/bscap0001.jpg\" alt=\"\" width=\"300\" height=\"124\" /><br />Сервисная стратегия деятельно искажает продвигаемый медиаплан, опираясь на опыт западных коллег. Внутрифирменная реклама, согласно Ф.Котлеру, откровенно цинична. Торговая марка исключительно уравновешивает презентационный материал, полагаясь на инсайдерскую информацию. Наряду с этим, узнавание бренда вполне выполнимо. Организация слубы маркетинга, согласно Ф.Котлеру, усиливает фактор коммуникации, осознавая социальную ответственность бизнеса. Экспертиза выполненного проекта восстанавливает потребительский презентационный материал, полагаясь на инсайдерскую информацию.</p>','a:0:{}','2012-08-27 03:51:27',1,1,NULL),(5,'ru','Текстер №5','a:0:{}','2013-03-21 06:03:37',1,0,NULL),(6,'ru','Text under menu in <strong>User</strong> folder.\r','a:0:{}','2013-03-25 21:53:12',1,1,NULL),(7,'ru','sdf gsdfg dsf gsdf gdsfg sdf g\r','a:0:{}','2013-08-10 11:14:55',1,1,NULL),(8,'ru','<p>\r\n  Нельзя так просто взять и написать цмс-ку ;)<br />\r\n  <br />\r\n  <img style=\"width: 100%; height: auto;\" src=\"/uploads/images/bscap0001_big.jpg\" alt=\"\" width=\"1680\" height=\"693\" /><br />\r\n</p>\r','a:0:{}','2013-12-20 20:11:42',1,1,NULL),(9,'ru','Powered by <a href=\"http://symfony.com\" target=\"_blank\">Symfony2</a>\r','a:0:{}','2014-01-20 03:47:18',1,1,NULL),(10,'ru','Очень интересные новости ;)','a:0:{}','2014-01-22 19:02:28',1,1,NULL),(11,'ru','Для жаждущих с Сущностью Вечной слиянья<br />\r\nЕсть йога познанья и йога деянья,<br />\r\n<br />\r\nВ бездействии мы не обрящем блаженства;<br />\r\nКто дела не начал, тот чужд совершенства.<br />\r\n<br />\r\nОднако без действий никто не пребудет:<br />\r\nТы хочешь того иль не хочешь — принудит<br />\r\n<br />\r\nПрирода тебя: нет иного удела,<br />\r\nИ, ей повинуясь, ты делаешь дело.<br />\r\n<br />\r\nКто, чувства поправ, все же помнит впечали<br />\r\nПредметы, что чувства его услаждали,—<br />\r\n<br />\r\nТот, связанный, следует ложной дорогой;<br />\r\nА тот, о сын Кунти, кто, волею строгой<br />\r\n<br />\r\nВсе чувства поправ, йогу действия начал,—<br />\r\nНа правой дороге себя обозначил.<br />\r\n<br />\r\nПоэтому действуй; бездействию дело<br />\r\nВсегда предпочти; отравления тела —<br />\r\n<br />\r\nИ то без усилий свершить невозможно:<br />\r\nДеянье — надежно, бездействие — ложно. &nbsp;\r','a:0:{}','2014-01-29 10:01:55',1,1,NULL),(12,'ru','<hr />\r\n<h4>\r\n  Последние новости\r\n</h4>\r','a:0:{}','2014-01-29 19:43:16',1,1,NULL),(13,'ru','<h4>\r\n  Меню\r\n</h4>\r\n<hr />\r','a:0:{}','2014-01-29 19:45:52',1,1,NULL),(14,'ru','Где чувства господствуют – там вожделенье,<br />\r\nА где вожделенье – там гнев, ослепленье,<br />\r\n<br />\r\nА где ослепленье – ума угасанье,<br />\r\nГде ум угасает – там гибнет познанье,<br />\r\n<br />\r\nГде гибнет познанье, – да ведает всякий, –<br />\r\nТам гибнет дитя человечье во мраке.<br />\r\n<br />\r\nА тот, кто добился над чувствами власти,<br />\r\nПопрал отвращенье, не знает пристрастий,<br />\r\n<br />\r\nКто их навсегда подчинил своей воле, –<br />\r\nДостиг просветленья, избавясь от боли,<br />\r\n<br />\r\nИ сердце с тех пор у него беспорочно,<br />\r\nИ разум его утверждается прочно.<br />\r\n<br />\r\nВне йоги к разумным себя не причисли:<br />\r\nВ неясности нет созидающей мысли;<br />\r\n<br />\r\nВне творческой мысли нет мира, покоя,<br />\r\nА где вне покоя и счастье людское?\r','a:0:{}','2014-01-29 20:16:33',1,1,NULL),(15,'ru','<hr />\r\n<h4>\r\n  Категории блога\r\n</h4>\r','a:0:{}','2014-02-08 21:01:35',1,1,NULL),(16,'ru','Проверка вложенных папок при условии, что в родительскую подключен модуль с роутингом 2.','a:0:{}','2014-02-08 21:04:03',1,1,NULL);
/*!40000 ALTER TABLE `texter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `texter_history`
--

DROP TABLE IF EXISTS `texter_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `texter_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned DEFAULT NULL,
  `locale` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `editor` smallint(6) NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci,
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_82529097126F525E` (`item_id`),
  KEY `IDX_82529097A76ED395` (`user_id`),
  KEY `IDX_825290974AF38FD1` (`deleted_at`),
  CONSTRAINT `FK_82529097126F525E` FOREIGN KEY (`item_id`) REFERENCES `texter` (`id`),
  CONSTRAINT `FK_82529097A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `texter_history`
--

LOCK TABLES `texter_history` WRITE;
/*!40000 ALTER TABLE `texter_history` DISABLE KEYS */;
INSERT INTO `texter_history` VALUES (1,2,'ru',1,'<h1>Главная страница!</h1>\r\n<p>С точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма, концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих геотрансплантационных квазипузлистатов всех кинетически коррелирующих аспектов. Исходя из этого, мы пришли к выводу, что каждый произвольно выбранный предикативно абсорбирующий объект.</p>\r\n<img src=\"/uploads/Advanced%20C%20Asana.jpg\" alt=\"\" width=\"891\" height=\"666\" />','a:1:{s:8:\"keywords\";s:3:\"123\";}','2014-02-10 07:49:39',1,NULL),(2,16,'ru',1,'<hr />\r\n<h4>\r\n  Тэги блога\r\n</h4>\r','a:0:{}','2014-02-10 11:30:25',1,NULL),(3,16,'ru',1,'Проверка вложенных папок при улсовии, что в родительскую подключен модуль с роутингом.\r','a:0:{}','2014-02-10 11:31:39',1,NULL),(20,14,'ru',1,'Где чувства господствуют – там вожделенье,<br />\r\nА где вожделенье – там гнев, ослепленье,<br />\r\n<br />\r\nА где ослепленье – ума угасанье,<br />\r\nГде ум угасает – там гибнет познанье,<br />\r\n<br />\r\nГде гибнет познанье, – да ведает всякий, –<br />\r\nТам гибнет дитя человечье во мраке.<br />\r\n<br />\r\nА тот, кто добился над чувствами власти,<br />\r\nПопрал отвращенье, не знает пристрастий,<br />\r\n<br />\r\nКто их навсегда подчинил своей воле, –<br />\r\nДостиг просветленья, избавясь от боли,<br />\r\n<br />\r\nИ сердце с тех пор у него беспорочно,<br />\r\nИ разум его утверждается прочно.<br />\r\n<br />\r\nВне йоги к разумным себя не причисли:<br />\r\nВ неясности нет созидающей мысли;<br />\r\n<br />\r\nВне творческой мысли нет мира, покоя,<br />\r\nА где вне покоя и счастье людское?\r','a:0:{}','2014-11-07 05:13:33',1,NULL),(21,9,'ru',1,'Powered by <a href=\"http://symfony.com\" target=\"_blank\">Symfony2</a>\r','a:0:{}','2014-11-07 05:13:47',1,NULL),(22,NULL,'ru',1,'Проверка вложенных папок при условии, что в родительскую подключен модуль с роутингом.\r','a:0:{}','2016-09-19 20:08:29',1,NULL),(23,NULL,'ru',1,'Футер\r','a:0:{}','2017-01-02 08:50:09',1,NULL),(24,NULL,'ru',1,'Футер 2','a:0:{}','2017-01-02 08:50:33',1,NULL);
/*!40000 ALTER TABLE `texter_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat__attributes`
--

DROP TABLE IF EXISTS `unicat__attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat__attributes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(1) DEFAULT '1',
  `is_dedicated_table` tinyint(1) NOT NULL DEFAULT '0',
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `position` smallint(6) DEFAULT '0',
  `type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_in_admin` tinyint(1) NOT NULL,
  `show_in_list` tinyint(1) NOT NULL,
  `show_in_view` tinyint(1) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `items_type_id` int(10) unsigned DEFAULT NULL,
  `params_yaml` longtext COLLATE utf8_unicode_ci,
  `is_show_title` tinyint(1) NOT NULL DEFAULT '1',
  `is_link` tinyint(1) NOT NULL DEFAULT '0',
  `open_tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '<p>',
  `close_tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '</p>',
  `configuration_id` int(10) unsigned DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '1',
  `is_items_type_many2many` tinyint(1) NOT NULL DEFAULT '0',
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D3165B6D5E237E0673F32DD8` (`name`,`configuration_id`),
  KEY `IDX_D3165B6D73F32DD8` (`configuration_id`),
  KEY `IDX_D3165B6DA76ED395` (`user_id`),
  KEY `IDX_D3165B6D46C53D4C` (`is_enabled`),
  KEY `IDX_D3165B6DFB9FF2E7` (`show_in_admin`),
  KEY `IDX_D3165B6D921EA9F` (`show_in_list`),
  KEY `IDX_D3165B6DB314B909` (`show_in_view`),
  KEY `IDX_D3165B6D462CE4F5` (`position`),
  KEY `IDX_D3165B6DB9CCD492` (`items_type_id`),
  CONSTRAINT `FK_D3165B6D73F32DD8` FOREIGN KEY (`configuration_id`) REFERENCES `unicat__configurations` (`id`),
  CONSTRAINT `FK_D3165B6DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_D3165B6DB9CCD492` FOREIGN KEY (`items_type_id`) REFERENCES `unicat__items_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat__attributes`
--

LOCK TABLES `unicat__attributes` WRITE;
/*!40000 ALTER TABLE `unicat__attributes` DISABLE KEYS */;
INSERT INTO `unicat__attributes` VALUES (1,1,0,1,1,'text','title','Заголовок',1,1,1,1,'2014-02-13 20:37:50','N;',NULL,NULL,1,1,'<h3>','</h3>',1,1,0,NULL),(2,1,0,0,3,'textarea','description','Описание',0,1,1,1,'2014-02-13 21:03:59','N;',NULL,NULL,1,0,'<p>','</p>',1,1,0,NULL),(3,1,0,0,255,'integer','price','Цена',1,1,1,1,'2014-02-13 22:29:43','N;',NULL,NULL,1,0,'<p>','</p>',1,1,0,NULL),(4,1,0,0,4,'checkbox','in_sight','В наличии',0,1,1,1,'2014-02-13 23:19:31','a:0:{}',NULL,NULL,1,0,'<p>','</p>',1,1,0,NULL),(5,1,0,0,2,'image','image','Картинка',0,1,1,1,'2014-02-15 20:54:17','a:1:{s:6:\"filter\";s:7:\"300_300\";}',NULL,'filter: 300_300',1,0,'<p>','</p>',1,1,0,NULL),(6,0,0,0,0,'text','picture','---- Картинка ---',0,0,0,1,'2015-09-27 15:55:28','a:0:{}',NULL,NULL,1,0,'<p>','</p>',1,1,0,NULL);
/*!40000 ALTER TABLE `unicat__attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat__attributes_groups`
--

DROP TABLE IF EXISTS `unicat__attributes_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat__attributes_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `configuration_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5E377FB75E237E0673F32DD8` (`name`,`configuration_id`),
  KEY `IDX_5E377FB773F32DD8` (`configuration_id`),
  CONSTRAINT `FK_5E377FB773F32DD8` FOREIGN KEY (`configuration_id`) REFERENCES `unicat__configurations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat__attributes_groups`
--

LOCK TABLES `unicat__attributes_groups` WRITE;
/*!40000 ALTER TABLE `unicat__attributes_groups` DISABLE KEYS */;
INSERT INTO `unicat__attributes_groups` VALUES (1,1,'2017-02-02 10:54:09','wares_description','Описание товара');
/*!40000 ALTER TABLE `unicat__attributes_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat__attributes_groups_relations`
--

DROP TABLE IF EXISTS `unicat__attributes_groups_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat__attributes_groups_relations` (
  `attribute_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attribute_id`,`group_id`),
  KEY `IDX_E6224AAFB6E62EFA` (`attribute_id`),
  KEY `IDX_E6224AAFFE54D947` (`group_id`),
  CONSTRAINT `FK_E6224AAFB6E62EFA` FOREIGN KEY (`attribute_id`) REFERENCES `unicat__attributes` (`id`),
  CONSTRAINT `FK_E6224AAFFE54D947` FOREIGN KEY (`group_id`) REFERENCES `unicat__attributes_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat__attributes_groups_relations`
--

LOCK TABLES `unicat__attributes_groups_relations` WRITE;
/*!40000 ALTER TABLE `unicat__attributes_groups_relations` DISABLE KEYS */;
INSERT INTO `unicat__attributes_groups_relations` VALUES (1,1),(2,1),(3,1),(4,1),(5,1);
/*!40000 ALTER TABLE `unicat__attributes_groups_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat__configurations`
--

DROP TABLE IF EXISTS `unicat__configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat__configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_collection_id` int(10) unsigned DEFAULT NULL,
  `default_taxonomy_id` int(10) unsigned DEFAULT NULL,
  `entities_namespace` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_inheritance` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `items_per_page` smallint(5) unsigned NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F622D4625E237E06` (`name`),
  UNIQUE KEY `UNIQ_F622D4622B36786B` (`title`),
  KEY `IDX_F622D462B52E685C` (`media_collection_id`),
  KEY `IDX_F622D462A76ED395` (`user_id`),
  KEY `IDX_F622D46241A4F540` (`default_taxonomy_id`),
  CONSTRAINT `FK_F622D46241A4F540` FOREIGN KEY (`default_taxonomy_id`) REFERENCES `unicat__taxonomies` (`id`),
  CONSTRAINT `FK_F622D462A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_F622D462B52E685C` FOREIGN KEY (`media_collection_id`) REFERENCES `media_collections` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat__configurations`
--

LOCK TABLES `unicat__configurations` WRITE;
/*!40000 ALTER TABLE `unicat__configurations` DISABLE KEYS */;
INSERT INTO `unicat__configurations` VALUES (1,1,1,'SandboxSiteBundle\\Entity\\Catalog\\',1,'2015-02-28 03:01:59','catalog','Каталог товаров',1,2);
/*!40000 ALTER TABLE `unicat__configurations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat__items_types`
--

DROP TABLE IF EXISTS `unicat__items_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat__items_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `configuration_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `to_string_pattern` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3E08A29773F32DD8` (`configuration_id`),
  KEY `IDX_3E08A297A76ED395` (`user_id`),
  CONSTRAINT `FK_3E08A29773F32DD8` FOREIGN KEY (`configuration_id`) REFERENCES `unicat__configurations` (`id`),
  CONSTRAINT `FK_3E08A297A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat__items_types`
--

LOCK TABLES `unicat__items_types` WRITE;
/*!40000 ALTER TABLE `unicat__items_types` DISABLE KEYS */;
INSERT INTO `unicat__items_types` VALUES (1,1,1,'2017-03-28 02:41:35','wares',0,'Товары',NULL);
/*!40000 ALTER TABLE `unicat__items_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat__items_types_attributes_groups_relations`
--

DROP TABLE IF EXISTS `unicat__items_types_attributes_groups_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat__items_types_attributes_groups_relations` (
  `attribute_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attribute_id`,`group_id`),
  KEY `IDX_927D1F27B6E62EFA` (`attribute_id`),
  KEY `IDX_927D1F27FE54D947` (`group_id`),
  CONSTRAINT `FK_927D1F27B6E62EFA` FOREIGN KEY (`attribute_id`) REFERENCES `unicat__items_types` (`id`),
  CONSTRAINT `FK_927D1F27FE54D947` FOREIGN KEY (`group_id`) REFERENCES `unicat__attributes_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat__items_types_attributes_groups_relations`
--

LOCK TABLES `unicat__items_types_attributes_groups_relations` WRITE;
/*!40000 ALTER TABLE `unicat__items_types_attributes_groups_relations` DISABLE KEYS */;
INSERT INTO `unicat__items_types_attributes_groups_relations` VALUES (1,1);
/*!40000 ALTER TABLE `unicat__items_types_attributes_groups_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat__items_types_taxonomies_relations`
--

DROP TABLE IF EXISTS `unicat__items_types_taxonomies_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat__items_types_taxonomies_relations` (
  `attribute_id` int(10) unsigned NOT NULL,
  `taxonomy_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attribute_id`,`taxonomy_id`),
  KEY `IDX_FC6EEED5B6E62EFA` (`attribute_id`),
  KEY `IDX_FC6EEED59557E6F6` (`taxonomy_id`),
  CONSTRAINT `FK_FC6EEED59557E6F6` FOREIGN KEY (`taxonomy_id`) REFERENCES `unicat__taxonomies` (`id`),
  CONSTRAINT `FK_FC6EEED5B6E62EFA` FOREIGN KEY (`attribute_id`) REFERENCES `unicat__items_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat__items_types_taxonomies_relations`
--

LOCK TABLES `unicat__items_types_taxonomies_relations` WRITE;
/*!40000 ALTER TABLE `unicat__items_types_taxonomies_relations` DISABLE KEYS */;
INSERT INTO `unicat__items_types_taxonomies_relations` VALUES (1,1),(1,2);
/*!40000 ALTER TABLE `unicat__items_types_taxonomies_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat__taxonomies`
--

DROP TABLE IF EXISTS `unicat__taxonomies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat__taxonomies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `configuration_id` int(11) unsigned DEFAULT NULL,
  `position` smallint(6) DEFAULT '0',
  `is_multiple_entries` tinyint(1) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_form` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_default_inheritance` tinyint(1) NOT NULL DEFAULT '0',
  `properties` longtext COLLATE utf8_unicode_ci,
  `is_tree` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_C1A645E473F32DD8` (`configuration_id`),
  KEY `IDX_C1A645E4A76ED395` (`user_id`),
  CONSTRAINT `FK_C1A645E473F32DD8` FOREIGN KEY (`configuration_id`) REFERENCES `unicat__configurations` (`id`),
  CONSTRAINT `FK_C1A645E4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat__taxonomies`
--

LOCK TABLES `unicat__taxonomies` WRITE;
/*!40000 ALTER TABLE `unicat__taxonomies` DISABLE KEYS */;
INSERT INTO `unicat__taxonomies` VALUES (1,1,1,0,'Категории',1,1,'2014-02-11 23:44:56','categories','Категория',0,'description: #textarea\r\n    type: textarea\r\n    attr:\r\n        class: wysiwyg\r\n        data-theme: advanced',1),(2,1,2,1,'Облаго тэгов',0,1,'2014-02-11 23:45:18','tags','Тэги',0,'',0);
/*!40000 ALTER TABLE `unicat__taxonomies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat_catalog_items`
--

DROP TABLE IF EXISTS `unicat_catalog_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat_catalog_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(1) DEFAULT '1',
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `attributes` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type_id` int(10) unsigned DEFAULT NULL,
  `hidden_extra` longtext COLLATE utf8_unicode_ci,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_163452F3989D9B62` (`slug`),
  UNIQUE KEY `UNIQ_163452F3D17F50A6` (`uuid`),
  KEY `IDX_163452F3462CE4F5` (`position`),
  KEY `IDX_163452F3A76ED395` (`user_id`),
  KEY `IDX_163452F3C54C8C93` (`type_id`),
  CONSTRAINT `FK_163452F3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_163452F3C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `unicat__items_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat_catalog_items`
--

LOCK TABLES `unicat_catalog_items` WRITE;
/*!40000 ALTER TABLE `unicat_catalog_items` DISABLE KEYS */;
INSERT INTO `unicat_catalog_items` VALUES (1,1,'np900','a:2:{s:11:\"description\";N;s:8:\"keywords\";N;}','a:6:{s:5:\"title\";s:13:\"Samsung NP900\";s:11:\"description\";s:18:\"Ультрабук\";s:8:\"in_sight\";b:0;s:5:\"price\";i:5451;s:5:\"image\";i:1;s:7:\"picture\";N;}',1,'2014-02-14 07:48:18',0,NULL,1,NULL,NULL),(2,1,'galaxy-s4','a:2:{s:11:\"description\";N;s:8:\"keywords\";N;}','a:6:{s:5:\"title\";s:17:\"Samsung Galaxy S4\";s:8:\"in_sight\";b:1;s:5:\"price\";i:19000;s:5:\"image\";i:4;s:11:\"description\";N;s:7:\"picture\";N;}',1,'2014-02-14 13:13:57',1,NULL,1,NULL,NULL),(3,1,'seagate-500g','a:2:{s:11:\"description\";N;s:8:\"keywords\";N;}','a:4:{s:5:\"title\";s:13:\"Seagate 500Gb\";s:5:\"image\";i:3;s:8:\"in_sight\";b:1;s:7:\"picture\";N;}',1,'2014-02-17 01:19:23',0,NULL,1,NULL,NULL),(4,1,'canon-650d','a:2:{s:11:\"description\";N;s:8:\"keywords\";N;}','a:5:{s:5:\"title\";s:10:\"Canon 650D\";s:8:\"in_sight\";b:1;s:5:\"price\";i:25000;s:5:\"image\";i:5;s:7:\"picture\";N;}',1,'2014-02-17 22:09:56',0,NULL,1,NULL,NULL),(5,1,'htc-one','a:2:{s:11:\"description\";N;s:8:\"keywords\";N;}','a:5:{s:5:\"title\";s:7:\"HTC One\";s:8:\"in_sight\";b:1;s:5:\"image\";i:6;s:5:\"price\";i:20000;s:7:\"picture\";s:3:\"123\";}',1,'2014-03-06 16:35:40',0,NULL,1,NULL,NULL),(6,0,'aaa','a:2:{s:11:\"description\";N;s:8:\"keywords\";N;}','a:3:{s:5:\"title\";s:3:\"aaa\";s:8:\"in_sight\";b:0;s:5:\"image\";N;}',1,'2015-12-10 19:32:00',0,NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `unicat_catalog_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat_catalog_items_taxons_relations`
--

DROP TABLE IF EXISTS `unicat_catalog_items_taxons_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat_catalog_items_taxons_relations` (
  `item_id` int(11) unsigned NOT NULL,
  `taxon_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`item_id`,`taxon_id`),
  KEY `IDX_437400EB126F525E` (`item_id`),
  KEY `IDX_437400EBDE13F470` (`taxon_id`),
  CONSTRAINT `FK_437400EB126F525E` FOREIGN KEY (`item_id`) REFERENCES `unicat_catalog_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_437400EBDE13F470` FOREIGN KEY (`taxon_id`) REFERENCES `unicat_catalog_taxons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat_catalog_items_taxons_relations`
--

LOCK TABLES `unicat_catalog_items_taxons_relations` WRITE;
/*!40000 ALTER TABLE `unicat_catalog_items_taxons_relations` DISABLE KEYS */;
INSERT INTO `unicat_catalog_items_taxons_relations` VALUES (1,5),(1,9),(2,2),(2,9),(3,8),(4,16),(4,18),(5,2),(6,4),(6,5),(6,14),(6,15);
/*!40000 ALTER TABLE `unicat_catalog_items_taxons_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat_catalog_items_taxons_single_relations`
--

DROP TABLE IF EXISTS `unicat_catalog_items_taxons_single_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat_catalog_items_taxons_single_relations` (
  `item_id` int(11) unsigned NOT NULL,
  `taxon_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`item_id`,`taxon_id`),
  KEY `IDX_9FC85324126F525E` (`item_id`),
  KEY `IDX_9FC85324DE13F470` (`taxon_id`),
  CONSTRAINT `FK_9FC85324126F525E` FOREIGN KEY (`item_id`) REFERENCES `unicat_catalog_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_9FC85324DE13F470` FOREIGN KEY (`taxon_id`) REFERENCES `unicat_catalog_taxons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat_catalog_items_taxons_single_relations`
--

LOCK TABLES `unicat_catalog_items_taxons_single_relations` WRITE;
/*!40000 ALTER TABLE `unicat_catalog_items_taxons_single_relations` DISABLE KEYS */;
INSERT INTO `unicat_catalog_items_taxons_single_relations` VALUES (1,5),(1,9),(2,2),(2,9),(3,8),(4,16),(4,18),(5,2),(6,5),(6,15);
/*!40000 ALTER TABLE `unicat_catalog_items_taxons_single_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unicat_catalog_taxons`
--

DROP TABLE IF EXISTS `unicat_catalog_taxons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unicat_catalog_taxons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_inheritance` tinyint(1) NOT NULL DEFAULT '1',
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `is_enabled` tinyint(1) DEFAULT '1',
  `position` smallint(6) DEFAULT '0',
  `taxonomy_id` int(10) unsigned DEFAULT NULL,
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_parent_taxonomy` (`title`,`parent_id`,`taxonomy_id`),
  UNIQUE KEY `slug_parent_taxonomy` (`slug`,`parent_id`,`taxonomy_id`),
  KEY `IDX_767F6678727ACA70` (`parent_id`),
  KEY `IDX_767F6678A76ED395` (`user_id`),
  KEY `IDX_767F667846C53D4C` (`is_enabled`),
  KEY `IDX_767F6678462CE4F5` (`position`),
  KEY `IDX_767F66789557E6F6` (`taxonomy_id`),
  CONSTRAINT `FK_767F6678727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `unicat_catalog_taxons` (`id`),
  CONSTRAINT `FK_767F66789557E6F6` FOREIGN KEY (`taxonomy_id`) REFERENCES `unicat__taxonomies` (`id`),
  CONSTRAINT `FK_767F6678A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unicat_catalog_taxons`
--

LOCK TABLES `unicat_catalog_taxons` WRITE;
/*!40000 ALTER TABLE `unicat_catalog_taxons` DISABLE KEYS */;
INSERT INTO `unicat_catalog_taxons` VALUES (1,NULL,'connection','Техника для связи',0,'N;','2014-02-10 09:29:09',1,0,1,NULL,1),(2,1,'smartphones','Смартфоны',0,'a:0:{}','2014-02-12 21:19:29',1,1,1,'a:1:{s:11:\"description\";N;}',1),(3,1,'signal_amplifiers','Усилители сигнала',0,'a:0:{}','2014-02-12 22:12:43',1,0,1,'a:1:{s:11:\"description\";N;}',1),(4,NULL,'pc','Компьютерная техника',0,'N;','2014-02-12 22:14:33',1,0,1,NULL,1),(5,4,'notebooks','Ноутбуки',0,'N;','2014-02-12 22:15:02',1,0,1,NULL,1),(6,4,'notebooks_stuff','Комплектующие для ноутбуков',0,'N;','2014-02-12 22:15:26',1,0,1,NULL,1),(7,6,'ram','Модули памяти',0,'a:0:{}','2014-02-12 22:15:42',1,0,1,'a:1:{s:11:\"description\";N;}',1),(8,6,'hdd25','Жесткие диски 2.5',0,'a:0:{}','2014-02-12 22:15:59',1,0,1,'a:1:{s:11:\"description\";N;}',1),(9,NULL,'samsung','Samsung',0,'N;','2014-02-12 22:17:02',1,0,2,NULL,1),(10,4,'monitors','Мониторы',0,'N;','2014-02-12 22:18:24',1,0,1,NULL,1),(11,NULL,'office','Офисная техника',0,'N;','2014-02-12 22:19:07',1,0,1,NULL,1),(12,11,'printers','Принтеры',0,'N;','2014-02-12 22:19:27',1,0,1,NULL,1),(13,11,'scanners','Сканеры',0,'N;','2014-02-12 22:19:43',1,0,1,NULL,1),(14,NULL,'sony','Sony',0,'N;','2014-02-12 22:20:07',1,0,2,NULL,1),(15,14,'vaio','Vaio',1,'N;','2014-02-17 21:37:57',1,0,2,NULL,1),(16,NULL,'canon','Canon',1,'N;','2014-02-17 21:38:32',1,0,2,NULL,1),(17,NULL,'portable','Портативная электроника',0,'N;','2014-02-17 22:00:09',1,0,1,NULL,1),(18,17,'reflex_cameras','Зеркальные фотоаппараты',1,'N;','2014-02-17 22:08:49',1,0,1,NULL,1);
/*!40000 ALTER TABLE `unicat_catalog_taxons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) NOT NULL,
  `username_canonical` varchar(180) NOT NULL,
  `email` varchar(180) NOT NULL,
  `email_canonical` varchar(180) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:array)',
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `patronymic` varchar(32) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_1483A5E9A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_1483A5E9C05FB297` (`confirmation_token`),
  KEY `IDX_1483A5E983A00E68` (`firstname`),
  KEY `IDX_1483A5E93124B5B6` (`lastname`),
  KEY `IDX_1483A5E9E42A05AB` (`patronymic`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'root','root','artem@mail.ru','artem@mail.ru',1,'','$2y$15$Iy.ZKMFPgunfwgr8A.Tc6u8ix/71u/kk8qO2icqe.jhtOItSAh./G','2017-03-28 02:33:58',NULL,NULL,'a:1:{i:0;s:9:\"ROLE_ROOT\";}','','','2014-01-20 00:00:00',NULL,'+7-923-123-12-34'),(2,'demo','demo','demo@mail.com','demo@mail.com',1,'','$2y$15$Z28c3UHszCiJGqNwteJED.aBZTYP74lBdDk3T0eyn2ImhFVBEgsfG','2015-05-22 00:28:12',NULL,NULL,'a:1:{i:0;s:14:\"ROLE_NEWSMAKER\";}','','','2014-01-20 00:00:00',NULL,NULL),(3,'aaa','aaa','aaa@aaa.ru','aaa@aaa.ru',1,'','$2y$15$W339oBgss/qxoIitAUyIHem/cZe6pNDZvihuXcsrzYzmRhwRSTUV6','2016-09-20 02:49:13',NULL,NULL,'a:0:{}','','','2014-01-20 00:00:00',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webforms`
--

DROP TABLE IF EXISTS `webforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webforms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_use_captcha` tinyint(1) NOT NULL DEFAULT '0',
  `send_button_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `send_notice_emails` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `final_text` longtext COLLATE utf8_unicode_ci,
  `from_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_ajax` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_641866195E237E06` (`name`),
  KEY `IDX_64186619A76ED395` (`user_id`),
  CONSTRAINT `FK_64186619A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webforms`
--

LOCK TABLES `webforms` WRITE;
/*!40000 ALTER TABLE `webforms` DISABLE KEYS */;
INSERT INTO `webforms` VALUES (1,'2015-03-17 02:36:43','Обратная связь',1,'feedback',1,NULL,NULL,'Сообщение отправлено','noreply@smart-core.org',1);
/*!40000 ALTER TABLE `webforms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webforms_fields`
--

DROP TABLE IF EXISTS `webforms_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webforms_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `web_form_id` int(10) unsigned DEFAULT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `params_yaml` longtext COLLATE utf8_unicode_ci,
  `type` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_enabled` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_4FE98D46B75935E3` (`web_form_id`),
  KEY `IDX_4FE98D46A76ED395` (`user_id`),
  CONSTRAINT `FK_4FE98D46A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_4FE98D46B75935E3` FOREIGN KEY (`web_form_id`) REFERENCES `webforms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webforms_fields`
--

LOCK TABLES `webforms_fields` WRITE;
/*!40000 ALTER TABLE `webforms_fields` DISABLE KEYS */;
INSERT INTO `webforms_fields` VALUES (1,'2015-03-17 03:56:03','email',2,'Ваш емаил',1,1,'a:0:{}',NULL,'email',1,1),(2,'2015-03-17 03:57:27','text',3,'Сообщение',1,1,'a:0:{}',NULL,'textarea',1,1),(3,'2015-03-24 03:15:32','name',0,'Имя',1,1,'a:1:{s:4:\"attr\";a:1:{s:5:\"class\";s:3:\"xxx\";}}','attr:\r\n    class: xxx','text',1,1),(4,'2016-03-16 00:20:33','gender',0,'Ваш пол',1,1,'a:1:{s:7:\"choices\";a:2:{s:1:\"m\";s:14:\"Мужской\";s:1:\"f\";s:14:\"Женский\";}}','choices:\r\n    m: Мужской\r\n    f: Женский','choice',1,1);
/*!40000 ALTER TABLE `webforms_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webforms_messages`
--

DROP TABLE IF EXISTS `webforms_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webforms_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` longtext COLLATE utf8_unicode_ci,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `web_form_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_24719905B75935E3` (`web_form_id`),
  KEY `IDX_24719905A76ED395` (`user_id`),
  CONSTRAINT `FK_24719905A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_24719905B75935E3` FOREIGN KEY (`web_form_id`) REFERENCES `webforms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webforms_messages`
--

LOCK TABLES `webforms_messages` WRITE;
/*!40000 ALTER TABLE `webforms_messages` DISABLE KEYS */;
INSERT INTO `webforms_messages` VALUES (1,'a:3:{s:4:\"name\";s:4:\"1234\";s:5:\"email\";s:12:\"root@mail.ru\";s:4:\"text\";s:3:\"dfg\";}','2015-03-24 04:17:00',1,'Надо уточникть :)',2,1,NULL),(2,'a:3:{s:4:\"name\";s:6:\"222222\";s:5:\"email\";s:12:\"root@mail.ru\";s:4:\"text\";s:11:\"54555555555\";}','2015-03-24 04:17:42',NULL,'hfgh 3',0,1,NULL),(3,'a:3:{s:4:\"name\";s:3:\"dfg\";s:5:\"email\";s:12:\"root@mail.ru\";s:4:\"text\";s:4:\"dfhj\";}','2015-03-24 04:50:33',NULL,NULL,0,1,NULL),(4,'a:3:{s:4:\"name\";s:7:\"dfg dfg\";s:5:\"email\";s:12:\"root@mail.ru\";s:4:\"text\";s:17:\"678 sdfg 547 8fgh\";}','2015-03-24 06:15:54',1,NULL,0,1,NULL),(5,'a:4:{s:4:\"name\";s:4:\"test\";s:6:\"gender\";s:1:\"f\";s:5:\"email\";s:13:\"god@world.com\";s:4:\"text\";s:3:\"123\";}','2016-03-16 00:36:52',1,NULL,0,1,'127.0.0.1');
/*!40000 ALTER TABLE `webforms_messages` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-28  3:18:01
