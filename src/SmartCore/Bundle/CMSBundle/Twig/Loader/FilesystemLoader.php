<?php

namespace SmartCore\Bundle\CMSBundle\Twig\Loader;

use Liip\ThemeBundle\Twig\Loader\FilesystemLoader as BaseFilesystemLoader;
use SmartCore\Bundle\CMSBundle\Entity\Node;
use SmartCore\Bundle\CMSBundle\Twig\Locator\TemplateLocator;

class FilesystemLoader extends BaseFilesystemLoader
{
    /**
     * @param Node|null $node
     */
    public function setModuleTheme(Node $node = null)
    {
        if ($node) {
            $this->getTemplateLocator()->getLocator()->setModuleTheme($node->getTemplate());
            $this->clearCacheForModule($node->getModule());
        } else {
            $this->getTemplateLocator()->getLocator()->setModuleTheme(null);
        }
    }

    /**
     * @param string $prefix
     */
    public function clearCacheForModule($prefix)
    {
        if (empty($this->cache)) {
            return;
        }

        $this->getTemplateLocator()->clearCacheForModule($prefix);

        $prefix .= 'Module';

        foreach ($this->cache as $tpl => $__dummy_path) {
            if (0 === strpos($tpl, $prefix.':')) {
                unset($this->cache[$tpl]);
            }
        }
    }

    /**
     * @return TemplateLocator
     */
    protected function getTemplateLocator()
    {
        return $this->locator;
    }
}
