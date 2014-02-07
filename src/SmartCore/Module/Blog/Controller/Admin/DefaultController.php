<?php

namespace SmartCore\Module\Blog\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('smart_blog_admin_article'));
    }
}
