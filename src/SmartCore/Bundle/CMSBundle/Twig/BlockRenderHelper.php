<?php

namespace SmartCore\Bundle\CMSBundle\Twig;

class BlockRenderHelper
{
    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        foreach ($this as $nodeId => $response) {
            echo $response->getContent();
        }

        return '';
    }
}
