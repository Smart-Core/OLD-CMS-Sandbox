Smart Core CMS Sandbox
======================
[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/Smart-Core/CMS-Sandbox?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

The modern system for creating and managing web projects with open source, based on the Symfony2 Framework.

Очищение песочницы для запуска пустого проекта
----------------------------------------------

Удалить следующие файлы и папки:
```
/app/Resources/views/*.twig
/dist/
/src/Demo/SiteBundle/Entity/*
/web/_media/* (кроме .htaccess)
/web/images/* (кроме .htaccess)
/web/theme/css/*
/web/theme/images/*
/web/theme/js/*
/smart_core.sql
```

Создать базовый шаблон
```
/app/Resources/views/index.html.twig
```

Со следующим содержимым:

``` twig
{% extends '@Html/base.html.twig' %}

{% set use_libs = {
    'bootstrap': null,
} %}

{% block title %}{{ cms_html_title() }}{% endblock %}

{% block styles %}
    {{ parent() }}
{% endblock %}

{% block scripts %}
    {{ parent() }}
{% endblock %}

{% block body %}
<div class="container">

    {% include '@CMS/flash_messages.thml.twig' %}

    <div id="content">
        {% block content content %}
    </div>

</div>
{% endblock body %}

```

Создание схемы БД

``` bash
$ app/console doctrine:schema:update --force
```

Создание нового пользователя

``` bash
$ app/console fos:user:create
```

Назначить роль ROLE_ADMIN

``` bash
$ app/console fos:user:promote
```

Пройти в раздел "Структура", по адресу http://my-projeсt/admin/structure/ и создать главную папку.

Создать "Хранилище" для медиаколлекции, по адресу http://my-projeсt/admin/config/media/storage_create/.

Для модулей, которые будут использовать медиаколлекцию, нужно создать коллекции по адресу http://my-projeсt/admin/config/media/collection_create/
