<?php 

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerAware;

class Theme extends ContainerAware
{
    protected $paths = [];
    protected $template;
    protected $theme_path;

    protected $css_path;
    protected $js_path;
    protected $img_path;

    protected $ini;
    protected $vendor_path;

    /**
     * Constructor.
     */
    public function __construct($path = '/')
    {
        $this->theme_path   = $path;
        $this->css_path     = $path . 'css/';
        $this->js_path      = $path . 'js/';
        $this->img_path     = $path . 'img/';
        $this->ini          = [];
    }

    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    public function setTemplate($name)
    {
        $this->template = $name;
    }

    public function setThemePath($path)
    {
        $this->theme_path = $path;
    }

    protected function parseIni($template)
    {
        foreach ($this->paths as $path) {
            $ini_file = $path . '/' . $template . '.ini';
            if (file_exists($ini_file)) {
                $current_ini = parse_ini_file($ini_file, true);

                if (isset($current_ini['extend'])) {
                    $this->parseIni($current_ini['extend']);
                }

                $this->ini = $current_ini + $this->ini;
            }
        }
    }

    public function processConfig($assets, $template)
    {
        // @todo продумать подключение ini-шников!!!
        $this->paths        = [
            $this->container->get('kernel')->getRootDir() . '/Resources/views',
            $this->container->get('kernel')->getBundle('DemoSiteBundle')->getPath() . '/Resources/views', // @todo Настройка имени бандла сайта.
            $this->container->get('kernel')->getBundle('CMSBundle')->getPath() . '/Resources/views',
        ];
        $this->template     = $template;
        $this->vendor_path  = $assets['vendor'];
        $this->theme_path   = $assets['theme_path'];
        $this->img_path     = $assets['theme_img_path'];
        $this->css_path     = $assets['theme_css_path'];
        $this->js_path      = $assets['theme_js_path'];

        krsort($this->paths);

        $this->parseIni($this->template);

        if (isset($this->ini['img_path'])) {
            $this->img_path = $this->theme_path . $this->ini['img_path'];
        }

        if (isset($this->ini['css_path'])) {
            $this->css_path = $this->theme_path . $this->ini['css_path'];
        }

        if (isset($this->ini['js_path'])) {
            $this->js_path = $this->theme_path . $this->ini['js_path'];
        }

        foreach ($this->ini as $key => $value) {
            switch ($key) {
                case 'doctype':
                    $this->container->get('html')->doctype($value);
                    break;
                case 'css':
                    $css_list = explode(',', $value);
                    foreach ($css_list as $css_filename) {
                        $css = trim($css_filename);
                        if ( ! empty($css) ) {
                            if (false !== strpos($css, '{VENDOR}')) {
                                $css = str_replace('{VENDOR}', $this->vendor_path, $css);
                            } else {
                                $css = $this->css_path . $css;
                            }

                            $this->container->get('html')->css($css);
                        }
                    }
                    break;
                case 'js':
                    $js_list = explode(',', $value);
                    foreach ($js_list as $js_filename) {
                        $tmp = trim($js_filename);
                        if ( ! empty($tmp) ) {
                            if (false !== strpos($tmp, '{VENDOR}')) {
                                $tmp = str_replace('{VENDOR}', $this->vendor_path, $tmp);
                            } else {
                                $tmp = $this->js_path . $tmp;
                            }

                            $this->container->get('html')->js($tmp);
                        }
                    }
                    break;
                case 'js_lib': // @todo 
                    $js_libs = explode(',', $value);
                    foreach ($js_libs as $js_lib) {
                        $this->container->get('cms.jslib')->call(trim($js_lib));
                    }
                    break;
                case 'icon':
                //case 'shortcut_icon':
                    if ( ! empty($value) ) {
                        $tmp = parse_url($value);
                        if (substr($tmp['path'], -4) == '.png') {
                            $type = 'image/png';
                        } elseif (substr($tmp['path'], -4) == '.gif') {
                            $type = 'image/gif';
                        } else {
                            $type = 'image/vnd.microsoft.icon';
                        }

                        $attr = [
                            'rel' => 'icon',
                            //'rel' => 'shortcut icon',
                            'type' => $type,
                            //'_ie' => 'IE',
                        ];

                        if (false !== strpos($value, '{IMG_PATH}')) {
                            $value = str_replace('{IMG_PATH}', $this->img_path, $value);
                        }

                        $this->container->get('html')->link($value, $attr);
                    }
                    break;
                default;
            }
        } // end foreach $this->ini
    }
}
