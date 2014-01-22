<?php
/**
 * Перегружены 2 метода.
 * 
 * В отличие от оригинального класса, отрисовка контента производится только в момент получения контента,
 * а не в момент его установки.
 * 
 * Таким образом появляется возможность получить "чистый" контент вернувшийся от контроллера.
 */
namespace SmartCore\Bundle\CMSBundle;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * @todo может быть переименовать в ModuleResponse
 */
class Response extends BaseResponse
{
    /**
     * @deprecated
     */
    protected $cms_front_controls = [];

    /**
     * @deprecated
     */
    public function setFrontControls($data)
    {
        $this->cms_front_controls = $data;
    }

    /**
     * @deprecated
     */
    public function getFrontControls()
    {
        return $this->cms_front_controls;
    }

    /**
     * В отличии от оригинального метода, контент ставится как есть, а не преобразовывается в строку.
     *
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable([$content, '__toString'])) {
            throw new \UnexpectedValueException('The Response content must be a string or object implementing __toString(), "'.gettype($content).'" given.');
        }

        $this->content = $content;

        return $this;
    }

    /**
     * Получить контент в виде строки.
     * 
     * @return string
     */
    public function getContent()
    {
        return (string) $this->content;
    }

    /**
     * Получить контент в нативном виде т.е. если это будет объект, то он будет получен без преобразования в строку.
     * 
     * @return object|string
     *
     * @deprecated
     */
    public function getContentRaw()
    {
        return $this->content;
    }
}
