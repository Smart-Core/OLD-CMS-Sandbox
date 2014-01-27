<?php

namespace SmartCore\Bundle\HtmlBundle\Service;

class TidyService
{
    /**
     * @param string $html
     * @return string
     */
    public function prettifyFragment($html)
    {
        if (!class_exists('tidy')) {
            return $html;
        }

        $tidy = new \tidy();
        $tidy->parseString($html, [
            'fix-backslash'  => true,
            'output-xhtml'   => true,
            'indent'         => true,
            'wrap'           => 150,
            'show-body-only' => true,
        ], 'utf8');

        return $tidy->value;
    }

    /**
     * @param string $html
     * @return string
     */
    public function prettifyDocument($html)
    {
        if (!class_exists('tidy')) {
            return $html;
        }

        $tidy = new \tidy();
        $tidy->parseString($html, [
            'fix-backslash'  => true,
            'output-xhtml'   => true,
            'indent'         => true,
            'wrap'           => 150,
        ], 'utf8');

        return $tidy->value;
    }
}
