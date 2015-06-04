-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Июн 05 2015 г., 04:58
-- Версия сервера: 5.6.13
-- Версия PHP: 5.6.9

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
-- Структура таблицы `blog_articles`
--

DROP TABLE IF EXISTS `blog_articles`;
CREATE TABLE IF NOT EXISTS `blog_articles` (
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
  KEY `IDX_CB80154F8B8E8428` (`created_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `blog_articles`
--

INSERT INTO `blog_articles` (`id`, `author_id`, `category_id`, `image_id`, `is_commentable`, `is_enabled`, `title`, `slug`, `annotation`, `text`, `keywords`, `description`, `created_at`, `updated_at`) VALUES
(3, 1, 3, NULL, 1, 1, 'Хлебные крошки в Yii', 'breadcrumbs_yii', 'Хлебные крошки (Breadcrumbs) - это строка навигации до текущей страницы, сделанная из ссылок на родительские элементы. В Yii есть удобное средство для работы с хлебными крошками - виджет zii CBreadcrumbs http://www.yiiframework.com/doc/api/1.1/CBreadcrumbs<br />\n	Хочу описать, как подключить CBreadcrumbs.', '<p></p>\n<hr id="readmore" />\n<p>\n	В контроллере определяем общедоступную переменную-массив хлебных крошек. public $breadcrumbs=array();<br />\n	В layout view вставляем</p>\n<div class="highlight">\n	<pre class="brush:php">\n	&lt;?php if(isset($this-&gt;breadcrumbs)):?&gt;\n		&lt;?php $this-&gt;widget(&#39;zii.widgets.CBreadcrumbs&#39;, array(\n			&#39;links&#39;=&gt;$this-&gt;breadcrumbs,\n                        &#39;homeLink&#39;=&gt;CHtml::link(&#39;Главная&#39;,&#39;/&#39; ),\n		)); ?&gt;&lt;!-- breadcrumbs --&gt;\n	&lt;?php endif?&gt;</pre>\n</div>\n<p>\n	Здесь links &ndash; массив ссылок навигации, мы берём его из текущего контроллера.<br />\n	homeLink &ndash; ссылка на главную страницу.<br />\n	Теперь во view не забываем определить массив:</p>\n<div class="highlight">\n	<pre class="brush:php">\n$this-&gt;breadcrumbs=array(\n	&#39;Записи&#39;=&gt;array(&#39;index&#39;),\n	$model-&gt;title,\n);</pre>\n</div>\n<p>\n	Вот и всё.</p>\n', 'Yii, хлебные крошки', 'Как создать хлебные крошки в Yii', '2011-11-26 10:06:15', NULL),
(4, 1, 3, NULL, 1, 1, 'Как подключить Ckeditor к фреймворку Yii', 'how_to_connect_ckeditor_to_framework_yii', 'Часто возникает необходимость использовать визуальный редактор на сайте. Есть несколько весьма популярных WYSIWYNG-редакторов. Один из них - Ckeditor. Сегодня я расскажу, как подключить Ckeditor к Yii.', '<p></p>\n<hr id="readmore" />\n<p>\n	Шаг первый: скачиваем сам редактор с официального сайта: <a href="http://ckeditor.com/download" target="_blank">http://ckeditor.com/download</a><br />\n	Распаковываем архив в корень сайта.<br />\n	Шаг второй: скачиваем расширение Yii ckeditor-integration <a href="http://www.yiiframework.com/extension/ckeditor-integration/">отсюда</a>.<br />\n	Распаковываем в папку protected/extensions.<br />\n	Шаг третий: подключаем к форме наш редактор:</p>\n<div class="highlight">\n	<pre class="brush: php">\n&lt;?php\n$this-&gt;widget(&#39;ext.ckeditor.CKEditorWidget&#39;,array(\n  &quot;model&quot;=&gt;$model,                 # Модель данных\n  &quot;attribute&quot;=&gt;&#39;content&#39;,          # Аттрибут в модели\n  &quot;defaultValue&quot;=&gt;$model-&gt;content, #Значение по умолчанию\n\n  &quot;config&quot; =&gt; array(\n      &quot;height&quot;=&gt;&quot;400px&quot;,\n      &quot;width&quot;=&gt;&quot;100%&quot;,\n      &quot;toolbar&quot;=&gt;&quot;Full&quot;, #панель инструментов\n      &quot;defaultLanguage&quot;=&gt;&quot;ru&quot;, # Язык по умолчанию\n      ),\n   &quot;ckEditor&quot;=&gt;Yii::app()-&gt;basePath.&quot;/../ckeditor/ckeditor.php&quot;,\n                                  # Путь к ckeditor.php\n  &quot;ckBasePath&quot;=&gt;Yii::app()-&gt;baseUrl.&quot;/ckeditor/&quot;,\n                                  # адрес к редактору\n  ) ); ?&gt;</pre>\n</div>\n<div class="code">\n	Все параметры конфига редактора смотрим <a href="http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html">здесь</a></div>\n', 'Yii, Ckeditor, подключение', 'В статье рассказывается о том, как быстро и правильно подключить Ckeditor к Yii', '2011-11-23 13:20:50', NULL),
(5, 1, 3, NULL, 1, 1, 'Форматирование даты и времени в Yii', 'formatting_of_date_and_time_in_yii', 'Передо мной встала такая задача: как в Yii вывести дату, отформатированную в родном, русском формате. Оказывается, очень просто. Во-первых, надо установить русский язык в конфигурационном файле приложения, и, во-вторых, воспользоваться методом компонента&nbsp; приложения CDateFormatter-&gt;format().', '<p></p>\n<hr id="readmore" />\n<p>\n	Итак, приступим. В конфигурационном файле пропишем две строчки, которые установят русификацию для сайта:</p>\n<div class="highlight">\n	<pre class="brush: php">\n   &#39;sourceLanguage&#39; =&gt; &#39;en&#39;,\n    &#39;language&#39; =&gt; &#39;ru&#39;,</pre>\n</div>\n<p>\n	Здесь sourceLanguage &ndash; язык, на котором написан сам сайт. У меня он, естественно, английский. Ну а текущий язык &ndash; language &ndash; русский.<br />\n	Теперь в том месте, где хотим вывести отформатированную дату, добавим такой код:</p>\n<div class="highlight">\n	<pre class="brush: php">\n	echo Yii::app()-&gt;dateFormatter-&gt;format(&quot;dd MMMM y, HH:mm&quot;, $vardatetime);</pre>\n</div>\n<p>\n	Выведет дату и время в таком формате:&nbsp; 29 ноября 2011, 16:41<br />\n	Метод format принимает два параметра: первый &ndash; шаблон времени в стандарте Юникода, второй &ndash; время в unix timestamp или Mysql DATETIME. Вот и всё.<br />\n	Более подробно о CDateFormatter смотрите <a href="http://www.yiiframework.com/doc/api/1.1/CDateFormatter" target="_blank">здесь</a><br />\n	&nbsp;</p>\n<p>\n	&nbsp;</p>\n', 'Yii, формат, дата', 'Как правильно и грамотно отформатировать дату и время в Yii', '2012-02-25 15:28:38', NULL),
(6, 1, 6, NULL, 1, 1, 'Symfony2: справочник команд', 's2_sik_knd', 'В этой статье буду писать самые часто используемые команды Симфони. Как ни странно, но на Симфони без командной строки ну никак. Полгода-год назад помнил многие команды наизусть, а сейчас, особенно после работы с Магенто, в голове чистый лист.', '<p>&nbsp;</p>\r\n<hr id="readmore" />\r\n<p>Инсталляция проекта:</p>\r\n<pre class="brush: text;"> composer install --prefer-dist</pre>\r\n<p>Обновление проекта:</p>\r\n<pre class="brush: text;"> composer update --prefer-dist</pre>\r\n<p>Обновление бандла:</p>\r\n<pre class="brush: text;"> composer update friendsofsymfony/user-bundle</pre>\r\n<p>Создание базы:</p>\r\n<pre class="brush: text;">php app/console doctrine:database:create</pre>\r\n<p>Создание таблиц:</p>\r\n<pre class="brush: text;">php app/console doctrine:schema:create</pre>\r\n<p>Загрузка фикстур:</p>\r\n<pre class="brush: text;">php app/console doctrine:fixtures:load</pre>\r\n<p>Обновление схемы базы:</p>\r\n<pre class="brush: text;">php app/console doctrine:schema:update --force</pre>\r\n<p>Создание бандла:</p>\r\n<pre class="brush: text;">php app/console generate:bundle --namespace=Acme/HelloBundle --format=yml</pre>\r\n<p>Очистка кэша:</p>\r\n<pre class="brush: text;">php app/console cache:clear --env=prod --no-debug</pre>\r\n<p>Отладка роутеров:</p>\r\n<pre class="brush: text;">php app/console router:debug</pre>\r\n<p>Показать все сервисы и классы связанные с сервисом:</p>\r\n<pre class="brush: text;">php app/console container:debug</pre>\r\n<p>Показать приватные сервисы :</p>\r\n<pre class="brush: text;">php app/console container:debug --show-private</pre>\r\n<p>Показать сервис по его id :</p>\r\n<pre class="brush: text;">php app/console container:debug my_mailer</pre>\r\n<p>Обновить ассеты :</p>\r\n<pre class="brush: text;">php app/console assetic:dump\r\nphp app/console assets:install\r\n</pre>\r\n<p>Примечание. Иногда бывает нужно явно указать --env=prod</p>', 'Symfony, команды', 'Самые часто используемые команды  Symfony2', '2013-08-07 17:19:21', '2014-02-09 09:55:42'),
(7, 1, 7, NULL, 1, 1, 'Подсвечиваем код на сайте', 'highlight_code_on_site', 'Давно мечтал о нормальной подсветке кода php, html и css. Наконец-то у меня выдалось время и я посветил этому вопросу несколько часов. Итак, небольшой обзор существующих способов подсветки показал, что её (подсветку) можно делать или не стороне клиента, или на стороне сервера. Для себя я сразу решил, что свой сервер грузить лишней работой не стоит. В общем, решил искать реализацию на JavaScript. Конечно, при отключённом js мои посетители не увидят подсветки, но таких, надеюсь, будет мало))', '<p>&nbsp;</p>\r\n<hr id="readmore" />\r\n<p>После гугления наткнулся на симпатичную статью. В ней описывался компактный скрипт highlight: <a href="http://softwaremaniacs.org/soft/highlight/">http://softwaremaniacs.org/soft/highlight/</a></p>\r\n<p>Увы, после подключения подсветки кода я не увидел. Зато мой &laquo;любимый&raquo; IE подсветил ошибку на JavaScript. Мол, объект не поддерживает какое-то там свойство. Как Вы наверное понимаете, копаться в чужом коде и искать ошибку я не стал. Не подключается &ndash; и ладно, ищем другой скрипт.</p>\r\n<p>Кандидатом номер два стал SyntaxHighlighter от Alex Gorbatchev. Особенность скрипта &ndash; что он не требует jQuery (хотя я не считаю это преимуществом) и можно указать только те языки, которые нужны. &nbsp;После скачивания и настройки подсветка кода тут же заработала, что очень и очень меня порадовало!</p>\r\n<p>Архив качаем отсюда: <a href="http://alexgorbatchev.com/SyntaxHighlighter/download/">http://alexgorbatchev.com/SyntaxHighlighter/download/</a></p>\r\n<p>Расскажу о некоторых особенностях настройки. Извлеките из скаченного архива и подключите следующие файлы:</p>\r\n<ol>\r\n<li>shCore.js</li>\r\n<li>shCore.css</li>\r\n<li>shThemeDefault.css</li>\r\n</ol>\r\n<p>Далее определитесь с языками, подсветка коих Вам нужна. Так, я выбрал себе css, html и php. Чтобы они заработали, надо подключить следующие файлы:&nbsp; shBrushCss.js,&nbsp; shBrushXml.js, shBrushPhp.js.</p>\r\n<p>И последний шаг &ndash; инициализация скрипта. Добавьте скрипт со строчкой</p>\r\n<pre class="brush: js;">SyntaxHighlighter.all();</pre>\r\n<p>- и подсветка заработает.</p>\r\n<p>&nbsp;</p>\r\n<p>На этом собственно все. Заключительный штрих &ndash; у себя я отключил боковую панельку (полоса прокрутки+ссылка на сайт автора) командой SyntaxHighlighter.defaults[''toolbar''] = false;</p>\r\n<p>Как пользоваться подсветкой? Используйте тег &lt;pre&gt;с классом brush:[язык подсветки]. Т.е. для php это будет выглядеть так:</p>\r\n<p>&nbsp;</p>\r\n<pre class="brush: html;">	&lt;pre class="brush: php;"&gt;echo "Привет, мир!"; &lt;/pre&gt;</pre>\r\n<p>&nbsp;</p>\r\n<p>Скриптом я доволен.</p>', 'подсветка кода, highlight', 'Как подсветить код на сайте: используем highlight', '2013-01-29 17:28:47', '2014-02-09 10:06:05'),
(8, 1, 6, NULL, 1, 1, 'Настройка Symfony2 в PhpStorm', 'adjustment_symfony2_in_phpstorm', 'По горячим следам, пока помню, напишу об интеграции поддержки Symfony2 в phpStorm.', '<p></p>\n<hr id="readmore" />\n<p>\n	Нам потребуется плагин: <a href="http://plugins.jetbrains.com/plugin/7219?pr=phpStorm">http://plugins.jetbrains.com/plugin/7219?pr=phpStorm</a></p>\n<p>\n	Устанавливаем его (File-&gt;Settings-&gt;Plugins, кнопка Install From Disk)</p>\n<p>\n	Перезапуcкаем PhpStorm. Идем в File-&gt;Settings-&gt;Symfony2 Plugin, ставим галку на Enable Plugin, проверяем пути (у меня var/cache/dev/appDevUrlGenerator.php и var/cache/dev/translations), в Container добавляем путь.</p>\n<p>\n	Ввводим команду php bin/warmup_cache</p>', 'phpStorm, Symfony2', 'Интеграции поддержки Symfony2 в phpStorm', '2013-08-10 10:14:05', NULL),
(9, 1, 6, NULL, 1, 1, 'Ссылки на Symfony2', 'fourth', 'Ссылки на полезную литературу по Symfony2', '<p></p>\n<hr id="readmore" /><p>Работа с контейнером сервисов: <a href="http://symfony.com/doc/current/book/service_container.html" target="_blank">http://symfony.com/doc/current/book/service_container.html</a></p>\n<p>Поиск бандлов для Symfony2: на сайте <a href="http://knpbundles.com/" target="_blank">KnpBundles</a></p>', NULL, NULL, '2013-08-10 10:14:05', NULL),
(10, 1, 4, NULL, 1, 1, 'Установка Memcached на Windows 7 x64 (php 5.4.17)', 'installation_memcached_on_windows7_x64_php_5_4_17', 'Встала задача поставить себе memcached. В интернете есть много мануалов, но они в основном под 32-разрядные версии. Т.к. у меня 64-разрядный php, то возникли определенные трудности…', '<p>&nbsp;</p>\r\n<hr id="readmore" />\r\n<p>Начать с того, что 64-раздяную версию самого &nbsp;memcache найти не так-то просто. На официальном сайт лежат сырые исходники: <a href="http://code.google.com/p/memcached/downloads/list">http://code.google.com/p/memcached/downloads/list</a></p>\r\n<p>Компилировать их показалось задачей сложной и страшной. После интенсивного поиска в гугле нашел вот <a href="http://s3.amazonaws.com/downloads.northscale.com/memcached-win64-1.4.4-14.zip">тут</a> файлы версии 1.4.4-14 под Windows x64. Версия устаревшая, но выхода у меня не было (гугл показывал еще более старые версия), скачал себе эту.</p>\r\n<p>Создал на диске себе папку <strong>memcached</strong> &nbsp;и распаковал туда архив. Далее запустил командную строку (от имени Администратора!) и выполнил</p>\r\n<pre class="brush: bash;">	C:\\memcached\\memcached.exe -d install</pre>\r\n<p>Пошел смотреть в Службы, как встал memcached (Панель управления-&gt;Администрирование-&gt;Службы) &ndash; служба с таким именем появилась. Запустил её, в свойствах прописал автоматический запуск.</p>\r\n<p>Осталось только подключиться к php. После поисков нашел тут: <a href="http://www.mediafire.com/download/8d3vd26z3fg6bf1/php_memcache-svn20120301-5.4-VC9-x64.zip">http://www.mediafire.com/download/8d3vd26z3fg6bf1/php_memcache-svn20120301-5.4-VC9-x64.zip</a> - похожее на нужную версию.</p>\r\n<p>Однако при копировании вдруг обнаружил, что расширение (у меня php 5.4.17) php_memcache.dll уже есть&hellip; Решил, что &laquo;из коробки&raquo; будет надежнее.</p>\r\n<p>Прописал в php.ini в разделе с расширениями</p>\r\n<pre class="brush: ini;">	[PHP_MEMCACHED]\r\n	extension = php_memcache.dll</pre>\r\n<p>Перезапустил апач, убедился, что php_info() вывел memcache</p>\r\n<p>Запустил тестовый файлик, ничего не сломалось.&nbsp; Ну посмотрим, как дальше себя поведет php&hellip;</p>\r\n<p>P.S. Так файлы на просторах интернета имеет тенденцию теряться (сколько я нерабочих ссылок сегодня нашел!), то прикладываю свой архивчик: <a href="/media/memcached.zip">скачать</a></p>', 'php 5.4, Memcached, Windows 7 x64', 'Как установить поддержку Memcached php 5.4 на Windows 7 x64', '2013-08-27 19:38:21', '2014-02-09 09:44:07'),
(11, 1, 8, NULL, 1, 1, 'Debain7 – горячие команды сервера', 'debain7_hot_commands_of_the_server', 'Тут собрал команды, которые все время приходится использовать на сервере (ОС – Debain7)', '<hr id="readmore" />\r\n<p>Запуск apache:</p>\r\n<pre class="brush: bash;">/etc/init.d/apache2 start</pre>\r\n<p>Остановка apache:</p>\r\n<pre class="brush: bash">/etc/init.d/apache2 stop</pre>\r\n<p>Перезапуск apache:</p>\r\n<pre class="brush: bash">/etc/init.d/apache2 restart</pre>', 'Debain7, команды', 'Часто используемые команды в Debain7', '2013-08-29 22:09:51', '2014-02-09 09:40:52'),
(12, 1, 10, NULL, 1, 1, 'CSS – линейный градиент фона', 'css_linear_gradient_of_background', 'Как сделать градиент фону, не прибегая к помощи фоновых рисунков? Современные браузеры поддерживают градиентную заливку с помощью CSS.', '<p></p>\n<hr id="readmore" />\n<div class="highlight">\n	<pre class="brush: css">\nbackground:#EFEFEF; /*цвет фона кнопки для браузеров без поддержки CSS3*/\nbackground: -webkit-gradient(linear, left top, left bottom, from(#3437CD), to(#538BFF)); /* для Webkit браузеров */\nbackground: -moz-linear-gradient(top,  #3437CD, #538BFF); /* для Firefox */\nbackground-image: -o-linear-gradient(top,  #3437CD,  #538BFF); /* для Opera 11 */\nfilter:  progid:DXImageTransform.Microsoft.gradient(startColorstr=&#39;#3437CD&#39;, endColorstr=&#39;#538BFF&#39;); /* фильтр для IE */\n\n</pre>\n</div>\n<p>\n	Чтобы сохранить&nbsp; кроссбраузерность, приходиться писать под каждый интернет-браузер отдельное правило CSS. Особо обрабатывается IE.&nbsp; В каждом правиле участвует два цвета &ndash; начальный и конечный.</p>\n', 'градиент фона, css', 'Создание градиента без помощи фоновых рисунков', '2012-02-25 17:03:11', NULL),
(13, 1, 12, NULL, 1, 1, 'Подключаем Twitter Bootstrap к Yii', 'connect_twitter_bootstrap_to_yii', 'Совсем недавно мне рассказали о такой классной вещи, как фреймворк css от Твиттера - Twitter Bootstrap. Раньше, максимум, что я использовал - это "reset css". Все остальное писал ручками. Каждый раз - одно и то же. Что, понятно, отрицательно сказывалось на производительности. Немного поработав с этим фреймворком (оформление админки на Симфони 2) - пришел к выводу, что вещь это безусловно полезная. Реально ускоряет работу в разы. И вот я решил перевести и свой блог на Yii к этому же виду.', '<p>&nbsp;</p>\r\n<hr id="readmore" />\r\n<p>Перво-наперво скачал сам Twitter Bootstrap с гитхаба: <a href="https://github.com/twitter/bootstrap">https://github.com/twitter/bootstrap</a>. Т.е я качал вместе с исходниками на языку less, т.к. планировал самостоятельно компилировать из них css. Вы же может скачать уже скомпилированные файлы, например, отсюда: <a href="http://bootstrap.veliovgroup.com/">http://bootstrap.veliovgroup.com/</a> Но в этом случае уже нельзя будет изменять расцветку ну и вообще вносить изменения&hellip; В общем, я остановился на сырых исходниках.</p>\r\n<p>Компилировать исходники less я решил с помощью расширения Yii-less: <a href="http://www.yiiframework.com/extension/yii-less/">http://www.yiiframework.com/extension/yii-less/</a></p>\r\n<p>Скачиваем данное расширение, ложем его в папку protected/extensions. В конфиге регистрируем новый&nbsp; behaviors:</p>\r\n<pre class="brush: php;">	''behaviors''=&gt;array(\r\n	    ''ext.yii-less.components.LessCompilationBehavior'',\r\n	)\r\n</pre>\r\n<p>Регистрируем расширение как компонент:</p>\r\n<pre class="brush: php;">''components''=&gt;array(\r\n  ''lessCompiler''=&gt;array(\r\n    ''class''=&gt;''ext.yii-less.components.LessCompiler'',\r\n    ''paths''=&gt;array(\r\n      // you can access to the compiled file on this path\r\n      ''/css/bootstrap.css'' =&gt; array(\r\n        ''precompile'' =&gt; true, // whether you want to cache the generation\r\n        ''paths'' =&gt; array(''/less/bootstrap.less'') //paths of less files. you can specify multiple files.\r\n      ),\r\n    ),\r\n  ),\r\n),\r\n</pre>\r\n<p>&nbsp;</p>\r\n<p>И в лайоте пишем Yii::app()-&gt;clientScript-&gt;registerCssFile(''/css/bootstrap.css'')</p>\r\n<p>Все, теперь при первом запуске в нашем ассете будет новый файл. Как альтернатива &ndash; можно компилировать файлы на стороне клиента (<a href="https://github.com/cloudhead/less.js">https://github.com/cloudhead/less.js</a>)&nbsp; &ndash; но, на мой взгляд, это сильно скажется на производительности&hellip;.</p>\r\n<p>Одной проблемой меньше. Остался вопрос с подсветкой кода на less. Мой редактор (NetBeans) по умолчанию не распознает less. Исправляем это с помощь плагина scss-editor <a href="http://code.google.com/p/scss-editor/">http://code.google.com/p/scss-editor/</a></p>\r\n<ol>\r\n<li>Качаем плагин, ставим его в NetBeans</li>\r\n<li>Ассоциируем с ним файлы Less &ndash; Сервис -&gt;Параметры -&gt;Файлы,&nbsp; создаем новое расширение less и в списке &laquo;Связанный тип файлов&raquo; задаем ему &laquo;text/x-scss&raquo;</li>\r\n</ol>\r\n<p style="margin-left: 18.0pt;">Перезапускаем NetBeans &ndash; и подсветка появилась!</p>\r\n<p style="margin-left: 18.0pt;">Напоследок замечу, что для Yii есть готовое решение в виде расширения yii-bootstrap: <a href="http://www.cniska.net/yii-bootstrap/" target="_blank">http://www.cniska.net/yii-bootstrap/</a> - но я его не пробовал. Лень разбираться&hellip;</p>\r\n<p><strong>UPD</strong> На Symfony2 этот же дизайн встал без проблем</p>', 'yii, twitter bootstap, подключение', 'Как подключить Twitter Bootstrap к Yii', '2013-01-29 17:42:26', '2014-02-09 09:56:53'),
(14, 1, 13, NULL, 1, 1, 'Создаем формы в Visual Studio 2012', 'create_forms_in_visual_sStudio_2012', 'Сегодня решил повозиться с Microsoft Visual Studio 2012 C++ - попробовать создать свою форму. Начал искать компоненты (как в Delphi) - но нигде их не нашел!', '<p></p>\n<hr id="readmore" />\n<p>\n	Погуглил и понял, что именно в 2012 версии, именно для языка C++ разработчики решили убрать поддержку Windows Forms Application. На просторах буржуйского интернета нашел замечательное решение. Нужно скачать шаблон <a href="http://dmitxe.ru/media/VS2012CPPWinForms.zip">http://dmitxe.ru/media/VS2012CPPWinForms.zip</a> и скопировать его в C:\\Program Files (x86)\\Microsoft Visual Studio 11.0\\VC\\vcprojects\\vcNET\\ - при этом лучше на всякий случай сделать бэкап файла &quot;vcNET.vsdir&quot;. Использование: Файл-&gt;Проект-&gt;Шаблоны-&gt;Visual C++ -&gt; CLR-&gt;MC++ WinApp</p>\n<p>\n	Источник:&nbsp; <a href="http://www.t-hart.org/vs2012/">http://www.t-hart.org/vs2012/</a></p>\n', 'форма, Visual Studio 2012 C++', 'Как создать приложение Windows Forms Application в Visual Studio 2012 C++', '2013-06-06 20:31:23', NULL),
(15, 1, 14, NULL, 1, 1, 'NotePad++', 'notepad_plus_plus', 'Часто возникает необходимость быстрой перекодировки файла (например, из ansi в utf8, или наоборот). Есть замечательный (и притом бесплатный) редактор - NotePad++. С помощью него можно легко перекодировать файл из одной кодировки в другую. В этом редакторе есть даже подсветка кода. Конечно, я предпочитаю работать где-нибудь в Adobe Dreamweaver, NuSphere PHPED или в NetBeans. Но эти монстры подолгу грузятся, а иногда хочется быстро подправить код и тут же закрыть файл. Для этого как раз подойдёт NotePad++', '<p></p>\n<hr id="readmore" />\n<p>\n	Есть одна особенность перекодирования в utf8. Для преобразования кодировки&nbsp; файла выбираем в меню &laquo;Кодировки&raquo;-&gt; &laquo;Преобразовать в utf8&nbsp; без BOM&raquo;. Если выбрать просто &laquo;Преобразовать в utf8&raquo;, тогда случиться трагедия &ndash; страница перестанет правильно отображаться в браузере. Преобразование в ANSI таких проблем не имеет &ndash; есть только одно действие.<br />\n	Программа качается <a href="http://notepad-plus-plus.org/download/" target="_blank">отсюда</a>.<br />\n	&nbsp;</p>\n', 'редактор, кодировка', 'Как перекодировать файл с помощью NotePad++', '2012-02-25 15:34:43', NULL),
(16, 1, 15, NULL, 1, 1, 'Что выбрать: фреймворк или CMS', 'framework_vs_cms', 'Свое знакомство с сайтостроением я начал с написания простейшего кода на HTML. Сайт получился, естественно, статическим. Следующий проект делал уже на PHP. Времени на написание ушло много, в результате у меня начала создаваться собственная CMS. К сожалению, данный факт осмыслил не сразу. А как только понял, что приду к CMS, решил не изобретать велосипед, освоил Joomla и WordPress.', '<p></p>\n<hr id="readmore" />\n<p>\n	&nbsp;Разработка стандартных сайтов (блогов, форумов и т.д.) пошла на ура. Но вся проблема оказалась в том, что многим заказчикам нужна некая особая, нестандартная функциональность. Реализовать которую в рамках данной CMS оказывается совсем непросто. Приходиться писать новые расширения или модифицировать существующий код. Времени такая работа занимает много, к тому же из-за взаимодействия с ядром CMS код не оптимальный. В общем, встал вопрос &ndash; что же проще &ndash; писать свою CMS или мучиться с существующими.</p>\n<p>\n	И тут я вспомнил о фреймворках. &nbsp;Фреймворк &ndash; это каркас для веб-приложения, а CMS &ndash; готовая система управления контентом. Наверное, можно фреймворк можно сравнить с кирпичами, из которых можно построить самые причудливые строения, а CMS &ndash; это стандартный дом.</p>\n<p>\n	После обзора самых популярных фреймворков я остановил свой выбор на Yii. Понравился достаточно строгий подход, относительная простота изучения (конечно, CodeIgniter осваивается легче, но возможности Yii богаче).</p>\n<p>\n	Теперь написать собственную, уникальную CMS стало гораздо проще. Конечно, стандартные проекты быстрее реализовать на готовой CMS, но многие проекты имею тенденцию превращаться из стандартных в нестандартные.</p>\n<p>\n	Этот блог я написал на Yii. А вот другой мой блог &ndash; netopus.ru написан CMS WordPress. Использовалась одна из бесплатных тем для WordPress.</p>\n<p><b>UPD</b> В сентябре 2013 года блог перешел на Symfony2 (движок SmartCore)</p>\n', 'фреймворк, CMS, выбор', 'Преимущества и недостатки фреймворка над CMS', '2011-11-23 13:15:19', NULL),
(17, 1, 6, NULL, 1, 1, 'Twig в Symfony2: работа с датой и временем.', 'twig_in_symfony2_work_with_date_and_time', 'Поначалу возник недоуменный вопрос: как в twig отдать дату в нужном формате? Неужели дату можно форматировать только в контролере? Но погуглив, нашел ответы на свои вопросы.', '<p>&nbsp;</p>\r\n<hr id="readmore" />\r\n<p>Форматирование даты:</p>\r\n<pre class="brush: php;">	var_date|date("d.m.y")\r\n</pre>\r\n<p>Получение текущей даты:</p>\r\n<pre class="brush: php;">	"new"|date("d.m.y")\r\n</pre>\r\n<p>Интернационализация:</p>\r\n<p>1. Подключаем сервис в конфиге Symfony2</p>\r\n<pre class="brush: yaml;">	services:\r\n        twig_extension.intl:\r\n            class: Twig_Extensions_Extension_Intl\r\n            tags: [{ name: "twig.extension" }]\r\n</pre>\r\n<p>2. Пример вызова</p>\r\n<pre class="brush: twig;">	{{ item.date|localizeddate("none", "none", null, null, "dd. LLLL YYYY") }}\r\n</pre>', 'Symfony2, Twig, дата и время', 'Symfony2 работа с датой и временем из Twig', '2013-09-05 18:19:56', '2014-02-09 09:36:49'),
(18, NULL, NULL, NULL, 1, 1, 'Проверка на изменение сущности', 'entity_change', NULL, '/* @var $em EntityManager */<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $uow = $em-&gt;getUnitOfWork();<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $uow-&gt;computeChangeSets();<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $r = $uow-&gt;getEntityChangeSet($entity);<br /><br />', NULL, NULL, '2014-03-05 19:35:31', '2014-11-07 01:25:37');

-- --------------------------------------------------------

--
-- Структура таблицы `blog_articles_tags_relations`
--

DROP TABLE IF EXISTS `blog_articles_tags_relations`;
CREATE TABLE IF NOT EXISTS `blog_articles_tags_relations` (
  `article_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `IDX_512A6F437294869C` (`article_id`),
  KEY `IDX_512A6F43BAD26311` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `blog_articles_tags_relations`
--

INSERT INTO `blog_articles_tags_relations` (`article_id`, `tag_id`) VALUES
(3, 2),
(3, 3),
(4, 3),
(4, 4),
(4, 5),
(5, 3),
(5, 6),
(5, 7),
(6, 8),
(6, 9),
(7, 10),
(8, 8),
(8, 11),
(9, 8),
(10, 12),
(10, 13),
(11, 9),
(11, 14),
(12, 15),
(12, 16),
(13, 3),
(13, 5),
(13, 17),
(14, 18),
(14, 19),
(15, 20),
(15, 21),
(16, 22),
(16, 23),
(16, 24),
(17, 7),
(17, 8),
(17, 25),
(18, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(10) unsigned DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_DC356481989D9B62` (`slug`),
  KEY `IDX_DC3564813D8E604F` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `parent`, `slug`, `title`, `created_at`) VALUES
(3, 4, 'yii', 'Yii', '2014-02-08 20:57:52'),
(4, 5, 'php', 'PHP', '2014-02-08 20:57:52'),
(5, NULL, 'programing', 'Программирование', '2014-02-08 20:57:52'),
(6, 4, 'symfony2', 'Symfony2', '2014-02-08 20:57:52'),
(7, 5, 'js', 'JavaScript', '2014-02-08 20:57:52'),
(8, 9, 'debian', 'Debian', '2014-02-08 20:57:52'),
(9, NULL, 'os', 'Операционные системы', '2014-02-08 20:57:52'),
(10, 11, 'css', 'CSS', '2014-02-08 20:57:52'),
(11, NULL, 'imposition', 'Верстка', '2014-02-08 20:57:52'),
(12, 10, 'twitter_bootstrap', 'Twitter Bootstrap', '2014-02-08 20:57:52'),
(13, 5, 'cpp', 'C++', '2014-02-08 20:57:52'),
(14, NULL, 'soft', 'Программы (софт)', '2014-02-08 20:57:52'),
(15, NULL, 'other', 'Другое', '2014-02-08 20:57:52');

-- --------------------------------------------------------

--
-- Структура таблицы `blog_tags`
--

DROP TABLE IF EXISTS `blog_tags`;
CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8F6C18B6989D9B62` (`slug`),
  UNIQUE KEY `UNIQ_8F6C18B62B36786B` (`title`),
  KEY `IDX_8F6C18B67CD5541` (`weight`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

--
-- Дамп данных таблицы `blog_tags`
--

INSERT INTO `blog_tags` (`id`, `slug`, `title`, `created_at`, `weight`) VALUES
(2, 'breadcrumbs', 'Breadcrumbs', '2014-02-08 20:57:52', 0),
(3, 'yii', 'Yii', '2014-02-08 20:57:52', 0),
(4, 'ckeditor', 'Ckeditor', '2014-02-08 20:57:52', 0),
(5, 'connect', 'Подключение', '2014-02-08 20:57:52', 0),
(6, 'formatting', 'Форматирование', '2014-02-08 20:57:52', 0),
(7, 'date_and_time', 'Дата и время', '2014-02-08 20:57:52', 0),
(8, 'symfony2', 'Symfony2', '2014-02-08 20:57:52', 0),
(9, 'commands', 'Консольные команды', '2014-02-08 20:57:52', 0),
(10, 'code_illumination', 'Подсветка кода', '2014-02-08 20:57:52', 0),
(11, 'phpstorm', 'phpStorm', '2014-02-08 20:57:52', 0),
(12, 'php', 'PHP', '2014-02-08 20:57:52', 0),
(13, 'memcached', 'Memcached', '2014-02-08 20:57:52', 0),
(14, 'debian', 'Debian', '2014-02-08 20:57:52', 0),
(15, 'css', 'CSS', '2014-02-08 20:57:52', 0),
(16, 'linear_gradient', 'Линейный градиент', '2014-02-08 20:57:52', 0),
(17, 'twitter_bootstrap', 'Twitter Bootstrap', '2014-02-08 20:57:52', 0),
(18, 'forms', 'Формы', '2014-02-08 20:57:52', 0),
(19, 'visual_sStudio_2012_cpp', 'Visual Studio 2012 C++', '2014-02-08 20:57:52', 0),
(20, 'editor', 'Редактор', '2014-02-08 20:57:52', 0),
(21, 'encoding', 'Кодировка', '2014-02-08 20:57:52', 0),
(22, 'framework', 'Фреймворк', '2014-02-08 20:57:52', 0),
(23, 'cms', 'CMS', '2014-02-08 20:57:52', 0),
(24, 'select', 'Выбор', '2014-02-08 20:57:52', 0),
(25, 'twig', 'Twig', '2014-02-08 20:57:52', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `elfinder_file`
--

DROP TABLE IF EXISTS `elfinder_file`;
CREATE TABLE IF NOT EXISTS `elfinder_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longblob NOT NULL,
  `size` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  `mime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `read` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `write` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locked` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `elfinder_file`
--


-- --------------------------------------------------------

--
-- Структура таблицы `engine_appearance_history`
--

DROP TABLE IF EXISTS `engine_appearance_history`;
CREATE TABLE IF NOT EXISTS `engine_appearance_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9078E776D1B862B8` (`hash`),
  KEY `IDX_9078E776B548B0F` (`path`),
  KEY `IDX_9078E7763C0BE965` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `engine_appearance_history`
--


-- --------------------------------------------------------

--
-- Структура таблицы `engine_folders`
--

DROP TABLE IF EXISTS `engine_folders`;
CREATE TABLE IF NOT EXISTS `engine_folders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder_pid` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_file` tinyint(1) NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `uri_part` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `description` longtext COLLATE utf8_unicode_ci,
  `meta` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `redirect_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `router_node_id` int(11) DEFAULT NULL,
  `permissions` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `lockout_nodes` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `template_inheritable` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `template_self` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6C047E64A640A07B79628CD` (`folder_pid`,`uri_part`),
  KEY `IDX_6C047E64A640A07B` (`folder_pid`),
  KEY `IDX_6C047E641B5771DD` (`is_active`),
  KEY `IDX_6C047E64FD07C8FB` (`is_deleted`),
  KEY `IDX_6C047E64462CE4F5` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `engine_folders`
--

INSERT INTO `engine_folders` (`id`, `folder_pid`, `title`, `is_file`, `position`, `uri_part`, `is_active`, `is_deleted`, `description`, `meta`, `redirect_to`, `router_node_id`, `permissions`, `lockout_nodes`, `template_inheritable`, `user_id`, `created_at`, `template_self`, `deleted_at`) VALUES
(1, NULL, 'Главная', 0, 0, NULL, 1, 0, ':)', 'a:4:{s:8:"keywords";s:3:"cms";s:11:"description";s:3:"cms";s:6:"robots";s:3:"all";s:6:"author";s:10:"Артём";}', NULL, NULL, NULL, NULL, 'main', 1, '2013-03-19 00:44:38', NULL, NULL),
(2, 1, 'О компании', 0, 10, 'about', 1, 0, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, 'inner', 1, '2013-03-11 16:42:33', NULL, NULL),
(3, 1, 'Аккаунт пользователя', 0, 255, 'user', 1, 0, NULL, 'a:0:{}', NULL, 7, 'N;', 'N;', NULL, 1, '2013-03-18 01:15:06', NULL, NULL),
(4, 8, 'Вложенная', 0, 0, 'under_news', 1, 0, NULL, 'a:0:{}', NULL, NULL, 'N;', 'N;', NULL, 1, '2013-03-18 01:15:27', NULL, NULL),
(5, 1, 'Так просто ;)', 0, 3, 'simple', 1, 0, NULL, 'N;', NULL, NULL, 'N;', 'N;', 'main', 1, '2013-03-19 04:43:50', NULL, NULL),
(6, 2, 'Вложенная папка', 0, 0, 'inner', 1, 0, NULL, 'a:0:{}', NULL, NULL, 'N;', 'N;', NULL, 1, '2013-03-19 04:47:22', NULL, NULL),
(7, 1, '22222222222222', 0, 10, '22222222', 0, 0, '22', 'N;', NULL, NULL, 'N;', 'N;', NULL, 1, '2013-08-10 11:14:06', NULL, NULL),
(8, 1, 'Новости', 0, 0, 'news', 1, 0, NULL, 'a:0:{}', NULL, 12, 'N;', 'N;', NULL, 1, '2013-12-22 21:45:42', NULL, NULL),
(11, 6, 'Еще одна вложенная', 0, 0, 'in2', 1, 0, NULL, 'a:0:{}', NULL, NULL, 'N;', 'N;', NULL, 1, '2014-01-29 10:30:42', NULL, NULL),
(12, 1, 'Слайдер', 0, 0, 'slider', 1, 0, NULL, 'N;', NULL, NULL, 'N;', 'N;', NULL, 1, '2014-01-30 20:38:12', NULL, NULL),
(13, 1, 'Блог', 0, 0, 'blog', 1, 0, NULL, 'N;', NULL, 22, 'N;', 'N;', NULL, 1, '2014-02-07 18:01:54', NULL, NULL),
(14, 12, 'Nivo', 0, 0, 'nivo', 1, 0, NULL, 'N;', NULL, NULL, 'N;', 'N;', NULL, 1, '2014-02-10 07:55:59', NULL, NULL),
(15, 1, 'Каталог', 0, 0, 'catalog', 1, 0, NULL, 'a:0:{}', NULL, 28, 'N;', 'N;', NULL, 1, '2014-02-12 16:12:18', NULL, NULL),
(16, 1, 'Блог на юникате', 0, 0, 'unicat_blog', 1, 0, NULL, 'a:0:{}', NULL, 32, 'N;', 'N;', NULL, 1, '2014-07-01 13:34:57', NULL, NULL),
(17, 1, 'Фотогалерея', 0, 0, 'gallery', 1, 0, NULL, 'a:0:{}', NULL, 31, 'N;', 'N;', NULL, 1, '2014-07-15 03:28:01', NULL, NULL),
(18, 1, 'Веб-форма', 0, 0, 'web-form', 1, 0, NULL, 'a:0:{}', NULL, 34, 'N;', 'N;', NULL, 1, '2015-03-24 03:17:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `engine_modules`
--

DROP TABLE IF EXISTS `engine_modules`;
CREATE TABLE IF NOT EXISTS `engine_modules` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `engine_modules`
--

INSERT INTO `engine_modules` (`id`, `is_enabled`, `created_at`, `name`, `title`, `description`, `class`) VALUES
(1, 1, '2015-02-18 05:20:34', 'Blog', 'Блог', NULL, '\\SmartCore\\Module\\Blog\\BlogModule');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_nodes`
--

DROP TABLE IF EXISTS `engine_nodes`;
CREATE TABLE IF NOT EXISTS `engine_nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder_id` int(10) unsigned DEFAULT NULL,
  `region_id` int(10) unsigned DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `position` smallint(6) DEFAULT '0',
  `priority` smallint(6) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `is_cached` tinyint(1) NOT NULL,
  `template` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `controls_in_toolbar` smallint(6) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `is_use_eip` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_3055D1B7FD07C8FB` (`is_deleted`),
  KEY `IDX_3055D1B7162CB942` (`folder_id`),
  KEY `IDX_3055D1B71B5771DD` (`is_active`),
  KEY `IDX_3055D1B7462CE4F5` (`position`),
  KEY `IDX_3055D1B798260155` (`region_id`),
  KEY `IDX_3055D1B7C242628` (`module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

--
-- Дамп данных таблицы `engine_nodes`
--

INSERT INTO `engine_nodes` (`id`, `folder_id`, `region_id`, `is_active`, `module`, `params`, `position`, `priority`, `description`, `user_id`, `created_at`, `is_cached`, `template`, `controls_in_toolbar`, `is_deleted`, `deleted_at`, `is_use_eip`) VALUES
(1, 1, 4, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:1;s:6:"editor";b:1;}', 20, 0, 'Футер', 1, '2013-03-20 05:46:40', 0, NULL, 0, 0, NULL, 1),
(2, 2, 5, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:4;s:6:"editor";b:1;}', 0, 1, 'Правая колонка', 1, '2013-03-20 09:07:33', 0, NULL, 1, 0, NULL, 1),
(3, 2, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:3;s:6:"editor";b:1;}', 0, 2, 'Левая колонка', 1, '2013-03-21 06:03:37', 0, NULL, 1, 0, NULL, 1),
(4, 1, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:2;s:6:"editor";b:1;}', 0, 0, 'Главная', 1, '2013-03-11 16:42:33', 0, NULL, 1, 0, NULL, 1),
(5, 1, 3, 1, 'Menu', 'a:6:{s:5:"depth";N;s:8:"group_id";i:1;s:9:"css_class";s:9:"main_menu";s:20:"selected_inheritance";b:0;s:13:"current_class";N;s:7:"menu_id";i:1;}', 1, 0, NULL, 1, '2013-03-11 16:42:33', 1, NULL, 0, 0, NULL, 1),
(6, 1, 2, 1, 'Breadcrumbs', 'a:2:{s:9:"delimiter";s:2:"»";s:17:"hide_if_only_home";b:1;}', 0, -255, NULL, 1, '2013-03-11 16:42:33', 0, NULL, 0, 0, NULL, 1),
(7, 3, 1, 1, 'User', 'a:2:{s:18:"allow_registration";b:1;s:24:"allow_password_resetting";b:1;}', 0, 255, NULL, 1, '2013-03-11 16:42:33', 0, NULL, 0, 0, NULL, 1),
(9, 3, 3, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:6;s:6:"editor";b:1;}', 1, 0, 'Текст под меню', 1, '2013-03-25 21:53:12', 0, NULL, 0, 0, NULL, 1),
(10, 7, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:7;s:6:"editor";b:1;}', 0, 0, NULL, 1, '2013-08-10 11:14:55', 0, NULL, 0, 0, NULL, 1),
(11, 5, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:8;s:6:"editor";b:1;}', 0, 0, NULL, 1, '2013-12-20 20:11:41', 0, NULL, 1, 0, NULL, 1),
(12, 8, 1, 1, 'SimpleNews', 'a:1:{s:14:"items_per_page";i:3;}', 1, 0, NULL, 1, '2013-12-22 21:58:57', 0, NULL, 1, 0, NULL, 1),
(13, 1, 6, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:9;s:6:"editor";b:1;}', 0, 0, NULL, 1, '2014-01-20 03:47:18', 0, NULL, 0, 0, NULL, 1),
(15, 8, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:10;s:6:"editor";b:1;}', 0, 0, 'Текст над новостями', 1, '2014-01-22 19:02:27', 0, NULL, 0, 0, NULL, 1),
(16, 6, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:11;s:6:"editor";b:1;}', 0, 0, NULL, 1, '2014-01-29 10:01:55', 0, NULL, 1, 0, NULL, 1),
(17, 1, 1, 1, 'Widget', 'a:5:{s:7:"node_id";s:2:"12";s:10:"controller";s:15:"NewsWidget:last";s:6:"params";s:8:"count: 3";s:8:"open_tag";s:48:"<hr /><h3>Последние новости</h3>";s:9:"close_tag";N;}', 10, 0, NULL, 1, '2014-01-29 18:27:59', 0, NULL, 0, 0, NULL, 1),
(18, 1, 3, 0, 'Texter', 'a:2:{s:12:"text_item_id";i:12;s:6:"editor";b:1;}', 9, 0, 'Последние новости', 1, '2014-01-29 19:43:16', 0, NULL, 0, 0, NULL, 1),
(19, 1, 3, 0, 'Texter', 'a:2:{s:12:"text_item_id";i:13;s:6:"editor";b:1;}', 0, 0, 'Надпись над меню', 1, '2014-01-29 19:45:52', 0, NULL, 0, 0, NULL, 1),
(20, 11, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:14;s:6:"editor";b:1;}', 0, 0, NULL, 1, '2014-01-29 20:16:33', 0, NULL, 1, 0, NULL, 1),
(21, 12, 1, 1, 'Slider', 'a:1:{s:9:"slider_id";i:1;}', 0, 0, 'Цветочки!', 1, '2014-01-30 20:38:27', 0, NULL, 1, 0, NULL, 1),
(22, 13, 1, 1, 'Blog', 'a:0:{}', 0, 0, NULL, 1, '2014-02-07 18:02:37', 0, NULL, 1, 0, NULL, 1),
(23, 13, 3, 1, 'Widget', 'a:5:{s:7:"node_id";s:2:"22";s:10:"controller";s:19:"BlogWidget:tagCloud";s:6:"params";N;s:8:"open_tag";s:34:"<hr /><h4>Тэги блога</h4>";s:9:"close_tag";N;}', 30, 0, 'Тэги блога', 1, '2014-02-07 22:55:10', 0, NULL, 0, 0, NULL, 1),
(24, 1, 3, 0, 'Texter', 'a:2:{s:12:"text_item_id";i:15;s:6:"editor";b:1;}', 19, 0, NULL, 1, '2014-02-08 21:01:35', 0, NULL, 0, 0, NULL, 1),
(25, 4, 1, 1, 'Texter', 'a:2:{s:12:"text_item_id";i:16;s:6:"editor";b:1;}', 29, 0, NULL, 1, '2014-02-08 21:04:03', 0, NULL, 1, 0, NULL, 1),
(26, 13, 3, 1, 'Widget', 'a:5:{s:7:"node_id";s:2:"22";s:10:"controller";s:23:"BlogWidget:categoryTree";s:6:"params";N;s:8:"open_tag";s:44:"<hr /><h4>Категории блога</h4>";s:9:"close_tag";N;}', 20, 0, 'Категории блога', 1, '2014-02-08 21:04:50', 0, NULL, 0, 0, NULL, 1),
(27, 14, 1, 1, 'Slider', 'a:1:{s:9:"slider_id";i:6;}', 0, 0, 'Nivo', 1, '2014-02-10 08:13:18', 0, NULL, 1, 0, NULL, 1),
(28, 15, 1, 1, 'Unicat', 'a:2:{s:13:"repository_id";i:1;s:16:"configuration_id";i:1;}', 0, 0, NULL, 1, '2014-02-12 16:23:22', 0, NULL, 1, 0, NULL, 1),
(29, 15, 3, 1, 'Widget', 'a:5:{s:7:"node_id";s:2:"28";s:10:"controller";s:25:"UnicatWidget:categoryTree";s:6:"params";s:12:"structure: 1";s:8:"open_tag";s:50:"<hr /><h4>Категории каталога</h4>";s:9:"close_tag";N;}', 0, 0, 'Виджет категорий каталога', 1, '2014-03-06 12:24:51', 0, NULL, 0, 0, NULL, 1),
(30, 16, 1, 1, 'Catalog2', 'a:1:{s:13:"repository_id";i:3;}', 0, 0, 'Тесты с каталогом', 1, '2014-07-01 13:42:20', 0, NULL, 0, 1, '2015-03-02 03:42:04', 1),
(31, 17, 1, 1, 'Gallery', 'a:1:{s:10:"gallery_id";i:1;}', 0, 0, NULL, 1, '2014-07-15 03:38:38', 0, NULL, 1, 0, NULL, 1),
(32, 16, 1, 1, 'Unicat', 'a:1:{s:16:"configuration_id";i:2;}', 0, 0, NULL, 1, '2015-03-02 11:37:36', 0, NULL, 1, 0, NULL, 1),
(33, 16, 3, 1, 'Widget', 'a:5:{s:7:"node_id";s:2:"32";s:10:"controller";s:25:"UnicatWidget:categoryTree";s:6:"params";s:12:"structure: 3";s:8:"open_tag";s:44:"<hr /><h4>Категории блога</h4>";s:9:"close_tag";N;}', 0, 0, NULL, 1, '2015-03-02 11:58:06', 0, NULL, 1, 0, NULL, 1),
(34, 18, 1, 1, 'WebForm', 'a:1:{s:10:"webform_id";i:1;}', 0, 0, NULL, 1, '2015-03-24 03:18:10', 0, NULL, 1, 0, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `engine_regions`
--

DROP TABLE IF EXISTS `engine_regions`;
CREATE TABLE IF NOT EXISTS `engine_regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position` smallint(6) DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `description` longtext,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3054D4985E237E06` (`name`),
  KEY `IDX_3054D498462CE4F5` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `engine_regions`
--

INSERT INTO `engine_regions` (`id`, `position`, `name`, `description`, `user_id`, `created_at`) VALUES
(1, 0, 'content', 'Рабочая область', 1, '2013-03-11 01:09:17'),
(2, 2, 'breadcrumbs', 'Хлебные крошки', 1, '2013-03-11 01:09:33'),
(3, 1, 'main_menu', 'Навигационное меню', 1, '2013-03-11 04:00:50'),
(4, 3, 'footer', 'Футер', 1, '2013-03-11 04:01:30'),
(5, 5, 'right_column', 'Правая колонка', 1, '2013-03-23 23:46:01'),
(6, 3, 'footer_right', 'Футер справа', 1, '2014-01-20 04:04:24');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_regions_inherit`
--

DROP TABLE IF EXISTS `engine_regions_inherit`;
CREATE TABLE IF NOT EXISTS `engine_regions_inherit` (
  `region_id` int(10) unsigned NOT NULL,
  `folder_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`region_id`,`folder_id`),
  KEY `IDX_41BBC12298260155` (`region_id`),
  KEY `IDX_41BBC122162CB942` (`folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `engine_regions_inherit`
--

INSERT INTO `engine_regions_inherit` (`region_id`, `folder_id`) VALUES
(2, 1),
(3, 1),
(4, 1),
(6, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `engine_roles`
--

DROP TABLE IF EXISTS `engine_roles`;
CREATE TABLE IF NOT EXISTS `engine_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `position` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9B56FA8C5E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `engine_roles`
--

INSERT INTO `engine_roles` (`id`, `name`, `position`) VALUES
(1, 'ROLE_ADMIN', 0),
(2, 'ROLE_ROOT', 0),
(3, 'ROLE_NEWSMAKER', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `galleries`
--

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `media_collection_id` int(11) unsigned NOT NULL,
  `order_albums_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F70E6EB7B52E685C` (`media_collection_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `created_at`, `user_id`, `media_collection_id`, `order_albums_by`) VALUES
(1, 'Тестовая фотогалерея', '2014-07-15 03:59:43', 1, 2, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_albums`
--

DROP TABLE IF EXISTS `gallery_albums`;
CREATE TABLE IF NOT EXISTS `gallery_albums` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `cover_image_id` int(11) DEFAULT NULL,
  `photos_count` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT '1',
  `last_image_id` int(11) DEFAULT NULL,
  `position` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_5661ABED4E7AF8F` (`gallery_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `gallery_albums`
--

INSERT INTO `gallery_albums` (`id`, `gallery_id`, `title`, `description`, `created_at`, `user_id`, `cover_image_id`, `photos_count`, `updated_at`, `is_enabled`, `last_image_id`, `position`) VALUES
(1, 1, 'Первый альбом', NULL, '2014-07-15 04:17:36', 1, 8, 2, '2014-07-15 23:54:05', 1, 8, 0),
(2, 1, 'Второй альбом', NULL, '2014-07-15 04:18:21', 1, NULL, 0, '2015-05-21 20:57:06', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_photos`
--

DROP TABLE IF EXISTS `gallery_photos`;
CREATE TABLE IF NOT EXISTS `gallery_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `album_id` int(11) unsigned DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `position` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_AAF50C7B1137ABCF` (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `gallery_photos`
--

INSERT INTO `gallery_photos` (`id`, `image_id`, `album_id`, `description`, `created_at`, `user_id`, `position`) VALUES
(1, 7, 1, NULL, '2014-07-15 23:49:51', 1, 0),
(2, 8, 1, NULL, '2014-07-15 23:54:05', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `media_categories`
--

DROP TABLE IF EXISTS `media_categories`;
CREATE TABLE IF NOT EXISTS `media_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_30D688FC727ACA70` (`parent_id`),
  KEY `IDX_30D688FC989D9B62` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `media_categories`
--


-- --------------------------------------------------------

--
-- Структура таблицы `media_collections`
--

DROP TABLE IF EXISTS `media_collections`;
CREATE TABLE IF NOT EXISTS `media_collections` (
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
  KEY `IDX_244DA17D14E68FF3` (`default_storage_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `media_collections`
--

INSERT INTO `media_collections` (`id`, `default_storage_id`, `title`, `default_filter`, `params`, `relative_path`, `filename_pattern`, `file_relative_path_pattern`, `created_at`) VALUES
(1, 1, 'Каталог товаров', '300_300', 'N;', '/catalog', '{hour}_{minutes}_{rand(10)}', '/{year}/{month}/{day}', '2014-02-14 13:43:18'),
(2, 1, 'Фотогалерея', NULL, 'N;', '/gallery', '{hour}_{minutes}_{rand(10)}', '/{year}/{month}/{day}', '2014-07-15 04:46:03'),
(3, 1, 'Блог', NULL, 'N;', '/blog', '{hour}_{minutes}_{rand(10)}', '/{year}/{month}/{day}', '2015-03-08 15:07:15');

-- --------------------------------------------------------

--
-- Структура таблицы `media_files`
--

DROP TABLE IF EXISTS `media_files`;
CREATE TABLE IF NOT EXISTS `media_files` (
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
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_192C84E8514956FD` (`collection_id`),
  KEY `IDX_192C84E812469DE2` (`category_id`),
  KEY `IDX_192C84E85CC5DB90` (`storage_id`),
  KEY `IDX_192C84E88CDE5729` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `media_files`
--

INSERT INTO `media_files` (`id`, `collection_id`, `category_id`, `storage_id`, `is_preuploaded`, `relative_path`, `filename`, `original_filename`, `type`, `mime_type`, `original_size`, `size`, `user_id`, `created_at`) VALUES
(1, 1, NULL, 1, 0, '/2014/02/16', '00_52_bbc4f846f6.jpeg', 'samsung-np900x3c-a02ru-1.jpg', 'image', 'image/jpeg', 131476, 131476, 0, '2014-02-16 00:52:17'),
(3, 1, NULL, 1, 0, '/2014/02/17', '01_19_53bd2543df.jpeg', '154655_0.1362072510.jpg', 'image', 'image/jpeg', 29693, 29693, 0, '2014-02-17 01:19:23'),
(4, 1, NULL, 1, 0, '/2014/02/17', '01_41_ec3e194bc1.jpeg', 'pic_18468_1.jpg', 'image', 'image/jpeg', 364655, 364655, 0, '2014-02-17 01:41:47'),
(5, 1, NULL, 1, 0, '/2014/02/17', '22_11_083fd66af8.jpeg', 'EOS_650D.jpg', 'image', 'image/jpeg', 1695916, 1695916, 0, '2014-02-17 22:11:20'),
(6, 1, NULL, 1, 1, '/2014/03/06', '16_38_725b3ca498.jpg', '3.jpg', 'image', 'image/jpeg', 897389, 897389, 0, '2014-03-06 16:38:36'),
(7, 2, NULL, 1, 1, '/2014/07/15', '23_49_f6f9679959.jpg', 'patanjali.jpg', 'image', 'image/jpeg', 18412, 18412, 0, '2014-07-15 23:49:51'),
(8, 2, NULL, 1, 1, '/2014/07/15', '23_54_519f730985.jpg', 'Code Complete.jpg', 'image', 'image/jpeg', 45971, 45971, 0, '2014-07-15 23:54:05');

-- --------------------------------------------------------

--
-- Структура таблицы `media_files_transformed`
--

DROP TABLE IF EXISTS `media_files_transformed`;
CREATE TABLE IF NOT EXISTS `media_files_transformed` (
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
  KEY `IDX_1084B87D5CC5DB90` (`storage_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `media_files_transformed`
--

INSERT INTO `media_files_transformed` (`id`, `file_id`, `collection_id`, `storage_id`, `filter`, `size`, `created_at`) VALUES
(1, 1, 1, 1, '300_300', 28215, '2015-03-09 05:11:54'),
(2, 3, 1, 1, '300_300', 34430, '2015-03-09 05:11:54'),
(3, 5, 1, 1, '300_300', 39337, '2015-03-09 05:12:00'),
(4, 6, 1, 1, '300_300', 46972, '2015-03-09 05:12:00'),
(5, 8, 2, 1, '200_200', 27695, '2015-03-09 05:14:15'),
(6, 7, 2, 1, '200_200', 21122, '2015-03-09 05:14:16'),
(7, 6, 1, 1, '100_100', 8506, '2015-03-09 20:27:01'),
(8, 1, 1, 1, '100_100', 4622, '2015-03-09 20:27:03'),
(9, 4, 1, 1, '300_300', 35813, '2015-03-09 20:28:33'),
(10, 4, 1, 1, '100_100', 6138, '2015-05-21 22:08:47'),
(11, 3, 1, 1, '100_100', 6289, '2015-05-24 08:12:20'),
(12, 5, 1, 1, '100_100', 6411, '2015-05-24 08:13:15');

-- --------------------------------------------------------

--
-- Структура таблицы `media_storages`
--

DROP TABLE IF EXISTS `media_storages`;
CREATE TABLE IF NOT EXISTS `media_storages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `relative_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `media_storages`
--

INSERT INTO `media_storages` (`id`, `provider`, `title`, `relative_path`, `params`, `created_at`) VALUES
(1, 'SmartCore\\Bundle\\MediaBundle\\Provider\\LocalProvider', 'Локальное хранилище', '/_media', 'N;', '2014-02-14 13:41:32');

-- --------------------------------------------------------

--
-- Структура таблицы `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` smallint(6) DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `properties` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_727508CF5E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `menus`
--

INSERT INTO `menus` (`id`, `position`, `name`, `description`, `user_id`, `created_at`, `properties`) VALUES
(1, 0, 'Главное', NULL, 1, '2013-05-06 03:54:13', NULL),
(2, 0, 'Второе', NULL, 1, '2015-02-15 20:24:38', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) unsigned DEFAULT NULL,
  `folder_id` int(10) unsigned DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `position` smallint(6) DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `pid` int(11) unsigned DEFAULT NULL,
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `open_in_new_window` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_70B2CA2A5550C4ED` (`pid`),
  KEY `IDX_70B2CA2ACCD7E912` (`menu_id`),
  KEY `IDX_70B2CA2A162CB942` (`folder_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `folder_id`, `is_active`, `position`, `title`, `description`, `url`, `user_id`, `created_at`, `updated_at`, `pid`, `properties`, `open_in_new_window`) VALUES
(1, 1, 1, 1, 0, NULL, 'Перейти на главную страницу', NULL, 1, '2013-05-06 05:25:48', '2015-02-21 01:37:06', NULL, NULL, 0),
(2, 1, 2, 1, 10, NULL, '123 561', NULL, 1, '2013-05-06 05:48:06', '2015-03-02 03:37:35', NULL, NULL, 0),
(3, 1, 3, 1, 999, NULL, NULL, NULL, 1, '2013-05-06 07:28:54', '2013-12-22 08:49:04', NULL, NULL, 0),
(5, 1, 6, 1, 0, NULL, NULL, NULL, 1, '2013-05-06 08:45:04', NULL, 2, NULL, 0),
(6, 1, 5, 1, 2, NULL, NULL, NULL, 1, '2013-05-06 09:38:51', '2014-01-21 15:52:24', NULL, NULL, 0),
(7, 1, 16, 1, 2, NULL, NULL, NULL, 1, '2013-08-10 11:14:29', '2015-03-02 03:36:03', NULL, NULL, 0),
(8, 1, 8, 1, 1, NULL, NULL, NULL, 1, '2013-12-22 21:45:59', '2014-01-21 15:52:18', NULL, NULL, 0),
(10, 1, 11, 1, 0, NULL, NULL, NULL, 1, '2014-01-29 10:31:12', '2014-01-29 10:34:31', 5, 'N;', 0),
(11, 1, 12, 1, 3, NULL, NULL, NULL, 1, '2014-01-30 20:42:06', '2015-03-02 03:37:08', NULL, 'N;', 0),
(12, 1, 13, 1, 2, NULL, NULL, NULL, 1, '2014-02-07 18:02:12', '2014-02-07 18:02:22', NULL, 'N;', 0),
(13, 1, 14, 1, 0, NULL, NULL, NULL, 1, '2014-02-10 07:56:17', NULL, 11, 'N;', 0),
(14, 1, 4, 1, 0, NULL, NULL, NULL, 1, '2014-02-10 11:28:48', NULL, 8, 'N;', 0),
(15, 1, 15, 1, 5, NULL, NULL, NULL, 1, '2014-02-12 16:12:41', '2014-02-12 16:12:51', NULL, 'N;', 0),
(17, 1, 17, 1, 2, NULL, NULL, NULL, 1, '2014-07-15 03:28:34', NULL, NULL, 'N;', 0),
(18, 2, 7, 1, 0, NULL, NULL, NULL, 1, '2015-02-15 21:41:46', NULL, NULL, 'N;', 0),
(19, 1, NULL, 1, 255, 'Ссылка на яндекс', 'Откроется в новом окне', 'http://ya.ru', 1, '2015-02-15 23:07:04', '2015-02-21 01:29:55', NULL, 'N;', 1),
(20, 1, 18, 1, 4, NULL, NULL, NULL, 1, '2015-03-24 03:17:53', NULL, NULL, 'N;', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `data` longblob NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `session`
--


-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bundle` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `serialized` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E545A0C5A57B32FD5E237E06` (`bundle`,`name`),
  KEY `IDX_E545A0C55E237E06` (`name`),
  KEY `IDX_E545A0C5A57B32FD` (`bundle`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `bundle`, `name`, `value`, `serialized`) VALUES
(1, 'cms', 'site_full_name', 'Smart Core CMS (based on Symfony2 Framework)', 0),
(2, 'cms', 'site_short_name', 'Smart Core CMS', 0),
(3, 'cms', 'html_title_delimiter', '&ndash;', 0),
(4, 'cms', 'appearance_editor_theme', 'idle_fingers', 0),
(5, 'cms', 'appearance_editor', 'ace', 0),
(8, 'cms', 'twitter_bootstrap_version', '3', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `simple_news`
--

DROP TABLE IF EXISTS `simple_news`;
CREATE TABLE IF NOT EXISTS `simple_news` (
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
  KEY `IDX_B232FBE9B80531F1` (`end_publish_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `simple_news`
--

INSERT INTO `simple_news` (`id`, `title`, `slug`, `annotation`, `text`, `created_at`, `end_publish_date`, `is_enabled`, `image_id`, `annotation_widget`, `publish_date`, `updated_at`, `instance_id`) VALUES
(1, 'Первая', 'first', 'Анонс первой новости.', 'Тема: &laquo;Сублимированный рейтинг в XXI веке&raquo; Взаимодействие корпорации и клиента амбивалентно. Агентская комиссия специфицирует мониторинг активности, используя опыт предыдущих кампаний. Ассортиментная политика предприятия развивает стратегический маркетинг, используя опыт предыдущих кампаний. Более того, взаимодействие корпорации и клиента искажает бренд, расширяя долю рынка.', '2013-12-22 22:17:46', NULL, 1, NULL, NULL, '2013-12-22 22:17:46', NULL, 1),
(2, 'Вторая', 'second', 'Анонс второй новости.', 'Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.', '2013-12-16 22:18:21', NULL, 1, NULL, NULL, '2013-12-16 22:18:00', '2014-07-06 00:18:40', 1),
(3, 'PHP: Hypertext Preprocessor', 'php', 'Server-side HTML embedded scripting language. It provides web developers with a full suite of tools for building dynamic websites: native APIs to Apache and ...', '<div class="blurb">\r\n<p>PHP is a popular general-purpose scripting language that is especially suited to web development.</p>\r\n<p>Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.<br /><br /><acronym title="PHP: Hypertext Preprocessor">PHP</acronym> (рекурсивный акроним словосочетания <em>PHP: Hypertext Preprocessor</em>) - это распространенный язык программирования общего назначения с открытым исходным кодом. PHP сконструирован специально для ведения Web-разработок и его код может внедряться непосредственно в HTML.</p>\r\n</div>', '2014-01-20 02:33:25', NULL, 1, NULL, NULL, '2014-01-20 02:33:00', '2014-07-06 00:19:06', 1),
(4, 'Symfony 2 for PHP developers – Part 1', 'symfony-2-for-php-developers-part-1', 'So, you heard a lot about this web framework called Symfony 2 and everyone is banging on about how fantastic it is, but you don&rsquo;t understand what the big deal is and now you&rsquo;re reading this..', '<p>To be honest, my introduction to Symfony 2 wasn&rsquo;t entirely voluntarily, it was forced upon me but I decided to take it and run with it. However, it wasn&rsquo;t that easy. It took me quite a bit of time to start &ldquo;thinking in Symfony&rdquo; and to be honest, after a period of denial, I now &ldquo;get it&rdquo;. I now understand what the fuss is about and now I&rsquo;m writing this in the hope that it might help some other developer out there to get up and running with Symfony 2.</p>\r\n<p>This article is not going to run you through a tutorial. Instead we&rsquo;re going to talk about philosophy and most importantly about architecture. To &ldquo;get&rdquo; Symfony you need to &ldquo;think in Symfony&rdquo;. It&rsquo;s here where most newcomers to the framework start to get into trouble. Usually when you come from another framework to Symfony you&rsquo;re simply not in the right frame of mind, there are certain things you need to learn and more importantly, things you need to unlearn. I hope that these articles are going to help you gab the bridge between having experience with some other framework and getting started with Symfony 2. My aim for this article, and the articles that follow, is that it will be the glue you need between the future and the past.</p>\r\n<p>When I started with Symfony 2 I actually wasn&rsquo;t really interested in working with it. I had plenty of experience writing all sorts of things with PHP and other languages such as Java and C++ and I had used other PHP web frameworks in the past such as CodeIgniter and Kohana and plenty of WordPress to through in. MVC here. MVC there. MVC everywhere. An age old design pattern milked dry and over hyped. I got it. How can this Symfony thing be any different, right?</p>\r\n<p>So, I started to read about Symfony, working my way through the documentation and cookbook and what&rsquo;s not. Dependency Injection this. Dependency Injection that. Bla bla.. I used what I could and ignored the rest. I had to get stuff done and didn&rsquo;t have time for all this theoretical mumbo jumbo. So I build stuff and it worked. Actually, to be honest, it didn&rsquo;t work very well. The problem was obviously that this Symfony thing that was standing in my way, obstructing my goals. The truth is, Symfony was standing in the way. It was standing in the way because I wasn&rsquo;t using it the way it was supposed to be used. But I ploughed on&hellip;</p>\r\n<p>Coincidentally I was looking into the <a href="http://spring.io" target="_blank">Spring Framework</a>. I love Java, always have. I love the Java ecosystem, the JVM and everything that runs on it. The Spring Framework is very similar to Symfony in the way that at its core it&rsquo;s all about Dependency Injection. The funny thing was, I &ldquo;was&rdquo; interested in learning Spring Framework so it was very easy for me to digest everything about it. It&rsquo;s only after I worked with Spring Framework and got my head around that, that I had my eureka moment in regards to Symfony.</p>\r\n<p>It&rsquo;s important to understand that Symfony is more than just a web framework. Yes, it&rsquo;s also a set of components but with Symfony also comes a workflow and if you come from another PHP web framework then it&rsquo;s this workflow that is going to make the real difference for you. If you haven&rsquo;t worked with <a href="https://getcomposer.org" target="_blank">Composer</a> yet, then well, that&rsquo;s going to change the way you think about building your projects. To work with Symfony you don&rsquo;t &ldquo;have&rdquo; to use Composer but you&rsquo;d be a fool if you didn&rsquo;t.</p>\r\n<p>Composer allows you to manage your dependencies in a way that hasn&rsquo;t been available to PHP developers until now. OK, PECL and PEAR are cool but Composer is so much better. For one thing, the problem with PEAR libraries is that they are tied to your operating system, not your project. So e.g, if your production server has version 1 of a PEAR library but your development system has version 2 which isn&rsquo;t compatible then somehow you need to manage all this, manually. With Composer your dependencies become part of your project, not your operating system. But the best thing about Composer is that anyone who clones your project only has to run a &ldquo;composer install&rdquo; command to get all the dependencies they need to run the project. Try doing that with PEAR.</p>\r\n<p>So, here we are. Starting with Symfony 2. In the next article I&rsquo;m going delve into to the philosophy of Dependency Injection and how this works within the context of Symfony.</p>\r\n<p>&nbsp;</p>', '2014-01-27 12:05:22', NULL, 1, NULL, NULL, '2014-01-27 12:05:00', '2014-07-06 00:18:57', 1),
(5, 'Symfony 2 for PHP developers – Part 2', 'symfony-2-for-php-developers-part-2', 'Dependency Injection is at the heart of Symfony 2. To understand Symfony 2 you need to understand Dependency Injection.', '<div class="entry-content">\r\n<p>Dependency Injection is at the heart of Symfony 2. To understand Symfony 2 you need to understand Dependency Injection.</p>\r\n<p>Fortunately for us, the principle of Dependency Injection is very simple. Rather than hard coding instantiations of objects into our classes we&rsquo;ll pass &lsquo;m in, something like this:</p>\r\n<div class="wp_syntax">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class="line_numbers">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\n11\r\n12\r\n13\r\n14\r\n15\r\n16\r\n17\r\n18\r\n19\r\n20\r\n21\r\n22\r\n23\r\n24\r\n25\r\n26\r\n27\r\n28\r\n29\r\n30\r\n31\r\n32\r\n33\r\n34\r\n35\r\n36\r\n</pre>\r\n</td>\r\n<td class="code">\r\n<pre class="php" style="font-family: monospace;"><span style="color: #000000; font-weight: bold;">class</span> Logger <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> write<span style="color: #009900;">(</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #b1b100;">echo</span><span style="color: #000088;">$message</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n<span style="color: #000000; font-weight: bold;">class</span> SmtpDriver <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">protected</span><span style="color: #000088;">$logger</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> setLogger<span style="color: #009900;">(</span><span style="color: #000088;">$logger</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">logger</span><span style="color: #339933;">=</span><span style="color: #000088;">$logger</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span> send<span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">logger</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">write</span><span style="color: #009900;">(</span><span style="color: #0000ff;">"Sending SMTP email to <span style="color: #006699; font-weight: bold;">{$to}</span>..."</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n      <span style="color: #666666; font-style: italic;">// Sending email through SMTP...</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n<span style="color: #000000; font-weight: bold;">class</span> Mailer <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">protected</span><span style="color: #000088;">$logger</span><span style="color: #339933;">;</span><span style="color: #000000; font-weight: bold;">protected</span><span style="color: #000088;">$driver</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> setLogger<span style="color: #009900;">(</span><span style="color: #000088;">$logger</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">logger</span><span style="color: #339933;">=</span><span style="color: #000088;">$logger</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> setDriver<span style="color: #009900;">(</span><span style="color: #000088;">$driver</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">driver</span><span style="color: #339933;">=</span><span style="color: #000088;">$driver</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> send<span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">driver</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">send</span><span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>The above example demonstrates the principle of Dependency Injection. To use the above defined classes and make them into a program we do the following:</p>\r\n<div class="wp_syntax">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class="line_numbers">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n</pre>\r\n</td>\r\n<td class="code">\r\n<pre class="php" style="font-family: monospace;"><span style="color: #000088;">$logger</span><span style="color: #339933;">=</span><span style="color: #000000; font-weight: bold;">new</span> Logger<span style="color: #009900;">(</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n<span style="color: #000088;">$driver</span><span style="color: #339933;">=</span><span style="color: #000000; font-weight: bold;">new</span> SmtpDriver<span style="color: #009900;">(</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #000088;">$driver</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">setLogger</span><span style="color: #009900;">(</span><span style="color: #000088;">$logger</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n<span style="color: #000088;">$mailer</span><span style="color: #339933;">=</span><span style="color: #000000; font-weight: bold;">new</span> Mailer<span style="color: #009900;">(</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #000088;">$mailer</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">setLogger</span><span style="color: #009900;">(</span><span style="color: #000088;">$logger</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #000088;">$mailer</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">setDriver</span><span style="color: #009900;">(</span><span style="color: #000088;">$driver</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #000088;">$mailer</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">send</span><span style="color: #009900;">(</span><span style="color: #0000ff;">"john@example.com"</span><span style="color: #339933;">,</span><span style="color: #0000ff;">"luke@example.com"</span><span style="color: #339933;">,</span><span style="color: #0000ff;">"Dependency Injection Test"</span><span style="color: #339933;">,</span><span style="color: #0000ff;">"A message..."</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>If you&rsquo;re new to Dependency Injection then you might have to look at the above a few times but you will realize that the above example demonstrates a few very important principles.</p>\r\n<p>The first is that the Mailer class doesn&rsquo;t send the email but it uses a &ldquo;driver&rdquo; instead. In our case we have an SmtpDriver class which we &ldquo;inject&rdquo; into the Mailer class by calling the setDriver method on the $mailer instance. Now, this is very important because it means that the Mailer class doesn&rsquo;t have a &ldquo;dependency&rdquo; on the StmpDriver class, it does have a dependency on a driver of some sort but as we&rsquo;ll see soon, not the SmtpDriver class in particular. In fact, we could create a new class and call it MockDriver and pass that into the $mailer instance. This would be really handy during testing where we don&rsquo;t want to actually send out real emails every time we run our tests but maybe just want to log a message.</p>\r\n<p>Let&rsquo;s look at an example of this:</p>\r\n<div class="wp_syntax">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class="line_numbers">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\n11\r\n</pre>\r\n</td>\r\n<td class="code">\r\n<pre class="php" style="font-family: monospace;"><span style="color: #000000; font-weight: bold;">class</span> MockDriver <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">protected</span><span style="color: #000088;">$logger</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> setLogger<span style="color: #009900;">(</span><span style="color: #000088;">$logger</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">logger</span><span style="color: #339933;">=</span><span style="color: #000088;">$logger</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span> send<span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">logger</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">write</span><span style="color: #009900;">(</span><span style="color: #0000ff;">"Sending mock email to <span style="color: #006699; font-weight: bold;">{$to}</span>..."</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>As you can see, the MockDriver is very similar to the SmtpDriver but there is one big difference and that is the &ldquo;send&rdquo; method only calls the logger and doesn&rsquo;t actually send the email.</p>\r\n<p>To use the MockDriver, our program would look like this:</p>\r\n<div class="wp_syntax">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class="line_numbers">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n</pre>\r\n</td>\r\n<td class="code">\r\n<pre class="php" style="font-family: monospace;"><span style="color: #000088;">$logger</span><span style="color: #339933;">=</span><span style="color: #000000; font-weight: bold;">new</span> Logger<span style="color: #009900;">(</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n<span style="color: #000088;">$driver</span><span style="color: #339933;">=</span><span style="color: #000000; font-weight: bold;">new</span> MockDriver<span style="color: #009900;">(</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #000088;">$driver</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">setLogger</span><span style="color: #009900;">(</span><span style="color: #000088;">$logger</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n<span style="color: #000088;">$mailer</span><span style="color: #339933;">=</span><span style="color: #000000; font-weight: bold;">new</span> Mailer<span style="color: #009900;">(</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #000088;">$mailer</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">setLogger</span><span style="color: #009900;">(</span><span style="color: #000088;">$logger</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #000088;">$mailer</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">setDriver</span><span style="color: #009900;">(</span><span style="color: #000088;">$driver</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #000088;">$mailer</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">send</span><span style="color: #009900;">(</span><span style="color: #0000ff;">"john@example.com"</span><span style="color: #339933;">,</span><span style="color: #0000ff;">"luke@example.com"</span><span style="color: #339933;">,</span><span style="color: #0000ff;">"Dependency Injection Test"</span><span style="color: #339933;">,</span><span style="color: #0000ff;">"A message..."</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>As you can see, the only difference in our program is the line:</p>\r\n<div class="wp_syntax">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class="code">\r\n<pre class="php" style="font-family: monospace;"><span style="color: #000088;">$driver</span><span style="color: #339933;">=</span><span style="color: #000000; font-weight: bold;">new</span> MockDriver<span style="color: #009900;">(</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>Instead of:</p>\r\n<div class="wp_syntax">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class="code">\r\n<pre class="php" style="font-family: monospace;"><span style="color: #000088;">$driver</span><span style="color: #339933;">=</span><span style="color: #000000; font-weight: bold;">new</span> SmtpDriver<span style="color: #009900;">(</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>this bring us to the second important principle; All the above means is that we&rsquo;re controlling the functionality of our program from the &ldquo;outside&rdquo; at the highest level rather than on the inside at the lowest level. In other words, we&rsquo;re using Inversion of Control.</p>\r\n<p>If your brain just exploded, don&rsquo;t worry. All will be fine.</p>\r\n<p>One thing the careful observer might have noticed in the above example is that all our classes are POPO&rsquo;s, i.e. Plain Old PHP Objects. There&rsquo;s no Symfony in any of the above and this is exactly what we want. In the first code snippet where we defined the Logger, the SmtpDriver and the Mailer classes, we did just that, we defined classes. We could say we defined the &ldquo;architecture&rdquo; of our program but not the actual program itself, i.e, on their own the classes don&rsquo;t do anything but when you &ldquo;wire them up&rdquo; it&rsquo;s where you create your program. It&rsquo;s this &ldquo;wiring&rdquo; that Symfony helps us with but we&rsquo;ll get to that later.</p>\r\n<p>There is one major problem with the code we have so far and that is that it&rsquo;s fragile. By this I mean, the driver classes need to have a &ldquo;send&rdquo; method so we like to enforce this. Another problem is the duplication of injecting the logger functionality. So let&rsquo;s revise these issues:</p>\r\n<div class="wp_syntax">\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td class="line_numbers">\r\n<pre>1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\n11\r\n12\r\n13\r\n14\r\n15\r\n16\r\n17\r\n18\r\n19\r\n20\r\n21\r\n22\r\n23\r\n24\r\n25\r\n26\r\n27\r\n28\r\n29\r\n30\r\n31\r\n32\r\n33\r\n34\r\n35\r\n36\r\n37\r\n38\r\n39\r\n40\r\n41\r\n42\r\n43\r\n</pre>\r\n</td>\r\n<td class="code">\r\n<pre class="php" style="font-family: monospace;"><span style="color: #000000; font-weight: bold;">class</span> Logger <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> write<span style="color: #009900;">(</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #b1b100;">echo</span><span style="color: #000088;">$message</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n<span style="color: #000000; font-weight: bold;">abstract</span><span style="color: #000000; font-weight: bold;">class</span> AbstractBase <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">protected</span><span style="color: #000088;">$logger</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> setLogger<span style="color: #009900;">(</span><span style="color: #000088;">$logger</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">logger</span><span style="color: #339933;">=</span><span style="color: #000088;">$logger</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n<span style="color: #000000; font-weight: bold;">interface</span> MailerDriverInterface <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> send<span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n<span style="color: #000000; font-weight: bold;">class</span> SmtpDriver <span style="color: #000000; font-weight: bold;">extends</span> AbstractBase implements MailerDriverInterface <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">public</span> send<span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">logger</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">write</span><span style="color: #009900;">(</span><span style="color: #0000ff;">"Sending SMTP email to <span style="color: #006699; font-weight: bold;">{$to}</span>..."</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n      <span style="color: #666666; font-style: italic;">// Sending email through SMTP...</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n<span style="color: #000000; font-weight: bold;">class</span> MockDriver <span style="color: #000000; font-weight: bold;">extends</span> AbstractBase implements MailerDriverInterface <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">public</span> send<span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">logger</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">write</span><span style="color: #009900;">(</span><span style="color: #0000ff;">"Sending mock email to <span style="color: #006699; font-weight: bold;">{$to}</span>..."</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n<span style="color: #000000; font-weight: bold;">class</span> Mailer <span style="color: #000000; font-weight: bold;">extends</span> AbstractBase <span style="color: #009900;">{</span><span style="color: #000000; font-weight: bold;">protected</span><span style="color: #000088;">$driver</span><span style="color: #339933;">;</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> setDriver<span style="color: #009900;">(</span> MailerDriverInterface <span style="color: #000088;">$driver</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">driver</span><span style="color: #339933;">=</span><span style="color: #000088;">$driver</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span>\r\n&nbsp;\r\n   <span style="color: #000000; font-weight: bold;">public</span><span style="color: #000000; font-weight: bold;">function</span> send<span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #009900;">{</span><span style="color: #000088;">$this</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">driver</span><span style="color: #339933;">-&gt;</span><span style="color: #004000;">send</span><span style="color: #009900;">(</span><span style="color: #000088;">$to</span><span style="color: #339933;">,</span><span style="color: #000088;">$from</span><span style="color: #339933;">,</span><span style="color: #000088;">$subject</span><span style="color: #339933;">,</span><span style="color: #000088;">$message</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span><span style="color: #009900;">}</span><span style="color: #009900;">}</span></pre>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<p>Our program is exactly the same as it was before. The only thing we&rsquo;ve changed is that we&rsquo;ve moved the functionality that was shared by all classes (the logger) to an abstract base class. The reason we&rsquo;re making this class abstract is because we don&rsquo;t want to allow for instances to be created of this class. As you might have noticed, we named our abstract base class AbstractBase. By doing this we communicate the &ldquo;intent&rdquo; of the class itself by saying it&rsquo;s a &ldquo;base&rdquo; class and that it&rsquo;s &ldquo;abstract&rdquo;.</p>\r\n<p>The second thing we did was to create an interface for the drivers that can be used on the Mailer class. In our case, the interface does nothing more than defining the &ldquo;send&rdquo; method but you can imagine that when your classes are more complex that an interface will force you to implement all the required functionality. An interface is therefore like a contract and it will create stability within your architecture. To illustrate this, the setDriver method of the Mailer class now has a typed parameter. I.e. the parameter passed to the setDriver method needs to implement the MailerDriverInterface.</p>\r\n<p>However, still no sign of Symfony. We&rsquo;re soon going to change that in the next article&hellip;</p>\r\n</div>', '2014-01-27 12:12:31', NULL, 1, NULL, NULL, '2014-01-27 12:12:00', '2014-07-06 00:18:49', 1),
(6, 'Unifying PHP', 'unifying-php', 'Over the last few weeks, there has been lots of talk about PHP community, packages, and tribalism. So, I thought I would offer a few thoughts on the topic. Currently, Laravel is the most eclectic full-stack PHP framework in existence. In other words, Laravel is the only full-stack PHP framework that actively eschews tribalism.', '<p>Over the last few weeks, there has been lots of talk about PHP community, packages, and tribalism. So, I thought I would offer a few thoughts on the topic.&nbsp;Currently, Laravel is the most eclectic full-stack PHP framework in existence. In other words, Laravel is the <strong>only</strong> full-stack PHP framework that <strong>actively</strong> eschews tribalism.</p>\r\n<p>Laravel, in additions to using its own custom packages like Eloquent and Blade, utilizes a whopping 23 packages from the wider PHP community. Using the &ldquo;best of the best&rdquo; PHP packages allows for greater interaction between Laravel and the wider PHP community. But, perhaps more importantly to you, it helps you write amazing applications at breakneck speed.</p>\r\n<p>We don&rsquo;t want to just speak about community, we want to participate! It&rsquo;s been a blast to coordinate and talk with developers of so many awesome packages, and I&rsquo;m very thankful that Laravel has been made better by their efforts.</p>\r\n<p>So, in this post, I want to highlight just a few of the wonderful packages that make Laravel awesome.</p>\r\n<h2>A few highlights:</h2>\r\n<p><strong>Carbon:</strong> An expressive date library by Brian Nesbitt. This library is used to power Eloquent&rsquo;s date mutators. It makes working with dates in PHP easy and enjoyable.</p>\r\n<p><strong>Predis:</strong> A robust Redis client authored by Daniele Alessandri. Predis powers all of the Redis interaction Laravel offers, including the Redis cache, session, and queue drivers.</p>\r\n<p><strong>Phenstalk:</strong> Full-featured PHP client for the Beanstalkd queue. Powers the Laravel Beanstalkd queue driver.</p>\r\n<p><strong>SuperClosure:</strong> Written by Jeremy Lindblom, this powerful library allows you to serialize and unserialize PHP Closures. It is used each time you push an anonymous function onto a queue.</p>\r\n<p><strong>Whoops:</strong> Displays the pretty error pages and stack traces while Laravel is in development mode.</p>\r\n<p><strong>Monolog:</strong> The de-facto standard of PHP logging libraries. Used for all logging. Primarily written by Jordi Boggiano.</p>\r\n<p><strong>Boris:</strong> Really slick PHP REPL which powers the amazing &ldquo;tinker&rdquo; console command.</p>\r\n<p><strong>PasswordCompat:</strong> Powers the secure Bcrypt hashing that is used by default by Laravel. Forward compatible with PHP 5.5. Written by Anthony Ferrara.</p>\r\n<p><strong>Symfony HttpFoundation:</strong> Extremely robust HTTP abstraction. Well tested and proven in many large, real-world applications. One of the most important community packages we use.</p>\r\n<p><strong>Symfony Routing:</strong> This package powers the compilation of Laravel routes to regular expressions, a trickier task than you might think! This library handles a lot of edge cases very well!</p>\r\n<p><strong>Symfony HttpKernel:</strong> The HttpKernel provides HTTP exceptions, which are used to indicate 404 responses in Laravel. Also, and perhaps more importantly, this package contains the HttpKernelInterface which is used as the bottom-level abstraction for a Laravel application.</p>\r\n<p><strong>Symfony BrowserKit:</strong> All that slick functional testing that Laravel offers? Powered by Symfony BrowserKit!</p>\r\n<strong>StackPHP:</strong> This project outlines a structure for building reusable, framework agnostic middlewares at the HTTP layer. Utilized in Laravel 4.1+ for all cookie encryption, sessions, and more. Developed by two of my favorite and most respected PHP developers: Igor Wiedler and Beau Simensen', '2014-01-27 12:34:21', NULL, 1, NULL, NULL, '2014-01-27 12:34:00', '2014-07-06 00:18:23', 1);
INSERT INTO `simple_news` (`id`, `title`, `slug`, `annotation`, `text`, `created_at`, `end_publish_date`, `is_enabled`, `image_id`, `annotation_widget`, `publish_date`, `updated_at`, `instance_id`) VALUES
(7, 'Почему мы предпочли Symfony 2 вместо Yii', 'pochemu-my-predpochitaem-symfony-2-vmesto-yii', 'Перевод статьи <a href="http://weavora.com/blog/2013/03/26/why-we-prefer-symfony-2-over-yii-framework/" target="_blank">Why We Prefer Symfony 2 Over Yii Framework</a>.', '<div class="post-bodycopy cf">\r\n<p>Перевод статьи <a href="http://weavora.com/blog/2013/03/26/why-we-prefer-symfony-2-over-yii-framework/" target="_blank">Why We Prefer Symfony 2 Over Yii Framework</a>.</p>\r\n<p>В этой статье я собираюсь рассказать вам историю, которая объясняет причину по которой наша команда предпочла Symfony 2 вместо Yii, который мы использовали продолжительное время и делали на нём наши лучшие приложения. Как это случилось и по каким причинам мы приняли наше решение о смене фреймворка?</p>\r\n<p>Вы, возможно, подумали, что у нас было большое совещание на котором мы решили, что нашим следующим фреймворком станет Symfony потому что бла бла бла. Я боюсь разочаровать вас. Реальная история гораздо более проще и очевидней.</p>\r\n<p>Изначально мы были вынуждены использовать Symfony 2, как одно из важных технические требований в проекте, который мы собирались делать. Мы бегло прошлись по документации Symfony 2, новым фичам и т.д. И нам казалось всё выглядит очень похожим. Тот же MVC концепт, только другой шаблонизатор, ORM и пр. Да, Symfony 2 имеет Dependency Injection Container, но это не меняет положения вещей в значительно мере. Более того, у нас есть несколько членов команды с превосходным опытом разработки проектов на Symfony 1.</p>\r\n<p>Мы знали, что миграция будет не простой и что мы будем вынуждены потратить много человеко-часов для поддержания нашей нормальной скорости разработки.&nbsp;Мы были открыты для нового опыта и нового вызова.</p>\r\n<p>У нашего проекта был совершенно сумасшедший дедлайн. В добавок к этому, мы использовали Symfony тем же старым путем, как мы делали это с Yii. Мы пытались сохранить те-же принципы, стиль, подход, которые прекрасно работали с Yii. И это была действительно плохая идея. Но этого не было столь плохо на первое время.</p>\r\n<p>Следующий проект был также на Symfony. В нём мы пытались исправить все недоразумения и тщательно следовать философии Symfony. В основном, мы начали делать вещи на основе symfony философии и это исправило большинство архитектурных проблем. Там мы впервые встретились с composer, PSR-2 и другими клёвыми инструментами. В итоге мы стали ближе к сообществу Symfony.</p>\r\n<p>Я думаю, только в процессе создания нашего 3D проекта мы почувствовали успех и удовлетворение от нашей новой &ldquo;авантюры&rdquo;. С тех пор мы перестали рассматривать Yii.</p>\r\n<p>Сейчас я попытаюсь объяснить более детально почему мы решили остановиться на Symfony? Что нам особо&nbsp;понравилось?</p>\r\n<p>Немного забегая вперед, я бы хотел поделиться результатами нашего внутреннего голосования. Мы приняли решение опросить каждого члена команды по порядку, чтобы быть уверенным, что мы на верном пути и хочет ли кто-то вернуться обратно на Yii. Я был очень удивлен, что никто не проголосовал за Yii. C самого начала некоторые члены команды были очень скептично настроены, часто критиковали, говоря, что symfony &mdash; корпоративный фреймворк, Yii &mdash; проще в разработке. Но, в итоге, они позволили проникнуть концепции Symfony в их душу и теперь нет пути обратно. Они сделали несколько попыток в личных небольших проектах, но затем окончательно сдались.</p>\r\n<p><strong>Итак, почему мы перешли на Symfony 2?</strong></p>\r\n<p>В действительности &mdash; это всё об управлении и сопровождении кода. Выполнять код быстро с самого начала &mdash; это реально просто. Мы никогда не имели проблем с этим и, я уверен, вы тоже. Реальные проблемы появились позднее, когда проект рос и становился долгосрочным. Почти все наши проекты долгосрочные (от 2-3 месяцев до года и более).</p>\r\n<p>Это причина по которой&nbsp;для нас было критично использовать подходы и техники для эффективного управления кодом на долгий срок. Мы имели длительные проекты на Yii и было сложно сохранять их в хорошем состоянии после 3-ёх месяцев разработки. У нас всегда были небольшие проблемы, мы были вынуждены применять мелкие хаки, хуки и т.д. Конечно это работало, но было неприятно это поддерживать.</p>\r\n<p>Мы хотели сохранить наш код хорошо-организованным и следовать некоторым понятным принципам, которые будут беспокоиться о коде и сохранять его от повреждений. Это был наш основной концепт. И не важно, как долго длился проект, один месяц или один год. Мы хотели быть счастливы с нашим кодом в любое время и сохранять интерес к разработке. В коде не должно быть хаков. Честно, хаки убивают команду проекта. Кому нравится грязное белье? Это обычно начинается с этого: итак, сегодня я сделал&nbsp;по-быстрому небольшой хак и завтра я найду и заменю это более лучшим решением. Запомните &mdash; это путь в пропасть.</p>\r\n<p>Какая проверка кода была у нас в Yii?</p>\r\n<p><strong>TDD (разработка через тестирование)</strong></p>\r\n<p>Первый вопрос &mdash; тестирование кода. Тесты должны писаться легко. Никто не будет следовать TDD если один простой тест требует mock для половины приложения. Это большая головная боль. И Yii не&nbsp;достаточно помогал в этом вопросе.</p>\r\n<p>Глобальный объект Yii::app() только убивал попытки написать тесты. Вы начинали с одного теста, затем понимали что вам нужно создать mock для этого сервиса и ещё другого и оба они зависят от третьего&hellip; бах! Большинство сервисов в Yii пересекаются друг с другом. Это хреново.</p>\r\n<p>У нас была тесная связь между компонентами. Было сложно раскладывать проект на составляющие. В идеале нам не следует делать этого вовсе, они изначально должны быть отделены друг от друга.</p>\r\n<p>Реально сложно следовать TDD в Yii. Да, он имеет CWebTestCase, fixtures, базовую интеграцию с phpUnit. Но это простые вещи. Гораздо более важно &mdash; тестировать сервисы/модели без моков других сервисов, без моков других классов фреймворка.</p>\r\n<p><strong>ORM/ActiveRecord</strong></p>\r\n<p>Иметь ActiveRecords как часть ядра фреймворка &mdash; это круто. Это реально удобно для новичнов. Но AR в Yii очень упрощен и, опять же, очень сильно связан. Я знаю, что когда мы говорим об AR, мы будем, скорее всего, иметь тесную связь, потому что сущности должны быть соединены по порядку для сохранения чего-либо без прямой передачи этого. Но это не причина, чтобы распространять эту идею везде.</p>\r\n<p>Более серьезный вопрос: почему AR в Yii не покрывает наши потребности &mdash; это из-за отсутствия разделения между сущностью и запросом. В Yii мы вынуждены использовать статические методы для запросов и нестатические методы для логики моделей. В Yii ActiveRecord и ActiveFinder представлены одним экземпляром. Это не круто, когда запросы перемешиваются с сущностью (getter/setter).</p>\r\n<p>Другой момент относится к статическим методам для запросов. Статические методы не могут иметь другого состояния, кроме статического. И если вы хотите перемешать несколько условий вы вынуждены объединить критерии. В Propel мне нравится делать такое: $query-&gt;filterByThis(1)-&gt;filterByThat(2)-&gt;find() и сохранять мои фильтры/критерии отдельно. В Yii вы вынуждены определить их массивами (вы не можете создавать условия динамически) и затем объединить массив или критерии. Довольно скучная работа. Также у меня не может быть несколько классов для запросов, расширяющих базовый, где я буду разделять фильтры/условия базирующиеся на бизнес логике.</p>\r\n<p>Нам нужны более серьезные вещи. В Symfony 2 у нас есть Doctrine 2. Достаточно серьезная ORM. И это было достаточно важно для нас. Но в итоге мы прилипли к Propel ORM.<br /> Я знаю, там есть также некоторые проблемы и сгенерированный код далёк от лучших стандартов кодирования. Но это работает для нас лучшим образом и мы точно можем разделить сущности и запросы. Главные вещи которые мы любим в propel &mdash; реальный getter/setter (мы можем посмотреть как они реализуются и переопределить их в случае необходимости), схемы базы данных и генерация миграция, поведений, &nbsp;интеграция в некоторые компоненты Symfony, такие как формы и валидаторы.</p>\r\n<p><strong>Стиль кодирования</strong></p>\r\n<p>Я знаю, команда Yii следует их личному стилю написания кода и это нормально. Но внутренне мы разные и в этом заключалась проблема.</p>\r\n<p>На самом деле, ранее у нас не было документации об основных принципах кодирования. Мы сделали несколько попыток написать их, но это было очень скучно. Имена классов, отступы и прочее. Это требовало много времени. У нас в тайне были некоторые общие стандарты, но это не было задокументировано.</p>\r\n<p>Проблема была в том, что наш стиль написания кода несколько отличался от того, который использует команда Yii. Мы сделали вклад в развитие Yii сообщества, разработав несколько дополнений и, конечно, мы хотели сохранить наши расширения похожими на родной Yii код. В итоге, мы&nbsp;были вынуждены переключаться между разными стилями кодирования всё время. Для расширений Yii мы использовали принципы кодирования Yii команды, для реальных проектов мы использовали свой собственный. Не круто.</p>\r\n<p>Мы хотели использовать что-то глобальное. В нашем коде, в компонентах, в фреймворке.</p>\r\n<p>Теперь мы следуем PSR-2. Мы были близки к этому и миграция была супер легкой. Symfony 2 следует PSR-2. Большинство компонентов следует этому также. Это работает для нас и мы, на самом деле, ценим php-fig. Безусловно есть много полемики о PSR, но большинство из неё о PSR-3. PSR-2 достаточно хорош. По крайней мере для нас.</p>\r\n<p>Ещё одна вещь &mdash; пространства имен (namespaces). Как вы знаете, Yii не использует пространства имен. И это не помогает в построении приложения. Пространства имен помогают укоротить имена классов, помогают с автозагрузкой классов и т.д. В общем, мы ощущаем себя более комфортно с namespaces, чем без них.</p>\r\n<p><strong>Расширения</strong></p>\r\n<p>Давайте посмотрим как мы получали расширение в Yii. Первое, нам необходимо найти его на сайте Yii, скачать, скопировать вручную в директорию проекта, прикрепить в конфиге. Затем нам необходимо наблюдать за сайтом Yii для поиска обновления. Это хорошо, но не в 2013 году.</p>\r\n<p>Прямо сейчас мы используем composer. Это великолепно, когда вы можете просто объявить зависимость в проекте и запустить &laquo;update&raquo;. Он скачает расширение/библиотеку/компонент/бандл или всё что вы хотите, установит и добавить в автозагрузку и вы может использовать это. Также composer будет беспокоиться обо всех зависимостях компонентов и скачивать их, если это необходимо. Одной командой вы можете обновить все компоненты до наиболее актуальной версии. Вы можете указать специфичную версию. Возможно, вы хотите скачать test или dev версии. Это тоже просто.</p>\r\n<p>Это чрезвычайно легко опубликовать ваш пакет и сделать его доступным отовсюду. Проще определять версии, проще сделать fork расширения, легче посылать запросы на внесение изменений и так далее.</p>\r\n<p>Composer является чрезвычайно полезным инструментом. Это именно то, что необходимо php сообществу.</p>\r\n<p>Мы используем composer для управления нашими зависимостями. Мы не держим внешних зависимостей в нашем репозитории вовсе. Всё что нам нужно &mdash; только лишь сохранять composer.json в актуальном состоянии.</p>\r\n<p><strong>Архитектура</strong></p>\r\n<p>Давайте вернемся к обслуживанию кода.Чаще всего, когда люди хотят сказать, что код должен быть чище, они говорят, что нуждаются в правильной архитектуре. Что это значит?</p>\r\n<p>Во-первых, это означает, что программное обеспечение должно быть основано на некоторых принципах и следовать хорошим практикам. Так что да, мы хотим, чтобы наше программное обеспечение было принципиальным. И Symfony помогает нам в этом. Архитектура Symfony 2 основана на тех же принципах.</p>\r\n<p><strong>Заключительные мысли &nbsp;</strong></p>\r\n<p>Я хочу, чтобы вы поняли меня правильно. Мы по-прежнему испытываем теплые чувства к Yii, продолжаем выражать уважение Yii команде, они сделали и делают большую работу. Это превосходно, и мы потратили несколько человеко-лет, создавая код с этим фреймворком, способствовали созданию ряда расширений и консультировали многих новичков на различных форумах. &nbsp;Но на данный момент, это просто не соответствует нашем потребностям. Мы, по-прежнему, следим за обновлениями в отношении Yii2, и ждалиYii2 так долго&hellip; Но мы должны двигаться. И следующая точка нашего путешествия &mdash; <strong>Symfony 2</strong>.</p>\r\n</div>', '2014-01-27 12:42:25', NULL, 1, NULL, NULL, '2014-01-27 12:42:00', '2014-07-06 00:18:18', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `simple_news_instances`
--

DROP TABLE IF EXISTS `simple_news_instances`;
CREATE TABLE IF NOT EXISTS `simple_news_instances` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `use_image` tinyint(1) NOT NULL,
  `use_annotation_widget` tinyint(1) NOT NULL,
  `media_collection_id` int(11) unsigned DEFAULT NULL,
  `use_end_publish_date` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_98EDD0015E237E06` (`name`),
  KEY `IDX_98EDD001B52E685C` (`media_collection_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `simple_news_instances`
--

INSERT INTO `simple_news_instances` (`id`, `name`, `created_at`, `use_image`, `use_annotation_widget`, `media_collection_id`, `use_end_publish_date`) VALUES
(1, 'Default news', '2014-07-06 01:02:11', 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `sitemap_urls`
--

DROP TABLE IF EXISTS `sitemap_urls`;
CREATE TABLE IF NOT EXISTS `sitemap_urls` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `sitemap_urls`
--


-- --------------------------------------------------------

--
-- Структура таблицы `sliders`
--

DROP TABLE IF EXISTS `sliders`;
CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `width` smallint(6) DEFAULT NULL,
  `height` smallint(6) DEFAULT NULL,
  `mode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `library` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slide_properties` longtext COLLATE utf8_unicode_ci,
  `pause_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `width`, `height`, `mode`, `library`, `slide_properties`, `pause_time`) VALUES
(1, 'Цветочки', 748, 300, 'INSET', 'jcarousel', NULL, 3000),
(6, 'Nivo', 618, 246, 'INSET', 'nivoslider', NULL, 3000);

-- --------------------------------------------------------

--
-- Структура таблицы `slides`
--

DROP TABLE IF EXISTS `slides`;
CREATE TABLE IF NOT EXISTS `slides` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(1) DEFAULT '1',
  `file_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `original_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` smallint(6) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `slider_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B8C02091D7DF1668` (`file_name`),
  KEY `IDX_B8C020912CCC9638` (`slider_id`),
  KEY `IDX_B8C02091462CE4F5` (`position`),
  KEY `IDX_B8C02091A76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `slides`
--

INSERT INTO `slides` (`id`, `is_enabled`, `file_name`, `original_file_name`, `title`, `position`, `created_at`, `user_id`, `properties`, `slider_id`) VALUES
(10, 1, 'e711d4c91a7deebfb9a1eabf1b6d012d.jpeg', 'img1.jpg', NULL, 0, '2014-02-09 21:53:37', 1, 'a:0:{}', 1),
(11, 1, '0722a36552cca4701498791b82992b89.jpeg', 'img2.jpg', 'На фоне реки', 0, '2014-02-09 21:53:42', 1, 'a:0:{}', 1),
(12, 1, '1268a90a72e1c513ecfd59adf20ccf22.jpeg', 'img3.jpg', 'В поле', 0, '2014-02-09 21:53:46', 1, 'a:0:{}', 1),
(13, 1, '17a47fc3272be3e25835650bf8e245a0.jpeg', 'nemo.jpg', 'Из мультика про рыбку Nemo', 0, '2014-02-10 08:07:48', 1, 'a:0:{}', 6),
(14, 1, '99b6811cb4f117ddc66472036b36d73f.jpeg', 'toystory.jpg', NULL, 0, '2014-02-10 08:07:52', 1, 'a:0:{}', 6),
(15, 1, '67c92c3ce3b80e665c2ecb4fb1be1a83.jpeg', 'up.jpg', NULL, 0, '2014-02-10 08:07:56', 1, 'a:0:{}', 6),
(16, 1, 'c38749ffadba47cb79ee06f7d10d158c.jpeg', 'walle.jpg', 'Wall-E', 0, '2014-02-10 08:08:00', 1, 'a:0:{}', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `texter`
--

DROP TABLE IF EXISTS `texter`;
CREATE TABLE IF NOT EXISTS `texter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) DEFAULT NULL,
  `text` longtext,
  `meta` longtext NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `editor` smallint(6) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `texter`
--

INSERT INTO `texter` (`id`, `locale`, `text`, `meta`, `created_at`, `user_id`, `editor`, `updated_at`) VALUES
(1, 'ru', 'Футер\r', 'a:0:{}', '2012-08-27 03:16:57', 1, 1, NULL),
(2, 'ru', '<h1>\r\n  Главная страница!\r\n</h1>\r\n<p>\r\n  С точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма,\r\n  концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией\r\n  всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих геотрансплантационных квазипузлистатов\r\n  всех кинетически коррелирующих аспектов. Исходя из этого, мы пришли к выводу, что каждый произвольно выбранный предикативно абсорбирующий объект.\r\n</p>\r', 'a:1:{s:8:"keywords";s:3:"123";}', '2012-08-27 03:17:27', 1, 1, NULL),
(3, 'ru', '<h2>Пример страницы с 2-мя колонками</h2>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>\r\n<p>Опросная анкета упорядочивает из ряда вон выходящий портрет потребителя, учитывая результат предыдущих медиа-кампаний. Спонсорство, в рамках сегодняшних воззрений, однородно стабилизирует принцип восприятия, используя опыт предыдущих кампаний. Узнавание бренда осмысленно переворачивает повторный контакт, признавая определенные рыночные тенденции. Стимулирование сбыта амбивалентно.</p>', 'a:1:{s:8:"keywords";s:3:"sdf";}', '2012-08-27 03:51:05', 1, 1, NULL),
(4, 'ru', '<p><img src="/uploads/images/bscap0001.jpg" alt="" width="300" height="124" /><br />Сервисная стратегия деятельно искажает продвигаемый медиаплан, опираясь на опыт западных коллег. Внутрифирменная реклама, согласно Ф.Котлеру, откровенно цинична. Торговая марка исключительно уравновешивает презентационный материал, полагаясь на инсайдерскую информацию. Наряду с этим, узнавание бренда вполне выполнимо. Организация слубы маркетинга, согласно Ф.Котлеру, усиливает фактор коммуникации, осознавая социальную ответственность бизнеса. Экспертиза выполненного проекта восстанавливает потребительский презентационный материал, полагаясь на инсайдерскую информацию.</p>', 'a:0:{}', '2012-08-27 03:51:27', 1, 1, NULL),
(5, 'ru', 'Текстер №5', 'a:0:{}', '2013-03-21 06:03:37', 1, 0, NULL),
(6, 'ru', 'Text under menu in <strong>User</strong> folder.\r', 'a:0:{}', '2013-03-25 21:53:12', 1, 1, NULL),
(7, 'ru', 'sdf gsdfg dsf gsdf gdsfg sdf g\r', 'a:0:{}', '2013-08-10 11:14:55', 1, 1, NULL),
(8, 'ru', '<p>\r\n  Нельзя так просто взять и написать цмс-ку ;)<br />\r\n  <br />\r\n  <img style="width: 100%; height: auto;" src="/uploads/images/bscap0001_big.jpg" alt="" width="1680" height="693" /><br />\r\n  <br />\r\n  <br />\r\n  <br />\r\n</p>\r', 'a:0:{}', '2013-12-20 20:11:42', 1, 1, NULL),
(9, 'ru', 'Powered by <a href="http://symfony.com" target="_blank">Symfony2</a>\r', 'a:0:{}', '2014-01-20 03:47:18', 1, 1, NULL),
(10, 'ru', 'Очень интересные новости ;)', 'a:0:{}', '2014-01-22 19:02:28', 1, 1, NULL),
(11, 'ru', 'Для жаждущих с Сущностью Вечной слиянья<br />\r\nЕсть йога познанья и йога деянья,<br />\r\n<br />\r\nВ бездействии мы не обрящем блаженства;<br />\r\nКто дела не начал, тот чужд совершенства.<br />\r\n<br />\r\nОднако без действий никто не пребудет:<br />\r\nТы хочешь того иль не хочешь — принудит<br />\r\n<br />\r\nПрирода тебя: нет иного удела,<br />\r\nИ, ей повинуясь, ты делаешь дело.<br />\r\n<br />\r\nКто, чувства поправ, все же помнит впечали<br />\r\nПредметы, что чувства его услаждали,—<br />\r\n<br />\r\nТот, связанный, следует ложной дорогой;<br />\r\nА тот, о сын Кунти, кто, волею строгой<br />\r\n<br />\r\nВсе чувства поправ, йогу действия начал,—<br />\r\nНа правой дороге себя обозначил.<br />\r\n<br />\r\nПоэтому действуй; бездействию дело<br />\r\nВсегда предпочти; отравления тела —<br />\r\n<br />\r\nИ то без усилий свершить невозможно:<br />\r\nДеянье — надежно, бездействие — ложно. &nbsp;\r', 'a:0:{}', '2014-01-29 10:01:55', 1, 1, NULL),
(12, 'ru', '<hr />\r\n<h4>\r\n  Последние новости\r\n</h4>\r', 'a:0:{}', '2014-01-29 19:43:16', 1, 1, NULL),
(13, 'ru', '<h4>\r\n  Меню\r\n</h4>\r\n<hr />\r', 'a:0:{}', '2014-01-29 19:45:52', 1, 1, NULL),
(14, 'ru', 'Где чувства господствуют – там вожделенье,<br />\r\nА где вожделенье – там гнев, ослепленье,<br />\r\n<br />\r\nА где ослепленье – ума угасанье,<br />\r\nГде ум угасает – там гибнет познанье,<br />\r\n<br />\r\nГде гибнет познанье, – да ведает всякий, –<br />\r\nТам гибнет дитя человечье во мраке.<br />\r\n<br />\r\nА тот, кто добился над чувствами власти,<br />\r\nПопрал отвращенье, не знает пристрастий,<br />\r\n<br />\r\nКто их навсегда подчинил своей воле, –<br />\r\nДостиг просветленья, избавясь от боли,<br />\r\n<br />\r\nИ сердце с тех пор у него беспорочно,<br />\r\nИ разум его утверждается прочно.<br />\r\n<br />\r\nВне йоги к разумным себя не причисли:<br />\r\nВ неясности нет созидающей мысли;<br />\r\n<br />\r\nВне творческой мысли нет мира, покоя,<br />\r\nА где вне покоя и счастье людское?\r', 'a:0:{}', '2014-01-29 20:16:33', 1, 1, NULL),
(15, 'ru', '<hr />\r\n<h4>\r\n  Категории блога\r\n</h4>\r', 'a:0:{}', '2014-02-08 21:01:35', 1, 1, NULL),
(16, 'ru', 'Проверка вложенных папок при условии, что в родительскую подключен модуль с роутингом.\r', 'a:0:{}', '2014-02-08 21:04:03', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `texter_history`
--

DROP TABLE IF EXISTS `texter_history`;
CREATE TABLE IF NOT EXISTS `texter_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL,
  `locale` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `editor` smallint(6) NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci,
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_82529097126F525E` (`item_id`),
  KEY `IDX_82529097FD07C8FB` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `texter_history`
--

INSERT INTO `texter_history` (`id`, `is_deleted`, `item_id`, `locale`, `editor`, `text`, `meta`, `created_at`, `user_id`) VALUES
(1, 0, 2, 'ru', 1, '<h1>Главная страница!</h1>\r\n<p>С точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма, концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих геотрансплантационных квазипузлистатов всех кинетически коррелирующих аспектов. Исходя из этого, мы пришли к выводу, что каждый произвольно выбранный предикативно абсорбирующий объект.</p>\r\n<img src="/uploads/Advanced%20C%20Asana.jpg" alt="" width="891" height="666" />', 'a:1:{s:8:"keywords";s:3:"123";}', '2014-02-10 07:49:39', 1),
(2, 0, 16, 'ru', 1, '<hr />\r\n<h4>\r\n  Тэги блога\r\n</h4>\r', 'a:0:{}', '2014-02-10 11:30:25', 1),
(3, 0, 16, 'ru', 1, 'Проверка вложенных папок при улсовии, что в родительскую подключен модуль с роутингом.\r', 'a:0:{}', '2014-02-10 11:31:39', 1),
(20, 0, 14, 'ru', 1, 'Где чувства господствуют – там вожделенье,<br />\r\nА где вожделенье – там гнев, ослепленье,<br />\r\n<br />\r\nА где ослепленье – ума угасанье,<br />\r\nГде ум угасает – там гибнет познанье,<br />\r\n<br />\r\nГде гибнет познанье, – да ведает всякий, –<br />\r\nТам гибнет дитя человечье во мраке.<br />\r\n<br />\r\nА тот, кто добился над чувствами власти,<br />\r\nПопрал отвращенье, не знает пристрастий,<br />\r\n<br />\r\nКто их навсегда подчинил своей воле, –<br />\r\nДостиг просветленья, избавясь от боли,<br />\r\n<br />\r\nИ сердце с тех пор у него беспорочно,<br />\r\nИ разум его утверждается прочно.<br />\r\n<br />\r\nВне йоги к разумным себя не причисли:<br />\r\nВ неясности нет созидающей мысли;<br />\r\n<br />\r\nВне творческой мысли нет мира, покоя,<br />\r\nА где вне покоя и счастье людское?\r', 'a:0:{}', '2014-11-07 05:13:33', 1),
(21, 0, 9, 'ru', 1, 'Powered by <a href="http://symfony.com" target="_blank">Symfony2</a>\r', 'a:0:{}', '2014-11-07 05:13:47', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_blog_attributes`
--

DROP TABLE IF EXISTS `unicat_blog_attributes`;
CREATE TABLE IF NOT EXISTS `unicat_blog_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned DEFAULT NULL,
  `is_dedicated_table` tinyint(1) NOT NULL DEFAULT '0',
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `show_in_admin` tinyint(1) NOT NULL,
  `show_in_list` tinyint(1) NOT NULL,
  `show_in_view` tinyint(1) NOT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `params_yaml` longtext COLLATE utf8_unicode_ci,
  `is_enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `is_show_title` tinyint(1) NOT NULL DEFAULT '1',
  `is_link` tinyint(1) NOT NULL DEFAULT '0',
  `open_tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '<p>',
  `close_tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '</p>',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FE1A5FAD5E237E06` (`name`),
  KEY `IDX_FE1A5FADFE54D947` (`group_id`),
  KEY `IDX_FE1A5FAD46C53D4C` (`is_enabled`),
  KEY `IDX_FE1A5FADFB9FF2E7` (`show_in_admin`),
  KEY `IDX_FE1A5FAD921EA9F` (`show_in_list`),
  KEY `IDX_FE1A5FADB314B909` (`show_in_view`),
  KEY `IDX_FE1A5FAD462CE4F5` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `unicat_blog_attributes`
--

INSERT INTO `unicat_blog_attributes` (`id`, `group_id`, `is_dedicated_table`, `is_required`, `type`, `show_in_admin`, `show_in_list`, `show_in_view`, `params`, `params_yaml`, `is_enabled`, `created_at`, `position`, `name`, `title`, `user_id`, `is_show_title`, `is_link`, `open_tag`, `close_tag`) VALUES
(1, 1, 0, 1, 'text', 1, 1, 1, 'a:0:{}', NULL, 1, '2015-03-02 11:06:53', 0, 'title', 'Заголовок', 1, 0, 1, '<h1>', '</h1>'),
(2, 1, 0, 0, 'textarea', 0, 1, 0, 'a:1:{s:4:"form";a:1:{s:4:"attr";a:2:{s:5:"class";s:7:"wysiwyg";s:10:"data-theme";s:8:"advanced";}}}', 'form:\r\n    attr:\r\n        class: ''wysiwyg''\r\n        data-theme: ''advanced''', 1, '2015-03-02 11:07:46', 0, 'annotation', 'Аннотация', 1, 0, 0, '<p>', '</p>'),
(3, 1, 0, 0, 'textarea', 0, 0, 1, 'a:1:{s:4:"form";a:1:{s:4:"attr";a:2:{s:5:"class";s:7:"wysiwyg";s:10:"data-theme";s:8:"advanced";}}}', 'form:\r\n    attr:\r\n        class: ''wysiwyg''\r\n        data-theme: ''advanced''', 1, '2015-03-02 11:19:18', 0, 'text', 'Текст', 1, 0, 0, '<p>', '</p>');

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_blog_attributes_groups`
--

DROP TABLE IF EXISTS `unicat_blog_attributes_groups`;
CREATE TABLE IF NOT EXISTS `unicat_blog_attributes_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL,
  `configuration_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9E1338C873F32DD8` (`configuration_id`),
  KEY `IDX_9E1338C812469DE2` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `unicat_blog_attributes_groups`
--

INSERT INTO `unicat_blog_attributes_groups` (`id`, `category_id`, `configuration_id`, `created_at`, `name`, `title`) VALUES
(1, NULL, 2, '2015-03-02 10:54:09', 'common', 'Основные свойства');

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_blog_categories`
--

DROP TABLE IF EXISTS `unicat_blog_categories`;
CREATE TABLE IF NOT EXISTS `unicat_blog_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `structure_id` int(10) unsigned DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `is_inheritance` tinyint(1) NOT NULL DEFAULT '1',
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `is_enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F57287B5989D9B62727ACA702534008B` (`slug`,`parent_id`,`structure_id`),
  KEY `IDX_F57287B5727ACA70` (`parent_id`),
  KEY `IDX_F57287B52534008B` (`structure_id`),
  KEY `IDX_F57287B546C53D4C` (`is_enabled`),
  KEY `IDX_F57287B5462CE4F5` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `unicat_blog_categories`
--

INSERT INTO `unicat_blog_categories` (`id`, `parent_id`, `structure_id`, `slug`, `is_inheritance`, `meta`, `properties`, `is_enabled`, `created_at`, `position`, `title`, `user_id`) VALUES
(1, NULL, 3, 'programing', 0, 'a:0:{}', 'N;', 1, '2015-03-02 10:44:16', 0, 'Программирование', 1),
(2, NULL, 3, 'os', 0, 'a:0:{}', 'N;', 1, '2015-03-02 10:44:40', 0, 'Операционные системы', 1),
(3, NULL, 3, 'imposition', 0, 'a:0:{}', 'N;', 1, '2015-03-02 10:44:52', 0, 'Верстка', 1),
(4, NULL, 3, 'soft', 0, 'a:0:{}', 'N;', 1, '2015-03-02 10:45:07', 0, 'Программы (софт)', 1),
(5, NULL, 3, 'other', 0, 'a:0:{}', 'N;', 1, '2015-03-02 10:45:17', 0, 'Другое', 1),
(6, NULL, 4, 'php', 1, 'a:0:{}', 'N;', 1, '2015-03-08 14:29:47', 0, 'PHP', 1),
(7, 1, 3, 'php', 1, 'a:0:{}', 'N;', 1, '2015-03-08 14:32:35', 0, 'PHP', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_blog_items`
--

DROP TABLE IF EXISTS `unicat_blog_items`;
CREATE TABLE IF NOT EXISTS `unicat_blog_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `attributes` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `is_enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_69D20251989D9B62` (`slug`),
  KEY `IDX_69D20251462CE4F5` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `unicat_blog_items`
--

INSERT INTO `unicat_blog_items` (`id`, `slug`, `meta`, `attributes`, `is_enabled`, `created_at`, `position`, `user_id`) VALUES
(1, 'twig_in_symfony2_work_with_date_and_time', 'a:2:{s:11:"description";N;s:8:"keywords";N;}', 'a:3:{s:5:"title";s:65:"Twig в Symfony2: работа с датой и временем.";s:10:"annotation";s:319:"<p>Поначалу возник недоуменный вопрос: как в twig отдать дату в нужном формате? Неужели дату можно форматировать только в контролере? Но погуглив, нашел ответы на свои вопросы.</p>";s:4:"text";s:653:"<p>Форматирование даты:</p>\r\n<pre class="brush: php;">	var_date|date("d.m.y")\r\n</pre>\r\n<p>Получение текущей даты:</p>\r\n<pre class="brush: php;">	"new"|date("d.m.y")\r\n</pre>\r\n<p>Интернационализация:</p>\r\n<p>1. Подключаем сервис в конфиге Symfony2</p>\r\n<pre class="brush: yaml;">	services:\r\n        twig_extension.intl:\r\n            class: Twig_Extensions_Extension_Intl\r\n            tags: [{ name: "twig.extension" }]\r\n</pre>\r\n<p>2. Пример вызова</p>\r\n<pre class="brush: twig;">	{{ item.date|localizeddate("none", "none", null, null, "dd. LLLL YYYY") }}\r\n</pre>";}', 1, '2015-03-02 11:36:24', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_blog_items_categories_relations`
--

DROP TABLE IF EXISTS `unicat_blog_items_categories_relations`;
CREATE TABLE IF NOT EXISTS `unicat_blog_items_categories_relations` (
  `item_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`item_id`,`category_id`),
  KEY `IDX_B34834DD126F525E` (`item_id`),
  KEY `IDX_B34834DD12469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `unicat_blog_items_categories_relations`
--

INSERT INTO `unicat_blog_items_categories_relations` (`item_id`, `category_id`) VALUES
(1, 6),
(1, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_blog_items_categories_relations_single`
--

DROP TABLE IF EXISTS `unicat_blog_items_categories_relations_single`;
CREATE TABLE IF NOT EXISTS `unicat_blog_items_categories_relations_single` (
  `item_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`item_id`,`category_id`),
  KEY `IDX_12F1558126F525E` (`item_id`),
  KEY `IDX_12F155812469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `unicat_blog_items_categories_relations_single`
--

INSERT INTO `unicat_blog_items_categories_relations_single` (`item_id`, `category_id`) VALUES
(1, 6),
(1, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_catalog_attributes`
--

DROP TABLE IF EXISTS `unicat_catalog_attributes`;
CREATE TABLE IF NOT EXISTS `unicat_catalog_attributes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(1) DEFAULT '1',
  `is_dedicated_table` tinyint(1) NOT NULL DEFAULT '0',
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `position` smallint(6) DEFAULT '0',
  `type` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_in_admin` tinyint(1) NOT NULL,
  `show_in_list` tinyint(1) NOT NULL,
  `show_in_view` tinyint(1) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `group_id` int(10) unsigned DEFAULT NULL,
  `params_yaml` longtext COLLATE utf8_unicode_ci,
  `is_show_title` tinyint(1) NOT NULL DEFAULT '1',
  `is_link` tinyint(1) NOT NULL DEFAULT '0',
  `open_tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '<p>',
  `close_tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '</p>',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3A3855D25E237E06` (`name`),
  KEY `IDX_3A3855D2FE54D947` (`group_id`),
  KEY `IDX_3A3855D246C53D4C` (`is_enabled`),
  KEY `IDX_3A3855D2FB9FF2E7` (`show_in_admin`),
  KEY `IDX_3A3855D2921EA9F` (`show_in_list`),
  KEY `IDX_3A3855D2B314B909` (`show_in_view`),
  KEY `IDX_3A3855D2462CE4F5` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `unicat_catalog_attributes`
--

INSERT INTO `unicat_catalog_attributes` (`id`, `is_enabled`, `is_dedicated_table`, `is_required`, `position`, `type`, `name`, `title`, `show_in_admin`, `show_in_list`, `show_in_view`, `user_id`, `created_at`, `params`, `group_id`, `params_yaml`, `is_show_title`, `is_link`, `open_tag`, `close_tag`) VALUES
(1, 1, 0, 1, 1, 'text', 'title', 'Заголовок', 1, 1, 1, 0, '2014-02-13 20:37:50', 'N;', 1, NULL, 1, 1, '<h1>', '</h1>'),
(2, 1, 0, 0, 3, 'textarea', 'description', 'Описание', 0, 1, 1, 0, '2014-02-13 21:03:59', 'N;', 1, NULL, 1, 0, '<p>', '</p>'),
(3, 1, 0, 0, 999, 'integer', 'price', 'Цена', 1, 1, 1, 0, '2014-02-13 22:29:43', 'N;', 1, NULL, 1, 0, '<p>', '</p>'),
(4, 1, 0, 0, 4, 'checkbox', 'in_sight', 'В наличии', 0, 1, 1, 0, '2014-02-13 23:19:31', 'a:0:{}', 1, NULL, 1, 0, '<p>', '</p>'),
(5, 1, 0, 0, 2, 'image', 'image', 'Картинка', 0, 1, 1, 0, '2014-02-15 20:54:17', 'a:1:{s:6:"filter";s:7:"300_300";}', 1, 'filter: 300_300', 1, 0, '<p>', '</p>');

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_catalog_attributes_groups`
--

DROP TABLE IF EXISTS `unicat_catalog_attributes_groups`;
CREATE TABLE IF NOT EXISTS `unicat_catalog_attributes_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `configuration_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6786BE5912469DE2` (`category_id`),
  KEY `IDX_6786BE5973F32DD8` (`configuration_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `unicat_catalog_attributes_groups`
--

INSERT INTO `unicat_catalog_attributes_groups` (`id`, `name`, `title`, `created_at`, `category_id`, `configuration_id`) VALUES
(1, 'wares_description', 'Описание товара', '2014-02-12 19:24:52', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_catalog_categories`
--

DROP TABLE IF EXISTS `unicat_catalog_categories`;
CREATE TABLE IF NOT EXISTS `unicat_catalog_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_inheritance` tinyint(1) NOT NULL DEFAULT '1',
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `is_enabled` tinyint(1) DEFAULT '1',
  `position` smallint(6) DEFAULT '0',
  `structure_id` int(10) unsigned DEFAULT NULL,
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_31508DCA989D9B62727ACA702534008B` (`slug`,`parent_id`,`structure_id`),
  KEY `IDX_31508DCA727ACA70` (`parent_id`),
  KEY `IDX_31508DCA2534008B` (`structure_id`),
  KEY `IDX_31508DCA46C53D4C` (`is_enabled`),
  KEY `IDX_31508DCA462CE4F5` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `unicat_catalog_categories`
--

INSERT INTO `unicat_catalog_categories` (`id`, `parent_id`, `slug`, `title`, `is_inheritance`, `meta`, `created_at`, `user_id`, `is_enabled`, `position`, `structure_id`, `properties`) VALUES
(1, NULL, 'connection', 'Техника для связи', 0, 'N;', '2014-02-10 09:29:09', 0, 1, 0, 1, NULL),
(2, 1, 'smartphones', 'Смартфоны', 0, 'a:0:{}', '2014-02-12 21:19:29', 0, 1, 1, 1, 'a:1:{s:11:"description";N;}'),
(3, 1, 'signal_amplifiers', 'Усилители сигнала', 0, 'a:0:{}', '2014-02-12 22:12:43', 0, 1, 0, 1, 'a:1:{s:11:"description";N;}'),
(4, NULL, 'pc', 'Компьютерная техника', 0, 'N;', '2014-02-12 22:14:33', 0, 1, 0, 1, NULL),
(5, 4, 'notebooks', 'Ноутбуки', 0, 'N;', '2014-02-12 22:15:02', 0, 1, 0, 1, NULL),
(6, 4, 'notebooks_stuff', 'Комплектующие для ноутбуков', 0, 'N;', '2014-02-12 22:15:26', 0, 1, 0, 1, NULL),
(7, 6, 'ram', 'Модули памяти', 0, 'N;', '2014-02-12 22:15:42', 0, 1, 0, 1, NULL),
(8, 6, 'hdd25', 'Жесткие диски 2.5', 0, 'N;', '2014-02-12 22:15:59', 0, 1, 0, 1, NULL),
(9, NULL, 'samsung', 'Samsung', 0, 'N;', '2014-02-12 22:17:02', 0, 1, 0, 2, NULL),
(10, 4, 'monitors', 'Мониторы', 0, 'N;', '2014-02-12 22:18:24', 0, 1, 0, 1, NULL),
(11, NULL, 'office', 'Офисная техника', 0, 'N;', '2014-02-12 22:19:07', 0, 1, 0, 1, NULL),
(12, 11, 'printers', 'Принтеры', 0, 'N;', '2014-02-12 22:19:27', 0, 1, 0, 1, NULL),
(13, 11, 'scanners', 'Сканеры', 0, 'N;', '2014-02-12 22:19:43', 0, 1, 0, 1, NULL),
(14, NULL, 'sony', 'Sony', 0, 'N;', '2014-02-12 22:20:07', 0, 1, 0, 2, NULL),
(15, 14, 'vaio', 'Vaio', 1, 'N;', '2014-02-17 21:37:57', 0, 1, 0, 2, NULL),
(16, NULL, 'canon', 'Canon', 1, 'N;', '2014-02-17 21:38:32', 0, 1, 0, 2, NULL),
(17, NULL, 'portable', 'Портативная электроника', 0, 'N;', '2014-02-17 22:00:09', 0, 1, 0, 1, NULL),
(18, 17, 'reflex_cameras', 'Зеркальные фотоаппараты', 1, 'N;', '2014-02-17 22:08:49', 1, 1, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_catalog_items`
--

DROP TABLE IF EXISTS `unicat_catalog_items`;
CREATE TABLE IF NOT EXISTS `unicat_catalog_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(1) DEFAULT '1',
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `attributes` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `user_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `position` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_163452F3989D9B62` (`slug`),
  KEY `IDX_163452F3462CE4F5` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `unicat_catalog_items`
--

INSERT INTO `unicat_catalog_items` (`id`, `is_enabled`, `slug`, `meta`, `attributes`, `user_id`, `created_at`, `position`) VALUES
(1, 1, 'np900', 'a:2:{s:11:"description";N;s:8:"keywords";N;}', 'a:5:{s:5:"title";s:13:"Samsung NP900";s:11:"description";s:18:"Ультрабук";s:8:"in_sight";b:0;s:5:"price";i:5451;s:5:"image";i:1;}', 0, '2014-02-14 07:48:18', 0),
(2, 1, 'galaxy-s4', 'a:2:{s:11:"description";N;s:8:"keywords";N;}', 'a:5:{s:5:"title";s:17:"Samsung Galaxy S4";s:8:"in_sight";b:1;s:5:"price";i:19000;s:5:"image";i:4;s:11:"description";N;}', 0, '2014-02-14 13:13:57', 1),
(3, 1, 'seagate-500g', 'a:2:{s:11:"description";N;s:8:"keywords";N;}', 'a:3:{s:5:"title";s:13:"Seagate 500Gb";s:5:"image";i:3;s:8:"in_sight";b:1;}', 0, '2014-02-17 01:19:23', 0),
(4, 1, 'canon-650d', 'N;', 'a:4:{s:5:"title";s:10:"Canon 650D";s:8:"in_sight";b:1;s:5:"price";i:25000;s:5:"image";i:5;}', 1, '2014-02-17 22:09:56', 0),
(5, 1, 'htc-one', 'a:2:{s:11:"description";N;s:8:"keywords";N;}', 'a:4:{s:5:"title";s:7:"HTC One";s:8:"in_sight";b:1;s:5:"image";i:6;s:5:"price";i:20000;}', 1, '2014-03-06 16:35:40', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_catalog_items_categories_relations`
--

DROP TABLE IF EXISTS `unicat_catalog_items_categories_relations`;
CREATE TABLE IF NOT EXISTS `unicat_catalog_items_categories_relations` (
  `item_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`item_id`,`category_id`),
  KEY `IDX_749FFFB7126F525E` (`item_id`),
  KEY `IDX_749FFFB712469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `unicat_catalog_items_categories_relations`
--

INSERT INTO `unicat_catalog_items_categories_relations` (`item_id`, `category_id`) VALUES
(1, 5),
(1, 9),
(2, 2),
(2, 9),
(3, 8),
(4, 16),
(4, 18),
(5, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat_catalog_items_categories_relations_single`
--

DROP TABLE IF EXISTS `unicat_catalog_items_categories_relations_single`;
CREATE TABLE IF NOT EXISTS `unicat_catalog_items_categories_relations_single` (
  `item_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`item_id`,`category_id`),
  KEY `IDX_85899D72126F525E` (`item_id`),
  KEY `IDX_85899D7212469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `unicat_catalog_items_categories_relations_single`
--

INSERT INTO `unicat_catalog_items_categories_relations_single` (`item_id`, `category_id`) VALUES
(1, 5),
(1, 9),
(2, 2),
(2, 9),
(3, 8),
(4, 16),
(4, 18),
(5, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat__configurations`
--

DROP TABLE IF EXISTS `unicat__configurations`;
CREATE TABLE IF NOT EXISTS `unicat__configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_collection_id` int(10) unsigned DEFAULT NULL,
  `default_structure_id` int(10) unsigned DEFAULT NULL,
  `entities_namespace` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_inheritance` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `items_per_page` smallint(5) unsigned NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F622D4625E237E06` (`name`),
  UNIQUE KEY `UNIQ_F622D4622B36786B` (`title`),
  KEY `IDX_F622D462B52E685C` (`media_collection_id`),
  KEY `IDX_F622D4627E2E521` (`default_structure_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `unicat__configurations`
--

INSERT INTO `unicat__configurations` (`id`, `media_collection_id`, `default_structure_id`, `entities_namespace`, `is_inheritance`, `created_at`, `name`, `title`, `user_id`, `items_per_page`) VALUES
(1, 1, 1, 'SandboxSiteBundle\\Entity\\Catalog\\', 1, '2015-02-28 03:01:59', 'catalog', 'Каталог товаров', 1, 2),
(2, 3, 3, 'SandboxSiteBundle\\Entity\\Blog\\', 1, '2015-03-02 05:23:03', 'blog', 'Блог', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `unicat__structures`
--

DROP TABLE IF EXISTS `unicat__structures`;
CREATE TABLE IF NOT EXISTS `unicat__structures` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `configuration_id` int(11) unsigned DEFAULT NULL,
  `position` smallint(6) DEFAULT '0',
  `entries` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_form` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_default_inheritance` tinyint(1) NOT NULL DEFAULT '0',
  `properties` longtext COLLATE utf8_unicode_ci,
  `is_tree` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_B933004773F32DD8` (`configuration_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `unicat__structures`
--

INSERT INTO `unicat__structures` (`id`, `configuration_id`, `position`, `entries`, `title`, `is_required`, `user_id`, `created_at`, `name`, `title_form`, `is_default_inheritance`, `properties`, `is_tree`) VALUES
(1, 1, 1, 'single', 'Категории', 1, 1, '2014-02-11 23:44:56', 'categories', 'Категория', 0, 'description: #textarea\r\n    type: textarea\r\n    attr:\r\n        class: wysiwyg\r\n        data-theme: advanced', 1),
(2, 1, 2, 'multi', 'Облаго тэгов', 0, 1, '2014-02-11 23:45:18', 'tags', 'Тэги', 0, '', 0),
(3, 2, 0, 'single', 'Категории', 0, 0, '2015-03-02 10:43:54', 'categories', 'Категория', 1, NULL, 1),
(4, 2, 0, 'multi', 'Тэги', 0, 0, '2015-03-08 14:24:38', 'tags', 'Тэги', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
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
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_1483A5E9A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `firstname`, `lastname`, `created_at`) VALUES
(1, 'root', 'root', 'artem@mail.ru', 'artem@mail.ru', 1, 'rvmppg4hla80gw0c88wwkogkc8cg88c', 'pSRvk1iSFWol6tPyvrt8ULb6A03pa3jT8LNsVv9eYC9DSQMFLL91dzHBNvPFUFuICMMvFqzYBnyDVaW+Eg3eRg==', '2015-06-05 04:54:35', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:9:"ROLE_ROOT";}', 0, NULL, '', '', '2014-01-20 00:00:00'),
(2, 'demo', 'demo', 'demo@mail.com', 'demo@mail.com', 1, '15lr4t5s1pdwowoc8k88goc88k00w8', 'k92fZzuVqY4hkumXP9B7EM4pJMNqFLcCKVu2/dRyNPToPjmk9BJneaEszgy4eWjly4hEPp9Tcj5qRAapOQHwJA==', '2015-05-22 00:28:12', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:14:"ROLE_NEWSMAKER";}', 0, NULL, '', '', '2014-01-20 00:00:00'),
(3, 'aaa', 'aaa', 'aaa@aaa.ru', 'aaa@aaa.ru', 1, 'teyhcartb3ks0kw4sw0co0k8ko0gk48', '+Qtvl5uc9knUH6z2ZB/7qqZLueaGSfs1yS7TVt4h6CQtNY/a/wG4gdDV+hxR/eSnotc4PGGrRvqnHfdzOmyJNA==', '2014-01-19 18:41:30', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, '', '', '2014-01-20 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `webforms`
--

DROP TABLE IF EXISTS `webforms`;
CREATE TABLE IF NOT EXISTS `webforms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_use_captcha` tinyint(1) NOT NULL DEFAULT '0',
  `send_button_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `send_notice_emails` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `final_text` longtext COLLATE utf8_unicode_ci,
  `from_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_ajax` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_641866195E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `webforms`
--

INSERT INTO `webforms` (`id`, `created_at`, `title`, `user_id`, `name`, `is_use_captcha`, `send_button_title`, `send_notice_emails`, `final_text`, `from_email`, `is_ajax`) VALUES
(1, '2015-03-17 02:36:43', 'Обратная связь', 1, 'feedback', 1, NULL, NULL, 'Сообщение отправлено', 'noreply@smart-core.org', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `webforms_fields`
--

DROP TABLE IF EXISTS `webforms_fields`;
CREATE TABLE IF NOT EXISTS `webforms_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` smallint(6) DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `web_form_id` int(10) unsigned DEFAULT NULL,
  `params` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `params_yaml` longtext COLLATE utf8_unicode_ci,
  `type` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_enabled` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4FE98D465E237E06` (`name`),
  KEY `IDX_4FE98D46B75935E3` (`web_form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `webforms_fields`
--

INSERT INTO `webforms_fields` (`id`, `created_at`, `name`, `position`, `title`, `user_id`, `web_form_id`, `params`, `params_yaml`, `type`, `is_required`, `is_enabled`) VALUES
(1, '2015-03-17 03:56:03', 'email', 2, 'Ваш емаил', 1, 1, 'a:0:{}', NULL, 'email', 1, 1),
(2, '2015-03-17 03:57:27', 'text', 3, 'Сообщение', 1, 1, 'a:0:{}', NULL, 'textarea', 1, 1),
(3, '2015-03-24 03:15:32', 'name', 0, 'Имя', 1, 1, 'a:0:{}', NULL, 'text', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `webforms_messages`
--

DROP TABLE IF EXISTS `webforms_messages`;
CREATE TABLE IF NOT EXISTS `webforms_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `comment` longtext COLLATE utf8_unicode_ci,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `web_form_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_24719905B75935E3` (`web_form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `webforms_messages`
--

INSERT INTO `webforms_messages` (`id`, `data`, `created_at`, `user_id`, `comment`, `status`, `web_form_id`) VALUES
(1, 'a:3:{s:4:"name";s:4:"1234";s:5:"email";s:12:"root@mail.ru";s:4:"text";s:3:"dfg";}', '2015-03-24 04:17:00', 1, 'nm bnm, bnm,', 1, 1),
(2, 'a:3:{s:4:"name";s:6:"222222";s:5:"email";s:12:"root@mail.ru";s:4:"text";s:11:"54555555555";}', '2015-03-24 04:17:42', 0, 'hfgh 3', 0, 1),
(3, 'a:3:{s:4:"name";s:3:"dfg";s:5:"email";s:12:"root@mail.ru";s:4:"text";s:4:"dfhj";}', '2015-03-24 04:50:33', 0, NULL, 0, 1),
(4, 'a:3:{s:4:"name";s:7:"dfg dfg";s:5:"email";s:12:"root@mail.ru";s:4:"text";s:17:"678 sdfg 547 8fgh";}', '2015-03-24 06:15:54', 1, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `_engine_permissions`
--

DROP TABLE IF EXISTS `_engine_permissions`;
CREATE TABLE IF NOT EXISTS `_engine_permissions` (
  `group_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `default_access` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `descr` text COMMENT 'Описание',
  PRIMARY KEY (`group_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Права доступа';

--
-- Дамп данных таблицы `_engine_permissions`
--

INSERT INTO `_engine_permissions` (`group_id`, `name`, `default_access`, `title`, `descr`) VALUES
('engine', 'folder.read', 1, 'Чтение папок', NULL),
('engine', 'folder.view', 1, 'Отображение', NULL),
('engine', 'folder.write', 0, 'Создание в папке других папок и нод.', NULL),
('engine', 'node.read', 1, 'Чтение ноды', 'Отображается нода или нет, соответственно обрабатывает её движок или нет.'),
('engine', 'node.write', 0, 'Запись ноды', 'Возможность передачи ноде POST данных. '),
('module.smartcore.news', 'create', 0, 'Создание новости', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `_engine_permissions_defaults`
--

DROP TABLE IF EXISTS `_engine_permissions_defaults`;
CREATE TABLE IF NOT EXISTS `_engine_permissions_defaults` (
  `permission` varchar(100) NOT NULL,
  `role_id` varchar(50) NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '0',
  `descr` text NOT NULL,
  PRIMARY KEY (`role_id`),
  KEY `access` (`access`),
  KEY `permission` (`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Переопределения прав по умолчанию для различных ролей';

--
-- Дамп данных таблицы `_engine_permissions_defaults`
--

INSERT INTO `_engine_permissions_defaults` (`permission`, `role_id`, `access`, `descr`) VALUES
('engine:folder.write', 'ROLE_ADMIN', 1, 'Для администраторов разрешена запись в папки.'),
('engine:folder.write', 'ROLE_USER', 0, 'Юзеры не могут создавать папки.');

-- --------------------------------------------------------

--
-- Структура таблицы `_engine_permissions_groups`
--

DROP TABLE IF EXISTS `_engine_permissions_groups`;
CREATE TABLE IF NOT EXISTS `_engine_permissions_groups` (
  `group_id` varchar(50) NOT NULL,
  `descr` text COMMENT 'Описание',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Группы прав доступа';

--
-- Дамп данных таблицы `_engine_permissions_groups`
--

INSERT INTO `_engine_permissions_groups` (`group_id`, `descr`) VALUES
('engine', 'Ядро'),
('module.smartcore.news', 'Модуль Новости'),
('module.smartcore.texter', 'Модуль Текстер');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `blog_articles`
--
ALTER TABLE `blog_articles`
  ADD CONSTRAINT `FK_E42BC34BF675F31B` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_CB80154F12469DE2` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `blog_articles_tags_relations`
--
ALTER TABLE `blog_articles_tags_relations`
  ADD CONSTRAINT `FK_512A6F43BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `blog_tags` (`id`),
  ADD CONSTRAINT `FK_512A6F437294869C` FOREIGN KEY (`article_id`) REFERENCES `blog_articles` (`id`);

--
-- Ограничения внешнего ключа таблицы `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD CONSTRAINT `FK_DC3564813D8E604F` FOREIGN KEY (`parent`) REFERENCES `blog_categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `engine_folders`
--
ALTER TABLE `engine_folders`
  ADD CONSTRAINT `FK_6C047E64A640A07B` FOREIGN KEY (`folder_pid`) REFERENCES `engine_folders` (`id`);

--
-- Ограничения внешнего ключа таблицы `engine_nodes`
--
ALTER TABLE `engine_nodes`
  ADD CONSTRAINT `FK_3055D1B798260155` FOREIGN KEY (`region_id`) REFERENCES `engine_regions` (`id`),
  ADD CONSTRAINT `FK_3055D1B7162CB942` FOREIGN KEY (`folder_id`) REFERENCES `engine_folders` (`id`);

--
-- Ограничения внешнего ключа таблицы `engine_regions_inherit`
--
ALTER TABLE `engine_regions_inherit`
  ADD CONSTRAINT `FK_41BBC12298260155` FOREIGN KEY (`region_id`) REFERENCES `engine_regions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_41BBC122162CB942` FOREIGN KEY (`folder_id`) REFERENCES `engine_folders` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `FK_F70E6EB7B52E685C` FOREIGN KEY (`media_collection_id`) REFERENCES `media_collections` (`id`);

--
-- Ограничения внешнего ключа таблицы `gallery_albums`
--
ALTER TABLE `gallery_albums`
  ADD CONSTRAINT `FK_5661ABED4E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`);

--
-- Ограничения внешнего ключа таблицы `gallery_photos`
--
ALTER TABLE `gallery_photos`
  ADD CONSTRAINT `FK_AAF50C7B1137ABCF` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`);

--
-- Ограничения внешнего ключа таблицы `media_categories`
--
ALTER TABLE `media_categories`
  ADD CONSTRAINT `FK_30D688FC727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `media_categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `media_collections`
--
ALTER TABLE `media_collections`
  ADD CONSTRAINT `FK_244DA17D14E68FF3` FOREIGN KEY (`default_storage_id`) REFERENCES `media_storages` (`id`);

--
-- Ограничения внешнего ключа таблицы `media_files`
--
ALTER TABLE `media_files`
  ADD CONSTRAINT `FK_192C84E85CC5DB90` FOREIGN KEY (`storage_id`) REFERENCES `media_storages` (`id`),
  ADD CONSTRAINT `FK_192C84E812469DE2` FOREIGN KEY (`category_id`) REFERENCES `media_categories` (`id`),
  ADD CONSTRAINT `FK_192C84E8514956FD` FOREIGN KEY (`collection_id`) REFERENCES `media_collections` (`id`);

--
-- Ограничения внешнего ключа таблицы `media_files_transformed`
--
ALTER TABLE `media_files_transformed`
  ADD CONSTRAINT `FK_1084B87D93CB796C` FOREIGN KEY (`file_id`) REFERENCES `media_files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1084B87D514956FD` FOREIGN KEY (`collection_id`) REFERENCES `media_collections` (`id`),
  ADD CONSTRAINT `FK_1084B87D5CC5DB90` FOREIGN KEY (`storage_id`) REFERENCES `media_storages` (`id`);

--
-- Ограничения внешнего ключа таблицы `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `FK_7D053A93FE54D947` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`),
  ADD CONSTRAINT `FK_7D053A93162CB942` FOREIGN KEY (`folder_id`) REFERENCES `engine_folders` (`id`),
  ADD CONSTRAINT `FK_7D053A935550C4ED` FOREIGN KEY (`pid`) REFERENCES `menu_items` (`id`);

--
-- Ограничения внешнего ключа таблицы `simple_news`
--
ALTER TABLE `simple_news`
  ADD CONSTRAINT `FK_B232FBE93A51721D` FOREIGN KEY (`instance_id`) REFERENCES `simple_news_instances` (`id`);

--
-- Ограничения внешнего ключа таблицы `simple_news_instances`
--
ALTER TABLE `simple_news_instances`
  ADD CONSTRAINT `FK_98EDD001B52E685C` FOREIGN KEY (`media_collection_id`) REFERENCES `media_collections` (`id`);

--
-- Ограничения внешнего ключа таблицы `slides`
--
ALTER TABLE `slides`
  ADD CONSTRAINT `FK_B8C020912CCC9638` FOREIGN KEY (`slider_id`) REFERENCES `sliders` (`id`);

--
-- Ограничения внешнего ключа таблицы `unicat_blog_attributes`
--
ALTER TABLE `unicat_blog_attributes`
  ADD CONSTRAINT `FK_CD6A232EFE54D947` FOREIGN KEY (`group_id`) REFERENCES `unicat_blog_attributes_groups` (`id`);

--
-- Ограничения внешнего ключа таблицы `unicat_blog_attributes_groups`
--
ALTER TABLE `unicat_blog_attributes_groups`
  ADD CONSTRAINT `FK_9E1338C82534008B` FOREIGN KEY (`category_id`) REFERENCES `unicat_blog_categories` (`id`),
  ADD CONSTRAINT `FK_9E1338C873F32DD8` FOREIGN KEY (`configuration_id`) REFERENCES `unicat__configurations` (`id`);

--
-- Ограничения внешнего ключа таблицы `unicat_blog_categories`
--
ALTER TABLE `unicat_blog_categories`
  ADD CONSTRAINT `FK_F57287B52534008B` FOREIGN KEY (`structure_id`) REFERENCES `unicat__structures` (`id`),
  ADD CONSTRAINT `FK_F57287B5727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `unicat_blog_categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `unicat_blog_items_categories_relations`
--
ALTER TABLE `unicat_blog_items_categories_relations`
  ADD CONSTRAINT `FK_B34834DD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `unicat_blog_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B34834DD126F525E` FOREIGN KEY (`item_id`) REFERENCES `unicat_blog_items` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `unicat_blog_items_categories_relations_single`
--
ALTER TABLE `unicat_blog_items_categories_relations_single`
  ADD CONSTRAINT `FK_12F155812469DE2` FOREIGN KEY (`category_id`) REFERENCES `unicat_blog_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_12F1558126F525E` FOREIGN KEY (`item_id`) REFERENCES `unicat_blog_items` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `unicat_catalog_attributes`
--
ALTER TABLE `unicat_catalog_attributes`
  ADD CONSTRAINT `FK_32E9C31CFE54D947` FOREIGN KEY (`group_id`) REFERENCES `unicat_catalog_attributes_groups` (`id`);

--
-- Ограничения внешнего ключа таблицы `unicat_catalog_attributes_groups`
--
ALTER TABLE `unicat_catalog_attributes_groups`
  ADD CONSTRAINT `FK_6786BE592534008B` FOREIGN KEY (`category_id`) REFERENCES `unicat_catalog_categories` (`id`),
  ADD CONSTRAINT `FK_41BAD1D773F32DD8` FOREIGN KEY (`configuration_id`) REFERENCES `unicat__configurations` (`id`);

--
-- Ограничения внешнего ключа таблицы `unicat_catalog_categories`
--
ALTER TABLE `unicat_catalog_categories`
  ADD CONSTRAINT `FK_8FD9B4B3727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `unicat_catalog_categories` (`id`),
  ADD CONSTRAINT `FK_8FD9B4B32534008B` FOREIGN KEY (`structure_id`) REFERENCES `unicat__structures` (`id`);

--
-- Ограничения внешнего ключа таблицы `unicat_catalog_items_categories_relations`
--
ALTER TABLE `unicat_catalog_items_categories_relations`
  ADD CONSTRAINT `FK_749FFFB7126F525E` FOREIGN KEY (`item_id`) REFERENCES `unicat_catalog_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_749FFFB712469DE2` FOREIGN KEY (`category_id`) REFERENCES `unicat_catalog_categories` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `unicat_catalog_items_categories_relations_single`
--
ALTER TABLE `unicat_catalog_items_categories_relations_single`
  ADD CONSTRAINT `FK_85899D72126F525E` FOREIGN KEY (`item_id`) REFERENCES `unicat_catalog_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_85899D7212469DE2` FOREIGN KEY (`category_id`) REFERENCES `unicat_catalog_categories` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `unicat__configurations`
--
ALTER TABLE `unicat__configurations`
  ADD CONSTRAINT `FK_9D36C6A0B52E685C` FOREIGN KEY (`media_collection_id`) REFERENCES `media_collections` (`id`),
  ADD CONSTRAINT `FK_9D36C6A07E2E521` FOREIGN KEY (`default_structure_id`) REFERENCES `unicat__structures` (`id`);

--
-- Ограничения внешнего ключа таблицы `unicat__structures`
--
ALTER TABLE `unicat__structures`
  ADD CONSTRAINT `FK_239D6D8E73F32DD8` FOREIGN KEY (`configuration_id`) REFERENCES `unicat__configurations` (`id`);

--
-- Ограничения внешнего ключа таблицы `webforms_fields`
--
ALTER TABLE `webforms_fields`
  ADD CONSTRAINT `FK_4FE98D46B75935E3` FOREIGN KEY (`web_form_id`) REFERENCES `webforms` (`id`);

--
-- Ограничения внешнего ключа таблицы `webforms_messages`
--
ALTER TABLE `webforms_messages`
  ADD CONSTRAINT `FK_24719905B75935E3` FOREIGN KEY (`web_form_id`) REFERENCES `webforms` (`id`);
