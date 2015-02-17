Smart Core CMS Sandbox
======================
[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/Smart-Core/CMS-Sandbox?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

The modern system for creating and managing web projects with open source, based on the Symfony2 Framework.

Очищение песочницы для запуска пустого проекта
----------------------------------------------

``` bash
bin/sandbox_purge
```

Login as new user into http://my-projeсt/admin/.

Пройти в раздел "Структура", по адресу `http://my-projeсt/admin/structure/` и создать главную папку.

Создать "Хранилище" для медиаколлекции, по адресу `http://my-projeсt/admin/config/media/storage_create/`.

Для модулей, которые будут использовать медиаколлекцию, нужно создать коллекции по адресу `http://my-projeсt/admin/config/media/collection_create/`.
