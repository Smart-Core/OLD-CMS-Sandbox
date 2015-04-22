<?php

namespace SmartCore\Bundle\SettingsBundle\Twig;

use SmartCore\Bundle\SettingsBundle\Manager\SettingsManager;

class SettingsExtension extends \Twig_Extension
{
    /** @var SettingsManager */
    protected $settingsManager;

    /**
     * @param SettingsManager $settingsManager
     */
    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            'setting' => new \Twig_Function_Method($this, 'getSetting'), // @todo \Twig_SimpleFunction
            'is_setting' => new \Twig_Function_Method($this, 'isSetting'),
        ];
    }

    /**
     * @param string $bundle
     * @param string $name
     *
     * @return string
     */
    public function getSetting($bundle, $name)
    {
        return $this->settingsManager->get($bundle, $name);
    }

    /**
     * @param string $bundle
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function isSetting($bundle, $name, $value)
    {
        if ($this->settingsManager->get($bundle, $name) == $value) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smart_core_settings_twig_extension';
    }
}
