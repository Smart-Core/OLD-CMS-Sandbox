<?php

namespace SmartCore\Bundle\CMSBundle\Twig\Locator;

use Liip\ThemeBundle\Locator\TemplateLocator as BaseTemplateLocator;

class TemplateLocator extends BaseTemplateLocator
{
    public function clearCacheForModule($prefix)
    {
        if (empty($this->cache)) {
            return;
        }

        $prefix .= 'Module';

        foreach ($this->cache as $tpl => $__dummy_path) {
            if (0 === strpos($tpl, $prefix.':')) {
                unset($this->cache[$tpl]);
            }
        }
    }

    /**
     * @return ModuleThemeLocator
     */
    public function getLocator()
    {
        return $this->locator;
    }
}
