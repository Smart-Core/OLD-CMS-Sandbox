SmartCore SessionBundle
=======================

Doctrine ORM Session Storage Handler

Из особенностей, бандл хранит поле user_id, что позволяет получать список пользователей online.

Installation
------------

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new SmartCore\Bundle\SessionBundle\SmartCoreSessionBundle(),
    );
}
```

Enable handler_id:

``` yaml    
# app/config/config.yml

framework:
    session:
        handler_id: smart_core_session.handler
```

@todo
-----

 *  Конфигурирование автоподключения, например:
 
    ``` yaml
    # app/config/config.yml
    
    smart_core_session:
        autoconfigure: true
    ```
    
    В этом случае, будет автоматически применяется handler_id в конфигурации framework бандла.
