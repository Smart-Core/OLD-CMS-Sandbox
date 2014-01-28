<?php

namespace SmartCore\Bundle\CMSBundle\Tools;

class Breadcrumbs implements \Iterator, \Countable
{
    protected $_position = 0;

    /**
     * Массив с хлебными крошками.
     */
    protected $_breadcrumbs = [];

    public function rewind()
    {
        $this->_position = 0;
    }

    public function current()
    {
        return $this->_breadcrumbs[$this->_position];
    }

    public function key()
    {
        return $this->_position;
    }

    public function next()
    {
        ++$this->_position;
    }

    public function valid()
    {
        return isset($this->_breadcrumbs[$this->_position]);
    }

    public function count()
    {
        return count($this->_breadcrumbs);
    }

    /**
     * Получить последний пункт.
     *
     * @return arra|null
     */
    public function getLast()
    {
        return (isset($this->_breadcrumbs[$this->count() - 1]))
            ? $this->_breadcrumbs[$this->count() - 1]
            : null;
    }

    /**
     * Обновление последнего пункта.
     *
     * @param $uri
     * @param $title
     * @param bool $descr
     */
    public function updateLast($uri, $title, $descr = false)
    {
        if (isset($this->_breadcrumbs[$this->count() - 1])) {
            $this->_breadcrumbs[$this->count() - 1] = [
                'uri'   => $uri,
                'title' => $title,
                'descr' => $descr,
            ];
        }
    }

    /**
     * Установить данные.
     *
     * @param $data
     */
    public function assign($data)
    {
        $this->_breadcrumbs = $data;
    }

    /**
     * Добавление хлебной крошки.
     *
     * @param string $uri
     * @param string $title
     * @param string $descr
     */
    public function add($uri, $title, $descr = false)
    {
        $this->_breadcrumbs[] = [
            'uri'   => $uri,
            'title' => $title,
            'descr' => $descr,
        ];
    }

    /**
     * Получиить хлебные крошки.
     *
     * @return array
     */
    public function get($num = false)
    {
        // @todo если $num отрицательный, то вернуть указанный номер с конца, например -1 это последний, а -2 предпослений и т.д...

        $data = [];
        $current_uri = '';
        foreach ($this->_breadcrumbs as $key => $value) {
            $data[$key] = $value;
            if ($this->isAbsolutePath($value['uri'])) {
                $current_uri = $value['uri'];
                continue;
            } else {
                $current_uri .= $value['uri'];
                $data[$key]['uri'] = $current_uri;
            }
        }

        return ($num === false) ? $data : $data[$num];
    }

    /**
     * Получить всю цепочку.
     *
     * @param
     */
    public function all()
    {
        return $this->get();
    }

    /**
     * Получение ссылки на последнюю крошку.
     */
    public function getLastUri()
    {
        $item = $this->get(count($this->_breadcrumbs) - 1);

        return $item['uri'];
    }

    /**
     * Отрисовщик по умолчанию.
     */
    public function display()
    {
        $bc = $this->get();
        $cnt = count($bc);
        if ($cnt > 0) {
            foreach ($bc as $item) {
                echo --$cnt ? "<a href=\"{$item['uri']}\" title=\"{$item['descr']}\">" : '';
                echo $item['title'];
                echo $cnt ? "</a>&nbsp;&raquo;&nbsp;" : '';
            }
            echo "\n";
        }
    }

    /**
     * Является ли указанный путь абсолютным.
     *
     * @param string $path
     * @return string
     */
    public function isAbsolutePath($path)
    {
        return (strpos($path, '/') === 0 or strpos($path, ':') === 1) ? true : false;
    }
}
