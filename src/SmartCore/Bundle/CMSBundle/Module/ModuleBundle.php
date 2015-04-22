<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use Knp\RadBundle\AppBundle\Bundle;

/**
 * Использование Knp\RadBundle для автоматического конфигурирования сервисов,
 * если у модуля существует файл config/services.yml.
 */
abstract class ModuleBundle extends Bundle
{
    use ModuleBundleTrait;
}
