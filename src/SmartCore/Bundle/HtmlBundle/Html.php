<?php

namespace SmartCore\Bundle\HtmlBundle;

/**
 * @todo поддерку тега <base>
 * @todo document_ready
 * @todo Безопасные скрипты: //<![CDATA[' .... //]]>
 * @todo продумать приоритеты подключения LESS и CSS, а то сейчас LESS подключается только через тег link, а он выводится вперед всех. возможно это и не так важно ;)
 * @todo поддержка тега lang внутри <html>
 */
class Html
{
    protected $sorted       = [];

    protected $doctype;
    public $lang            = 'ru';

    protected $html;

    public $title           = '';
    public $meta            = [];
    public $styles          = [];
    public $scripts         = [];
    public $links           = [];
    public $document_ready  = '';
    public $custom_code     = '';
    public $body_attributes = [];

    protected $direction    = 'ltr';

    // Завершающий символ. Например для HTML - <br>, а для XHTML - <br />
    public $end;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setDoctype('html5');
        //$this->setHtml('html5');
        //$this->setMetaHttpEquiv('Content-Type', 'text/html; charset=utf-8');
        //$this->setMetaHttpEquiv('Content-Language', 'ru');
    }

    /**
     * Здесь же генерация открытия тега <html> с аргументами для доктайпа.
     *
     * @return string
     */
    public function getDoctype()
    {
        $doctype = "<!DOCTYPE html>\n<html lang=\"{$this->lang}\">";
        switch ($this->doctype) {
            case 'xhtml11':
                $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
                $doctype .= "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemalocation=\"http://www.w3.org/1999/xhtml http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd\" xml:lang=\"{$this->lang}\" lang=\"{$this->lang}\" dir=\"{$this->direction}\">";
                break;
            case 'xhtml1_strict':
            case 'xhtml1-strict':
            case 'xhtml1':
            case 'xhtml':
                $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
                $doctype .= "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"{$this->lang}\" lang=\"{$this->lang}\" dir=\"{$this->direction}\">";
                break;
            case 'xhtml1_transitional':
            case 'xhtml1-trans':
            case 'xhtml-trans':
                $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
                $doctype .= "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"{$this->lang}\" lang=\"{$this->lang}\" dir=\"{$this->direction}\">";
                break;
            case 'xhtml1_frameset':
            case 'xhtml1-frame':
            case 'xhtml-frame':
                $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
                $doctype .= "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"{$this->lang}\" lang=\"{$this->lang}\" dir=\"{$this->direction}\">";
                break;
            case 'xhtml1_rdfa':
                $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">';
                $doctype .= "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"{$this->lang}\" lang=\"{$this->lang}\" dir=\"{$this->direction}\">";
                break;
            case 'xhtml_basic1':
                $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">';
                $doctype .= "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"{$this->lang}\" lang=\"{$this->lang}\" dir=\"{$this->direction}\">";
                break;
            case 'html4-trans':
            case 'html4_loose':
            case 'html4_transitional':
                $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
                $doctype .= "\n<html>";
                break;
            case 'html4':
            case 'html4_strict':
            case 'html4-strict':
                $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
                $doctype .= "\n<html>";
                break;
            case 'html4_frameset':
            case 'html4-frame':
                $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
                $doctype .= "\n<html>";
                break;
            default:
        }

        return $doctype . "\n";
    }

    /**
     * @return bool
     */
    public function isHtml5()
    {
        return ($this->doctype == 'html5') ? true : false;
    }

    /**
     * @param string $doctype
     * @return $this
     */
    public function setDoctype($doctype = 'html5')
    {
        $this->doctype = $doctype;
        $this->end     = strpos(strtolower($doctype), 'html4') ? '' : ' /';

        return $this;
    }

    /**
     * @param string $keyword
     * @return $this
     */
    public function addMetaKeyword($keyword)
    {
        if (isset($this->meta['name']['keywords']) and ! empty($this->meta['name']['keywords'])) {
            $this->meta['name']['keywords'] .= ', ' . $keyword;
        } else {
            $this->setMeta('keywords', $keyword);
        }

        return $this;
    }

    /**
     * @param string $descr
     * @return $this
     */
    public function setMetaDescription($descr)
    {
        $this->setMeta('description', $descr);

        return $this;
    }

    /**
     * @param string $lang
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = strtolower($lang);

        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Добавить атрибут тэга <body>.
     *
     * @param string $attr
     * @param string $value
     * @return $this
     */
    public function setBodyAttribute($attr, $value)
    {
        $this->body_attributes[$attr] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param string $content
     * @param string $type (name, http-equiv, property)
     * @return $this
     */
    public function setMeta($name, $content, $type = 'name')
    {
        $this->meta[$type][strtolower($name)] = $content;

        return $this;
    }

    /**
     * @param array  $meta_tags
     * @return $this
     */
    public function setMetas(array $meta_tags)
    {
        $this->meta['name'] = $meta_tags;

        return $this;
    }

    /**
     * @param string $name
     * @param string $content
     * @return $this
     */
    public function setMetaHttpEquiv($name, $content)
    {
        $this->setMeta($name, $content, 'http-equiv');

        return $this;
    }

    /**
     * @param string $name
     * @param string $content
     * @return $this
     */
    public function setMetaProperty($name, $content)
    {
        $this->setMeta($name, $content, 'property');

        return $this;
    }

    /**
     * Привязка внешних документов.
     *
     * @param string $href
     * @param array null $params
     * @param int $priority
     * @return $this
     */
    public function addLink($href, $params = null, $priority = 0)
    {
        $data = array('href' => $href);

        if (is_array($params)) {
            $data = $params + $data;
        } elseif (is_numeric($params)) {
            $priority = $params;
        }

        ksort($data);
        $this->sorted['links'][$priority][] = $data;
        $this->sort('links');

        return $this;
    }

    /**
     * Добавить данные для тега <script>.
     *
     * @param string $input - src или code.
     * @param array|string $params - параметры. (_code - вставить код между тегами <script> и </script>.)
     * @param int $priority - чем больше, чем раньше подключится
     * @return $this
     */
    public function addScript($input, $params = null, $priority = 0)
    {
        $data = array('type' => 'text/javascript');

        if (is_array($params)) {
            $data = $params + $data;
        } elseif (is_numeric($params)) {
            $priority = $params;
        }

        $tmp = parse_url($input);
        if (substr($tmp['path'], -3) == '.js') {
            $data['src'] = $input;
        } else {
            $data['_code'] = $input;
        }

        ksort($data);
        $this->sorted['scripts'][$priority][] = $data;
        $this->sort('scripts');

        return $this;
    }

    /**
     * Добавить данные для тега <style>.
     *
     * @param string $input - href или code.
     * @param array|string $params - параметры. (_code - вставляет код между тегами <style> и </style>)
     * @param int $priority - позиция (чем больше, чем раньше подключится)
     * @return $this
     */
    public function addStyle($input, $params = null, $priority = 0)
    {
        $data = ['type' => 'text/css', 'media' => 'all'];

        if (is_array($params)) {
            $data = $params + $data;
        } elseif (is_numeric($params)) {
            $priority = $params;
        }

        $tmp = parse_url($input);
        if (substr($tmp['path'], -4) == '.css') {
            $data['href'] = $input;
        } elseif (substr($tmp['path'], -5) == '.less') {
            $this->addLink($input, [
                'rel'   => 'stylesheet/less',
                'type'  => 'text/css',
                'media' => $data['media'],
            ]);

            return true;
        } else {
            $data['_code'] = $input;
        }

        ksort($data);
        $this->sorted['styles'][$priority][] = $data;
        $this->sort('styles');

        return $this;
    }

    /**
     * Добавить JS код, который должен быть исполнен при событии document-ready.
     * Метод автоматически подключает либу jquery.
     *
     * @param string $js_code
     * @return $this
     */
    public function addDocumentReady($js_code)
    {
        $this->document_ready[] = $js_code;

        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Добавить строку перед title.
     *
     * @param string $title
     * @return $this
     */
    public function titlePrepend($title)
    {
        $this->title = $title . $this->title;

        return $this;
    }

    /**
     * Добавить строку после title.
     *
     * @param string $title
     * @return $this
     */
    public function titleAppend($title)
    {
        $this->title .= $title;

        return $this;
    }

    /**
     * Соритировка.
     *
     * @param string $name
     */
    protected function sort($name)
    {
        $this->$name = array();
        krsort($this->sorted[$name]);
        foreach ($this->sorted[$name] as $value) {
            foreach ($value as $value2) {
                array_push($this->$name, $value2);
            }
        }
    }

    /**
     * Добавление произвольного кода в секцию <head>
     *
     * @param string $code
     */
    public function appendToHead($code)
    {
        $this->custom_code .= $code . "\n";
    }

    // -----------------------------------------------------------------------
    // Ниже описаны алиасы на основные методы для сокрашенного синтаксиса.
    // -----------------------------------------------------------------------

    public function js($input, $params = null, $priority = 0)
    {
        return $this->addScript($input, $params, $priority);
    }

    public function css($input, $params = null, $priority = 0)
    {
        return $this->addStyle($input, $params, $priority);
    }

    public function meta($name, $content, $type = 'name')
    {
        return $this->setMeta($name, $content, $type);
    }

    public function metas($meta_tags)
    {
        return $this->setMetas($meta_tags);
    }

    public function title($title)
    {
        return $this->setTitle($title);
    }

    public function description($descr)
    {
        return $this->setMetaDescription($descr);
    }
    public function keyword($keyword)
    {
        return $this->addMetaKeyword($keyword);
    }

    public function keywords($keyword)
    {
        return $this->setMeta('keywords', $keyword);
    }

    public function link($href, $args = null, $priority = 0)
    {
        return $this->addLink($href, $args, $priority);
    }

    public function bodyAttr($attr, $value)
    {
        return $this->setBodyAttribute($attr, $value);
    }

    public function doctype($doctype)
    {
        return $this->setDoctype($doctype);
    }

    public function lang($lang)
    {
        return $this->setLang($lang);
    }
}
