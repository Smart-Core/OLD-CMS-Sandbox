Smart Core CMS Sandbox
======================
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/Smart-Core/chat?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

The modern system for creating and managing web projects with open source, based on the Symfony2 Framework.


Пока не созданы тэги, для проектов использовать следующий набор пакетов в `composer.json`:
```
    "smart-core/module-breadcrumbs": "dev-master",
    "smart-core/module-gallery": "dev-master",
    "smart-core/module-menu": "dev-master",
    "smart-core/module-simplenews": "dev-master",
    "smart-core/module-slider": "dev-master",
    "smart-core/module-texter": "dev-master",
    "smart-core/module-unicat": "dev-master",
    "smart-core/module-user": "dev-master",
    "smart-core/module-webform": "dev-master",
    "smart-core/module-widget": "dev-master",
    
    "smart-core/cms-bundle": "dev-master",    
    "smart-core/cms-generator-bundle": "dev-master",
    "smart-core/core-bundle": "dev-master",
    "smart-core/db-dumper-bundle": "dev-master",
    "smart-core/felib-bundle": "dev-master",
    "smart-core/html-bundle": "dev-master",
    "smart-core/media-bundle": "dev-master",
    "smart-core/rich-editor-bundle": "dev-master",
    "smart-core/seo-bundle": "dev-master",
    "smart-core/session-bundle": "dev-master",
    "smart-core/settings-bundle": "dev-master",
    "smart-core/simple-profiler-bundle": "dev-master",
    "smart-core/sitemap-bundle": "dev-master"
```


Удаление подмодуля (например `src/SmartCore/Bundle/SimpleProfilerBundle`):
```
    git submodule deinit -f src/SmartCore/Bundle/SimpleProfilerBundle
    git rm -f src/SmartCore/Bundle/SimpleProfilerBundle
    rm -rf .git/modules/src/SmartCore/Bundle/SimpleProfilerBundle
``` 

